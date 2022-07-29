<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DelseyFormController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FQController;
use App\Http\Controllers\FQFormController;
use App\Http\Controllers\JobProductionController;
use App\Http\Controllers\JobProductionIdeaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductCommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductStateController;
use App\Http\Controllers\RequestForRepresentationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnderConstructionController;
use App\Http\Controllers\Upload;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminCheck;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::post('/under_const/save', [UnderConstructionController::class, 'save']);
Route::get('/under_const/show', [UnderConstructionController::class, 'showAll']);
Route::post('/catalog/representation', [RequestForRepresentationController::class, 'save']);
Route::post('/catalog_delsey/representation', [DelseyFormController::class, 'save']);

//job production empty
Route::post('/job_production_empty', [JobProductionController::class, 'save']);
Route::get('/job_production_empty', [JobProductionController::class, 'show']);

//job production ideas
Route::post('/job_production_ideas', [JobProductionIdeaController::class, 'save']);
Route::get('/job_production_ideas', [JobProductionIdeaController::class, 'show']);


//Blog
Route::get('/blogs', [BlogController::class, 'showAll']);
Route::get('/blogs/{id}', [BlogController::class, 'showOne']);
Route::get('/blogs_id/{id}', [BlogController::class, 'showIds']);
Route::get('blogs/search/{str}', [BlogController::class, 'search']);
Route::get('/blogs_latest', [BlogController::class, 'latestFour']);
Route::get('/blogs-random', [BlogController::class, 'showSome']);


//category for blog
Route::get('/blog_categories', [BlogCategoryController::class, 'showAll']);
Route::get('/blog_categories/{id}', [BlogCategoryController::class, 'showOne']);
Route::get('/blog_categories_with_blog', [BlogCategoryController::class, 'showAllWithBlog']);
Route::get('/blog_categories_with_blog/{id}', [BlogCategoryController::class, 'showOneWithBlog']);
Route::get('/blog_categories_list', [BlogCategoryController::class, 'categoryList']);


//tag
Route::get('/tags', [TagController::class, 'showAll']);
Route::get('/tags_pagi', [TagController::class, 'showAllPagi']);
Route::get('/tags/{id}', [TagController::class, 'showOne']);
Route::get('/tags_with_blog', [TagController::class, 'showAllWithBlog']);
Route::get('/tags_with_blog/{id}', [TagController::class, 'showOneWithBlog']);
Route::get('/tags_with_product', [TagController::class, 'showAllWithProduct']);
Route::get('/tags_with_product/{id}', [TagController::class, 'showOneWithProduct']);
Route::get('/tags_only_pro', [TagController::class, 'forProducts']);
Route::get('/tags_only_blog', [TagController::class, 'forblogs']);

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

//testimonial
Route::post('/testimonial', [TestimonialController::class, 'save']);
Route::put('/testimonial{id}', [TestimonialController::class, 'update']);
Route::get('/testimonial', [TestimonialController::class, 'show']);
Route::delete('/testimonial', [TestimonialController::class, 'destroy']);

//products
Route::get('/products', [ProductController::class, 'showAll']);
Route::get('/products_page', [ProductController::class, 'showAllPagi']);
Route::get('/products/{id}', [ProductController::class, 'showOne']);
Route::get('/products_with_category', [ProductController::class, 'showAllWithCategory']);
Route::get('/products_with_category/{id}', [ProductController::class, 'showOneWithCategory']);
Route::get('/products_with_state', [ProductController::class, 'showAllWithState']);
Route::get('/products_with_state/{id}', [ProductController::class, 'showOneWithState']);
Route::get('/products_search/{str}', [ProductController::class, 'searchBoth']);
Route::get('/just_products_search/{str}', [ProductController::class, 'searchProducts']);
Route::get('/products_search_suggestion', [ProductController::class, 'searchSuggestion']);
Route::get('/products_faq{id}', [ProductController::class, 'showFAQ']);
Route::get('/products_random', [ProductController::class, 'showSome']);
Route::get('/products_totaly', [ProductController::class, 'show']);


//store user
Route::post('/login_or_reg', [UserController::class, 'loginOrRegister']);
Route::post('/confirm_code', [UserController::class, 'finishLogin']);

//admin panel
Route::post('admin/forget_password', [AdminController::class, 'forgetPassword']);
Route::post('admin/reset_password', [AdminController::class, 'resetPassword']);
Route::post('admin/auth/login', [AdminController::class, 'login']);
Route::put('/products/{id}', [ProductController::class, 'update']);

//slider for home page
Route::get('/slider_home', [SliderController::class, 'showHomeSlider']);



///////////////////**************** Needs Authentication *************************//////////

