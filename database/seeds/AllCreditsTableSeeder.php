<?php

use Illuminate\Database\Seeder;
use Aham\Models\SQL\CreditsPurchased;
use Aham\Models\SQL\CreditsPromotional;
use Aham\Models\SQL\CreditsHubOnly;
use Aham\Models\SQL\CreditsBucket;
use Aham\Models\SQL\Credits;
use Aham\Models\SQL\Hub;


class AllCreditsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(is_null(Hub::find(1))) {

            Hub::create([
                'id' => 1,
                'name' => 'nano',
                'location' => 'Gachibowli'
            ]);

        }

        for($i = 1; $i <= 10; $i++) {

            $random = RAND(30,100);

            $purchased =  CreditsPurchased::create([
                                'user_id' => 6,
                                'type' => 'INR',
                                'credits' => $random,
                                'price' => ($random*1100),
                                'of_id' => 1,
                                'of_type' => '\Aham\Model\SQL\Hub'
                          ]);

            $promotional =  CreditsPromotional::create([
                                'user_id' => 6,
                                'credits' => ($random - 30),
                                'coupon' => 'typeofcoupon',
                                'of_id' => 'Aham\Model\SQL\Hub',
                                'parchased_id' => $purchased->id,
                            ]);
            
            $hubOnly = CreditsHubOnly::create([
                                'user_id' => 6,
                                'credits' => $random,
                                'price' => ($random*1100)
                       ]);

            $bucket =  CreditsBucket::create([
                            'user_id' => 6,
                            'purchased_total' => $purchased->credits,
                            'promotional_total' => $promotional->credits,
                            'hub_only_total' => $hubOnly->credits,
                            'total_credits' => ($purchased->credits+$promotional->credits+$hubOnly->credits),
                            'purchased_remaining' => $purchased->credits, 
                            'promotional_remaining' => $promotional->credits, 
                            'hub_only_remaining' => $hubOnly->credits, 
                            'total_remining' => ($purchased->credits+$promotional->credits+$hubOnly->credits), 
                       ]);
                       
       }

    }

}
