<?php

namespace Tests\Feature\Host;

use App\Models\Host;
use App\Notifications\EmailOtpVerification;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Client;
use Notification;
use Storage;

/**
 * Validations: 
 * 
 * NOTE: 
 * date_of_birth: 
 *  . should be a valid date 
 *  . and apply date validation ( like 13+ etc )
 * email: 
 *  . Email is required 
 *  . Test entered email is a valid email address 
 *  . If email is already registered, return and error : Email already registered. 
 * FIXME: mobile_number: Test a valid mobile number, min:6, max:10, and must be a number only
 * country_code: 
 *    . Test a valid country code, min:3,max:3, 
 *    FIXME: and match with a country code field( in table, JSON data) 
 * gender: should be in the following [male,female,na] or null by default
 * avatar: 
 *  . should be a file and proper image ( jpg, jpeg , bmp etc. )
 *  . should not more than a desired size
 *  . Image must be in 1:1 ratio
 * password: 
 *  . Password is required 
 *  . Test for password strength - min_length:8,A-B:1,a-b:1,special_char:1,digits:1,not_serial
 *  . Regex: ^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$
 * 
 * Category & sub category ( Category and sub category ): 
 *  . Category & sub category is required as CATEGORY & CHILD-CATEGORY 
 */

const API_ROUTE = '/api/v1/hosts/signup';

