<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Impor Log Facade

class LoginRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            // Menggunakan 'username' sebagai nama input form, yang dapat berupa username atau email.
            // Logika untuk membedakan antara username dan email ada di metode authenticate().
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Dapatkan pesan error untuk aturan validasi yang ditentukan.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ];
    }

    /**
     * Coba autentikasi kredensial permintaan.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Kredensial yang akan digunakan untuk autentikasi
        $credentials = [
            'password' => $this->password,
        ];

        // Menentukan apakah input 'username' adalah format email atau bukan
        $loginField = filter_var($this->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials[$loginField] = $this->username;

        // Logging untuk debugging
        Log::info('Percobaan autentikasi:', [
            'input_username' => $this->username,
            'kolom_login' => $loginField,
            'kredensial_digunakan' => array_merge($credentials, ['password' => '******']) // Jangan log password asli
        ]);

        // Mencoba autentikasi dengan kredensial yang disiapkan
        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            Log::warning('Autentikasi gagal untuk user:', [
                'input_username' => $this->username,
                'kolom_login' => $loginField
            ]);
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }
        
        Log::info('Autentikasi berhasil untuk user:', [
            'username' => $this->username
        ]);
    }

    /**
     * Pastikan permintaan login tidak dibatasi lajunya.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Dapatkan kunci pembatasan laju untuk permintaan.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('username')).'|'.$this->ip());
    }
}
