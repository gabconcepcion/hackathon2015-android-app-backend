<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;
class Feed extends Model {

	protected $fillable = ['user_id','city_id','message'];
	public function toArray()
    {
        $array = parent::toArray();
        $array['name'] = User::find($this->user_id)->name;
        $array['age'] = $this->age;
        return $array;
    }

    public function getAgeAttribute()
    {
        return self::time_elapsed_string(strtotime($this->created_at));    
    }

    private static function time_elapsed_string($ptime)
	{
	    $etime = time() - $ptime;

	    if ($etime < 1)
	    {
	        return '0 seconds';
	    }

	    $a = array( 365 * 24 * 60 * 60  =>  'year',
	                 30 * 24 * 60 * 60  =>  'month',
	                      24 * 60 * 60  =>  'day',
	                           60 * 60  =>  'hour',
	                                60  =>  'minute',
	                                 1  =>  'second'
	                );
	    $a_plural = array( 'year'   => 'years',
	                       'month'  => 'months',
	                       'day'    => 'days',
	                       'hour'   => 'hours',
	                       'minute' => 'minutes',
	                       'second' => 'seconds'
	                );

	    foreach ($a as $secs => $str)
	    {
	        $d = $etime / $secs;
	        if ($d >= 1)
	        {
	            $r = round($d);
	            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
	        }
	    }
	}
}
