<?php

namespace Tests\Feature\AdminRoot;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminRootAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('adminroot.path', 'adminroot');
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/adminroot');

        $response
            ->assertOk()
            ->assertSee('Acessar painel Root');
    }

    public function test_root_user_can_authenticate(): void
    {
        $user = User::factory()->create([
            'email' => 'root@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ROOT,
            'is_active' => true,
        ]);

        $response = $this->post('/adminroot/login', [
            'email' => 'root@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('adminroot.dashboard'));
        $this->assertAuthenticatedAs($user, 'root');
    }

    public function test_inactive_root_cannot_authenticate(): void
    {
        User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ROOT,
            'is_active' => false,
        ]);

        $response = $this->from('/adminroot')->post('/adminroot/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertRedirect('/adminroot')
            ->assertSessionHasErrors('email');

        $this->assertGuest('root');
    }

    public function test_non_root_user_cannot_authenticate(): void
    {
        User::factory()->create([
            'email' => 'seller@example.com',
            'password' => Hash::make('password123'),
            'role' => 'seller',
        ]);

        $response = $this->from('/adminroot')->post('/adminroot/login', [
            'email' => 'seller@example.com',
            'password' => 'password123',
        ]);

        $response
            ->assertRedirect('/adminroot')
            ->assertSessionHasErrors('email');

        $this->assertGuest('root');
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/adminroot/dashboard');

        $response->assertRedirect(route('adminroot.login.form'));
    }

    public function test_authenticated_root_can_view_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'root@example.com',
            'password' => Hash::make('password123'),
            'role' => User::ROLE_ROOT,
        ]);

        $this->actingAs($user, 'root');

        $response = $this->get('/adminroot/dashboard');

        $response
            ->assertOk()
            ->assertSee('Visão geral');
    }
}
