<?php

use Illuminate\Http\Response;

it('returns the application configuration with default appId', function () {
    // Act - without setting appId header
    $response = $this->getJson('/api/v1/app-config');

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    
    // Get base config and add expected values
    $expectedConfig = config('app-configurations.default');
    $expectedConfig['appid'] = 'fibodo';
    
    expect($response->json('data'))->toBe($expectedConfig);
});

it('returns the application configuration with custom appId from header', function () {
    // host app ID to test
    $customAppId = 'host';
    
    // Act - with custom appId header
    $response = $this->getJson('/api/v1/app-config', [
        'appId' => $customAppId
    ]);

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    
    // Get base config and add expected values
    $expectedConfig = config('app-configurations.host');
    $expectedConfig['appid'] = $customAppId;
    
    expect($response->json('data'))->toBe($expectedConfig);
});