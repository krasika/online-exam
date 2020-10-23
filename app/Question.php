<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	protected $guarded = ['id'];

    public function options()
    {
    	return $this->hasMany('App\Answer','question_id','id');
    }
}
