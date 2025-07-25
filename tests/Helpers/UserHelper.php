<?php


if (!function_exists('mockHost')) {
    /**
     * mockHost
     *
     * @return App\Models\User
     */
    function mockHost(): App\Models\User
    {
        $user = App\Models\User::factory()->create();
        mockHostRole($user);
        return $user;
    }
}

if (!function_exists('mockHostRole')) {
    /**
     * mockHostRole
     *
     * @return void
     */
    function mockHostRole($user)
    {
        $user->assignRole(
            App\Models\Role::firstOrCreate(['name' => 'host', 'guard_name' => 'api'])
        );
    }
}

if (!function_exists('prepareHostSignupData')) {
    /**
     * Prepare data for host signup test
     *
     * @return array
     */
    function prepareHostSignupData()
    {

        // create host role to be assigned to user 
        \App\Models\Role::create(['name' => 'host']);
        // create a category & sub-category 
        $category = \App\Models\Category::create(['name' => 'Test Category']);
        $sub_category = $category->children()->create(['name' => 'Test Sub Category']);

        // Return the signup data
        $fakeEmail = fake()->safeEmail();

        return [
            'user' => [
                'first_name' => 'John',
                'last_name' => 'A',
                'email' => $fakeEmail,
                'country_code' => 91,
                'mobile_number' => 9999999923,
                'gender' => 'male',
                'date_of_birth' => '2000-12-25',
                'confirm_email' => $fakeEmail,
                'password' => "Abc@2025#",
                'confirm_password' => "Abc@2025#",
            ],
            'host' => [
                'business_name'  => 'ABC Sports',
                'category_id' => $category->id,
                'sub_category_id' => $sub_category->id,
            ]
        ];
    }
}