beforeEach(function () {
    $client = Client::create([
        'name' => 'Fibo Test',
        'redirect' => 'http://localhost',
        'personal_access_client' => true,
        'password_client' => false,
        'revoked' => false,
        'secret' => \Illuminate\Support\Str::random(40),
    ]);

    // Register Personal Access Client
    DB::table('oauth_personal_access_clients')->insert([
        'client_id' => $client->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
});

// any required missing field validation
it('returns validation errors for missing fields', function () {
    $response = $this->postJson(API_ROUTE, []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'first_name',
            'last_name',
            'business_name',
            'email',
            'confirm_email',
            'password',
            'confirm_password',
            'category_id',
            'sub_category_id'
        ]);
});

// email validations
it('returns an error if email is not in correct format', function () {

    $response = $this->postJson(API_ROUTE, [
        'email' => 'confirmed.test.com', // not a correct email format, missing @
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
it('returns an error for duplicate email', function () {

    $fakeEmail = fake()->safeEmail();
    \App\Models\User::factory()->create(['email' => $fakeEmail]);

    $response = $this->postJson(API_ROUTE, [
        'email' => $fakeEmail,
        'confirm_email' => $fakeEmail
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
it('returns an error if email and confirmed email does not match', function () {

    $response = $this->postJson(API_ROUTE, [
        'email' => 'confirmed@test.com',
        'confirm_email' => 'notconfirmed@test.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['confirm_email']);
});

// password validations
it('fails when the password is less than 8 characters', function () {
    $response = $this->postJson(API_ROUTE, ['password' => 'Short1!']);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
it('fails when the password does not have an uppercase letter', function () {
    $response = $this->postJson(API_ROUTE, ['password' => 'lowe@#rcase1!']);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
it('fails when the password does not have a lowercase letter', function () {
    $response = $this->postJson(API_ROUTE, ['password' => 'UPPE@RCASE1!']);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
it('fails when the password does not contain a digit', function () {
    $response = $this->postJson(API_ROUTE, ['password' => 'NoNum@#bers!']);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
it('fails when the password does not contain a special character', function () {
    $response = $this->postJson(API_ROUTE, ['password' => 'NoSpecial1']);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['password']);
});
it('returns an error if password and confirmed password does not match', function () {
    $response = $this->postJson(API_ROUTE, [
        'password' => 'securepassword123',
        'confirm_password' => 'abc1234',
    ]);
    $response->assertStatus(422)
        ->assertJsonValidationErrors(['confirm_password']);
});

// Date of birth validation
it('validates the date of birth must be in a valid date format of Y-m-d', function () {
    $response = $this->postJson(API_ROUTE, [
        'date_of_birth' => Carbon::now()->addDays(-30) // passing Y-d-m while it expects Y-m-d
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['date_of_birth']);
});
it('validates the date of birth must be a date in past', function () {
    $response = $this->postJson(API_ROUTE, [
        'date_of_birth' => Carbon::now()->addDays(30) // passing future date , this should not allow, only past date should be accepted. 
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['date_of_birth']);
});

// Country code validation  
it('returns an error if country code is not a number', function () {
    $response = $this->postJson(API_ROUTE, [
        'country_code' => "IN" //
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['country_code']);
});
it('returns an error if country code is less than 2 digits', function () {
    $response = $this->postJson(API_ROUTE, [
        'country_code' => 8 //
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['country_code']);
});
it('returns an error if country code is greter than 3 digits', function () {
    $response = $this->postJson(API_ROUTE, [
        'country_code' => 1000 //
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['country_code']);
});

// category sub-category validation 
it('fails when the sub-category does not belong to the specified category', function () {

    // create a category & sub-category 
    $category = \App\Models\Category::create(['name' => 'Test Category 101']);
    $category2 = \App\Models\Category::create(['name' => 'Test Category 102']);
    $sub_category = $category2->children()->create(['name' => 'Test Category belongs to 102']);

    $response = $this->postJson(API_ROUTE, [
        'category_id' => $category->id,
        'sub_category_id' => $sub_category->id,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sub_category_id']);
});
it('passes only when the sub-category does belong to the specified category', function () {

    // create a category & sub-category 
    $category = \App\Models\Category::create(['name' => 'Test Category 101']);
    $sub_category = $category->children()->create(['name' => 'Test Category belongs to 101']);

    $response = $this->postJson(API_ROUTE, [
        'category_id' => $category->id,
        'sub_category_id' => $sub_category->id,
    ]);

    $response->assertStatus(422)
        ->assertJsonMissingValidationErrors(['sub_category_id']);
});

// avatar validation test 
it('fails when avatar is not a valid image', function () {

    // Simulate a POST request to the signup endpoint with avatar
    $response = $this->postJson(API_ROUTE, [
        'avatar' => 'String passed instad of image'
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['avatar' => __('validation.image', ['attribute' => 'avatar'])]);
});

// avatar tests 
it('allows host signup with avatar upload', function () {
    // fake storage 
    Storage::fake('public');

    // Prepare the data for signup including the avatar file
    $avatar = UploadedFile::fake()->image('avatar.jpg', 300, 300);

    $hostSignUpData = prepareHostSignupData();

    // Simulate a POST request to the signup endpoint with avatar
    $response = $this->postJson(API_ROUTE, $hostSignUpData['user'] + $hostSignUpData['host'] + [
        'avatar' => $avatar
    ]);

    $response->assertStatus(Response::HTTP_CREATED);
    Storage::disk('public')->assertExists('avatars/' . $avatar->hashName());
});


// Final all checks validation
it('creates a host & a user with the host role with valid data', function () {

    $hostSignUpData = prepareHostSignupData();

    $response = $this->postJson(API_ROUTE, array_merge($hostSignUpData['user'], $hostSignUpData['host']));

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                    'business_name',
                    'email',
                    'mobile_number',
                    'country_code',
                    'date_of_birth',
                    'gender',
                    'category_id',
                    'sub_category_id',
                ]
            ]
        ]);

    $this->assertDatabaseHas('hosts', $hostSignUpData['host']);

    // remove extra fields 
    unset(
        $hostSignUpData['user']['confirm_email'],
        $hostSignUpData['user']['confirm_password'],
        $hostSignUpData['user']['password']
    );
    $this->assertDatabaseHas('users', $hostSignUpData['user']);

    // get the user and test the role assigned is host 
    $user = \App\Models\User::where(['email' => $hostSignUpData['user']['email']])->first();
    expect($user->hasRole('host'))->toBe(true);
    expect($user->host->user_id)->toBe($user->id);
});

// JWT access token validation
it('returns JWT access token upon successfull signup', function () {

    $hostSignUpData = prepareHostSignupData();
    $response = $this->postJson(API_ROUTE, array_merge($hostSignUpData['user'], $hostSignUpData['host']));

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure([
            'data' => [
                'auth' => ["token"]
            ]
        ]);
});

it('sends OTP on host email successfully', function () {

    $hostSignUpData = prepareHostSignupData();

    // Fake the notification
    Notification::fake();

    $response = $this->postJson(API_ROUTE, array_merge($hostSignUpData['user'], $hostSignUpData['host']));

    // Assert that the notification was sent
    Notification::assertSentTo(Host::find($response->json('data.user.id'))->user, EmailOtpVerification::class);
});
