<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Product;
use App\Models\ProductState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tag;
use Throwable;

class ProductController extends Controller
{
    public function save(Request $request)
    {
        Validator::validate($request->all(),[

            'name'=>"bail|required|string",
            'image'=>"bail|required|string",
            'description_excerpt'=>"bail|required|string",
            'description'=>"bail|required",
            'category_id'=>"bail|required|integer",
            'metaDescription' => 'bail|required|max:100',
            'metaKeyword' => 'bail|required|max:20',
            'pageTitle' => 'required|max:100',
            'states' => 'required',
            'costs' => 'required',
        ],
            [
                'name.required'=>'لطفا نام محصول را وارد کنید!',
                'description_excerpt.required'=>'لطفا چکیده توضیحات محصول را وارد کنید!',
                'description.required'=>'لطفا توضیحات محصول را وارد کنید!',
                'category_id.required'=>'لطفا دسته بندی محصول را وارد کنید!',
                'category_id.integer'=>'لطفا دسته بندی محصول را به درستی وارد کنید!',
                'name.string'=>'لطفا نام محصول را به درستی وارد کنید!',
                'description_excerpt.string'=>'لطفا چکیده توضیحات محصول را به درستی وارد کنید!',
                'image.required'=>'لطفا ادرس فایل را وارد کنید!',
                'image.string'=>'لطفا آدرس فایل را به درستی وارد کنید!',
                'metaDescription.required'=>'لطفا توضیحات متا را به درستی وارد کنید!',
                'metaDescription.max'=>'حداکثر تعداد حروف 100 حرف میباشد!',
                'metaKeyword.required'=>'لطفا کلمه کلیدی متا را به درستی وارد کنید!',
                'metaKeyword.max'=>'حداکثر تعداد حروف 20 حرف میباشد!',
                'pageTitle.required'=>'لطفا تیتر صفحه را به درستی وارد کنید!',
                'states.required'=>'لطفا اطلاعات متغیر محصول را وارد کنید!',
                'costs.required'=>'لطفا اطلاعات متغیر محصول را وارد کنید!',
                'pageTitle.max'=>'حداکثر تعداد حروف 100 حرف میباشد!',
            ]);
        $states = $request->states;
        $costs = $request->costs;
        $dynamic_info =[] ;

        DB::beginTransaction();
        try {
            $product_id = Product::create([
                'name' => $request->name,
                'image' => $request->image,
                'description_excerpt' => $request->description_excerpt,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'metaDescription' => $request->metaDescription,
                'metaKeyword' => $request->metaKeyword,
                'pageTitle' => $request->pageTitle,
                'inventory' => $request->inventory,
            ]);


            for ($x = 0; $x < sizeof($states); $x++) {
                array_push($dynamic_info ,[
                    'type' => $states[$x],
                    'price' => $costs[$x],
                    'product_id' => $product_id->id
                ] );
            }
            ProductState::insert($dynamic_info);
            DB::commit();
            return response()->json([
                'msg'=>Lang::get('messages.success',['attribute' => 'محصول']),
                'product' => $product_id
            ]);
        }
        catch (Throwable $throwable)
        {
            DB::rollBack();
            return response()->json([
                'errors'=>Lang::get('messages.fail',['attribute' => 'محصول'])
            ],401);
        }
        return 'Not done!';

    }
    public function update(Request $request , $id)
    {
        Validator::validate($request->all(),[

            'name'=>"bail|required|string",
            'image'=>"bail|required|string",
            'description_excerpt'=>"bail|required|string",
            'description'=>"bail|required",
            'category_id'=>"bail|required|integer",
            'metaDescription' => 'bail|required|max:100',
            'metaKeyword' => 'bail|required|max:20',
            'pageTitle' => 'required|max:100',
            'states' => 'required',
            'costs' => 'required',
        ],
            [
                'name.required'=>'لطفا نام محصول را وارد کنید!',
                'description_excerpt.required'=>'لطفا چکیده توضیحات محصول را وارد کنید!',
                'description.required'=>'لطفا توضیحات محصول را وارد کنید!',
                'category_id.required'=>'لطفا دسته بندی محصول را وارد کنید!',
                'category_id.integer'=>'لطفا دسته بندی محصول را به درستی وارد کنید!',
                'name.string'=>'لطفا نام محصول را به درستی وارد کنید!',
                'description_excerpt.string'=>'لطفا چکیده توضیحات محصول را به درستی وارد کنید!',
                'image.required'=>'لطفا ادرس فایل را وارد کنید!',
                'image.string'=>'لطفا آدرس فایل را به درستی وارد کنید!',
                'metaDescription.required'=>'لطفا توضیحات متا را به درستی وارد کنید!',
                'metaDescription.max'=>'حداکثر تعداد حروف 100 حرف میباشد!',
                'metaKeyword.required'=>'لطفا کلمه کلیدی متا را به درستی وارد کنید!',
                'metaKeyword.max'=>'حداکثر تعداد حروف 20 حرف میباشد!',
                'pageTitle.required'=>'لطفا تیتر صفحه را به درستی وارد کنید!',
                'states.required'=>'لطفا اطلاعات متغیر محصول را وارد کنید!',
                'costs.required'=>'لطفا اطلاعات متغیر محصول را وارد کنید!',
                'pageTitle.max'=>'حداکثر تعداد حروف 100 حرف میباشد!',
            ]);
        $states = $request->states;
        $costs = $request->costs;
        $dynamic_info =[] ;

        DB::beginTransaction();
        try {
            $product_id = Product::where('id',$id)->update([
                'name' => $request->name,
                'image' => $request->image,
                'description_excerpt' => $request->description_excerpt,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'metaDescription' => $request->metaDescription,
                'metaKeyword' => $request->metaKeyword,
                'pageTitle' => $request->pageTitle,
                'inventory' => $request->inventory,
            ]);
            ProductState::where('product_id',$id)->delete();

            for ($x = 0; $x < sizeof($states); $x++) {
                array_push($dynamic_info ,[
                    'type' => $states[$x],
                    'price' => $costs[$x],
                    'product_id' => $id
                ] );
            }
            ProductState::insert($dynamic_info);
            DB::commit();
            return response()->json([
                'msg'=>Lang::get('messages.success',['attribute' => 'محصول']),
                'product' => $product_id
            ]);
        }
        catch (Throwable $throwable)
        {
            DB::rollBack();
            return response()->json([
                'errors'=>Lang::get('messages.fail',['attribute' => 'محصول'])
            ],401);
        }
        return 'Not done!';


    }
    public function destroy($id)
    {
        if (!$id) {
            return response()->json([
                'errors' => Lang::get('messages.nochoosen')
            ], 401);
        }
        $img = Product::where('id',$id)->get('image');
        $imgg = $img[0]->image;
        $upload = new Upload();
        $upload->handydelete($imgg);
        return Product::where('id', $id)->delete();
    }
    public function showOne($id)
    {
        return Product::where('id',$id)->orderByDesc('id')->get();
    }
    public function showAll()
    {
        return Product::orderByDesc('id')->get();
    }
    public function showOneWithCategory($id)
    {
        //with tags and category
     return Product::with(['category','tag'])->where('id',$id)->first();
    }
    public function showAllWithCategory()
    {        //with tags and category
        return Product::with(['category','tag'])->orderByDesc('id')->get();
    }
    public function showOneWithState($id)
    {
        return Product::with(['state'])->where('id',$id)->first();
    }
    public function showAllWithState()
    {
        return Product::with(['state'])->orderByDesc('id')->get();
    }

