<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'user_id',
    ];

    /**
     * Get the user related.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
