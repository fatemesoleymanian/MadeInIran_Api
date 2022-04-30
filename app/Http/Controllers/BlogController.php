<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogLike;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class BlogController extends Controller
{
    ////////////////********* this methods have been tested => OK!
    public function like(Request $request)
    {
        return BlogLike::create([
            'blog_id' => $request->blog_id ,
            'user_id' => $request->user_id
        ]);
    }
    public function showAll()
    {
        return Blog::with(['tag', 'category'])->orderByDesc('id')->paginate(10);
    }
    public function showOne($id)
    {
        return Blog::with(['tag', 'category'])->where('id',$id)->first();
    }
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:blogs',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required|max:100',
            'metaKeyword' => 'required|max:100',
            'pageTitle' => 'required|max:100',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
        $categories=$request->category_id;
        $tags=$request->tag_id;

        $blogTags=[];
        DB::beginTransaction();
        try {
            Blog::where('id', $id)->update([
                'title'=>$request->title,
                'post'=>$request->post,
                'post_excerpt'=>$request->post_excerpt,
                'metaDescription'=>$request->metaDescription,
                'metaKeyword' =>$request->metaKeyword,
                'pageTitle' => $request->pageTitle,
                'slug'=>Str::slug($request->title),
                'category_id' => $categories,
                'featuredImage' =>$request->featuredImage
            ]);
            // insert blog tags
            foreach ($tags as $t) {
                array_push($blogTags, ['tag_id' => $t, 'blog_id' => $id]);
            }
            Blogtag::where('blog_id', $id)->delete();
            Blogtag::insert($blogTags);
            DB::commit();
            return response()->json([
                'errors'=>Lang::get('messages.blogsuccess')
            ]);
        } catch (\Throwable $e) {

            DB::rollback();
            return response()->json([
                'errors'=>Lang::get('messages.blogfailed')
            ],401);
            return 'not done';
        }
    }
    public  function destroy($id)
    {
        if (!$id) {
            return response()->json([
                'errors' => Lang::get('messages.nochoosen')
            ], 401);
        }
        $img = Blog::where('id',$id)->get('featuredImage');
        $imgg = $img[0]->featuredImage;
        $upload = new Upload();
        $upload->handydelete($imgg);
        return Blog::where('id', $id)->delete();

    }
    public function save(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:blogs',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required|max:100',
            'metaKeyword' => 'required|max:100',
            'pageTitle' => 'required|max:100',
            'category_id' => 'required',
            'tag_id' => 'required',
            'featuredImage' =>'required'
        ]);
        $categories=$request->category_id;
        $tags=$request->tag_id;
//return $tags;
        $blogTags=[];
        DB::beginTransaction();
        try {
            $blog=Blog::create(
                [
                    'title'=>$request->title,
                    'post'=>$request->post,
                    'post_excerpt'=>$request->post_excerpt,
                    'metaDescription'=>$request->metaDescription,
                    'metaKeyword' =>$request->metaKeyword,
                    'pageTitle' => $request->pageTitle,
                    'slug'=>Str::slug($request->title),
                    'category_id' => $categories,
                    'featuredImage' =>$request->featuredImage
                ]
            );

            foreach ($tags as $t)
            {
                array_push($blogTags,['tag_id'=>$t,'blog_id'=>$blog->id]);
            }
            Blogtag::insert($blogTags);
            DB::commit();
            return response()->json([
                'msg'=>Lang::get('messages.blogsuccess')
            ]);
        }
        catch (Throwable $throwable)
        {
            DB::rollBack();
            return response()->json([
                'errors'=>Lang::get('messages.blogfailed')
            ],401);
        }
        return 'Not done!';
    }

}


