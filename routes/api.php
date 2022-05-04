<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStateController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UnderConstructionController;
use App\Http\Controllers\Upload;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


    Route::post('/under_const/save', [UnderConstructionController::class, 'save']);
    Route::get('/under_const/show', [UnderConstructionController::class, 'showAll']);

//Blog
    Route::get('/blogs', [BlogController::class, 'showAll']);
    Route::get('/blogs/{id}', [BlogController::class, 'showOne']);

//category for blog
    Route::get('/blog_categories', [BlogCategoryController::class, 'showAll']);
    Route::get('/blog_categories/{id}', [BlogCategoryController::class, 'showOne']);
    Route::get('/blog_categories_with_blog', [BlogCategoryController::class, 'showAllWithBlog']);
    Route::get('/blog_categories_with_blog/{id}', [BlogCategoryController::class, 'showOneWithBlog']);


//tag
    Route::get('/tags', [TagController::class, 'showAll']);
    Route::get('/tags/{id}', [TagController::class, 'showOne']);
    Route::get('/tags_with_blog', [TagController::class, 'showAllWithBlog']);
    Route::get('/tags_with_blog/{id}', [TagController::class, 'showOneWithBlog']);
    Route::get('/tags_with_product', [TagController::class, 'showAllWithProduct']);
    Route::get('/tags_with_product/{id}', [TagController::class, 'showOneWithProduct']);

//department
    Route::get('/departments', [DepartmentController::class, 'showAll']);
    Route::get('/departments/{id}', [DepartmentController::class, 'showOne']);
    Route::get('/departments_with_category', [DepartmentController::class, 'showAllWithCategory']);
    Route::get('/departments_with_category/{id}', [DepartmentController::class, 'showOneWithCategory']);

//categories for products
    Route::get('/categories', [CategoryController::class, 'showAll']);
    Route::get('/categories/{id}', [CategoryController::class, 'showOne']);
    Route::get('/categories_with_department', [CategoryController::class, 'showAllWithDepartment']);
    Route::get('/categories_with_department/{id}', [CategoryController::class, 'showOneWithDepartment']);
    Route::get('/categories_with_product', [CategoryController::class, 'showAllWithProduct']);
    Route::get('/categories_with_product/{id}', [CategoryController::class, 'showOneWithProduct']);

//products
    Route::get('/products', [ProductController::class, 'showAll']);
    Route::get('/products/{id}', [ProductController::class, 'showOne']);
    Route::get('/products_with_category', [ProductController::class, 'showAllWithCategory']);
    Route::get('/products_with_category/{id}', [ProductController::class, 'showOneWithCategory']);
    Route::get('/products_with_state', [ProductController::class, 'showAllWithState']);
    Route::get('/products_with_state/{id}', [ProductController::class, 'showOneWithState']);
    Route::get('/products_search/{str}', [ProductController::class, 'search']);
    Route::get('/products_search_suggestion', [ProductController::class, 'searchSuggestion']);

//store user
    Route::post('/login_or_reg', [UserController::class, 'loginOrRegister']);
    Route::post('/confirm_code', [UserController::class, 'finishLogin']);

    //admin panel
    Route::post('admin/forget_password', [AdminController::class, 'forgetPassword']);
    Route::post('admin/reset_password', [AdminController::class, 'resetPassword']);
    Route::post('admin/auth/login', [AdminController::class, 'login']);
