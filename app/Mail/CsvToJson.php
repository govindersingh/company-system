<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CsvToJson extends Mailable
{
    use Queueable, SerializesModels;

    private $jsonFilePath;

    /**
     * Create a new message instance.
     */
    public function __construct($jsonFilePath)
    {
        $this->jsonFilePath = $jsonFilePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Csv To Json',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.csv_to_json',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            public_path($this->jsonFilePath)
        ];
    }

    /**
     * Override the send method to perform cleanup after sending the email.
     *
     * @param  \Illuminate\Mail\Mailer  $mailer
     * @return void
     */
    public function send($mailer)
    {
        parent::send($mailer);

        // Delete the JSON file after the email has been sent
        unlink(public_path($this->jsonFilePath));
    }
}
