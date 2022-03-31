<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
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

Route::get('/', function () {

    // FETCH FROM DB
    $check_in = '2020-06-29';
    $check_out = '2020-06-30';
    $city_id = 2;
    $room_size = 2;

    // $result = Reservation::where(function($q) use($check_in, $check_out) {
    //     $q->where('check_in', '>', $check_in);
    //     $q->where('check_in', '>=', $check_out);
    // })
    // ->orWhere(function($q) use($check_in, $check_out) {
    //     $q->where('check_out', '<=', $check_in);
    //     $q->where('check_out', '<', $check_out);
    // })->get();

    // GET DATE BASED MATCHING ROOM : QUERY BUILDER
    // $result = DB::table('rooms')
    // ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
    // ->whereNotExists(function($query) use($check_in, $check_out) {
    //     $query->select('reservations.id')
    //         ->from('reservations')
    //         ->join('reservation_room', 'reservations.id', '=', 'reservation_room.reservation_id')
    //         ->whereColumn('rooms.id', 'reservation_room.room_id')
    //         ->where(function($q) use ($check_in, $check_out) {
    //             // get all rooms that don't have reservation like this
    //             $q->where('check_out', '>', $check_in);
    //             $q->where('check_in', '<', $check_out);
    //         })
    //         ->limit(1);
    // })->paginate(10);

    // GET DATE BASED MATCHING ROOM : ELOQUENT
    // $result = Room::with('type')
    //         ->whereDoesntHave('reservations', function($q) use($check_in, $check_out) {
    //             $q->where('check_out', '>', $check_in);
    //             $q->where('check_in', '<', $check_out);
    //         })->get();

    // SEARCH AVAILABLE ROOMS BY CITY_ID COLUMN OF RELATED TABLE (QUERY BUILDER)
    $result = DB::table('rooms')
    ->select('rooms.*', 'room_types.size', 'room_types.price', 'room_types.amount', 'hotels.name as hotel_name', 'hotels.id as hotel_id')
    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
    ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
    ->whereNotExists(function($query) use($check_in, $check_out) {
        $query->select('reservations.id')
            ->from('reservations')
            ->join('reservation_room', 'reservations.id', '=', 'reservation_room.reservation_id')
            ->whereColumn('rooms.id', 'reservation_room.room_id')
            ->where(function($q) use ($check_in, $check_out) {
                // get all rooms that don't have reservation like this
                $q->where('check_out', '>', $check_in);
                $q->where('check_in', '<', $check_out);
            })
            ->limit(1);
    }) // using date constrains
    ->whereExists(function($q) use($city_id){
        $q->select('hotels.id')
            ->from('hotels')
            ->whereColumn('rooms.hotel_id', 'hotels.id')
            ->whereExists(function($q) use($city_id) {
                $q->select('cities.id')
                ->from('cities')
                ->whereColumn('cities.id', 'hotels.city_id')
                ->where('id', $city_id)
                ->limit(1);
            })->limit(1);
    }) // using city constrains
    ->where('room_types.amount', '>', 0)
    ->where('room_types.size', '=', $room_size)
    ->orderBy('room_types.price', 'asc')
    ->paginate(10);

    // SEARCH AVAILABLE ROOMS BY CITY_ID COLUMN OF RELATED TABLE (ELOQUENT)
    // $result = Room::with(['type', 'hotel'])
    //         ->whereDoesntHave('reservations', function($q) use($check_in, $check_out) {
    //             $q->where('check_out', '>', $check_in);
    //             $q->where('check_in', '<', $check_out);
    //         })
    //         ->whereHas('hotel.city', function($q) use($city_id){
    //             $q->where('id', $city_id);
    //         })
    //         ->whereHas('type', function($q) use($room_size){
    //             $q->where('amount', '>', 0);
    //             $q->where('size', '=', $room_size);
    //         }) // whereHas() is for relationships in eloquent
    //         ->paginate(10)
    //         ->sortBy('type.price'); // sortByDesc()


    dump($result);

    return view('welcome');
});

