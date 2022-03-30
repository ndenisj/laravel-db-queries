<?php

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/showing_data_on_main_page', function () {

    // $result = Category::select('id', 'title')->orderBy('title')->get();
    // $result = Tag::select('id', 'name')->get();
    // $result = Tag::select('id', 'name')->orderByDesc('name')->get(); // order by a column name

    // Order by other related table
    // $result = Tag::select('id', 'name')->orderByDesc(
    //     DB::table('post_tag')
    //         ->selectRaw('count(tag_id) as tag_count')
    //         ->whereColumn('tags.id', 'post_tag.tag_id')
    //         ->orderBy('tag_count', 'desc')
    //         ->limit(1)
    // )->get(); // MOST USED TAGS

    // $result = Post::select('id', 'title')
    //             ->latest()
    //             ->take(5)
    //             ->withCount('comments')
    //             ->get(); // Latest posts

    // MOST POPULAR POSTS (HIGHEST AMOUNT OF COMMENT)
    // $result = Post::select('id', 'title')
    //             ->orderByDesc(
    //                 Comment::selectRaw('count(post_id) as comment_count')
    //                     ->whereColumn('posts.id', 'comments.post_id')
    //                     ->orderBy('comment_count', 'desc')
    //                     ->limit(1)
    //             )
    //             ->withCount('comments')
    //             ->take(5)
    //             ->get();

    // MOST ACTIVE USERS (USER WITH MOST POSTS)
    // $result = User::select('id', 'name')
    //             ->orderByDesc(
    //                 Post::selectRaw('count(user_id) as post_count')
    //                     ->whereColumn('users.id', 'posts.user_id')
    //                     ->orderBy('post_count', 'desc')
    //                     ->limit(1)
    //             )
    //             ->withCount('posts')
    //             ->take(3)
    //             ->get();

    // MOST POPULAR CATEGORY (HIGHEST AMOUNT OF COMMENTS BUT THROUGH POST TABLE (INTERMEDIATE TABLE))
    // $result = Category::select('id', 'title')
    //             ->withCount('comments')
    //             ->orderBy('comments_count', 'desc')
    //             ->take(1)
    //             ->get();

    // QUERIES FOR 1 POST ONLY, WITH TAGS AND COMMENTS WITH THE LIST OF POSTS
    $item_id = 2;
    // $result = Post::with('comments') // eager loading
    //             ->find($item_id);

    // $result = Tag::with(['posts' => function($q) {
    //     $q->select('posts.id', 'posts.title');
    // }])->find($item_id);

    $result = Category::with([
        'posts' => function($q) {
            $q->select('posts.id', 'posts.title', 'posts.category_id');
        },
    ])->find($item_id);

    dump($result);

    return view('welcome');
});

Route::get('/search', function () {

    // USING LIKE OPERATOR
    // $post_title = 'Sapiente';
    // $post_content = 'Reiciendis';

    // $result = DB::table('posts')
    //             ->where('title', 'like', "%$post_title%")
    //             ->orWhere('content', 'like', "%$post_content%")
    //             ->paginate(5);

    // USING FULLTEXT INDEX (create index on posts table/column, its good when the column type is 'text')
    $search_term = '+Sapiente -possium';
    $result = DB::table('posts')
                ->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$search_term])
                ->paginate(5);

    dump($result);

    return view('welcome');
});

Route::get('/sort', function () {

    // sort by column name from same table
    // $search_term = '+Sapiente -possium';
    // //  $sortBy = 'created_at';
    // $sortBy = 'updated_at desc, title asc';
    // $result = DB::table('posts')
    //             ->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$search_term])
    //             ->when($sortBy, function($q, $sortBy){
    //                 return $q->orderByRaw($sortBy, 'desc');
    //             }, function($q){
    //                 return $q->orderBy('title');
    //             }) // when 3rd argument is executed if 2nd is false or null
    //             ->paginate(5);

    // SORT or ORDER By RELATED MODELS
    $search_term = '+Sapiente -possium';
    //  $sortBy = 'created_at';
    $sortBy = 'updated_at desc, title asc';
    $sortByMostCommented = true;
    $result = DB::table('posts')
                ->select('id', 'title')
                ->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$search_term])
                ->when($sortBy, function($q, $sortBy){
                    return $q->orderByRaw($sortBy, 'desc');
                }, function($q){
                    return $q->orderBy('title');
                }) // when 3rd argument is executed if 2nd is false or null
                ->when($sortByMostCommented, function($q){ // run if $sortByMostCommented is true
                    return $q->orderByDesc(
                        DB::table('comments')
                        ->selectRaw('count(comments.post_id)')
                        ->whereColumn('comments.post_id', 'posts.id')
                        ->orderByRaw('count(comments.post_id) DESC')
                        ->limit(1)
                    );
                })
                ->simplePaginate(5);

    dump($result);

    return view('welcome');
});

Route::get('/filter', function () {

    // SORT or ORDER By RELATED MODELS
    $search_term = '+Sapiente -possium';
    //  $sortBy = 'created_at';
    $sortBy = 'updated_at desc, title asc';
    $sortByMostCommented = true;
    $filterByUserId = 1;

    $result = DB::table('posts')
                ->select('id', 'title')
                ->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)", [$search_term]);
    $result->when($filterByUserId, function($q, $filterByUserId){
        return $q->where('user_id', $filterByUserId);
    });
    $result->when($sortBy, function($q, $sortBy){
        return $q->orderByRaw($sortBy, 'desc');
    }, function($q){
        return $q->orderBy('title');
    }); // when 3rd argument is executed if 2nd is false or null
    $result->when($sortByMostCommented, function($q){ // run if $sortByMostCommented is true
        return $q->orderByDesc(
            DB::table('comments')
            ->selectRaw('count(comments.post_id)')
            ->whereColumn('comments.post_id', 'posts.id')
            ->orderByRaw('count(comments.post_id) DESC')
            ->limit(1)
        );
    });
    $result = $result->paginate(5);

    dump($result);

    return view('welcome');
});

Route::get('/create_update_delete', function () {

    // CREATE RECORD
    // $user_id = 1;
    // $category_id = 1;

    // $post = new Post;
    // $post->title = 'post title';
    // $post->content = 'post content';
    // $post->category()->associate($category_id);
    // $result = User::find($user_id)->posts()->save($post);

    // $post_id = 1;

    // $comment = new Comment;
    // $comment->content = 'comment content';
    // $comment->post()->associate($post_id);
    // $result = $comment->save();

    // UPDATING RECORD
    // $post = Post::find(1);
    // $post->title = 'updated title';
    // $result = $post->save();

    // DELETE RECORD
    // $result = $post->delete();

    // UPDATE RECORD WITH MANY 2 MANY RELATIONSHIP
    // $post = Post::find(2);
    // $post->tags()->attach(1); // attach tag to a post
    // $post->tags()->detach(); // delete all tags from a post
    // $result = $post->save();

    $post = Post::find(2);
    // $post->category()->associate(1); // 1:Many // a post belong to only 1 category
    $post->category()->dissociate(1); // remove exsisting category from the post
    $result = $post->save();

    dump($result);

    return view('welcome');
});
