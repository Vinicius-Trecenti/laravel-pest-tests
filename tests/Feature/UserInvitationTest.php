<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('It should allow access to invite only for logged users', function(){

    $response = $this->postJson('invitations/invite', [
        'email' => 'test@example.com'
    ]);

    $response->assertStatus(401);
});

test('It should check if email is filled for the new invitation', function(){
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('invitations/invite', [
        'email' => ''
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('email');
});

test('It should check if email address is valid for the new invitation', function(){
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('invitations/invite', [
        "email" => "email-invalido",
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('email');
});

test('It should check email size for new invitation', function(){
    $user = User::factory()->create();

    $email = str_repeat('a', 260) . "@gmail.com";

    $response = $this->actingAs($user)->postJson('invitations/invite', [
        'email' => $email
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('email');
});