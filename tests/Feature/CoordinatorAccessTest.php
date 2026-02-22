<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Coordinator can access the report page', function (){
    $coordinator = User::factory()->create([
        'role' => 'coordinator',
    ]);
    
    $response = $this->actingAs($coordinator)->get('/reports');
    $response->assertStatus(200);
});

test('Regular user cannot access the report page', function(){
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $response = $this->actingAs($user)->get('/reports');
    $response->assertStatus(403);
});