    ////*************** search suggestion in store *********
    public function searchSuggestion()
    {
        return Product::orderByDesc('id')->get('name');
    }
    ///*************** search product and blogs in store*******
    public function search($str)
    {
        if ($str) {

            $product = Product::with(['category', 'state', 'tag'])
                ->when($str != '', function (Builder $q) use ($str) {
                    $q->where('name', 'LIKE', "%{$str}%")
                        ->orWhereHas('category', function (Builder $builder) use ($str) {
                            $builder->where('name', 'LIKE', "%{$str}%");
                        })
                        ->orWhereHas('tag', function (Builder $builder) use ($str) {
                            $builder->where('name', 'LIKE', "%{$str}%");
                        })
                        ->orWhereHas('state', function (Builder $builder) use ($str) {
                            $builder->where('type', 'LIKE', "%{$str}%");
                        });
                })->paginate(10);

            $blog = Blog::with(['tag','category'])
                ->when($str != '' , function (Builder $q) use ($str){
                $q->where('title','LIKE',"%{$str}%")
                    ->orWhereHas('category', function (Builder $builder) use ($str) {
                        $builder->where('name', 'LIKE', "%{$str}%");
                    })
                    ->orWhereHas('tag', function (Builder $builder) use ($str) {
                        $builder->where('name', 'LIKE', "%{$str}%");
                    });
            })->paginate(10);
            //age paginate nmikhay ->get() bzar tash na paginate()
            return response()->json([
            'products'=>$product,
            'blogs'=>$blog
            ]);
        }
    }

