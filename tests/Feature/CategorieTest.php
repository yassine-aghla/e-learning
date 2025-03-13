<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

test("list categories", function () {
    
    Category::factory()->count(3)->create();


    $response = $this->get('api/categories');
    $response->assertStatus(200);

  
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'name',
                'sub_category',
            ],
        ],
    ]);
});

test("can create a category", function () {
    $categoryData = [
        'name' => 'Test Category',
        'categorie_id' => null,
    ];

    $response = $this->post('api/categories', $categoryData);
    $response->assertStatus(201); 

    $response->assertJson([
        'data' => [
            'name' => 'Test Category',
            'sub_category' => [],
        ],
    ]);

    $this->assertDatabaseHas('categories', [
        'name' => 'Test Category',
        'categorie_id' => null,
    ]);
});

test("can show a specific category", function () {
   
    $category = Category::factory()->create();

 
    $response = $this->get("api/categories/{$category->id}");
    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'id' => $category->id,
            'name' => $category->name,
            'sub_category' => [],
        ],
    ]);
});



test("can delete a category", function () {
  
    $category = Category::factory()->create();

    
    $response = $this->delete("api/categories/{$category->id}");
    $response->assertStatus(204); 
    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});