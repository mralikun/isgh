<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class cycle extends Model {

    protected $table = "Cycle";

    protected $fillable = ["id", "start_date","end_date"];

}
