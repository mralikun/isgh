<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Fridays extends Model {

    protected $table = "fidays";

    protected $fillable = ["id", "date","cycle_id"];

}
