<?php

namespace App\Mail;

use App\Models\Projet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationProjet extends Mailable
{
    use Queueable, SerializesModels;

    public $projet;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Projet $projet, User $user)
    {
        $this->projet = $projet;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation Projet',
        );
    }

    public function build()
    {
        return 
        $this-> subject('Invitation à rejoindre un projet : {$this->projet->titre')
        ->view( 'emails.invitation');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
