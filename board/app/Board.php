<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'reId', 'name', 'email', 'addtime', 'content', 'created_at', 'updated_at'
    ];
}
