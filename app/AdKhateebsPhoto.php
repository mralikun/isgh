<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AdKhateebsPhoto extends Model {

	//
    protected $table ="ad_khateebs_photo";

    protected $fillable = ["id","ad_id","photo_url"];


}
