<?php

use App\Models\Product;

beforeEach(function () {
    // Create a user for testing
    $this->host = mockHost();
    $this->product = Product::factory()->create(['user_id' => $this->host->id]);
    $this->api_endpoint = '/api/v1/schedules/?product_id=' . $this->product->id;
});

it('fails when logged in user is not the product owner and tries to add the schedules for the product', function () {

    $user = mockHost();
    $this->actingAs($user, 'api');

    // act
    $response = $this->postJson($this->api_endpoint, []);

    // assert 
    $response->assertForbidden();
});


// any required missing field validation
it('returns validation errors for missing fields', function () {
    $this->actingAs($this->host, 'api');
    $response = $this->postJson($this->api_endpoint, []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'weekly_schedules'
        ]);
});

it('fails when required fields are missing in weekly_schedules array', function () {
    $this->actingAs($this->host, 'api');
    $response = $this->postJson($this->api_endpoint, ['weekly_schedules' => [['test' => 'test']]]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'weekly_schedules.0.name',
            'weekly_schedules.0.days'
        ]);
});


it('passes and creates schedules when all required fields are provided', function () {

    // arrange
    $this->actingAs($this->host, 'api');

    // act 
    $response = $this->postJson($this->api_endpoint, json_decode('{"recurres_in":15,"status":1,"weekly_schedules":[{"name":"Et expedita.","is_default":1,"status":1,"days":[{"day_of_week":2,"start_time":"01:23","end_time":"04:23","breaks":[{"name":"Porro aut expedita.","start_time":"05:32","end_time":"09:30"},{"name":"Dolor qui in eum.","start_time":"13:15","end_time":"23:35"}]},{"day_of_week":6,"start_time":"23:11","end_time":"02:11","breaks":[{"name":"Suscipit repudiandae veniam dignissimos.","start_time":"05:36","end_time":"12:49"},{"name":"Reprehenderit quaerat facilis sunt.","start_time":"04:58","end_time":"02:10"}]},{"day_of_week":2,"start_time":"17:41","end_time":"21:41","breaks":[{"name":"Perspiciatis occaecati modi.","start_time":"07:51","end_time":"08:10"},{"name":"Numquam maxime debitis.","start_time":"18:34","end_time":"21:47"}]},{"day_of_week":4,"start_time":"00:22","end_time":"02:22","breaks":[{"name":"Id ea aut porro.","start_time":"14:00","end_time":"06:04"},{"name":"Ratione iure sed.","start_time":"11:16","end_time":"14:15"}]},{"day_of_week":6,"start_time":"07:12","end_time":"10:12","breaks":[{"name":"Omnis magnam nostrum.","start_time":"10:25","end_time":"23:02"},{"name":"Exercitationem et.","start_time":"14:04","end_time":"04:57"}]},{"day_of_week":0,"start_time":"12:46","end_time":"16:46","breaks":[{"name":"Et illo non tempore.","start_time":"02:47","end_time":"00:55"},{"name":"Veritatis molestiae aut qui.","start_time":"21:46","end_time":"21:12"}]},{"day_of_week":2,"start_time":"13:55","end_time":"14:55","breaks":[{"name":"Aliquam voluptatem ad rerum.","start_time":"12:10","end_time":"19:52"},{"name":"Aspernatur sint enim ut.","start_time":"13:32","end_time":"01:20"}]}]},{"name":"Alternate Schedule 101.","is_default":0,"status":1,"days":[{"day_of_week":2,"start_time":"06:59","end_time":"08:59","breaks":[{"name":"Labore voluptate voluptas.","start_time":"10:09","end_time":"22:06"}]},{"day_of_week":5,"start_time":"04:18","end_time":"08:18","breaks":[{"name":"Magnam aliquid eaque qui.","start_time":"21:02","end_time":"20:54"},{"name":"Magni labore eligendi sequi.","start_time":"08:31","end_time":"19:44"}]}]}]}', true));

    // assert
    $response->assertCreated()
        ->assertJsonFragment([
            'recurres_in' => 15,
            'status' => 1,
        ]);

    $this->assertDatabaseHas('schedules', ['product_id' => $this->product->id]);
});
