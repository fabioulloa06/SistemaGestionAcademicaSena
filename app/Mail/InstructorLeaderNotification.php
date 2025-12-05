<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstructorLeaderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $reason;
    public $details;
    public $group;
    public $competencia;
    public $assignedInstructor; // Instructor específico que recibe la notificación

    /**
     * Create a new message instance.
     */
    public function __construct($student, $reason, $details, $group, $competencia = null, $assignedInstructor = null)
    {
        $this->student = $student;
        $this->reason = $reason; // 'consecutive' o 'total'
        $this->details = $details;
        $this->group = $group;
        $this->competencia = $competencia;
        $this->assignedInstructor = $assignedInstructor;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $isWarning = in_array($this->reason, ['consecutive_warning', 'total_warning']);
        $subject = $isWarning 
            ? '⚠️ Alerta Temprana: Estudiante Cerca de Límites de Inasistencias - SENA'
            : '🚨 Alerta Crítica: Estudiante Alcanzó Límites de Inasistencias - SENA';
        
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
            markdown: 'emails.attendance.instructor_leader_notification',
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

