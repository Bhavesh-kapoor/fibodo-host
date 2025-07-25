<?php

namespace App\Services;

use App\Models\Setting;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SettingService
{
    /**
     * Get all settings with pagination
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function get(array $filters = []): LengthAwarePaginator
    {
        try {

            $query = Setting::query();
            $per_page = $filters['per_page'] ?? 15;

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['setting_key'])) {
                $query->where('setting_key', $filters['setting_key']);
            }

            if (isset($filters['setting_group'])) {
                $query->where('setting_group', $filters['setting_group']);
            }

            return $query->paginate($per_page);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get a setting by ID
     *
     * @param int $id
     * @return Setting
     * @throws Exception
     */
    public function find(int $id): Setting
    {
        try {
            $setting = Setting::find($id);

            if (!$setting) {
                throw new Exception('Setting not found', 404);
            }

            return $setting;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new setting
     *
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function updateOrCreate(array $data): LengthAwarePaginator
    {
        try {
            $user = Auth::user();

            // create or update multiple settings

            foreach ($data['settings'] as $setting) {
                $user->settings()->updateOrCreate(
                    ['setting_key' => $setting['setting_key']],
                    $setting + ['setting_group' => $data['setting_group']]
                );
            }
            return $user->settings()->where('setting_group', $data['setting_group'])->paginate();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a setting
     *
     * @param Setting $setting
     * @return bool
     */
    public function delete(Setting $setting): bool
    {
        try {
            return $setting->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get setting by key
     *
     * @param string $key
     * @return Setting|null
     */
    public function getByKey(string $key): ?Setting
    {
        try {
            return Auth::user()->settings()->where('setting_key', $key)->first();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get settings by group
     *
     * @param string $group
     * @return Collection
     */
    public function getByGroup(string $group): Collection
    {
        try {
            return Auth::user()->settings()->where('setting_group', $group)->get();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
