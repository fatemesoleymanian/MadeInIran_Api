<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Blog;
use App\Models\FQ;
use App\Models\FQProduct;
use App\Models\Product;
use App\Models\ProductState;
use App\Models\ProductTag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tag;
use Illuminate\Support\Str;

use Throwable;

class ProductController extends Controller
{
    public function save(CreateProductRequest $request)
    {
        //ATTENTION :: discounted_price ghymte hale hazere, che mahsool off bkhre che nkhre , price age mahsool off bkhre ghymte ghdime age nkhre 0e
        $request->validated();
        $states = $request->states;
        $costs = $request->costs;
        $off = $request->off;
        $dynamic_info = [];
        $tags = [];

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
                'discount' => $request->discount,
                'slug' => Str::slug($request->slug),
            ]);

            foreach ($request->tags as $c) {
                array_push($tags, ['tag_id' => $c, 'product_id' => $product_id->id]);
            }
            ProductTag::insert($tags);


            for ($x = 0; $x < sizeof($states); $x++) {
                array_push($dynamic_info, [
                    'type' => $states[$x],
                    'price' => $costs[$x],
                    'product_id' => $product_id->id,
                    'discounted_price' => $off[$x]
                ]);
            }
            ProductState::insert($dynamic_info);
            DB::commit();
            return response()->json([
                'msg' => Lang::get('messages.success', ['attribute' => 'محصول']),
                'product' => $product_id
            ]);
        } catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json([
                'errors' => Lang::get('messages.fail', ['attribute' => 'محصول']),
                'throw' => $throwable
            ], 401);
        }
        return 'Not done!';
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $request->validated();
        $tags = [];
        $states = $request->states;
        $costs = $request->costs;
        $off = $request->off;
        $ids = $request->ids;

        $dynamic_info = array();

        DB::beginTransaction();
        try {
            $product_id = Product::where('id', $id)->update([
                'name' => $request->name,
                'image' => $request->image,
                'description_excerpt' => $request->description_excerpt,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'metaDescription' => $request->metaDescription,
                'metaKeyword' => $request->metaKeyword,
                'pageTitle' => $request->pageTitle,
                'inventory' => $request->inventory,
                'discount' => $request->discount,
                'slug' => Str::slug($request->slug),
            ]);
            //tag
            ProductTag::where('product_id', $id)->delete();
            foreach ($request->tags as $c) {
                array_push($tags, ['tag_id' => $c, 'product_id' => $id]);
            }
            ProductTag::insert($tags);

            //states
            for ($x = 0; $x < sizeof($states); $x++) {
                array_push($dynamic_info, [
                    'type' => $states[$x],
                    'price' => $costs[$x],
                    'discounted_price' => $off[$x],
                    'product_id' => $id,
                ]);
            }
            $old = ProductState::where('product_id', $id)->get('id');

            if (sizeof($old) > sizeof($dynamic_info)) {
                $x = 0;
                foreach ($old as $o) {
                    $x = 0;
                    $flag = false;
                    foreach ($ids as $i) {
                        if ($o->id == $i) {
                            ProductState::find($i)->update($dynamic_info[$x]);
                            $flag = true;
                            break;
                        }
                        $x++;
                    }
                    if ($flag == false)  ProductState::find($o->id)->delete();
                }
            } else if (sizeof($old) < sizeof($dynamic_info)) {
                $x = 0;
                foreach ($dynamic_info as $d) {
                    if (isset($dynamic_info[$x]) && isset($ids[$x])) ProductState::find($ids[$x])->update($dynamic_info[$x]);
                    if (!isset($ids[$x])) ProductState::insert($d);
                    $x++;
                }
            } else {
                $x = 0;
                foreach ($old as $o) {
                    ProductState::find($ids[$x])->update($dynamic_info[$x]);
                    $x++;
                }
            }


            DB::commit();
            return response()->json([
                'msg' => 'ویرایش محصول با موفقیت انجام شد.',
                'product' => $product_id
            ]);
        } catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json([
                'errors' => Lang::get('messages.fail', ['attribute' => 'محصول']),
                'throw' => $throwable
            ], 401);
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
        $img = Product::where('id', $id)->get('image');
        $imgg = $img[0]->image;
        $upload = new Upload();
        $upload->handydelete($imgg);
        return Product::where('id', $id)->delete();
    }

    public function showOne($id)
    {
        return Product::with(['category', 'tag', 'state', 'comment', 'faq'])
            ->where('id', $id)->first();
    }
    public function showFAQ($id)
    {
        $faq_ids = FQProduct::where('product_id', $id)->get('fq_id');
        return FQ::whereIn('id', $faq_ids)->get();
    }

    public function showAll()
    {
        //front will handle pagination
        // return Product::with(['bookmark', 'category', 'tag', 'state'])->orderByDesc('id')->get();
        // $products = Cache::remember('productss', now()->addMinute(1), function () {
        return Product::with(['category', 'tag', 'state'])->orderByDesc('id')->get();
        // });
        // return $products;
    }
    public function showAllPagi()
    {
        //front will handle pagination
        // return Product::with(['bookmark', 'category', 'tag', 'state'])->orderByDesc('id')->get();
        // $products = Cache::remember('productss', now()->addMinute(1), function () {
//        return Product::with(['category', 'tag', 'state'])->orderByDesc('id')->paginate(10);
        return Product::with(['category', 'tag', 'state'])->orderByDesc('id')->get();
        // });
        // return $products;
    }

    public function showSome()
    {
        // $products = Cache::remember('products_totaly', now()->addMinute(1), function () {
        return Product::with(['category', 'tag', 'state',])->latest()->take(8)->get();
        // });
        // return $products;
    }

    public function show()
    {
        // $products = Cache::remember('products_totaly', now()->addMinute(1), function () {
        return Product::orderByDesc('id')->select('name', 'id')->get();
        // });
        // return $products;
    }

    public function showOneWithCategory($id)
    {
        //with tags and category
        return Product::with(['category', 'tag'])->where('id', $id)->first();
    }

    public function showAllWithCategory()
    {        //with tags and category
        return Product::with(['category', 'tag'])->orderByDesc('id')->paginate(10);
    }

    public function showOneWithState($id)
    {
        return Product::with(['state'])->where('id', $id)->first();
    }

    public function showAllWithState()
    {
        return Product::with(['state'])->orderByDesc('id')->paginate(10);
    }

    ////*************** search suggestion in store *********
    public function searchSuggestion()
    {
        $product = Product::orderByDesc('id')->get('name');
        $tag = DB::table('tags')->orderByDesc('id')->get('name');
        $blog = Blog::orderByDesc('id')->get('title');
        return response()->json([
            'products' => $product,
            'blogs' => $blog,
            'tags' => $tag
        ]);
    }


    ///*************** search product and blogs in store*******
    public function searchBoth($str)
    {
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
            })->orderByDesc('id')->get();

        $blog = Blog::with(['tag', 'category'])
            ->when($str != '', function (Builder $q) use ($str) {
                $q->where('title', 'LIKE', "%{$str}%")
                    ->orWhereHas('category', function (Builder $builder) use ($str) {
                        $builder->where('name', 'LIKE', "%{$str}%");
                    })
                    ->orWhereHas('tag', function (Builder $builder) use ($str) {
                        $builder->where('name', 'LIKE', "%{$str}%");
                    });
            })->orderByDesc('id')->get();
        //age paginate nmikhay ->get() bzar tash na paginate()
        return response()->json([
            'products' => $product,
            'blogs' => $blog
        ]);
    }


    public function searchProducts($str)
    {
        if ($str) {
            $product = null;
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
                })->get();
            return response()->json([
                'products' => $product
            ]);
        }
    }


    //rajebe filter krdn voice grfti too gushit
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
