<?php

namespace Tests\Feature\AdminRoot;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CreateRootUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_root_user(): void
    {
        $this->artisan('root:create-user')
            ->expectsQuestion('E-mail do usuário Root', 'root@example.com')
            ->expectsQuestion('Nome do usuário Root', 'Root User')
            ->expectsQuestion('Senha (mínimo 8 caracteres)', 'password123')
            ->expectsQuestion('Confirme a senha', 'password123')
            ->expectsConfirmation('Usuário deve estar ativo?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'root@example.com',
            'role' => User::ROLE_ROOT,
            'is_active' => true,
        ]);

        $user = User::where('email', 'root@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_it_updates_existing_root_user_without_password_change(): void
    {
        $user = User::factory()->create([
            'email' => 'root@example.com',
            'role' => User::ROLE_ROOT,
            'password' => Hash::make('oldpassword'),
            'is_active' => false,
        ]);

        $this->artisan('root:create-user')
            ->expectsQuestion('E-mail do usuário Root', 'root@example.com')
            ->expectsQuestion('Nome do usuário Root', 'Novo Nome Root')
            ->expectsConfirmation('Deseja definir/alterar a senha?', 'no')
            ->expectsConfirmation('Usuário deve estar ativo?', 'yes')
            ->assertExitCode(0);

        $user->refresh();

        $this->assertSame('Novo Nome Root', $user->name);
        $this->assertTrue($user->is_active);
        $this->assertTrue(Hash::check('oldpassword', $user->password));
    }
}
