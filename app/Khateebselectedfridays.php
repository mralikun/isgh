<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Khateebselectedfridays extends Model {

	protected $table = "Khateebselectedfridays";

    protected $fillable= ["id","friday_id","khateeb_id"];

}
