<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AssociateDirector extends Model
{

    protected $table = "associate_director";

    protected $fillable = ["id", "name", "email", "phone", "address", "bio", "post_code"];

}
