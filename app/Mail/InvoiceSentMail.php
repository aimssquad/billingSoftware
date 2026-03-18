<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
//use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Barryvdh\DomPDF\Facade\Pdf;
//implements ShouldQueue
class InvoiceSentMail extends Mailable 
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
        public string $siteUrl,
        public string $fromAddress,
        public string $fromName,
        public string $template
    ) {}

    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         from: new Address(
    //             $this->fromAddress,
    //             $this->fromName
    //         ),
    //         subject: 'Invoice #' . $this->invoice->invoice_no .
    //                  ' from ' . $this->invoice->organization->name,
    //     );
    // }



    

    // public function content(): Content
    // {
    //     $view = 'emails.' . $this->template;

    //     if (!view()->exists($view)) {
    //         $view = 'emails.default';
    //     }

    //     return new Content(view: $view);
    // }

    public function build()
    {
        // ✅ Load org template
        $template = 'emails.default'; // fallback

        if ($this->invoice->organization->invoiceSetting?->template?->slug) {
            $template = 'emails.' . $this->invoice->organization->invoiceSetting->template->slug;
        }

        // ✅ Generate PDF
        $pdf = Pdf::loadView($template, [
            'invoice' => $this->invoice,
            'siteUrl' => $this->siteUrl
        ]);

        return $this->from($this->fromAddress, $this->fromName)
            ->subject('Invoice #' . $this->invoice->invoice_no)
            ->view('emails.invoice-sent') // email body
            ->attachData(
                $pdf->output(),
                'invoice-' . $this->invoice->invoice_no . '.pdf'
            );
    }
}