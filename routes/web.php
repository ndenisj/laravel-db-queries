<?php

use App\Http\Resources\UserResource;
use App\Http\Resources\UsersCollection;
use App\Models\Address;
use App\Models\City;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Image;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
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

Route::get('/', function () {

    // $user = DB::select('select * from users where id = ?', [1]);
    // $users = DB::connection('sqlite')->select('select * from users');

    // dump("mysql:",$user);
    // dump("sqlite:",$users);

    // DB query builder
    // $result1 = DB::select('select * from users where id = ? and name = ?', [1, 'Lennie Will PhD']);
    // $result = DB::select('select * from users where id = :id', ['id'=>1]);

    // $result = DB::insert('insert into users (name, email, password) values (?, ?, ?)',['Paul', 'paul@email.com','123456']);

    // $result = DB::update('update users set email = "paul2@email.com" where email = ?', ['paul@email.com']);

    // DB::statement('truncate table users');

    // $result = DB::delete('delete from users where id = ?', [4]);

    // dump('Result: ', $result);

    // COMPARE
    // DB Facades vs Query Builder vs Eloquent Builder
    // $result = DB::select('select * from users');
    // $result = DB::table('users')->select()->get(); // Query builder
    $result = User::all(); // eloquent

    return view('welcome');
});

// Query Event and Transactions
Route::get('/event_transaction', function () {



        try {

            DB::transaction(function(){

            $r = DB::table('users')->where('id', 4)->update(['email' => 'none']);
            $result = DB::table('users')->where('id', 3)->update(['email' => 'noneup@email.com']);

            print($r);

        }, 5); // second param is the number of times the transaction should retry

        } catch (\Exception $e) {
            dump($e);
            DB::rollBack();
        }




    $result = DB::table('users')->select()->get();
    dump($result);

    return view('welcome');
});

// Getting results from db Query builder
Route::get('/geting_result', function () {

    // $users = DB::table('users')->get();
    // $users = DB::table('users')->pluck('email');
    // $user = DB::table('users')->where('name', 'Dr. Coby Toy II')->first();
    // $user = DB::table('users')->where('name', 'Dr. Coby Toy II')->value('email');
    // $user = DB::table('users')->find(3);

    // dump($user);

    // $result = DB::table('comments')->select('content as comment_content')->get();
    // $result = DB::table('comments')->select('user_id')->distinct()->get();
    // $result = DB::table('comments')->count();
    // $result = DB::table('comments')->max('user_id');
    // $result = DB::table('comments')->sum('user_id');
    // $result = DB::table('comments')->where('content', 'content')->exists();
    $result = DB::table('comments')->where('content', 'content')->doesntExist();

    dump($result);

    return view('welcome');
});

// Where clause Query builder
Route::get('/where_clause', function () {

    // $result = DB::table('rooms')->where('price', '<', 200)->get();
    // $result = DB::table('rooms')->where([
    //     ['room_size', '2'],
    //     ['price', '<', '400'],
    // ])->get();
    // $result = DB::table('rooms')
    //     ->where('room_size', '2')
    //     ->orWhere('price', '<', '400')->get();
    // $result = DB::table('rooms')
    //     ->where('room_size', '2')
    //     ->orWhere('price', '<', '400')->get();
    // $result = DB::table('rooms')
    //     ->where('price', '<', '400')
    //     ->orWhere(function($query){
    //         $query->where('room_size', '>', '1')
    //             ->where('room_size', '<', '4');
    //     })
    //     ->get();


    // ADVANCE WHERE
    // $result = DB::table('rooms')
    //     ->whereBetween('room_size', [1,3]) // whereNotBetween
    //     ->get();
    // $result = DB::table('rooms')
    //     ->whereNotIn('id', [1,2,3]) // whereIn
    //     ->get();
    // $result = DB::table('rooms')
    //     ->whereNull('COLUMN_NAME') // whereNotNull
    //     ->get();
    // $result = DB::table('rooms')
    //     ->whereDate('created_at', '2020-05-13') // whereNotNull
    //     ->get();
    // ->whereMonth('created_at', '5')
    // ->whereDay('created_at', '15')
    // ->whereYear('created_at', '2020')
    // ->whereTime('created_at', '=', '12:25:10')
    // ->whereColumn('column1', '>', 'column2')
    // ->whereColumn([
    //     ['first_name', '=', 'last_name'],
    //     ['updated_at', '>', 'created_at'],
    // ])

    // ADVANCE ADVANCE
    // $result = DB::table('users')
    //         ->whereExists(function($query){
    //             $query->select('id')
    //                 ->from('reservations')
    //                 ->whereRaw('reservations.user_id = users.id')
    //                 ->where('check_in', '=', '2020-05-20')
    //                 ->limit(1);

    //         })->get();

    $result = DB::table('users')
                // ->whereJsonContains('meta->skills', 'Laravel')
                ->where('meta->settings->site_language', 'en')
                ->get();

    dump($result);

    return view('welcome');
});

