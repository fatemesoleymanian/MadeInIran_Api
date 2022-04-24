<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    ////////////////********* this controller hase been tested => OK!
    public function save(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:tags',
            'type' => 'required' //0=> blog  1=>product
        ]);

        return Tag::create([
        'name' => $request->name,
        'type' => $request->type
        ]);
    }
    public function showAll()
    {
        return Tag::orderByDesc('id')->get();
    }
    public function showOne($id)
    {
        return Tag::where('id',$id)->first();
    }
    public function showOneWithBlog($id)
    {
        return Tag::with(['blog'])->where([
            'id'=>$id,
            'type'=>0
        ])->get();
    }
    public function showAllWithBlog()
    {
        return Tag::with(['blog'])->where('type',0)->get();
    }
    public function showOneWithProduct($id)
    {
        return Tag::with(['product'])->where([
            'id'=>$id,
            'type'=>1
        ])->get();
    }
    public function showAllWithProduct()
    {
        return Tag::with(['product'])->where('type',1)->get();
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'id' => 'required',
            'type' => 'required' //0=> blog  1=>product
        ]);
        return Tag::where('id', $request->id)->update([
            'name' => $request->name,
            'type' => $request->type
        ]);
    }
    public  function destroy($id)
    {
        return Tag::where('id', $id)->delete();
    }
}

