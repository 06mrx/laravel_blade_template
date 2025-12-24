<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{

    public function __construct()
    {
        // Hanya user dengan permission "view-users" yang bisa akses index
        $this->middleware('permission:view-user')->only(['index']);

        // Hanya user dengan permission "create-user" yang bisa tambah user
        $this->middleware('permission:create-user')->only(['create', 'store']);

        // Hanya user dengan permission "edit-user" yang bisa edit user
        $this->middleware('permission:edit-user')->only(['edit', 'update']);

        // Hanya user dengan permission "delete-user" yang bisa hapus user
        $this->middleware('permission:delete-user')->only(['destroy']);
    }
    public function index()
    {
        // Default 10 jika tidak ada parameter
        $perPage = request('per_page', 10);

        // Validasi nilai input (opsional tapi aman)
        $validPerPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;

        $query = User::with('roles');

        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->paginate($validPerPage)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'roles' => 'nullable|array',
            'is_active' => 'boolean',
            'plan_type' => 'in:free,basic,premium',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date|after_or_equal:subscription_start',
            'trial_ends_at' => 'nullable|date',
            'max_mikrotiks' => 'nullable|integer|min:0',
            'max_customers' => 'nullable|integer|min:0',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_active' => $request->boolean('is_active'),
            'plan_type' => $request->input('plan_type', 'free'),
            'subscription_start' => $request->input('subscription_start'),
            'subscription_end' => $request->input('subscription_end'),
            'trial_ends_at' => $request->input('trial_ends_at'),
            'max_mikrotiks' => $request->input('max_mikrotiks'),
            'max_customers' => $request->input('max_customers'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->input('roles'));
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'roles' => 'nullable|array',
            'is_active' => 'boolean',
            'plan_type' => 'in:free,basic,premium',
            'subscription_start' => 'nullable|date',
            'subscription_end' => 'nullable|date|after_or_equal:subscription_start',
            'trial_ends_at' => 'nullable|date',
            'max_mikrotiks' => 'nullable|integer|min:0',
            'max_customers' => 'nullable|integer|min:0',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $data = $request->only([
            'name',
            'email',
            'is_active',
            'plan_type',
            'subscription_start',
            'subscription_end',
            'trial_ends_at',
            'max_mikrotiks',
            'max_customers',
            'phone',
            'address'
        ]);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        if ($request->has('roles')) {
            $user->syncRoles($request->input('roles'));
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}