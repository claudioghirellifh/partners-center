<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPassword extends ResetPassword
{
    public function __construct(string $token, protected Company $company)
    {
        parent::__construct($token);
    }

    protected function resetUrl($notifiable): string
    {
        return route('admin.password.reset', [
            'company' => $this->company,
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);
    }

    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);
        $expire = (int) config('auth.passwords.users.expire', 60);

        return (new MailMessage)
            ->subject('Redefinição de senha — ' . $this->company->name)
            ->view('emails.admin_password_reset', [
                'company' => $this->company,
                'url' => $url,
                'expire' => $expire,
            ]);
    }
}
