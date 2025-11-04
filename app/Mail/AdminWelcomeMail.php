<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AdminWelcomeMail extends Mailable
{
    public function __construct(
        public Company $company,
        public User $admin,
        public string $temporaryPassword,
        public string $loginUrl,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bem-vindo(a) como Admin — '.$this->company->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_welcome',
            with: [
                'company' => $this->company,
                'admin' => $this->admin,
                'temporaryPassword' => $this->temporaryPassword,
                'loginUrl' => $this->loginUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

