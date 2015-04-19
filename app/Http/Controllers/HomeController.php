<?php namespace App\Http\Controllers;

/**
* @Controller("/")
*/
use App\User;
use Session;
use Input;
use Validator;
use Exception;
use App\Models\City;
use Request;
use App\Models\Hotline;
use App\Models\Help;
use App\Models\Notification;
use App\Models\Feed;
class HomeController extends Controller {

	/**
	* @Get("api/feeds")
	*/
	public function feeds()
	{
		return json_encode( (object)[
			'success'=>1,
			'data'=>Feed::all(),
		] );
	}
	/**
	* @Post("postFeed")
	*/
	public function postFeed()
	{
		try {
			$user = User::where('mobile',Request::get('number'))->first();
			Feed::create([
				'user_id'=>$user->id,
				'city_id'=>Request::get('city_id'),
				'message'=>Request::get('message'),
			]);
			return json_encode( (object)[
				'success'=>1,
			] );
		} catch (Exception $e) {
			return json_encode( (object)[
				'success'=>0,
				'message'=>$e->getMessage(),
			] );
		}
	}
	/**
	* @Post("api/fetchHelp")
	*/
	public function fetchHelp()
	{
		$city_id = Request::get('city_id');
		$user = User::where('mobile', Request::get('mobile') )->first();
		return $user;
		$sended = Notification::where('recipient_id',$user->id)->where('status',1)->lists('id');
		$notifications = Notification::where('status',0)
			->whereNotIn('recipient_id', $sended)->lists('help_id');
		$helps = Help::whereIn('id',$notifications)->get();
		return $helps;
	}
	/**
	* @Get("cron")
	*/
	public function cron(){
		$new = Notification::where('status',0)->get();
		foreach ($new as $notif) {
			try {
				//send
				return dd($notif);
				$notif->status = 1;
			} catch (Exception $e) {
				
			}
		}
	}
	/**
	* @Post("help")
	*/
	public function help()
	{
		try{
			$help = Help::create([
				'city_id'=>Request::get('city_id'),
				'number'=>Request::get('number'),
				'message'=>Request::get('message'),
			]);

			$user = User::where('mobile',$help->number);
			
			if(!$user->count())
				throw new Exception("Unregistered Number", 1);
			
			$user = $user->first();

			// #todo for multi request, help will only be saved if duration is atleast 10seconds
			Notification::create([
				'help_id'=>$help->id,
				'recipient_id'=>$user->id,
				'status'=>0,
			]);

			return json_encode( (object) [
				'success'=>1,
				'message'=>'Your help request has been sent to authority and all users within your city. Stay safe, rescue will arrive shortly.',
			] );
		}
		catch(Exception $e){
			return json_encode( (object) [
				'success'=>0,
				'message'=>$e->getMessage(),
			] );
		}
	}
	/**
	* @Get("api/data")
	*/
	public function data()
	{//return dd(Hotline::get(['name','number','city_id']));
		return json_encode((object) [
			'success'=>1,
			'data'=>[
				'contacts'=>User::get(['id','name','mobile','city_id']),
				'hotlines'=>Hotline::get(['name','number','city_id']),
				'feeds'=>Feed::all(),
			]
		]);
	}
	/**
	* @Get("token")
	*/
	public function token()
	{
		return Session::token();
	}
	/**
	 * Show the application dashboard to the user.
	 * @Post("register")
	 * @return Response
	 */
	public function postRegister()
	{

		try {

			$validator = Validator::make(
			    Input::all(),
			    [
					'name'=>'required',
					'mobile'=>'required|regex:/^09[0-9]{9}$/',//|unique:users,mobile
					'city'=>'required|numeric'
				]
			);
			if($validator->fails())
				throw new Exception($validator->messages()->first(), 1);

			$city = City::find( Input::get('city'));

			if( !$city )
				throw new Exception( 'Invalid City ID', 1);
				
			$user = User::create([
				'name'=>Request::get('name'),
				'mobile'=>Request::get('mobile'),
				'city_id'=>Request::get('city'),
			]);	

			return json_encode( (object) [
				'success'=>1,
				'user_id'=>$user->id,
				'data'=>[
					'cities'=>[],//City::get(['id','name']),
					'contacts'=>[],//User::get(['id','name','mobile','city_id']) ? User::get(['id','name','mobile','city_id']): [],
					'hotlines'=>[],//Hotline::get(['name','number','city_id']),
					'feeds'=>[],//Feed::all(),
				]
			] );
		} catch (Exception $e) {
			return json_encode( (object) ['success'=>0,'message'=>$e->getMessage()] );
		}
	}
	/**
	* @Get("api/cities")
	*/
	public function listCities()
	{
		$data = [ 'success'=>1,'data'=>City::all() ];
		return json_encode((object) $data );		
	}
}
