<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use App\Models\Hotline;
class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		 //$this->call('CitiesTableSeeder');
		 $this->call('HotlineTableSeeder');
	}

}
class CitiesTableSeeder extends Seeder
{
	public function run()
	{
		City::insert([
			['id'=>1,'name'=>'Manila'],
			['id'=>2,'name'=>'Caloocan'],
			['id'=>3,'name'=>'Quezon'],
			['id'=>4,'name'=>'Mandaluyong'],
		]);
	}
}
class HotlineTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('hotlines')->truncate();
		Hotline::insert([
			['name'=>'Police Station 1','city_id'=>1,'number'=>'+123'],
			['name'=>'Police Station 2','city_id'=>1,'number'=>'+123'],
			['name'=>'Police Station 3','city_id'=>2,'number'=>'+123'],
			['name'=>'Police Station 4','city_id'=>3,'number'=>'+123'],
			['name'=>'Hospital Station 5','city_id'=>4,'number'=>'+123'],
		]);
	}
}