    ///********** search in tags , blogs and products in admin panel*****//
    public function adminSearch($str)
    {
        if ($str) {

            $product = Product::with(['category', 'state', 'tag'])
                ->when($str != '', function (Builder $q) use ($str) {
                    $q->where('name', 'LIKE', "%{$str}%")
                        ->orWhereHas('category', function (Builder $builder) use ($str) {
                            $builder->where('name', 'LIKE', "%{$str}%");
                        })
                        ->orWhereHas('tag', function (Builder $builder) use ($str) {
                            $builder->where('name', 'LIKE', "%{$str}%");
                        })
                        ->orWhereHas('state', function (Builder $builder) use ($str) {
                            $builder->where('type', 'LIKE', "%{$str}%");
                        });
                })->paginate(10);

            $blog = Blog::with(['tag','category'])
                ->when($str != '' , function (Builder $q) use ($str){
                    $q->where('title','LIKE',"%{$str}%")
                        ->orWhereHas('category', function (Builder $builder) use ($str) {
                            $builder->where('name', 'LIKE', "%{$str}%");
                        })
                        ->orWhereHas('tag', function (Builder $builder) use ($str) {
                            $builder->where('name', 'LIKE', "%{$str}%");
                        });
                })->paginate(10);

            $tag =  DB::table('tags')->where('name','LIKE',"%{$str}%")->paginate(10);
            //age paginate nmikhay ->get() bzar tash na paginate()
            return response()->json([
                'products'=>$product,
                'blogs'=>$blog,
                'tags'=>$tag
            ]);
        }
    }



    ///************ Filter Product in both panel and store
    /// there is no search string
//    public function filter(Request $request)
//    {
//        $priceSort ='';
//        $normalSort = '';
//        $model = $request->model;
//
//        $price = $request->price;
//        $color_ids=[];
//        foreach ($request->color as $c)
//        {
//            array_push($color_ids,$c);
//        }
//        $brand=[];
//        foreach ($request->brand as $c)
//        {
//            array_push($brand,$c);
//        }
//        $category=[];
//        foreach ($request->category as $c)
//        {
//            array_push($category,$c);
//        }
//        if ($request->sort == 'amount') {
//            $normalSort='id';
//            $priceSort = $request->sort;
//        }
//        else {
//            $normalSort = $request->sort;
//            $priceSort='id';
//        }
//
//        $products = Product::with(['category','color','brand','catFilter'])
//            ->when($color_ids, function ($q) use ($color_ids){
//                $q->whereHas('color',function ($qu) use($color_ids){
//                    $qu->whereIn('color_id',$color_ids);
//                });
//            })
//            ->when($brand,function ($q) use ($brand){
//                $q->whereIn('brand_id',$brand);
//
//            })
//            ->when($category , function ($q) use ($category){
//                $q->whereHas('catFilter', function ($query) use ($category){
//                    $query->whereIn('category_id',$category);
//                });
//            })
//            ->when($request->inventory , function ($q) {
//                $q->where('inventory','>',0);
//            })
//            ->when($request->model , function ($q) use ($model){
//                $q->where('model',$model);
//            })
//            ->when($request->guarantee , function ($q) {
//                $q->where('guarantee','>',0);
//            })
//            ->orderBy($normalSort,$request->direction)->get();
//        $price = DB::table('prices')
//            ->when($request->price , function ($q) use ($price){
//                $q->where('off',0)->where('amount','>=',$price[0])->where('amount','<=',$price[1])
//                    ->orWhere('off','!=',0)->where('discount','>=',$price[0])->where('discount','<=',$price[1])
//                    ->orWhere('special','!=',0)->where('special','>=',$price[0])->where('special','<=',$price[1]);
//            })
//            ->when($request->off,function ($q){
//                $q->where('off','>',0);
//            })
//            ->when($request->special,function ($q){
//                $q->where('special','>',0);
//            })
//            ->orderBy($priceSort,$request->direction)->get('product_id');
//
//        $prices =[];
//        foreach ($price as $p)
//        {
//            array_push($prices,$p->product_id);
//        }
//        return $products->whereIn('id',$prices)->toQuery()->paginate(1);
//    }
}
