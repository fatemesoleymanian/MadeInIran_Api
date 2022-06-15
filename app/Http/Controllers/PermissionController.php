<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    public  function change(Request $request)
    {
        foreach ($request->permissions as $i) {
            Permission::where([
                'module_id' => $i['module_id'],
                'role_id' => $i['role_id'],
            ])->update([
                'create' => $i['create'],
                'update' => $i['update'],
                'read' => $i['read'],
                'delete' => $i['delete'],
            ]);
        }
    }
}
