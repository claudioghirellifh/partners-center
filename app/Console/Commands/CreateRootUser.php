<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;

class CreateRootUser extends Command
{
    protected $signature = 'root:create-user';

    protected $description = 'Cria ou atualiza um usuário Root para acesso ao painel administrativo';

    public function handle(): int
    {
        $this->components->info('Configuração do usuário Root');

        $email = $this->askValidEmail();

        /** @var User|null $user */
        $user = User::query()->where('email', $email)->first();

        if ($user && ! $user->isRoot()) {
            $this->components->error('Este e-mail pertence a outro tipo de usuário. Escolha outro e-mail.');

            return self::FAILURE;
        }

        $name = $this->ask('Nome do usuário Root', $user?->name ?? 'Administrador Root');

        $password = $this->askForPassword($user === null);

        $isActive = $this->confirm(
            'Usuário deve estar ativo?',
            $user?->is_active ?? true
        );

        $passwordHash = $password ? Hash::make($password) : $user?->password;

        $user = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'role' => User::ROLE_ROOT,
                'is_active' => $this->toBoolean($isActive),
                'password' => $passwordHash,
                'remember_token' => Str::random(40),
            ],
        );

        $this->components->info("Usuário Root {$user->email} configurado com sucesso.");

        if ($password) {
            $this->components->warn('A senha foi atualizada. Compartilhe-a com responsabilidade.');
        }

        return self::SUCCESS;
    }

    protected function askValidEmail(): string
    {
        while (true) {
            $email = (string) $this->ask('E-mail do usuário Root');

            $validator = validator(
                ['email' => $email],
                ['email' => ['required', 'email', 'max:255']],
            );

            if ($validator->fails()) {
                $this->displayValidationErrors($validator->errors());
                continue;
            }

            return $email;
        }
    }

    protected function askForPassword(bool $isNewUser): ?string
    {
        $shouldUpdate = $isNewUser || $this->confirm('Deseja definir/alterar a senha?', $isNewUser);

        if (! $shouldUpdate) {
            return null;
        }

        while (true) {
            $password = (string) $this->secret('Senha (mínimo 8 caracteres)');
            $confirmation = (string) $this->secret('Confirme a senha');

            $validator = validator(
                [
                    'password' => $password,
                    'password_confirmation' => $confirmation,
                ],
                [
                    'password' => ['required', 'min:8', 'confirmed'],
                ],
            );

            if ($validator->fails()) {
                $this->displayValidationErrors($validator->errors());
                continue;
            }

            return $password;
        }
    }

    protected function displayValidationErrors(MessageBag $errors): void
    {
        collect($errors->all())
            ->each(fn (string $error) => $this->components->error($error));
    }

    protected function toBoolean(mixed $value): bool
    {
        $boolean = filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);

        return $boolean ?? false;
    }
}
