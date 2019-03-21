<?php

namespace App;

// 所有 Model 都繼承這個 class
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // 設定資料表名稱
     protected $table = 'board';

    protected $fillable = [
        'id', 'reId', 'name', 'email', 'addtime', 'content'
    ];

    // 開啟 timestamps 控制（預設為開啟）
    public $timestamps = true;
}
