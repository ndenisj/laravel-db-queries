<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Every models may fire the following events
     * retrieved, creating, created, updating, updated, saving, saved, deleting, deleted,
     * restoring, restored
     *
     * protected $dispatchesEvents = [
     *    'saved' => 'class to handle saved event',
     *    'deleted' => 'class to handle deleted event',
     * ];

     */

    protected $touches = ['user']; // updated_at timestamp of the parent model will be updated also

     protected $casts = [
         'rating' => 'float',
     ];


    protected $fillable = ['rating', 'content', 'user_id'];
    protected $guarded = [];

    protected static function booted()
    {
        // static::addGlobalScope('rating', function(Builder $builder){
        //     $builder->where('rating', '>', 2);
        // });

        static::retrieved(function ($comment){
            echo $comment->ration;
        });
    }

    // local scope
    // public function scopeRating($query, int $value = 4){
    //     return $query->where('rating', '>', $value);
    // }

    // public function getRatingAttribute($value)
    // {
    //     return $value + 10;
    // }

    // public function getWhoWhatAttribute()
    // {
    //     return "user {$this->user_id} rates {$this->rating}";
    // }

    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = $value + 1;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function country()
    {
        return $this->hasOneThrough(Address::class, User::class, 'id', 'user_id', 'user_id','id')->select('country as name');
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
