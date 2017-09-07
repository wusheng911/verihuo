<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentComment extends Model
{
    /**
     * The name of table.
     * 
     * @var string
     */
    protected $table = 'content_comments';

    public function user(){
        return $this->hasOne('App\Customer', 'id', 'user_id');
    }

    public function parent(){
        return $this->hasOne('App\Models\ContentComment', 'id', 'pid');
    }
}
