<?php

namespace Tests\Feature\Advertisement;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


const API_ROUTE = '/api/v1/advertisements';


//title validations
it('returns an error when required fields are missing', function () {
    $response = $this->postJson(API_ROUTE, []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'title',
            //'image',
            'content',
            'start_date',
            'end_date',
        ]);
});

it('returns an error if the required length is less than 2', function () {
    $response = $this->postJson(API_ROUTE, [
        'title' => 'H' //check for if charcters are less than 5
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

it('returns an error if the title length exceeds 30 characters', function () {
    $response = $this->postJson(API_ROUTE, [
        'title' => fake()->regexify('[A-Za-z]{40}')
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

it('returs error when content length is less than 30 charecters', function () {
    $response = $this->postJson(API_ROUTE, [
        'content' => fake()->regexify('[A-Za-z]{20}')
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['content']);
});

it('returns an error if the content length is less than 30 characters', function () {
    $response = $this->postJson(API_ROUTE, [
        'content' => fake()->regexify('[A-Za-z]{101}')
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['content']);
});

it('returns an error if the date format is not in the "Y-m-d" format', function () {
    $response = $this->postJson(API_ROUTE, [
        'start_date' => '2025-29-02' //passing y-d-m while it expects y-m-d
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['start_date']);
});

//test for end date is in correct format
it('returns an error if the date format is not "yy-mm-dd"', function () {
    $response = $this->postJson(API_ROUTE, [
        'end_date' => '2025-13-02' //passing y-d-m while it expects y-m-d
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['end_date']);
});

//extention and size  test for image
it('fails if the attachement is not a valid image', function () {

    // action 
    $response = $this->postJson(API_ROUTE, [
        'image' => 'abc.jpg',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
});

it('fails when title length is greter than 30 characters', function () {
    $response = $this->postJson(API_ROUTE, [
        'title' => fake()->regexify('[A-Za-z]{40}')
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title']);
});

// passes image test if image is valid and image upload successful
it('passes if the attachement is valid image and image uploaded successfully', function () {

    Storage::fake('public');

    // arrange / prepare 
    $image = UploadedFile::fake()->image('abc.jpg');

    // action 
    $response = $this->postJson(API_ROUTE, [
        'image' => $image,
        'title' => 'Gajendra Singh',
        'content' => 'Hello brands how are you , we are here for your service and always available',
        'start_date' => "2025-01-05",
        'end_date' => "2025-06-05",
        'status' => 1
    ]);

    // assertion 
    $response->assertStatus(201);
    Storage::disk('public')->assertExists('ad/' . $image->hashName());

    // file path exists in databsae ? 
    $this->assertDatabaseHas('advertisements', [
        'image' => 'ad/' . $image->hashName()
    ]);
});

// passes test when data created
it('creates an advertisement when valid data is passed', function () {


    $response = $this->postJson(API_ROUTE, [
        'title' => 'Gajendra Singh',
        'content' => 'Hello brands how are you , we are here for your service and always available',
        'start_date' => "2025-01-05",
        'end_date' => "2025-06-05",
        'status' => 1
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'title',
                'content',
                'start_date',
                'end_date',
                'status'
            ]
        ]);

    $this->assertDatabaseHas('advertisements', [
        'title' =>  'Gajendra Singh',
        'content' => 'Hello brands how are you , we are here for your service and always available',
        'start_date' => '2025-01-05',
        'end_date' => '2025-06-05',
        'status' => 1
    ]);
});
