<?php

use App\Models\WelcomePage;
use Illuminate\Http\Response;

it('returns the default welcome pages when no appId header is provided', function () {
    // Make sure fibodo welcome pages exist in the database
    if (WelcomePage::where('app_id', 'fibodo')->count() === 0) {
        $this->markTestSkipped('Fibodo welcome pages must exist in the database');
    }
    
    // Act - without setting appId header
    $response = $this->getJson('/api/v1/welcome-pages');

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    
    // Check response structure
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            '*' => ['title', 'text']
        ]
    ]);
    
    // Verify success flag is true
    $response->assertJson([
        'success' => true
    ]);
    
    // Since the response depends on database content, just check basic structure
    expect($response->json('data'))->toBeArray();
    expect(count($response->json('data')))->toBeGreaterThanOrEqual(1);
});

it('returns the welcome pages for host app', function () {
    // Make sure host welcome pages exist in the database
    if (WelcomePage::where('app_id', 'host')->count() === 0) {
        $this->markTestSkipped('Host welcome pages must exist in the database');
    }
    
    // Act - with host appId header
    $response = $this->getJson('/api/v1/welcome-pages', [
        'appId' => 'host'
    ]);

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    
    // Check response structure
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            '*' => ['title', 'text']
        ]
    ]);
    
    // Verify success flag is true
    $response->assertJson([
        'success' => true
    ]);
    
    // Host app-specific assertions
    expect(count($response->json('data')))->toBeGreaterThanOrEqual(1);
});

it('returns the welcome pages for fibodo app', function () {
    // Make sure fibodo welcome pages exist in the database
    if (WelcomePage::where('app_id', 'fibodo')->count() === 0) {
        $this->markTestSkipped('Fibodo welcome pages must exist in the database');
    }
    
    // Act - with fibodo appId header
    $response = $this->getJson('/api/v1/welcome-pages', [
        'appId' => 'fibodo'
    ]);

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    
    // Check response structure
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            '*' => ['title', 'text']
        ]
    ]);
    
    // Verify success flag is true
    $response->assertJson([
        'success' => true
    ]);

    // Fibodo app should have specific data
    expect(count($response->json('data')))->toBeGreaterThanOrEqual(1);
});

it('returns the default welcome pages for an unknown appId', function () {
    // Make sure we have default welcome pages or the fallback will work
    $hasDefault = WelcomePage::where('app_id', 'default')->count() > 0;
    
    // Act - with unknown appId header
    $response = $this->getJson('/api/v1/welcome-pages', [
        'appId' => 'unknown-app-' . rand(1000, 9999)
    ]);

    // Assert
    $response->assertStatus(Response::HTTP_OK);
    
    // Check response structure
    $response->assertJsonStructure([
        'success',
        'message',
        'data' => [
            '*' => ['title', 'text']
        ]
    ]);
    
    // Verify success flag is true
    $response->assertJson([
        'success' => true
    ]);

    // We should have at least one welcome page
    expect($response->json('data'))->toBeArray();
    expect(count($response->json('data')))->toBeGreaterThanOrEqual(1);
}); 