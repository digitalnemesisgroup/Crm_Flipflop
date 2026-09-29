<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\User;

class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $identifier = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    // ── OTP login state ────────────────────────────────────────────────────
    public string $otp = '';
    public bool   $otpSent  = false;
    public string $otpChannel = '';
    public string $otpTarget  = '';
    public string $maskedOtpTarget = '';
    public string $demoOtp    = '';
    public string $loginMode  = 'password'; // 'password' | 'otp'

    /**
     * Attempt password-based authentication.
     */
    public function authenticate(): void
    {
        $this->loginMode = 'password';

        $this->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string',
        ], [], ['identifier' => 'email or mobile']);

        $this->ensureIsNotRateLimited();

        $user = $this->resolveUserFromIdentifier($this->identifier);

        if (! $user || ! \Illuminate\Support\Facades\Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey('password'));

            throw ValidationException::withMessages([
                'form.identifier' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey('password'));
        Auth::login($user, $this->remember);
    }

    /**
     * Send a demo OTP to the email or mobile of the matching user.
     */
    public function sendOtp(): void
    {
        $this->loginMode = 'otp';
        $this->resetOtpState();

        $this->validate([
            'identifier' => 'required|string',
        ], [], ['identifier' => 'email or mobile']);

        $this->ensureCanSendOtp();

        $user = $this->resolveUserFromIdentifier($this->identifier);

        if (! $user) {
            throw ValidationException::withMessages([
                'form.identifier' => 'No account found for that email or mobile.',
            ]);
        }

        // Generate a 6-digit OTP
        $otp = (string) random_int(100000, 999999);
        session()->put('login_otp_' . $user->email, $otp);

        $this->demoOtp     = '';
        $this->otpSent     = true;
        $this->otpChannel  = 'email';
        $this->otpTarget   = $user->email;
        
        $emailParts = explode("@", $user->email);
        $name = $emailParts[0];
        $domain = $emailParts[1];
        $maskedName = substr($name, 0, 3) . str_repeat('*', max(0, strlen($name) - 3));
        $this->maskedOtpTarget = $maskedName . '@' . $domain;

        $this->identifier  = '';

        \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey('send_otp'), 900); // 15 minutes soft ban

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($otp));
    }

    /**
     * Verify the supplied OTP and authenticate.
     * Uses the channel/target chosen when the OTP was issued — no need to ask again.
     */
    public function verifyOtp(): void
    {
        $this->loginMode = 'otp';

        $this->validate([
            'otp' => 'required|digits:6',
        ], [], ['otp' => 'OTP']);

        $this->ensureIsNotRateLimited();

        $user = $this->lookupUserByChannel();

        if (! $user) {
            RateLimiter::hit($this->throttleKey('otp'));
            throw ValidationException::withMessages([
                'form.otp' => 'No matching account found for the OTP target.',
            ]);
        }

        $sessionOtp = session()->get('login_otp_' . $user->email);

        if ((string) $this->otp !== (string) $sessionOtp || empty($sessionOtp)) {
            RateLimiter::hit($this->throttleKey('otp'));
            throw ValidationException::withMessages([
                'form.otp' => 'Invalid OTP.',
            ]);
        }
        
        session()->forget('login_otp_' . $user->email);

        RateLimiter::clear($this->throttleKey('otp'));
        RateLimiter::clear($this->throttleKey('send_otp'));
        Auth::login($user, $this->remember);
    }

    /**
     * Resolve the user that the OTP was issued to, using stored channel/target.
     */
    protected function lookupUserByChannel(): ?User
    {
        if ($this->otpChannel === 'email') {
            return User::where('email', $this->otpTarget)->first();
        }

        return User::where('mobile', preg_replace('/\D+/', '', (string) $this->otpTarget))->first();
    }

    /**
     * Switch back to password mode and clear OTP state.
     */
    public function usePassword(): void
    {
        $this->loginMode = 'password';
        $this->resetOtpState();
    }

    /**
     * Clear current OTP state so user can request a new code.
     * Keeps them in OTP mode but lets them re-enter a fresh code.
     */
    public function resetOtp(): void
    {
        $this->otp      = '';
        $this->demoOtp  = '';
        $this->otpSent  = false;
        $this->otpChannel = '';
        $this->otpTarget  = '';
    }

    /**
     * Resolve a user by email or mobile number.
     */
    protected function resolveUserFromIdentifier(string $identifier): ?User
    {
        $identifier = trim($identifier);

        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            return User::where('email', $identifier)->first();
        }

        $digits = preg_replace('/\D+/', '', $identifier);

        return User::where('mobile', $digits)
            ->orWhere('mobile', $identifier)
            ->first();
    }

    protected function resetOtpState(): void
    {
        $this->otpSent    = false;
        $this->otp        = '';
        $this->demoOtp    = '';
        $this->otpChannel = '';
        $this->otpTarget  = '';
        $this->maskedOtpTarget = '';
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($this->loginMode), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey($this->loginMode));

        throw ValidationException::withMessages([
            'form.identifier' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function ensureCanSendOtp(): void
    {
        $key = $this->throttleKey('send_otp');
        if (! RateLimiter::tooManyAttempts($key, 3)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'form.identifier' => "Too many OTP requests. Please try again in " . ceil($seconds / 60) . " minutes.",
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(string $mode): string
    {
        return Str::transliterate(Str::lower($this->identifier ?? $this->email ?? 'guest').'|'.$mode.'|'.request()->ip());
    }
}