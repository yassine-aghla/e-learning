<?php

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test("list tags", function () {
    
    Tag::factory()->count(3)->create();

    $response = $this->get("api/tags");
    $response->assertStatus(200);

    
    $response->assertJsonStructure([
        "data" => [
            "*" => [
                'id',
                'name',
            ],
        ],
    ]);
});

test("can create tags", function () {
    $tags = [
        ['name' => 'node.js'],
        ['name' => 'laravel'],
    ];

    $response = $this->post("api/tags", ['tags' => $tags]);
    $response->assertStatus(201);

   
    foreach ($tags as $tag) {
        $this->assertDatabaseHas('tags', ['name' => $tag['name']]);
    }
});

test("can show a specific tag", function () {
    
    $tag = Tag::create(['name' => 'node.js']);


    $response = $this->get("api/tags/{$tag->id}");
    $response->assertStatus(200);


    $response->assertJson([
        'data' => [
            'id' => $tag->id,
            'name' => $tag->name,
        ],
    ]);
});

test("can update a tag", function () {
    
    $tag = Tag::create(['name' => 'node.js']);


    $updatedData = ['name' => 'updated-node.js'];


    $response = $this->put("api/tags/{$tag->id}", $updatedData);
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'data' => 'Tag Updated Successfully',
    ]);
    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        'name' => $updatedData['name'],
    ]);
});

test("can delete a tag", function () {

    $tag = Tag::create(['name' => 'node.js']);
    $response = $this->delete("api/tags/{$tag->id}");
    $response->assertStatus(204); 

   
    $this->assertDatabaseMissing('tags', [
        'id' => $tag->id,
    ]);
});