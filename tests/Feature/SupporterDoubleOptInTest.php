<?php

namespace Tests\Feature;

use App\Mail\SupporterConfirmationMail;
use App\Mail\SupporterUnsubscribeMail;
use App\Models\Supporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SupporterDoubleOptInTest extends TestCase
{
    use RefreshDatabase;

    public function test_supporter_registration_creates_pending_record_and_sends_confirmation_mail(): void
    {
        Mail::fake();

        $response = $this->post(route('supporters.store'), [
            'email' => ' Test@Example.org ',
        ]);

        $response->assertRedirect(route('supporters.check-email'));

        $supporter = Supporter::first();
        $this->assertSame('test@example.org', $supporter->email);
        $this->assertSame(Supporter::STATUS_PENDING, $supporter->status);
        $this->assertNotNull($supporter->confirmation_token_hash);
        $this->assertNull($supporter->confirmed_at);

        Mail::assertSent(SupporterConfirmationMail::class, 1);
    }

    public function test_supporter_is_counted_only_after_valid_confirmation(): void
    {
        Mail::fake();

        $this->post(route('supporters.store'), ['email' => 'person@example.org']);

        $supporter = Supporter::first();
        $this->assertSame(0, Supporter::where('status', Supporter::STATUS_CONFIRMED)->count());

        $mail = null;
        Mail::assertSent(SupporterConfirmationMail::class, function (SupporterConfirmationMail $message) use (&$mail): bool {
            $mail = $message;
            return true;
        });

        $this->get($mail->confirmationUrl)
            ->assertOk()
            ->assertSee('Danke, deine Unterstützung ist bestätigt');

        $supporter->refresh();
        $this->assertSame(Supporter::STATUS_CONFIRMED, $supporter->status);
        $this->assertNull($supporter->confirmation_token_hash);
        $this->assertNotNull($supporter->confirmed_at);
        $this->assertSame(1, Supporter::where('status', Supporter::STATUS_CONFIRMED)->count());
    }

    public function test_expired_confirmation_link_is_rejected(): void
    {
        Mail::fake();

        $this->post(route('supporters.store'), ['email' => 'expired@example.org']);
        $supporter = Supporter::first();
        $supporter->update(['token_expires_at' => now()->subMinute()]);

        $mail = null;
        Mail::assertSent(SupporterConfirmationMail::class, function (SupporterConfirmationMail $message) use (&$mail): bool {
            $mail = $message;
            return true;
        });

        $this->get($mail->confirmationUrl)
            ->assertOk()
            ->assertSee('ungültig oder abgelaufen');

        $this->assertSame(0, Supporter::where('status', Supporter::STATUS_CONFIRMED)->count());
    }

    public function test_duplicate_confirmed_registration_does_not_create_second_supporter(): void
    {
        Mail::fake();

        $this->post(route('supporters.store'), ['email' => 'same@example.org']);
        $mail = null;
        Mail::assertSent(SupporterConfirmationMail::class, function (SupporterConfirmationMail $message) use (&$mail): bool {
            $mail = $message;
            return true;
        });
        $this->get($mail->confirmationUrl);

        Mail::fake();
        $this->post(route('supporters.store'), ['email' => 'SAME@example.org']);

        $this->assertSame(1, Supporter::count());
        $this->assertSame(1, Supporter::where('status', Supporter::STATUS_CONFIRMED)->count());
        Mail::assertNothingSent();
    }

    public function test_registration_is_rate_limited(): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('supporters.store'), ['email' => "rate{$i}@example.org"])
                ->assertRedirect(route('supporters.check-email'));
        }

        $this->post(route('supporters.store'), ['email' => 'rate-final@example.org'])
            ->assertStatus(429);
    }

    public function test_confirmed_supporter_can_unsubscribe_with_mail_link(): void
    {
        Mail::fake();

        $this->post(route('supporters.store'), ['email' => 'leave@example.org']);
        $confirmationMail = null;
        Mail::assertSent(SupporterConfirmationMail::class, function (SupporterConfirmationMail $message) use (&$confirmationMail): bool {
            $confirmationMail = $message;
            return true;
        });
        $this->get($confirmationMail->confirmationUrl);

        Mail::fake();
        $this->post(route('supporters.unsubscribe.request'), ['email' => 'leave@example.org'])
            ->assertOk()
            ->assertSee('Widerrufs-Mail');

        $unsubscribeMail = null;
        Mail::assertSent(SupporterUnsubscribeMail::class, function (SupporterUnsubscribeMail $message) use (&$unsubscribeMail): bool {
            $unsubscribeMail = $message;
            return true;
        });

        $this->get($unsubscribeMail->unsubscribeUrl)
            ->assertOk()
            ->assertSee('wurde widerrufen');

        $supporter = Supporter::first();
        $this->assertSame(Supporter::STATUS_UNSUBSCRIBED, $supporter->status);
        $this->assertNotNull($supporter->unsubscribed_at);
        $this->assertSame(0, Supporter::where('status', Supporter::STATUS_CONFIRMED)->count());
    }
}
