<?php

use App\Models\Policy;

it('returns 401 unauthenticated response when user tries to access get refund-policy api without login', function () {

    $response = $this->getJson('/api/v1/refund-policies');

    $response->assertUnauthorized();
});

it('returns 403 unauthorized exception when logged in user is not a HOST and tries to access get refund-policy', function () {

    $user = App\Models\User::factory()->create();
    $this->actingAs($user, 'api');

    $response = $this->getJson('/api/v1/refund-policies');

    $response->assertForbidden();
});

it('returns only active refund policies', function () {

    // arrange 
    $this->actingAs(mockHost(), 'api');
    Policy::factory()->create(['status' => 1]);
    Policy::factory()->create(['status' => 0]);

    // action 
    $response = $this->getJson('/api/v1/refund-policies');

    // assert 
    $response->assertOk()
        ->assertJsonCount(1, 'data');

    $this->assertDatabaseHas('refund_policies', [
        'id' => $response->json('data')[0]['id'],
        'status' => 1
    ]);
});


it('returns refund policies list', function () {

    $this->actingAs(mockHost(), 'api');

    // create mock data
    $refundPolicies = array_map(function ($item) {
        return ['id' => $item['id'], 'title' => $item['title']];
    }, Policy::factory(10)->create(['status' => 1])->toArray());

    // action 
    $response = $this->getJson('/api/v1/refund-policies');

    // assert
    $this->assertDatabaseCount('refund_policies', 10);
    $response->assertOk()
        ->assertJsonCount(10, 'data');
    expect($response->json('data'))->toBe($refundPolicies);
});
