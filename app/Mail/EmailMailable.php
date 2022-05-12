<?php

namespace App\Mail;

use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Email
     */
    public $email;

    /**
     * Create a new message instance.
     *
     * @param Email $email
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (!empty($this->email->attachment)) {
            return $this->view('mail.email')
                ->attach($this->email->attachment, [
                    'as' => $this->email->attachment_filename,
                    'meme' => 'application/' . substr($this->email->attachment_filename, -3),
                ]);
        }

        return $this->view('mail.email');
    }
}
