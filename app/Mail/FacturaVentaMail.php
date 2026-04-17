<?php
namespace App\Mail;

use App\Models\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturaVentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Venta $venta) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Factura de Venta #{$this->venta->id} — El Sepulturero Juan",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.factura_venta',
            with: ['venta' => $this->venta],
        );
    }

    public function attachments(): array
    {
        $this->venta->load([
            'cliente','empleado','contrato.espacio.cementerio',
            'contrato.espacio.direccion','contrato.espacio.tipoInhumacion',
            'contrato.espacio.dimension','pagoContado','pagoCredito.planPago.cuotas',
        ]);

        $pdf = Pdf::loadView('reportes.pdf.factura', ['venta' => $this->venta])
                  ->setPaper('a4');

        return [
            Attachment::fromData(
                fn() => $pdf->output(),
                "factura_venta_{$this->venta->id}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}