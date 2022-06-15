<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RoleController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function save(Request $request)
    {
        Validator::validate($request->all(),
            [
                'name' => "required|string|unique:roles"
            ],
            [
                'name.required' => 'لطفا نام نقش را وارد کنید!',
                'name.string' => 'لطفا نام نقش را به درستی وارد کنید!'
            ]);


            $role = Role::create(['name' => $request->name]);
            for($i=1 ; $i<16 ; $i++)
            {
                Permission::create([
                    'role_id' => $role->id,
                    'module_id' => $i,
                    'create' => 0,
                    'read' => 0,
                    'update' => 0,
                    'delete' => 0,
                ]);
            }


            return response()->json([
                'role' => $role
            ]);
    }

    public function showAll()
    {
        $roles = Role::with(['permission'])->get();
        return response()->json([
            'roles' => $roles
        ]);
    }

    public function showOne($id)
    {
        $role = Role::with(['permission'])->where('id', $id)->get();
        return response()->json([
            'role' => $role
        ]);
    }

    public function update(Request $request)
    {
        Validator::validate($request->all(),
            [
                'name' => "required|string"
            ],
            [
                'name.required' => 'لطفا نام نقش را وارد کنید!',
                'name.string' => 'لطفا نام نقش را به درستی وارد کنید!'
            ]);
            $role = Role::where('id', $request->id)->update(['name' => $request->name]);
    return response()->json([
                'role' => $role
            ], 201);
    }

    public function delete(Request $request)
    {
        return Role::where('id', $request->id)->delete();
    }
}