// Pagination Query builder
Route::get('/where_clause', function () {

    $result = DB::table('comments')->paginate(3);

    dump($result);

    return view('welcome');
});

// Full text search
Route::get('/where_clause', function () {

    // $result = DB::statement('ALTER TABLE comments ADD FULLTEXT fulltext_index(content)');

    // $result = DB::table('comments')
    //         ->whereRaw("MATCH(content) AGAINST(? IN BOOLEAN MODE)", ['facilis'])
    //         ->get();

    // $result = DB::table('comments')
    //         ->whereRaw("MATCH(content) AGAINST(? IN BOOLEAN MODE)", ['+facilis -Veniam'])
    //         ->get();

    // $result = DB::table('comments')
    //         ->selectRaw('count(user_id) as number_of_comments, users.name')
    //         ->join('users', 'users.id', '=', 'comments.user_id')
    //         ->groupBy('user_id')
    //         ->get();

    // $result = DB::table('comments')
    //         ->orderByRaw('updated_at - created_at DESC')
    //         ->get();

    $result = DB::table('users')
            ->selectRaw('LENGTH(name) as name_length, name')
            ->orderByRaw('LENGTH(name) DESC')
            ->get();

    dump($result);

    return view('welcome');
});


Route::get('/order_group_limit_offset', function () {

    // $result = DB::table('users')
    //         ->orderBy('name', 'desc')
    //         ->get();

    // $result = DB::table('users')
    //         ->latest() // created_at default
    //         ->first();

    // $result = DB::table('users')
    //         ->inRandomOrder() // created_at default
    //         ->first();

    // $result = DB::table('comments')
    //         ->selectRaw('count(id) as number_of_5stars_comment, rating')
    //         ->groupBy('rating')
    //         ->where('rating', '=', 5)
    //         ->get();

    // $result = DB::table('comments')
    //         ->skip(5)
    //         ->take(5)
    //         ->get();

    $result = DB::table('comments')
            ->skip(5)
            ->take(5)
            ->get();

    dump($result);

    return view('welcome');
});

// Conditional statements and running queries in chunks
Route::get('/conditional_clause', function () {

    // CONDITIONAL QUERY
    // $room_id = 1;
    // $result = DB::table('reservations')
    //         ->when($room_id, function($query, $room_id){ // only runs when the first argumnet of 'when' result to a value other than null
    //             return $query->where('room_id', $room_id);
    //         })
    //         ->get();

    // $sortBy = null;
    // $result = DB::table('rooms')
    //         ->when($sortBy, function($query, $sortBy){
    //             return $query->orderBy($sortBy); // runs when the first argumnet of 'when' is a value other than null
    //         }, function($query){
    //             return $query->orderBy('price'); // runs when the first argument of when is null
    //         })
    //         ->get();

    // CHUNCKING (good for fetching very large amount of data)
    // $result = DB::table('comments')->orderBy('id')->chunk(2, function($comments){
    //     foreach($comments as $comment){
    //         if($comment->id == 5) return false;
    //     }
    // });

    $result = DB::table('comments')->orderBy('id')->chunkById(5, function($comments){
        foreach($comments as $comment){
            DB::table('comments')
                ->where('id', $comment->id)
                ->update(['rating' => 0]);
        }
    }); // useful for admin task

    dump($result);

    return view('welcome');
});

