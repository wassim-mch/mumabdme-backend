<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json([
            'status' => 200,
            'roles' => $roles
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if($request->has('permissions')){
            $role->permissions()->attach($request->permissions);
        }

        return response()->json([
            'status' => 201,
            'message' => 'Role créé avec succès.'
        ], 201);
    }

    public function show(Role $role)
    {
        return response()->json([
            'status' => 200,
            'role' => $role->load('permissions')
        ], 200);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);

        if($request->has('permissions')){
            $role->permissions()->sync($request->permissions);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Role mis à jour avec succès.',
        ], 200);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Role a été supprimé avec succès.'
        ], 200);
    }

}
