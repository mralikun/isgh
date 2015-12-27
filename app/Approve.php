<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Approve extends Model {

    protected $table ="approves";

    protected $fillable =["approve","cycle_id"];

}
