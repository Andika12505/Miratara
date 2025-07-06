<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password; // <-- Gunakan objek aturan Password untuk keterbacaan
use Illuminate\Validation\ValidationException; // <-- Untuk melempar error validasi secara manual
use Illuminate\Support\Facades\Storage;

class CustomerAccountController extends Controller
{
    /**
     * Menampilkan halaman profil atau akun pengguna.
     */
    public function viewAccount()
    {
        $user = Auth::user();
        $transactionHistory = collect();
        $shoppingCart = collect();

       return view('customer.account.view', compact('user', 'transactionHistory', 'shoppingCart'));
    }

    /**
     * Memperbarui biodata pengguna.
     */
    public function updateProfile(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'full_name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'min:3', 'max:20', Rule::unique('users')->ignore($user->id)],
        'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        'phone' => ['nullable', 'string', 'max:15'],
        // Tambahkan validasi untuk foto
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], 
    ]);

    // Logika untuk menangani upload foto
    if ($request->hasFile('photo')) {
        // 1. Hapus foto lama jika ada
        if ($user->profile_photo_path) {
            Storage::delete('public/' . $user->profile_photo_path);
        }
        // 2. Simpan foto baru dan dapatkan path-nya
        $path = $request->file('photo')->store('profile-pictures', 'public');
        // 3. Simpan path baru ke dalam data yang akan di-update
        $validated['profile_photo_path'] = $path;
    }

    try {
        $user->update($validated);

        // Kirim path foto baru ke frontend agar bisa langsung ditampilkan
        $newPhotoUrl = $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null;

        return response()->json([
            'success' => true,
            'message' => 'Biodata Anda berhasil diperbarui!',
            'newPhotoUrl' => $newPhotoUrl,
        ]);
    } catch (\Exception $e) {
        Log::error("Error updating customer profile: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan internal saat memperbarui biodata.'
        ], 500);
    }
}

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        // Cek dulu password lama secara manual
        if (!Hash::check($request->current_password, $user->password)) {
            // Lempar ValidationException agar respons error konsisten dengan validasi lainnya
            throw ValidationException::withMessages([
                'current_password' => 'Password lama tidak cocok.',
            ]);
        }

        // Lanjutkan dengan validasi password baru
        $validated = $request->validate([
            'current_password' => ['required'], // Tetap validasi keberadaannya
            'password' => [
                'required',
                'confirmed',
                // Aturan password yang lebih mudah dibaca daripada regex
                Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
            ],
        ]);

        try {
            $user->update([
                'password' => Hash::make($validated['password'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password Anda berhasil diperbarui!'
            ]);
        } catch (\Exception $e) {
            Log::error("Error updating customer password: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal saat memperbarui password.'
            ], 500);
        }
    }
}