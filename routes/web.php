<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('original/', function () {
    return view('posts');
});

//The Route facade provides a static interface to the underlying Illuminate\Routing\Router class
Route::get('post-old-way', function () {
    $post = file_get_contents(__DIR__ . '/../resources/posts/my-first-post.html');

    return view('post', [ //You can pass an array of values to a view which can be referenced in the view
        'post' => $post,
    ]);
});

Route::get('post-old-2/{post}', function ($slug) { //Wild card gets passed to the function as slug
    $path = __DIR__ . "/../resources/posts/{$slug}.html";

    if (! file_exists($path)) {
        //ddd('File does not exist');
        //abort(404);
        return redirect('/');
    }

    $post = cache()->remember("posts.{$slug}", now()->addMinutes(20), function () use ($path) { //This stores what is returned in the cache of the server to avoid repeating expensive operations such as file system acces.
        return file_get_contents($path);
    });
    
    return view('post', [
        'post' => $post,
    ]);
})->where('post', '[A-z_\-]+');//This is how you place constraints on the wildcards
//->whereAlpha('post')
//->whereNumber('post')

Route::get('posts-old/{post}', function ($slug) {
    $post = Post::findOrFail($slug);
    return view('post', [
        'post' => $post,
    ]);
})->where('post', '[A-z_\-]+');

Route::get('posts-old-2/{post}', function ($id) {
    $post = Post::findOrFail($id);
    return view('post', [
        'post' => $post,
    ]);
});

Route::get('posts-old-3/{post:slug}', function (Post $post) { //Route model binding variable name has to match wildcard for this to work. This gets the first post with the matching slug
    return view('post', [
        'post' => $post,
    ]);
});

Route::get('posts/{post:slug}', [PostsController::class, 'show']);

Route::get('/-old', function () {
    $posts = Post::latest()->with('category', 'author')->get(); //Latest will sort the database entries. You can pass a column name into the latest function. With is used to tackle to n+1 problem. All entries are fetched when the view is loaded using one sql query
    return view('posts', [
        'posts' => $posts,
        'categories' => Category::all()
    ]);
});

Route::get('/', [PostsController::class, 'index'])->name('home'); //First example using a controller

/*Route::get('categories/{category:slug}', function (Category $category) {
    return view('posts', [
        'posts' => $category->posts->load(['category', 'author']), //Eaager loading to avoid n+1 problem
        'categories' => Category::all(),
        'currentCategory' => $category
    ]);
});*/

Route::get('authors-old/{author:username}', function (User $author) {
    return view('posts.index', [
        'posts' => $author->posts->load(['category', 'author'])
    ]);
});

Route::get('register', [RegisterController::class, 'create'])->middleware('guest'); //Middleware is executed in betwen requests being received and passed to the controller
Route::post('register', [RegisterController::class, 'store'])->middleware('guest');
Route::post('logout', [SessionsController::class, 'destroy'])->middleware('auth');
Route::get('login', [SessionsController::class, 'create'])->middleware('guest');
Route::post('login', [SessionsController::class, 'store'])->middleware('guest');