Route::get('/join_statement', function () {

    // $result = DB::table('reservations')
    //         ->join('rooms', 'reservations.room_id', '=', 'rooms.id')
    //         ->join('users', 'reservations.user_id', '=', 'users.id')
    //         ->where('rooms.id', '>', 3)
    //         ->where('users.id', '>', 1)
    //         ->get();

    // $result = DB::table('reservations')
    //             ->join('rooms', function($join){
    //                 $join->on('reservations.room_id', '=', 'rooms.id')
    //                     ->where('rooms.id', '>', 3);
    //             })
    //             ->join('users', function($join){
    //                 $join->on('reservations.user_id', '=', 'users.id')
    //                     ->where('users.id', '>', 1);
    //             })
    //             ->get();

    // $rooms = DB::table('rooms')
    //         ->where('id', '>', 3);
    // $users = DB::table('users')
    //         ->where('id', '>', 1);
    // $result = DB::table('reservations')
    //         ->joinSub($rooms, 'rooms', function($join) {
    //             $join->on('reservations.room_id', '=', 'rooms.id');
    //         })
    //         ->joinSub($users, 'users', function($join) {
    //             $join->on('reservations.user_id', '=', 'users.id');
    //         })
    //         ->get();

    // $result = DB::table('rooms')
    //         ->leftJoin('reservations', 'rooms.id', '=', 'reservations.room_id')
    //         ->leftJoin('cities', 'reservations.city_id', '=', 'cities.id')
    //         ->selectRaw('room_size, cities.name, count(reservations.id) as reservations_count')
    //         ->groupBy('room_size', 'cities.name')
    //         ->orderByRaw('count(reservations.id) DESC')
    //         ->get();

    $result = DB::table('rooms')
            ->crossJoin('cities')
            ->leftJoin('reservations', function($join) {
                $join->on('rooms.id', '=', 'reservations.room_id')
                    ->on('cities.id', '=', 'reservations.city_id');
            })
            ->selectRaw('count(reservations.id) as reservations_count, room_size, cities.name')
            // ->selectRaw('count(reservations.id) as reservations_count, cities.name')
            ->groupBy('room_size', 'cities.name')
            // ->groupBy('cities.name')
            ->orderByRaw('rooms.room_size DESC')
            // ->orderByRaw('count(reservations.id) DESC')
            ->get();

    dump($result);

    return view('welcome');
});

Route::get('/union_select', function () {

    // $users = DB::table('users')
    //         ->select('name');

    // $result = DB::table('cities')
    //         ->select('name')
    //         ->union($users)
    //         ->get();

    $comment = DB::table('comments')
            ->select('rating as rating_or_room_id', 'id', DB::raw('"comments" as type_of_activity'))
            ->where('user_id', 1);

    $result = DB::table('reservations')
            ->select('room_id as rating_or_room_id', 'id', DB::raw('"reservation" as type_of_activity'))
            ->union($comment)
            ->where('user_id', 1)
            ->get();

    dump($result);

    return view('welcome');
});

Route::get('/inserting', function () {

    // DB::table('rooms')->insert([
    //     [
    //         'room_number' => 1,
    //         'room_size' => 1,
    //         'price' => 1,
    //         'description' => 'Room 1 description',
    //     ],
    //     [
    //         'room_number' => 2,
    //         'room_size' => 2,
    //         'price' => 2,
    //         'description' => 'Room 2 description',
    //     ],
    // ]);

    // $result = DB::table('rooms')
    //         ->get();

    $result = DB::table('rooms')->insertGetId([
        'room_number' => 3,
            'room_size' => 3,
            'price' => 3,
            'description' => 'Room 3 description',
    ]);

    dump($result);

    return view('welcome');
});

Route::get('/updating', function () {

    // $result = DB::table('rooms')
    //         ->where('id', 1)
    //         ->update(['price' => 222]);

    // $result = DB::table('users')
    //         ->where('id', 1)
    //         ->update(['meta->settings->site_language' => 'es']);

    // $result = DB::table('rooms')->increment('price', 10);

    $result = DB::table('rooms')->decrement('price', 5, [
        'description' => 'New general description'
    ]);

    dump($result);

    return view('welcome');
});