Route::get('/use_db_transaction_make_reservation', function () {

    // // FETCH FROM DB
    // $check_in = '2020-06-29';
    // $check_out = '2020-06-30';
    // $city_id = 2;
    // $room_size = 2;

    // // SEARCH AVAILABLE ROOMS BY CITY_ID COLUMN OF RELATED TABLE (QUERY BUILDER)
    // $result = DB::table('rooms')
    // ->select('rooms.*', 'room_types.size', 'room_types.price', 'room_types.amount', 'hotels.name as hotel_name', 'hotels.id as hotel_id')
    // ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
    // ->join('hotels', 'rooms.hotel_id', '=', 'hotels.id')
    // ->whereNotExists(function($query) use($check_in, $check_out) {
    //     $query->select('reservations.id')
    //         ->from('reservations')
    //         ->join('reservation_room', 'reservations.id', '=', 'reservation_room.reservation_id')
    //         ->whereColumn('rooms.id', 'reservation_room.room_id')
    //         ->where(function($q) use ($check_in, $check_out) {
    //             // get all rooms that don't have reservation like this
    //             $q->where('check_out', '>', $check_in);
    //             $q->where('check_in', '<', $check_out);
    //             $q->where('room_types.amount', '=', 0);
    //         })
    //         ->limit(1);
    // }) // using date constrains
    // ->whereExists(function($q) use($city_id){
    //     $q->select('hotels.id')
    //         ->from('hotels')
    //         ->whereColumn('rooms.hotel_id', 'hotels.id')
    //         ->whereExists(function($q) use($city_id) {
    //             $q->select('cities.id')
    //             ->from('cities')
    //             ->whereColumn('cities.id', 'hotels.city_id')
    //             ->where('id', $city_id)
    //             ->limit(1);
    //         })->limit(1);
    // }) // using city constrains
    // // ->where('room_types.amount', '>', 0)
    // ->where('room_types.size', '=', $room_size)
    // ->orderBy('room_types.price', 'asc')
    // ->paginate(10);

    // // MAKE RESERVATION
    // $room_id = 12;
    // $user_id = 1;

    // DB::transaction(function() use($room_id,$user_id,$check_in,$check_out){
    //     $room = Room::findOrFail($room_id);

    //     $reservation = new Reservation;
    //     $reservation->user_id = $user_id;
    //     $reservation->check_in = $check_in;
    //     $reservation->check_out = $check_out;
    //     $reservation->price = $room->type->price;
    //     $reservation->save();

    //     $room->reservations()->attach($reservation->id);

    //     RoomType::where('id', $room->room_type_id)
    //             ->where('amount', '>', 0)
    //             ->decrement('amount');
    // });

    // GET ALL RESERVATIONS MADE BY THE USER
    // $user_id = 1;

    // $result = Reservation::with(['rooms.type', 'rooms.hotel'])
    //         ->where('user_id', $user_id)
    //         ->get();

    // GET ALL RESERVATIONS OF A HOTEL
    $hotel_id = [1];
    // $result = Reservation::with(['rooms.type', 'user'])
    //         ->select('reservations.*', DB::raw('DATEDIFF(check_out, check_in) as nights')) // calculates the number of days between check_out and check_in date
    //         ->whereHas('rooms.hotel', function($q) use($hotel_id){
    //             $q->whereIn('hotel_id', $hotel_id);
    //         })
    //         ->orderBy('nights', 'DESC')
    //         ->get();

    // GET ROOM AND ORDER BY NUMBER OF RESERVATIONS
    $result = Room::whereHas('hotel', function($q) use($hotel_id) {
        $q->whereIn('hotel_id', $hotel_id);
    })
    ->withCount('reservations')
    ->orderBy('reservations_count', 'DESC')
    ->get();

    dump($result);

    return view('welcome');
});

Route::get('/count_related_models_and_group', function () {

    $hotel_id = range(1, 10);

    // $result = Hotel::whereIn('id', $hotel_id)
    //         ->withCount('rooms')
    //         ->orderBy('rooms_count', 'desc')
    //         ->get();

    $result = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->selectRaw('sum(room_types.amount) as number_of_single_rooms, rooms.name')
            ->groupBy('rooms.name', 'room_types.size')
            ->having('room_types.size', '=', 1)
            ->whereIn('rooms.hotel_id', $hotel_id)
            ->orderBy('number_of_single_rooms', 'desc')
            ->get();

    dump($result);

    return view('welcome');
});

Route::get('/order_users_by_reservations', function () {

    $result = DB::table('users')
            ->orderByDesc(
                DB::table('reservations')
                ->select('price')
                ->whereColumn('users.id', 'reservations.user_id')
                ->orderByDesc('price')
                ->limit(1)
            )->get();

    dump($result);

    return view('welcome');
});

Route::get('/create_hotel_room_with_relations', function () {

    // $city = City::find(1);

    // $hotel = new Hotel;
    // $hotel->name = 'hotel name';
    // $hotel->description = 'hotel description';
    // $hotel->city()->associate($city);
    // $result = $hotel->save();

    // $hotel = Hotel::find(1);
    // $room_type = new RoomType();
    // $room_type->size = 2;
    // $room_type->price = 200;
    // $room_type->amount = 2;
    // $room_type->save();

    // $room = new Room;
    // $room->name = 'hotel name';
    // $room->description = 'hotel description';
    // $room->type()->associate($room_type);

    // $result = $hotel->rooms()->save($room);

    // UPDATE AND DELETE USING ELOQUENT
    // $room = Room::find(1);
    // $room->name = 'new name';
    // $result = $room->save();

    // $country = Country::find(5);
    // $result = $country->delete();

    // $result = Country::destroy([2,3,4,5]);

    $result = Reservation::chunk(5, function($reservations){
        foreach($reservations as $reservation) {
           foreach($reservation->rooms()->get() as $room){
                if(!$room->pivot->status){
                    $reservation->delete();
                }
           }
        }
    });

    dump($result);

    return view('welcome');
});
