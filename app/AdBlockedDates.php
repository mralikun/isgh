<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AdBlockedDates extends Model {

    protected $table = "ad_blocked_dates";

    protected $fillable= ["id","friday_id","ad_id"];

}
