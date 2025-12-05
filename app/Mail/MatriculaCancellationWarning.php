<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MatriculaCancellationWarning extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $reason;
    public $details;

    /**
     * Create a new message instance.
     */
    public function __construct($student, $reason, $details)
    {
        $this->student = $student;
        $this->reason = $reason; // 'consecutive' o 'total'
        $this->details = $details;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $isWarning = in_array($this->reason, ['consecutive_warning', 'total_warning']);
        $subject = $isWarning 
            ? '⚠️ Alerta Temprana: Prevención de Cancelación de Matrícula - SENA'
            : '🚨 Alerta Crítica: Proceso de Cancelación de Matrícula - SENA';
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.attendance.cancellation_warning',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

