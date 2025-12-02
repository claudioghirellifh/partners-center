<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IuguPaymentLinksMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Project $project, public array $links)
    {
    }

    public function build(): self
    {
        return $this->subject('Links de pagamento - '.$this->project->name)
            ->view('emails.iugu.payment-links');
    }
}