Route::group(['middleware' => ['auth:sanctum']], function () {
    //store user
    Route::put('/update_acc', [UserController::class, 'update']);
    Route::delete('/delete_acc', [UserController::class, 'deleteAccount']);
    Route::get('/users', [UserController::class, 'showAll']);
    Route::get('/show_acc/{id}', [UserController::class, 'show']);
    Route::post('/logout', [UserController::class, 'logout']);


    //admin panel
    Route::get('/admin_search/{str}', [AdminController::class, 'adminSearch']);
    Route::post('admin/auth/register', [AdminController::class, 'register']);
    Route::post('admin/logout', [AdminController::class, 'logout']);
    Route::get('admin/show/{id}', [AdminController::class, 'showOne']);
    Route::get('admins/show', [AdminController::class, 'showAll']);
    Route::put('admin/edit', [AdminController::class, 'update']);
    Route::delete('admin/delete', [AdminController::class, 'delete']);

    //slider for admin panel
    Route::post('/slider', [SliderController::class, 'create']);
    Route::put('/slider', [SliderController::class, 'update']);
    Route::delete('/slider', [SliderController::class, 'delete']);
    Route::get('/slider', [SliderController::class, 'showAll']);
    Route::get('/slider{id}', [SliderController::class, 'showOne']);


    //role
    Route::post('admin/role', [RoleController::class, 'save']);
    Route::put('admin/role', [RoleController::class, 'update']);
    Route::get('admin/role', [RoleController::class, 'showAll']);
    Route::get('admin/role/{id}', [RoleController::class, 'showOne']);
    Route::delete('admin/role', [RoleController::class, 'delete']);

    //permission
    Route::put('/permission', [PermissionController::class, 'change']);

    //module
    Route::get('/modules', function () {
        return Module::all();
    });

    //Product Comments
    Route::post('pcomment/save', [ProductCommentController::class, 'save']);
//    Route::put('pcomment/update{id}', [ProductCommentController::class, 'confirmComment']);
    Route::get('pcomment/all', [ProductCommentController::class, 'showRequests']);


    //upload image
    Route::post('/upload', [Upload::class, 'uploadImage']);
    Route::post('/remove_upload', [Upload::class, 'deleteUploaded']);
    Route::post('/remove_uploads', [Upload::class, 'deleteGroupImages']);
    Route::post('/upload_slider', [Upload::class, 'uploadSliderImage']);

    //blog
    Route::put('/blogs/{id}', [BlogController::class, 'update']);
    Route::post('/blog_like', [BlogController::class, 'like']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);
    Route::post('/blogs', [BlogController::class, 'save']);

    //category for blog
    Route::put('/blog_categories', [BlogCategoryController::class, 'update']);
    Route::delete('/blog_categories', [BlogCategoryController::class, 'destroy']);
    Route::post('/blog_categories', [BlogCategoryController::class, 'save']);
    Route::get('/blog_categories_pagi', [BlogCategoryController::class, 'showAllPagi']);


    //tag
    Route::post('/tags', [TagController::class, 'save']);
    Route::put('/tags', [TagController::class, 'update']);
    Route::delete('/tags', [TagController::class, 'destroy']);


    //department
    Route::post('/departments', [DepartmentController::class, 'save']);
    Route::put('/departments/{id}', [DepartmentController::class, 'update']);
    Route::delete('/departments', [DepartmentController::class, 'destroy']);

    //categories for products
    Route::post('/categories', [CategoryController::class, 'save']);
    Route::put('/categories', [CategoryController::class, 'update']);
    Route::delete('/categories', [CategoryController::class, 'destroy']);
    Route::get('/categories_pagi', [CategoryController::class, 'showAllPagi']);


    //FAQ
    Route::post('/faq', [FQController::class, 'save']);
    Route::put('/faq{id}', [FQController::class, 'update']);
    Route::get('/faq', [FQController::class, 'showAll']);
    Route::delete('/faq', [FQController::class, 'delete']);

    //FAQ Form
    Route::post('/faq_form', [FQFormController::class, 'save']);
    Route::get('/faq_form', [FQFormController::class, 'show']);

    //products
    Route::post('/products', [ProductController::class, 'save']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
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
    Route::get('/card_one_pro/{id}', [CardController::class, 'showOneProduct']);
    Route::delete('/card', [CardController::class, 'remove']);
    Route::delete('/empty_card', [CardController::class, 'emptyCard']);
    Route::get('/show_cards/{product}', [CardProductController::class, 'showCards']);
    Route::get('/card_count/{product}', [CardController::class, 'countOfProduct']);
    Route::post('/card_inc_count', [CardProductController::class, 'increaseQuantity']);
    Route::post('/card_dec_count', [CardProductController::class, 'decreaseQuantity']);

    //order
    Route::post('order/step_1', [OrderController::class, 'saveCardAndOrderAfterPay']);
    Route::post('order/step_2', [OrderController::class, 'userInfo']);
    Route::get('order/step_3/{user}', [OrderController::class, 'showOrder']);
    Route::get('order/user/{user}', [OrderController::class, 'showUserInfo']);
    Route::get('/orders/{user}', [OrderController::class, 'showAllByUser']);
    Route::get('orders', [OrderController::class, 'showAll']);
    Route::post('order/change_state', [OrderController::class, 'changeState']);
    Route::get('order/state/{id}', [OrderController::class, 'showState']);
    Route::delete('order/delete', [OrderController::class, 'delete']);
    Route::get('order/items{card}', [OrderController::class, 'showPastOrderItems']);

    //transactions and payment
    Route::post('/payment',[TransactionController::class,'payment']);
    Route::post('/payment_verify',[TransactionController::class,'verify']);
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
Route::get('/foo', function () {
    Artisan::call('storage:link');
});
Route::get('/queue-listen', function () {
    Artisan::call('queue:listen');
});
