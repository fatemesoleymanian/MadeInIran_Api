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
  public function save(Request $request)
  {
      Validator::validate($request->all(),
          [
              'name' => "required|string"
          ],
          [
              'name.required' => 'لطفا نام نقش را وارد کنید!',
              'name.string' => 'لطفا نام نقش را به درستی وارد کنید!'
          ]);
      $permissions = $request->permissions;
      $create = $request->create;
      $read = $request->read;
      $update = $request->update;
      $delete = $request->delete;

      $permission=[];
      DB::beginTransaction();
      try {
          $role = Role::create(['name' => $request->name]);

          for ($x = 0; $x < sizeof($permissions); $x++) {

              array_push($permission, [
                  'permission' => $permissions[$x],
                  'role_id' => $role->id,
                  'create' => $create[$x],
                  'read' => $read[$x],
                  'update' => $update[$x],
                  'delete' => $delete[$x]
              ]);
          }
          Permission::insert($permission);

          return response()->json([
              'role' => $role
          ], 201);
      }
      catch (Throwable $throwable) {
          DB::rollBack();
          return response()->json([
              'errors' => Lang::get('messages.fail', ['attribute' => 'نقش'])
          ], 401);
      }
  }
    public function show()
    {

    }
    public function update(Request $request,$id)
    {
        Validator::validate($request->all(),
            [
                'name' => "required|string"
            ],
            [
                'name.required' => 'لطفا نام نقش را وارد کنید!',
                'name.string' => 'لطفا نام نقش را به درستی وارد کنید!'
            ]);
        $permissions = $request->permissions;
        $create = $request->create;
        $read = $request->read;
        $update = $request->update;
        $delete = $request->delete;

        $permission=[];
        DB::beginTransaction();
        try {
            $role = Role::where('id',$id)->update(['name' => $request->name]);

            for ($x = 0; $x < sizeof($permissions); $x++) {

                array_push($permission, [
                    'permission' => $permissions[$x],
                    'role_id' => $role->id,
                    'create' => $create[$x],
                    'read' => $read[$x],
                    'update' => $update[$x],
                    'delete' => $delete[$x]
                ]);
            }
            Permission::where('role_id',$id)->delete();
            Permission::insert($permission);

            return response()->json([
                'role' => $role
            ], 201);
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json([
                'errors' => Lang::get('messages.fail', ['attribute' => 'نقش'])
            ], 401);
        }
    }
    public function delete(Request $request)
    {

    }
}
