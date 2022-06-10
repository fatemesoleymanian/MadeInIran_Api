<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        // $tags = Cache::remember('tags_for_blogs',now()->addHour(1),function (){
        return Tag::with(['blog', 'product'])->orderByDesc('id')->get();
        // });
        // return $tags;
    }
    public function showOne($id)
    {
        return Tag::where('id', $id)->first();
    }
    public function showOneWithBlog($id)
    {
        return Tag::with(['blog'])->where([
            'id' => $id,
            'type' => 0
        ])->get();
    }
    public function showAllWithBlog()
    {
        return Tag::with(['blog'])->where('type', 0)->paginate(10);
    }
    public function showOneWithProduct($id)
    {
        return Tag::with(['product'])->where([
            'id' => $id,
            'type' => 1
        ])->get();
    }
    public function showAllWithProduct()
    {
        return Tag::with(['product'])->where('type', 1)->get();
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
    public  function destroy(Request $request)
    {
        return Tag::where('id', $request->id)->delete();
    }
    public function forProducts()
    {
        return Tag::where('type', 1)->orderByDesc('id')->get();
    }
    public function forblogs()
    {
        return Tag::where('type', 0)->orderByDesc('id')->get();
    }
}