Route::get('/deleting', function () {

    // $result = DB::table('rooms')
    //         ->where('id', '>', 10)
    //         ->delete();

    // $result = DB::table('rooms')
    //         ->delete();

    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    $result = DB::table('rooms')
    ->truncate();
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    dump($result);

    return view('welcome');
});

Route::get('/permission', function () {

    //    $result = DB::table('rooms')
    //         ->sharedLock() // If you query for something and later wants to insert/update related data (within transaction). Other sessions can read but can not modify
    //         ->find(1);

   $result = DB::table('rooms')
        ->where('room_size', 3)
        ->lockForUpdate() // Other session cannot read and cannot modify
        ->get();

    dump($result);

    return view('welcome');
});

// ELOQUENT ORM
Route::get('/elo_fetch', function(){

    // $result = Room::where('room_size', 3)->get();

    // $result = Room::all();

    // $result = User::select('name','email')->get();

    // $result = User::select('name','email')
    //         ->addSelect([
    //             'worst_rating' => Comment::select('rating')->whereColumn('user_id', 'users.id')->orderBy('rating', 'asc')->limit(1),
    //         ])
    //         ->get()->toArray();

    // $result = User::orderByDesc(
    //     Reservation::select('check_in')
    //         ->whereColumn('user_id', 'users.id')
    //         ->orderBy('check_in', 'desc')
    //         ->limit(1)
    // )->select('id', 'name')->get()->toArray();

    // $result = Reservation::chunk(2, function($reservations){
    //     foreach($reservations as $reservation){
    //         echo $reservation->id;
    //     }
    // }); // uses less memory that get() and cursor() but takes longer than get() and cursor(), the bigger the chunk set is the less time a query takes but memory usage increases

    // foreach(Room::cursor() as $reservation){
    //     echo $reservation->id;
    // } // takes faster than get() and chunk() but uses more memory than chunk() (not as much as get() method)

    foreach(Room::cursor() as $reservation){
        echo $reservation->id;
    }

    // dump($result);

    return view('welcome');
});

Route::get('/elo_query_scope', function(){

    // $result = User::where('email', 'like', '%@%')->first()->toArray();

    // $result = User::where('email', 'like', '%@email2.com')->firstOr(function(){
        //     User::where('id', 1)->update(['email' => 'email@email2.com']);
        // });


    // $result = User::findOrFail(100);

    // $result = Comment::max('rating'); // max, min, count, avg, sum

    // $result = Comment::all();

    // $result = Comment::withoutGlobalScope('rating')->get();

    $result = Comment::rating(1)->get(); // working with local scope

    dump($result);

    return view('welcome');
});

Route::get('/elo_collections_operations', function(){

    // $result = Comment::all()->toArray();
    // $result = Comment::all()->count();
    // $result = Comment::all()->toJson();

    $comments = Comment::all();
    // $result = $comments->reject(function($comment){
    //     return $comment->rating < 3;
    // });
    $result = $comments->map(function($comment){
        return $comment->content;
    });

    dump($result);

    return view('welcome');
});

Route::get('/elo_insert', function(){

    // $comment = new Comment();
    // $comment->user_id = 1;
    // $comment->rating = 3;
    // $comment->content = 'comment content';
    // $result = $comment->save();

    $result = Comment::create([
        'user_id' => 1,
        'rating' => 4,
        'content' => 'comment 4 content',
    ]);

    dump($result);

    return view('welcome');
});

Route::get('/elo_update', function(){

    // $comment = Comment::find(1);
    // $comment->user_id = 1;
    // $comment->rating = 3;
    // $comment->content = 'comment content updated';
    // $result = $comment->save();

    $result = Room::where('price', '<', 200)
                ->update(['price' => 250]);

    dump($result);

    return view('welcome');
});

Route::get('/elo_delete', function(){

    // $comment = Comment::find(3);
    // $result = $comment->delete();

    // $result = Comment::destroy([3,4]);

    // $result = Comment::withTrashed()->get(); // onlyTrashed()

    // $result = Comment::withTrashed()->restore(); // onlyTrashed()

    $result = Comment::where('rating', 1)->forceDelete(); // delete even even it has soft delete

    dump($result);

    return view('welcome');
});

