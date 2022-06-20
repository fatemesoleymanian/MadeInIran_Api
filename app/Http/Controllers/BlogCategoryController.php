<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    ////////////////********* this controller has been tested => OK!
    public function showAll()
    {
        // $categories = Cache::remember('category_for_blogs', now()->addHour(1), function () {
        return  BlogCategory::with(['blog'])->orderByDesc('id')->get();
        // });
        // return $categories;
    }
    public function showAllPagi()
    {
        // $categories = Cache::remember('category_for_blogs', now()->addHour(1), function () {
        return  BlogCategory::with(['blog'])->orderByDesc('id')->paginate(10);
        // });
        // return $categories;
    }
    public function showOne($id)
    {
        return BlogCategory::where('id', $id)->first();
    }
    public function showAllWithBlog()
    {
        return BlogCategory::with(['blog'])->orderByDesc('id')->paginate(10);
    }
    public function showOneWithBlog($id)
    {
        return BlogCategory::with(['blog'])->where('id', $id)->first();
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'pageTitle' => 'required',
            'metaKeyword' => 'required',
            'metaDescription' => 'required',
        ]);
        return BlogCategory::where('id', $request->id)->update([
            'name' => $request->name,
            'pageTitle' => $request->pageTitle,
            'metaKeyword' => $request->metaKeyword,
            'metaDescription' => $request->metaDescription,
        ]);
    }
    public  function destroy(Request $id)
    {
        return BlogCategory::where('id', $id->id)->delete();
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'pageTitle' => 'required',
            'metaKeyword' => 'required',
            'metaDescription' => 'required',
        ]);
        return BlogCategory::create([
            'name' => $request->name,
            'pageTitle' => $request->pageTitle,
            'metaKeyword' => $request->metaKeyword,
            'metaDescription' => $request->metaDescription,
        ]);
    }
    public function categoryList()
    {
        return BlogCategory::orderByDesc('id')->get();
    }
}
