<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

	protected $fillable = ['help_id','recipient_id','status'];

}