Route::put('/products/{id}', [ProductController::class, 'update']);
///////////////////**************** Needs Authentication *************************//////////

    Route::group(['middleware' => ['auth:sanctum']], function () {
//store user
    Route::put('/update_acc', [UserController::class, 'update']);
    Route::delete('/delete_acc', [UserController::class, 'deleteAccount']);
    Route::get('/show_acc/{id}', [UserController::class, 'show']);
    Route::post('/logout', [UserController::class, 'logout']);


//admin panel
    Route::get('/admin_search/{str}', [ProductController::class, 'adminSearch']);
    Route::post('admin/auth/register', [AdminController::class, 'register']);
    Route::post('admin/logout', [AdminController::class, 'logout']);
    Route::get('admin/show/{id}', [AdminController::class, 'showOne']);
    Route::get('admins/show', [AdminController::class, 'showAll']);
    Route::put('admin/edit', [AdminController::class, 'update']);
    Route::delete('admin/delete', [AdminController::class, 'delete']);


    //role
     Route::post('admin/role',[RoleController::class,'save']);
     Route::put('admin/role',[RoleController::class,'update']);
     Route::get('admin/role',[RoleController::class,'showAll']);
     Route::get('admin/role/{id}',[RoleController::class,'showOne']);
     Route::delete('admin/role',[RoleController::class,'delete']);


//upload image
    Route::post('/upload', [Upload::class, 'uploadImage']);
    Route::post('/remove_upload', [Upload::class, 'deleteUploaded']);
    Route::post('/remove_uploads', [Upload::class, 'deleteGroupImages']);

//blog
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::post('/blog_like', [BlogController::class, 'like']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
    Route::post('/blogs', [BlogController::class, 'save']);

    //category for blog
    Route::put('/blog_categories', [BlogCategoryController::class, 'update']);
    Route::delete('/blog_categories/{id}', [BlogCategoryController::class, 'destroy']);
    Route::post('/blog_categories', [BlogCategoryController::class, 'save']);

    //tag
    Route::post('/tags', [TagController::class, 'save']);
    Route::put('/tags', [TagController::class, 'update']);
    Route::delete('/tags/{id}', [TagController::class, 'destroy']);

    //department
    Route::post('/departments', [DepartmentController::class, 'save']);
    Route::put('/departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy']);

    //categories for products
    Route::post('/categories', [CategoryController::class, 'save']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    //products
    Route::post('/products', [ProductController::class, 'save']);
//    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    //product filter
    Route::get('/products_filter/{type}', [ProductStateController::class, 'filterOnState']);

    //bookmark
    Route::post('/bookmark', [BookmarkController::class, 'save']);
    Route::get('/bookmark/{id}', [BookmarkController::class, 'show']);
    Route::get('/bookmark_user/{product}', [BookmarkController::class, 'showByProduct']);
    Route::delete('/bookmark', [BookmarkController::class, 'remove']);

    //card
    Route::post('/card', [CardController::class, 'save']);
    Route::get('/card/{user}', [CardController::class, 'show']);
    Route::delete('/card', [CardController::class, 'remove']);
    Route::get('/show_cards/{product}', [CardProductController::class, 'showCards']);
    Route::get('/card_count/{product}', [CardController::class, 'countOfProduct']);
    Route::post('/card_change_count', [CardProductController::class, 'changeCount']);

    //order
    Route::post('order/step_1', [OrderController::class, 'saveCard']);
    Route::post('order/step_2', [OrderController::class, 'userInfo']);
    Route::get('order/step_3', [OrderController::class, 'showOrder']);
    Route::get('order/user/{user}', [OrderController::class, 'showUserInfo']);
    Route::get('orders/{user}', [OrderController::class, 'showAllByUser']);
    Route::get('orders', [OrderController::class, 'showAll']);
    Route::post('order/change_state', [OrderController::class, 'changeState']);
    Route::get('order/state/{id}', [OrderController::class, 'showState']);

});

    //inja ba middleware check miknim blogger joz blog be chizi dstresi ndashte bashe
//    Route::group(['middleware' => ['auth:sanctum'],[AdminCheck::class]], function () {
//
//    //admin panel
//        Route::post('admin/auth/register', [AdminController::class, 'register']);
//        Route::get('admin/show/{id}', [AdminController::class, 'showOne']);
//        Route::get('admins/show', [AdminController::class, 'showAll']);
//        Route::put('admin/edit', [AdminController::class, 'update']);
//        Route::delete('admin/delete', [AdminController::class, 'delete']);
//
//
//        //role
//        Route::post('admin/role',[RoleController::class,'save']);
//        Route::put('admin/role',[RoleController::class,'update']);
//        Route::get('admin/role',[RoleController::class,'showAll']);
//        Route::get('admin/role/{id}',[RoleController::class,'showOne']);
//        Route::delete('admin/role',[RoleController::class,'delete']);
//
//
//        //category for blog
//        Route::put('/blog_categories', [BlogCategoryController::class, 'update']);
//        Route::delete('/blog_categories/{id}', [BlogCategoryController::class, 'destroy']);
//        Route::post('/blog_categories', [BlogCategoryController::class, 'save']);
//
//        //tag
//        Route::post('/tags', [TagController::class, 'save']);
//        Route::put('/tags', [TagController::class, 'update']);
//        Route::delete('/tags/{id}', [TagController::class, 'destroy']);
//
//    });

Route::get('/test', function () {
    return response()->json(
        'salam'
    );
});
Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
});

Route::get('/config-clear', function () {
    Artisan::call('config:clear');
});

