<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class InvoiceSentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
        public string $siteUrl,
        public string $fromAddress,
        public string $fromName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                $this->fromAddress,
                $this->fromName
            ),
            subject: 'Invoice #' . $this->invoice->invoice_no .
                     ' from ' . $this->invoice->organization->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice-sent',
        );
    }
}