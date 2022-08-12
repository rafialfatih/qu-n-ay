<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function actingAsAuth()
    {
        $user = User::factory()->create();
        return $this->actingAs($user);
    }

    /** @test */
    public function login_page_can_be_accessed_by_guest()
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Login');
    }

    /** @test */
    public function register_page_can_be_accessed_by_guest()
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertSee('Register');
    }

    /** @test */
    public function user_can_register()
    {
        $this->withoutExceptionHandling();
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
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'password'
        ]);

        $response->assertRedirect('/')
            ->assertSessionHas('message');

        $this->assertAuthenticated();
    }

    /** @test */
    public function user_cannot_login_with_wrong_credentials()
    {
        $user = User::factory()->create();

        $response = $this->from('login')->post('/login', [
            'username' => $user->username,
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect('login')
            ->assertSessionHasErrors('message')
            ->assertSessionHasInput('username');
    }

    /** @test */
    public function user_can_logout()
    {
        $response = $this->actingAsAuth();

        $this->post('logout')
            ->assertRedirect('/login')
            ->assertSessionHas('message');

        $response->assertGuest();
    }

    /** @test */
    public function guest_cannot_access_authenticated_pages()
    {
        $this->get('/questions/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_cannot_access_login_page()
    {
        $this->actingAsAuth();

        $this->get('/login')
            ->assertRedirect('/');
    }

    /** @test */
    public function authenticated_user_cannot_access_register_page()
    {
        $this->actingAsAuth();

        $this->get('/register')
            ->assertRedirect('/');
    }
}
