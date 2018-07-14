<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Locations extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	DB::table('locations')->where('id',1)->update(['color' => '#EBBB72']);
    	DB::table('locations')->where('id',2)->update(['color' => '#E9B2D3']);
		DB::table('locations')->where('id',3)->update(['color' => '#D1CDE5']);
		DB::table('locations')->where('id',4)->update(['color' => '#89E5E3']);
		DB::table('locations')->where('id',5)->update(['color' => '#EDF5AA']);
		DB::table('locations')->where('id',6)->update(['color' => '#9295CC']);


    }
}
