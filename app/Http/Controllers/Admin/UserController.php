<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage; // Digunakan jika ada fitur upload file di UserController, tapi saat ini tidak ada.

class UserController extends Controller
{
    /**
     * Mengambil daftar user dengan paginasi dan pencarian.
     * Digunakan oleh public/js/admin/admin_users.js
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);

        // Kecualikan user 'admin' dari daftar yang ditampilkan di tabel (opsional)
        $usersQuery = User::where('username', '!=', 'admin'); 

        if ($search) {
            $usersQuery->where(function($query) use ($search) {
                $query->where('full_name', 'LIKE', "%{$search}%")
                      ->orWhere('username', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Ambil user dengan paginasi, diurutkan berdasarkan tanggal dibuat terbaru
        $users = $usersQuery->orderBy('created_at', 'desc')->paginate($limit, ['*'], 'page', $page);

        // Format data untuk respons JSON ke frontend JavaScript
        $formattedUsers = $users->map(function($user) {
            return [
                'id' => $user->id,
                'full_name' => htmlspecialchars($user->full_name),
                'username' => htmlspecialchars($user->username),
                'email' => htmlspecialchars($user->email),
                'phone' => $user->phone ? htmlspecialchars($user->phone) : '-', // Tampilkan '-' jika nomor telepon kosong
                'created_at' => $user->created_at->format('d/m/Y H:i') // Format tanggal menggunakan Carbon
            ];
        });

        // Kembalikan respons dalam format JSON
        return response()->json([
            'success' => true,
            'data' => $formattedUsers,
            'pagination' => [
                'current_page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'total_users' => $users->total(),
                'showing_from' => $users->firstItem(),
                'showing_to' => $users->lastItem(),
                'per_page' => $users->perPage()
            ],
            'search' => $search
        ]);
    }

    /**
     * Menyimpan user baru ke database.
     * Digunakan oleh public/js/admin/admin_add_user.js
     */
    public function store(Request $request)
    {
        // Aturan validasi untuk setiap field yang diterima dari form
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'regex:/^[a-zA-Z\s]{2,50}$/'],
            'username' => ['required', 'string', 'min:3', 'max:20', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]{3,20}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|62|0)[2-9]\d{7,11}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'max:20', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@!$#%^<>?_-]).{8,20}$/'],
        ]);

        // Jika validasi gagal, kembalikan respons error JSON
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first() // Ambil pesan error pertama
            ], 422); // HTTP 422 Unprocessable Entity
        }

        try {
            // Buat record user baru di tabel 'users'
            $user = User::create([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => !empty($request->phone) ? $request->phone : null, // Set null jika input phone kosong
                'password' => Hash::make($request->password), // Hash password sebelum disimpan
            ]);

            // Kembalikan respons sukses JSON
            return response()->json([
                'success' => true,
                'message' => "User '{$user->full_name}' berhasil ditambahkan.",
                'user_data' => $user->only(['id', 'full_name', 'username', 'email']) // Hanya kirim data tertentu
            ]);

        } catch (\Exception $e) {
            // Log error untuk debugging di sisi server
            \Log::error("Error adding user from admin: " . $e->getMessage(), ['exception' => $e]);
            // Kembalikan respons error generic ke frontend
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambah user. Silakan coba lagi.'
            ], 500); // HTTP 500 Internal Server Error
        }
    }

    /**
     * Menghapus user dari database.
     * Digunakan oleh public/js/admin/admin_users.js
     */
    public function destroy(Request $request)
    {
        // Validasi user_id yang diterima dari permintaan
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id' // Pastikan user_id adalah integer dan ada di tabel users
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first()
            ], 422);
        }

        $user_id = $request->user_id;

        try {
            $user = User::find($user_id);

            // Cek jika user tidak ditemukan (meskipun 'exists' rule sudah cukup)
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
            }

            // Mencegah penghapusan user dengan username 'admin' (user utama admin)
            if ($user->username === 'admin') { 
                return response()->json(['success' => false, 'message' => 'User admin utama tidak dapat dihapus.'], 403);
            }

            // Hapus user dari database
            $user->delete();

            // Kembalikan respons sukses JSON
            return response()->json([
                'success' => true,
                'message' => "User '{$user->full_name}' berhasil dihapus.",
                'deleted_user' => $user->only(['id', 'username', 'full_name'])
            ]);

        } catch (\Exception | \Throwable $e) { // Tangkap Exception atau Throwable
            // Log error
            \Log::error("Error deleting user from admin: " . $e->getMessage(), ['exception' => $e]);
            // Kembalikan respons error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus user. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Memeriksa ketersediaan username, email, atau phone untuk validasi unik.
     * Digunakan oleh public/js/admin/admin_add_user.js
     */
    public function checkAvailability(Request $request)
    {
        // Aturan validasi untuk tipe dan nilai yang akan diperiksa
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:username,email,phone', // Hanya izinkan tipe tertentu
            'value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['available' => false, 'message' => 'Invalid request.'], 422);
        }

        $type = $request->type;
        $value = trim($request->value);

        // Periksa apakah nilai sudah ada di tabel 'users' untuk tipe yang diberikan
        $available = !User::where($type, $value)->exists();

        // Kembalikan status ketersediaan
        return response()->json(['available' => $available]);
    }
}