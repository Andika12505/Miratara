<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Mengambil daftar user dengan paginasi dan pencarian.
     * Digunakan oleh public/js/admin/admin_users.js
     */
    public function getUsersJson(Request $request)
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
                'phone' => $user->phone ? htmlspecialchars($user->phone) : '-',
                'created_at' => $user->created_at->format('d/m/Y H:i')
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
     * Menampilkan daftar user di halaman index admin.
     * Mengembalikan view yang akan memuat data via AJAX.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Menampilkan form untuk membuat user baru.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru ke database.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'regex:/^[a-zA-Z\s]{2,50}$/'],
            'username' => ['required', 'string', 'min:3', 'max:20', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]{3,20}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|62|0)[2-9]\d{7,11}$/', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'max:20', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@!$#%^<>?_-]).{8,20}$/'],
            
            // is_admin sekarang 'nullable' untuk store
            'is_admin' => ['nullable', 'boolean'], 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'full_name' => $request->full_name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => !empty($request->phone) ? $request->phone : null,
                'password' => Hash::make($request->password),
                // is_admin diambil dari request: 1 jika dicentang, 0 jika tidak dicentang
                'is_admin' => $request->has('is_admin') ? $request->boolean('is_admin') : false, 
            ]);

            return response()->json([
                'success' => true,
                'message' => "User '{$user->full_name}' berhasil ditambahkan.",
                'user_data' => $user->only(['id', 'full_name', 'username', 'email'])
            ]);

        } catch (\Exception $e) {
            \Log::error("Error adding user from admin: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambah user. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Menampilkan form untuk mengedit user yang spesifik.
     *
     * @param  \App\Models\User  $user  Instance model User (Route Model Binding)
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Pastikan user tidak mengedit user 'admin' utama melalui form edit biasa jika tidak diinginkan
        if ($user->username === 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'User admin utama tidak dapat diedit melalui form ini.');
        }

        // Mengembalikan view untuk form edit, dengan data user yang akan diedit
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui user yang spesifik di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user  Instance model User (Route Model Binding)
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Aturan validasi untuk memperbarui user
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'regex:/^[a-zA-Z\s]{2,50}$/'],
            'username' => ['required', 'string', 'min:3', 'max:20', Rule::unique('users')->ignore($user->id), 'regex:/^[a-zA-Z0-9_]{3,20}$/'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', Rule::unique('users')->ignore($user->id), 'regex:/^(\+62|62|0)[2-9]\d{7,11}$/'],
            'password' => ['nullable', 'string', 'min:8', 'max:20', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@!$#%^<>?_-]).{8,20}$/'],
            
            // PERUBAHAN DI SINI: 'is_admin' sekarang 'nullable' untuk update juga
            'is_admin' => ['nullable', 'boolean'], 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Mencegah perubahan role admin utama atau username-nya
        if ($user->username === 'admin' && ($request->username !== 'admin' || ($request->has('is_admin') && !$request->boolean('is_admin')))) {
            return response()->json([
                'success' => false,
                'message' => 'User admin utama tidak dapat diubah username-nya atau status admin-nya dicabut.'
            ], 403);
        }

        try {
            $user->full_name = $request->full_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone = !empty($request->phone) ? $request->phone : null;
            
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            // Logika untuk is_admin di update:
            // Jika checkbox DICENTANG, is_admin akan di-set ke TRUE (1).
            // Jika checkbox TIDAK DICENTANG, is_admin akan di-set ke FALSE (0).
            // Ini adalah perilaku standar dari checkbox di Laravel ketika 'nullable|boolean' digunakan.
            $user->is_admin = $request->has('is_admin') ? $request->boolean('is_admin') : false;

            $user->save();

            return response()->json([
                'success' => true,
                'message' => "User '{$user->full_name}' berhasil diperbarui.",
                'user_data' => $user->only(['id', 'full_name', 'username', 'email', 'is_admin'])
            ]);

        } catch (\Exception $e) {
            \Log::error("Error updating user from admin: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui user. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Menghapus user yang spesifik dari database.
     *
     * @param  \App\Models\User  $user  Instance model User (Route Model Binding)
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Mencegah penghapusan user dengan username 'admin' (user utama admin)
        if ($user->username === 'admin') { 
            return response()->json(['success' => false, 'message' => 'User admin utama tidak dapat dihapus.'], 403);
        }

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "User '{$user->full_name}' berhasil dihapus.",
                'deleted_user' => $user->only(['id', 'username', 'full_name'])
            ]);

        } catch (\Exception | \Throwable $e) {
            \Log::error("Error deleting user from admin: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus user. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Memeriksa ketersediaan username, email, atau phone untuk validasi unik.
     * Digunakan oleh public/js/admin/admin_add_user.js (dan juga akan digunakan oleh admin_edit_user.js)
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:username,email,phone',
            'value' => 'required|string',
            'ignore_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['available' => false, 'message' => 'Invalid request.'], 422);
        }

        $type = $request->type;
        $value = trim($request->value);
        $ignoreId = $request->ignore_id;

        $query = User::where($type, $value);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $available = !$query->exists();

        return response()->json(['available' => $available]);
    }
}
