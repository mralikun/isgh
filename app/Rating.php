<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model {

    protected $table = "rating";

    protected $fillable = ["id", "ad_id", "khateeb_id", "ad_rate_khateeb", "khateeb_rate_ad", "cycle_id"];


}
