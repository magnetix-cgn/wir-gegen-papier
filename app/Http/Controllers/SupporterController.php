<?php

namespace App\Http\Controllers;

use App\Mail\SupporterConfirmationMail;
use App\Mail\SupporterUnsubscribeMail;
use App\Models\Supporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SupporterController extends Controller
{
    private const TOKEN_TTL_HOURS = 48;

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        $email = $this->normalizeEmail($data['email']);
        $supporter = Supporter::firstOrNew(['email' => $email]);

        if (! $supporter->exists || ! $supporter->isConfirmed()) {
            $token = $this->newToken();
            $supporter->fill([
                'status' => Supporter::STATUS_PENDING,
                'confirmation_token_hash' => $this->hashToken($token),
                'token_expires_at' => now()->addHours(self::TOKEN_TTL_HOURS),
                'unsubscribed_at' => null,
                'consent_text_version' => Supporter::CONSENT_TEXT_VERSION,
                'locale' => app()->getLocale(),
                'source' => 'landingpage',
            ]);
            $supporter->save();

            $this->sendConfirmationMail($supporter, $token);
        }

        return redirect()->route('supporters.check-email');
    }

    public function checkEmail(): View
    {
        return view('supporters.message', [
            'title' => 'Bitte bestätige deine E-Mail-Adresse.',
            'message' => 'Wir haben dir eine Bestätigungs-Mail gesendet. Erst nach dem Klick auf den Link zählt deine Unterstützung öffentlich mit.',
            'supporterCount' => $this->confirmedCount(),
        ]);
    }

    public function confirm(Supporter $supporter, string $token): View
    {
        if (! $this->validConfirmationToken($supporter, $token)) {
            return view('supporters.message', [
                'title' => 'Der Bestätigungslink ist ungültig oder abgelaufen.',
                'message' => 'Du kannst dir unten eine neue Bestätigungs-Mail anfordern.',
                'showResendForm' => true,
                'supporterCount' => $this->confirmedCount(),
            ]);
        }

        $supporter->fill([
            'status' => Supporter::STATUS_CONFIRMED,
            'confirmation_token_hash' => null,
            'token_expires_at' => null,
            'confirmed_at' => $supporter->confirmed_at ?? now(),
            'unsubscribed_at' => null,
        ])->save();

        return view('supporters.message', [
            'title' => 'Danke, deine Unterstützung ist bestätigt.',
            'message' => 'Deine Stimme zählt jetzt öffentlich für die Forderung: Digital, wenn ich will.',
            'showUnsubscribeForm' => true,
            'supporterCount' => $this->confirmedCount(),
        ]);
    }

    public function resend(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        $supporter = Supporter::where('email', $this->normalizeEmail($data['email']))->first();

        if ($supporter && ! $supporter->isConfirmed()) {
            $token = $this->newToken();
            $supporter->fill([
                'status' => Supporter::STATUS_PENDING,
                'confirmation_token_hash' => $this->hashToken($token),
                'token_expires_at' => now()->addHours(self::TOKEN_TTL_HOURS),
                'consent_text_version' => Supporter::CONSENT_TEXT_VERSION,
            ])->save();

            $this->sendConfirmationMail($supporter, $token);
        }

        return redirect()->route('supporters.check-email');
    }

    public function unsubscribeRequest(Request $request): View
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc', 'max:255'],
        ]);

        $supporter = Supporter::where('email', $this->normalizeEmail($data['email']))->first();

        if ($supporter && $supporter->isConfirmed()) {
            $token = $this->newToken();
            $supporter->fill([
                'unsubscribe_token_hash' => $this->hashToken($token),
                'unsubscribe_token_expires_at' => now()->addHours(self::TOKEN_TTL_HOURS),
            ])->save();

            Mail::to($supporter->email)->send(new SupporterUnsubscribeMail(
                $supporter,
                route('supporters.unsubscribe.confirm', [$supporter, $token]),
                $supporter->unsubscribe_token_expires_at->timezone(config('app.timezone'))->format('d.m.Y H:i'),
            ));
        }

        return view('supporters.message', [
            'title' => 'Falls die Adresse bestätigt ist, wurde eine Widerrufs-Mail gesendet.',
            'message' => 'Bitte prüfe dein Postfach. Aus Datenschutzgründen zeigen wir hier nicht an, ob die Adresse bei uns vorhanden ist.',
            'supporterCount' => $this->confirmedCount(),
        ]);
    }

    public function unsubscribeConfirm(Supporter $supporter, string $token): View
    {
        if (! $this->validUnsubscribeToken($supporter, $token)) {
            return view('supporters.message', [
                'title' => 'Der Widerrufslink ist ungültig oder abgelaufen.',
                'message' => 'Du kannst über das Formular unten eine neue Widerrufs-Mail anfordern.',
                'showUnsubscribeForm' => true,
                'supporterCount' => $this->confirmedCount(),
            ]);
        }

        $supporter->fill([
            'status' => Supporter::STATUS_UNSUBSCRIBED,
            'unsubscribe_token_hash' => null,
            'unsubscribe_token_expires_at' => null,
            'unsubscribed_at' => now(),
        ])->save();

        return view('supporters.message', [
            'title' => 'Deine Unterstützung wurde widerrufen.',
            'message' => 'Die Adresse wird nicht mehr als bestätigte Unterstützung gezählt.',
            'supporterCount' => $this->confirmedCount(),
        ]);
    }

    private function sendConfirmationMail(Supporter $supporter, string $token): void
    {
        Mail::to($supporter->email)->send(new SupporterConfirmationMail(
            $supporter,
            route('supporters.confirm', [$supporter, $token]),
            $supporter->token_expires_at->timezone(config('app.timezone'))->format('d.m.Y H:i'),
        ));
    }

    private function normalizeEmail(string $email): string
    {
        return Str::lower(trim($email));
    }

    private function newToken(): string
    {
        return Str::random(64);
    }

    private function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    private function validConfirmationToken(Supporter $supporter, string $token): bool
    {
        return $supporter->status === Supporter::STATUS_PENDING
            && $supporter->confirmation_token_hash
            && $supporter->token_expires_at
            && $supporter->token_expires_at->isFuture()
            && hash_equals($supporter->confirmation_token_hash, $this->hashToken($token));
    }

    private function validUnsubscribeToken(Supporter $supporter, string $token): bool
    {
        return $supporter->status === Supporter::STATUS_CONFIRMED
            && $supporter->unsubscribe_token_hash
            && $supporter->unsubscribe_token_expires_at
            && $supporter->unsubscribe_token_expires_at->isFuture()
            && hash_equals($supporter->unsubscribe_token_hash, $this->hashToken($token));
    }

    private function confirmedCount(): int
    {
        return Supporter::where('status', Supporter::STATUS_CONFIRMED)->count();
    }
}
