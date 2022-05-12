<?php

namespace Tests\Feature;

use App\Mail\EmailMailable;
use App\Models\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailTest extends TestCase
{
     use RefreshDatabase;

    /**
     * testEmailCanBeQueuedWithoutAttachment
     *
     * @return void
     */
    public function testEmailCanBeQueuedWithoutAttachment()
    {
        Mail::fake();

        $response = $this->post('/api/send_email', [
            'email_address' => 'test_email@email.com',
            'message' => 'here is a test message',
        ]);

        Mail::assertQueued(EmailMailable::class);

        $response->assertStatus(200);
    }

    /**
     * testEmailCanBeQueuedWithAttachment
     *
     * @return void
     */
    public function testEmailCanBeQueuedWithAttachment()
    {
        Mail::fake();

        $response = $this->post('/api/send_email', Email::factory()->create()->toArray());

        Mail::assertQueued(EmailMailable::class);

        $response->assertStatus(200);
    }

    /**
     * testEmailCanBeQueuedWithoutAttachment
     *
     * @return void
     */
    public function testEmailIsNotQueuedIfBadData()
    {
        Mail::fake();

        $response = $this->post('/api/send_email', [
            'email_address' => 'bad_data_emailnogood',
            'message' => 'here is a test message',
        ]);

        Mail::assertNotQueued(EmailMailable::class);

        $response->assertStatus(400);
    }

    /**
     * testMailableContentHasMessage
     *
     * @return void
     */
    public function testMailableContentHasMessage()
    {
        $email = Email::factory()->create();

        $mailable = new EmailMailable($email);
        $mailable->assertSeeInHtml($email->message);
    }

    /**
     * testShowSuccessfulEmailsSent
     *
     * @return void
     */
    public function testShowSuccessfulEmailsSent()
    {
        $emails = Email::factory()->count(3)->create();
        $emailsArray = $emails->pluck('email_address')->toArray();

        foreach ($emails as $email) {
            $email->was_sent = 1;
            $email->sent_at = now();
            $email->save();
        }

        $response = $this->get('/api/success_emails');

        $response->assertStatus(200)
            ->assertJson($emailsArray);
    }
}
