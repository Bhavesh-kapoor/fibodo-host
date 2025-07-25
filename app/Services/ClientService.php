<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\ClientInvitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Events\AccountCreated;

class ClientService
{

    /**
     * get
     *
     * @return Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function get(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {

            $sortBy = request()->input('sort_by', 'created_at');
            $sortOrder = request()->input('sort_order', 'desc');
            $perPage = request()->input('per_page', 15);
            $is_archived = request()->input('is_archived', 0);

            $clients = User::role('client')
                ->join('host_client', 'host_client.client_id', '=', 'users.id')
                ->where('host_client.host_id', Auth::id())
                ->when(request()->has('s'), fn($q) => $q->search(request('s')))
                ->when(!empty($sortBy), function ($q) use ($sortBy, $sortOrder) {
                    $q->orderBy($sortBy, $sortOrder);
                })
                ->when($is_archived, fn($q) => $q->Archived())
                ->when(!$is_archived, fn($q) => $q->Unarchived())
                ->paginate($perPage);

            return ClientResource::collection($clients);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * createClient
     *
     * @param  array $data
     * @return User
     */
    public function createClient(array $data): User
    {
        try {
            $mobile_number = $data['mobile_number'] ?? null;
            // check if user already exists with the same host in clients table
            if (Client::existWithHost(Auth::id(), $data['email'], $mobile_number))
                throw new \Exception('A client with this email or mobile number already exists with the host.');

            // Start a transaction
            DB::beginTransaction();

            // check if user already exists with the same email or mobile number
            $mobile_number = $data['mobile_number'] ?? null;
            $user = User::where('email', $data['email'])
                ->when($mobile_number, function ($q) use ($mobile_number) {
                    $q->orWhere('mobile_number', $mobile_number);
                })
                ->first();

            if (!$user) {
                $user = User::create([
                    'email' => $data['email'],
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'mobile_number' => $mobile_number,
                    'password' => Hash::make(\Str::random(8)),
                    'invited_at' => $data['invited_at'] ?? null,
                ]);
            }

            // assign client role 
            $user->assignRole('client');

            // associate client with host
            (Client::find($user->id))->hosts()->attach(Auth::id());

            // save record
            DB::commit();

            return $user;
        } catch (Exception $e) {
            // Rollback the transaction and handle validation errors
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * createClient
     *
     * @return ClientResource
     */
    public function create(): ClientResource
    {
        try {

            $client = $this->createClient(request()->all());

            // DISPATCH Account Created EVENT - to send email OTP to verify 
            event(new AccountCreated($client));

            // return success response 
            return new ClientResource($client);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * updateClient
     *
     * @param User $client
     * @param array $data
     * @return ClientResource
     */
    public function update(User $client, array $data): ClientResource
    {
        try {
            // TODO: Update client record, email will be updated only if it is not exists in users table
            $client->update($data);
            return new ClientResource($client);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * findByEmail
     *
     * @param  mixed $email
     * @return Client|null
     */
    public function findByEmail($email): Client|null
    {
        return User::where('email', $email)->active()->first();
    }

    /**
     * Get bookings for a client
     *
     * @param User $client
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBookings(User $user)
    {
        try {
            return $user->client->bookings()
                ->where('bookings.status', BookingStatus::CONFIRMED) // confirmed bookings
                ->with(['activity'])
                ->orderBy('activity_start_time')
                ->paginate(request()->per_page);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Invite a new client
     *
     * @param array $data
     * @return ClientResource
     */
    public function invite(array $data): ClientResource
    {
        try {
            // Check if client already exists
            if (User::where('email', $data['email'])->exists()) {
                throw new \Exception('A client with this email already exists.');
            }

            // create client with auth 
            $client = $this->createClient($data + ['invited_at' => Carbon::now()]);

            // Send invitation notification email to the newly created user
            $client->notify(new ClientInvitation($client));

            return new ClientResource($client);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * archive
     *
     * @param User $client
     * @return ClientResource
     */
    public function archive(User $client): ClientResource
    {
        try {
            $client->update(['archived_at' => Carbon::now()]);
            return new ClientResource($client);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * restore
     *
     * @param User $client
     * @return ClientResource
     */
    public function restore(User $client): ClientResource
    {
        try {
            $client->update(['archived_at' => null]);
            return new ClientResource($client);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
