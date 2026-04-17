<?php
namespace App\Mail;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReciboPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Pago $pago) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Recibo de Pago #{$this->pago->id} — El Sepulturero Juan",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.recibo_pago',
            with: ['pago' => $this->pago],
        );
    }
}