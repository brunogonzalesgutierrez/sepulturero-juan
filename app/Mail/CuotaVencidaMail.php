<?php
namespace App\Mail;

use App\Models\Cuota;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CuotaVencidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Cuota $cuota) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aviso de cuota vencida — El Sepulturero Juan',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cuota_vencida',
            with: ['cuota' => $this->cuota],
        );
    }
}