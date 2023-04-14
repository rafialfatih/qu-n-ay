<?php

use App\Models\User;

it('can be accessed by guest on login page')
    ->get('/login')
    ->assertOk()
    ->assertSee('Login');


it('can be accessed by guest on register page')
    ->get('/register')
    ->assertOk()
    ->assertSee('Register');

it('can register user', function() {
    $user = User::factory()->create();

    $response = $this->post('/register', [
        'username' => 'test21',
        'name' => $user->name,
        'email' => 'test@test.com',
        'password' => 'secretme',
        'password_confirmation' => 'secretme',
    ]);

    $response->assertRedirect('/')
        ->assertSessionHas('message');

    $this->assertAuthenticated();
});

it('can user login', function(){
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/questions')
        ->assertSessionHas('message');

    $this->assertAuthenticated();
});

it('cannot login user with wrong credentials', function(){
    $user = User::factory()->create();

    $response = $this->from('login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrongpassword',
    ]);

    $response->assertRedirect('login')
        ->assertSessionHasErrors('message')
        ->assertSessionHasInput('email');
});

it('can logout user', function (){
    $response = $this->actingAsAuth();

    $this->delete('/logout')
        ->assertRedirect('/login')
        ->assertSessionHas('message');

    $response->assertGuest();
});

it('cannot guest access authenticated pages')
    ->get('/questions/create')
    ->assertRedirect('/login');

it('cannot access login page when user is authenticated')
    ->actingAsAuth()
    ->get('/login')
    ->assertRedirect('/');

it('cannot access register page when user is authenticated')
    ->actingAsAuth()
    ->get('/register')
    ->assertRedirect('/');
