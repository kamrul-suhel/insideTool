<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'name' => 'UNILAD',
            'facebook_id' => '146505212039213',
			'access_token' => 'EAAMZCJahrFcABAMWZAQRfciKFSMe8rcJYZBdrd6svlWXMnuA1rxDUlgabiwHPljBmvyDDD0tZCIVRBLE3kUymaCwzh4joK3rmJICaUchWM0taHOmk099c2p7JDfifV8lnmOlKOWYALa6aaJQmtdZBC3urmQZCZBCk7OwL1kwpySxwZDZD'
        ]);

		DB::table('pages')->insert([
			'name' => 'UNILAD Adventure',
			'facebook_id' => '1648609298801884',
			'access_token' => 'EAAMZCJahrFcABALsWAT0I4YhySU7voCxMXEzZAe1riGOpdKIDZAYbeVXykshtcRo8Ac68NOitCWbfLRoApncZBXDLZAv6J4ikuzMJsDqWCAnb0nQWX9M56QR7hKEJWpRjDlTI0goVLfzUOPNyzUYjKOqg2ZBALjkUlcxOxes2pZAP7fsZCGcvyTLpiwdHt1etKEZD'
		]);

		DB::table('pages')->insert([
			'name' => 'UNILAD Gaming',
			'facebook_id' => '659974074026473',
			'access_token' => 'EAAMZCJahrFcABAAhZBFOmcBaZAkJDITvtJZALxVuCDT9dqvkLAxCNincduBY4bxttPuZC9xfRosN4oZCkSK6k0QEct36pz02ztfgpps0i4YVNW1VuhFarBvnNrd5mZC4rUWGBC19GIV7ZC1P5gt8m2MBrqBVa1tBYP3A1C5borBrvgZDZD'
		]);
    }
}