Route::get('/elo_event', function(){

    $result = Comment::all()->toArray();

    dump($result);

    return view('welcome');
});

Route::get('/elo_accessors', function(){ // manipulate model properties before accessing them

    $result = Comment::find(1);

    // dump($result->rating);
    dump($result->who_what);

    return view('welcome');
});

Route::get('/elo_mutators', function(){ // manipulate model properties before saving them

    $result = Comment::find(1);
    $result->rating = 4;
    $result->save();

    dump($result->rating);

    return view('welcome');
});

Route::get('/elo_casting_attr', function(){ // manipulate model properties before saving them

    $result = User::select([
        'users.*',
        'last_commented_at' => Comment::selectRaw('MAX(created_at)')
                                ->whereColumn('user_id', 'users.id')
    ])->withCasts([
        'last_commented_at' => 'datetime:Y-m-d' // date and datetime works only for array or json result
    ])->get()->toArray();

    dump($result);

    return view('welcome');
});

// Relationships in eloquent
Route::get('/elo_rel_1_1', function(){

    // $result = User::find(1);
    // dump($result->address->street, $result->address->number);

    $result = Address::find(1);
    dump($result->user->name);

    return view('welcome');
});

Route::get('/elo_rel_1_to_many', function(){

    // $result = User::find(1);
    // dump($result->comments);

    $result = Comment::find(1);
    dump($result->user->name);

    return view('welcome');
});

Route::get('/elo_rel_many_to_many', function(){

    // $result = City::find(1);
    // dump($result->rooms);

    $result = Room::where('room_size', 1)->get();

    foreach($result as $rooms){
        foreach($rooms->cities as $city){

            echo $city->pivot->room_id . '<br>';

            echo $city->name . '<br>';
            // dump($city->name);
        }
    }

    return view('welcome');
});

Route::get('/elo_rel_has_1_through', function(){

    $result = Comment::find(3);

    dump($result->country->name);

    return view('welcome');
});

Route::get('/elo_rel_has_many_through', function(){

    $result = Company::find(2);

    dump($result->reservations);

    return view('welcome');
});

Route::get('/elo_rel_1_to_1_polymophic', function(){

    // $result = User::find(1);
    // dump($result->image);

    $result = Image::find(6);

    dump($result->imageable);

    return view('welcome');
});

Route::get('/elo_rel_1_to_many_polymophic', function(){

    $result = Room::find(10);

    // dump($result->comments);
    dump($result->commentable);

    return view('welcome');
});

Route::get('/elo_rel_many_to_many_polymophic', function(){

    // $result = User::find(1);
    // dump($result->likedImages, $result->likedRooms);

    $result = Room::find(2);
    dump($result->likes);

    return view('welcome');
});

Route::get('/elo_quering_counting_db_rel', function(){

    // $result = User::find(1)->comments()
    //         ->where('rating', '>', 3)
    //         ->orWhere('rating', '<', 2)
    //         ->get();

    // $result = User::find(1)->comments()
    //         ->where(function($query){
    //             return $query->where('rating', '>', 3)
    //                     ->orWhere('rating', '<', 2);
    //         })
    //         ->get();

    // $result = User::has('comments', '>=', 6)->get();

    // $result = Comment::has('user.address')->get();

    // $result = User::whereHas('comments', function($query){
    //     $query->where('rating', '>', 2);
    // }, '>=', 2)->get(); // get all users that has comments greater than 2 and the amount of comment is greater than or equal to 20

    // $result = User::doesntHave('comments')->get(); // ->orDoesHave

    // $result = User::whereDoesntHave('comments', function($query){
    //     $query->where('rating', '<', 2);
    // })->get(); // ->orWhereDoesntHave() // get the user that have not written comment with the rating less than 2

    // $result = Reservation::whereDoesntHave('user.comments', function($query){
    //     $query->where('rating', '<', 2);
    // })->get(); // more realistic scenario: give me all posts written by users who rated with at least 3 stars

    // $result = User::withCount('comments')->get();

    $result = User::withCount([
        'comments',
        'comments as negative_comments_count' => function($query){
            $query->where('rating', '<=', 2);
        }
    ])->get();

    dump($result->toArray());

    return view('welcome');
});

