<?php

namespace App\Mail;

use App\Models\StudentEventRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRegistrationApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public StudentEventRegistration $registration;

    /**
     * Create a new message instance.
     */
    public function __construct(StudentEventRegistration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تمت الموافقة على تسجيلك في الفعالية: ' . $this->registration->event->title_ar,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.events.registration_approved', // ستحتاج لإنشاء هذا الـ view
            with: [
                'studentName' => $this->registration->student->full_name_ar,
                'eventName' => $this->registration->event->title_ar,
                'eventDate' => $this->registration->event->event_start_datetime->format('Y-m-d H:i'),
            ],
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