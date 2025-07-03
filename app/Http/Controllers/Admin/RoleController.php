<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        // Hanya user dengan permission "view-roles" yang bisa akses index
        $this->middleware('permission:view-role')->only(['index']);

        // Hanya user dengan permission "create-role" yang bisa tambah user
        $this->middleware('permission:create-role')->only(['create', 'store']);

        // Hanya user dengan permission "edit-role" yang bisa edit user
        $this->middleware('permission:edit-role')->only(['edit', 'update']);

        // Hanya user dengan permission "delete-role" yang bisa hapus user
        $this->middleware('permission:delete-role')->only(['destroy']);
    }
    public function index()
    {
        $perPage = request('per_page', 10);

        // Validasi nilai input (opsional tapi aman)
        $validPerPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;

        $query = Role::query();

        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->paginate($validPerPage)->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);

        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role)
    {

        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|unique:roles,name,' . $role->id]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted.');
    }
}