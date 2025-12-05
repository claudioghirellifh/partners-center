<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IuguChargeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Customer $customer,
        public string $link,
        public ?string $emailMessage = null
    ) {
    }

    public function build(): self
    {
        return $this->subject('Cobrança avulsa')
            ->view('emails.iugu.charge');
    }
}
