<?php

use Illuminate\Database\Seeder;

use Aham\Models\SQL\Country;
use Aham\Models\SQL\State;
use Aham\Models\SQL\City;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {

        DB::table('countries')->truncate();
        DB::table('states')->truncate();
        DB::table('cities')->truncate();

        $india = Country::create([
                'name' => 'India',
                'code' => 'IND'
            ]);


        $indian_states = [

            'AP' => 'Andhra Pradesh',
            'AR' => 'Arunachal Pradesh',
            'AS' => 'Assam',
            'BR' => 'Bihar',
            'CT' => 'Chhattisgarh',
            'GA' => 'Goa',
            'GJ' => 'Gujarat',
            'HR' => 'Haryana',
            'HP' => 'Himachal Pradesh',
            'JK' => 'Jammu and Kashmir',
            'JH' => 'Jharkhand',
            'KA' => 'Karnataka',
            'KL' => 'Kerala',
            'MP' => 'Madhya Pradesh',
            'MH' => 'Maharashtra',
            'MN' => 'Manipur',
            'ML' => 'Meghalaya',
            'MZ' => 'Mizoram',
            'NL' => 'Nagaland',
            'OR' => 'Odisha',
            'PB' => 'Punjab',
            'RJ' => 'Rajasthan',
            'SK' => 'Sikkim',
            'TN' => 'Tamil Nadu',
            'TG' => 'Telangana',
            'TR' => 'Tripura',
            'UT' => 'Uttarakhand',
            'UP' => 'Uttar Pradesh',
            'WB' => 'West Bengal',
            'AN' => 'Andaman and Nicobar Islands',
            'CH' => 'Chandigarh',
            'DN' => 'Dadra and Nagar Haveli',
            'DD' => 'Daman and Diu',
            'DL' => 'Delhi',
            'LD' => 'Lakshadweep',
            'PY' => 'Puducherry',

        ];

        foreach($indian_states as $code => $state)
        {
            $india->states()->save(new State(['code' => $code, 'name' => $state]));
        }


        $hyderabad = new City(['name' => 'Hyderabad']);

        $telangana = State::where('code', 'TG')->first();

        $telangana->cities()->save($hyderabad);

    }
}
