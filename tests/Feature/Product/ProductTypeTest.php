<?php

use App\Models\ProductType;
use Database\Seeders\ProductTypeSeeder;
use Illuminate\Http\Response;

it('runs the Product Type seeder successfully', function () {

    $this->seed(ProductTypeSeeder::class);

    $this->assertCount(6, ProductType::all());

    $productTypes = ProductType::all();

    $this->assertEquals('private sessions', $productTypes[0]->title);
    $this->assertEquals('live streamed', $productTypes[1]->title);
    $this->assertEquals('home visits', $productTypes[2]->title);
    $this->assertEquals('classes', $productTypes[3]->title);
    $this->assertEquals('courses', $productTypes[4]->title);
    $this->assertEquals('walk-ins', $productTypes[5]->title);
});

it('returns the fixed product type list', function () {

    $testData = [];
    // arrange 
    foreach (['private sessions', 'live streamed', 'home visits', 'classes', 'courses', 'walk-ins'] as $title) {
        $testData[] = (ProductType::factory()->create(['title' => $title]))->only(['id', 'title']);
    }

    // act
    $response = $this->getJson('/api/v1/product-types');

    // assert
    $response->assertStatus(Response::HTTP_OK);
    expect($response->json('data'))->toBe($testData);
});