Route::get('/elo_quering_counting_polymorh_db', function(){

    $result = Comment::whereHasMorph(
        'commentable',
        [Image::class, Room::class],
        function($query, $type){
            if($type === Room::class){
                $query->where('room_size', '>', 2);
                $query->orWhere('room_size', '<', 2);
            }
            if($type === Image::class){
                $query->where('path', 'like', '%lorem%');
            }
        }
    )->get();

    dump($result->toArray());

    return view('welcome');
});

Route::get('/elo_crud_related_models', function(){

    //    $user = User::find(1);
    //    $result = $user->address()->delete();
    // $result = $user->address()->saveMany([ // save(new Address)
    //     new Address([
    //         'number' => 12,
    //         'street' => 'street',
    //         'country' => 'Canada',
    //     ]),
    // ]);
    // $result = $user->address()->createMany([ // create()
    //     ['number' => 2, 'street' => 'street2', 'country' => 'Mexico'],
    // ]);

    // $user = User::find(2);
    // $address = Address::find(2);
    // $address->user()->associate($user);
    // $result = $address->save();
    // $address->user()->dissociate();
    // $result = $address->save();

    // $room = Room::find(3);
    // $result = $room->cities()->attach(1);
    // $result = $room->cities()->detach([1]);

    $comment = Comment::find(1);
    $comment->content = "Edited content";
    $result = $comment->save();

    dump($result);

    return view('welcome');
});

Route::get('/elo_custom_pivot_model_for_many_2_many', function(){

    $city = City::find(1);
    $result = $city->rooms()->attach(1);

    dump($result);

    return view('welcome');
});

Route::get('/elo_lazy_eager_loading', function(){

    // $result = User::all(); // lazy loading, avoid it if u want to get relation tables.

    // $result = User::with([
    //     'address'
    // ])->get(); // eager load. better
    // foreach($result as $user){
    //     echo "{$user->address->street} <br>";
    // }

    // $result = Reservation::with('user.address')->get();

    // lazy-eager loading
    $result = User::all();
    $result->load('address'); // address => function($query){...}

    dump($result);

    // return view('welcome');
});

Route::get('/db_performance', function(){

    // $result = User::with('comments')->get(); // Eloquent
    // $result = DB::table('users')->join('comments', 'users.id', '=', 'comments.user_id')->get(); // Query builder
    // $result = DB::select(' select * from `users` inner join `comments` on `users`.`id` = `comments`.`user_id` '); // Raw SQL statement

    // Use eloquent for small CRUD operations and Query Builder for larger CRUD operations

    $result = DB::table('comments')
            ->selectRaw('count(rating) as rating_count, rating') // and other aggregate functions like avg, sum, max. min, etc.
            ->groupBy('rating')
            ->orderBy('rating_count', 'desc')
            ->get();

    dump($result);

});

Route::get('/redis', function(){

    // strings
    // $result = Redis::set('name', 'John');
    // $result = Redis::get('name');
    // $result = Redis::del('name');
    // $result = Redis::exists('name');
    // $result = Redis::incr('counter'); // decr()

    // lists (like arrays)
    // Redis::lpush('data', 'lvalue'); // delete with lpop() - deletes from the left... rpop() - from the right
    // $result = Redis::llen('data'); // length
    $result = Redis::lrange('data', 0, -1); // get all


    dump($result);

});

Route::get('/api_resources', function(){

    // $result = User::with('comments')->get()->makeVisible('password')->toArray(); // makeHidden()

    // return $result = new UserResource(User::find(1));
    // return UserResource::collection(User::all());

    // return new UsersCollection(User::all());
    // return new UsersCollection(User::with('address','comments')->get());
    return new UsersCollection(User::with('address','comments')->paginate(3));

    // dump($result);

});

// DB::statement('DROP TABLE addresses');
// DB::statement('ALTER TABLE rooms ADD INDEX index_name (price)'); // indexes help speedup select queries but slows write operations
