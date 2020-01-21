<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    protected $primaryKey = 'id';
    public $timestamps = true;
     protected $fillable = ['user_id',
							'profile_image',
							'profile_path',
							'cover_image',
							'cover_path',
							'description',
						];
}
