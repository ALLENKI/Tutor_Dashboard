
<?php

use Aham\Models\SQL\ClassTiming;
use Aham\Models\SQL\Slot;
use Illuminate\Support\Collection;

function getNodes($topics)
{
   $nodes = new \Illuminate\Support\Collection;

   foreach($topics as $topic)
   {
        if($topic->children->count() == 0)
        {
            $nodes->push($topic);
        }
        else
        {
            $children = getNodes($topic->children);

            foreach($children as $child)
            {
                $nodes->push($child);
            }

            $nodes->push($topic);
        }
   }

   return $nodes;

}


function getCourseNodes($courses)
{
   $nodes = new \Illuminate\Support\Collection;
   $nodes->children = [];
//    dd($nodes);
   $childrens = new \Illuminate\Support\Collection;

   foreach($courses as $course)
   {
        if($course->courses->count() == 0)
        {
            $nodes->push($course);
        }
        else
        {
           
            foreach($course->courses as $child)
            {
                $nodes->push($child);
            }

        }
   }

   return $nodes;
}

function schedule($unit_id, $class_id)
{
    $timing = ClassTiming::where('class_id',$class_id)
               ->where('unit_id',$unit_id)
               ->first();

    if($timing)
    {
        return $timing->date->format('jS M Y, D')
              .' / '.$timing->start_time.' - '.$timing->end_time.' / '.
              ucwords(str_replace('_', ' ', $timing->session));
    }
    
    return 'NA';
}

function timing($unit_id, $class_id)
{
    $timing = ClassTiming::where('class_id',$class_id)
               ->where('unit_id',$unit_id)
               ->first();

    return $timing;
}

function classTimingStatus($unit_id, $class_id)
{
    $timing = ClassTiming::where('class_id',$class_id)
               ->where('unit_id',$unit_id)
               ->first();

    if(is_null($timing))
    {
        return $timing;
    }

    $endTime = $timing->date->format('d-m-Y').' '.$timing->end_time;
    $endTime = Carbon::createFromTimestamp(strtotime($endTime));

    $timing->end_time = $endTime;

    return $timing;
}

function cdn($asset)
{
    if (env('ASSET_SOURCE', 'local') == 'local') {
        return asset($asset);
    }

    $cdns = env('AWS_STATIC');

    return cdnPath($cdns, $asset);
}

function cdnPath($cdn, $asset)
{
    return  rtrim($cdn, '/').'/'.ltrim($asset, '/');
}

function array_neighbor($arr, $key)
{
    krsort($arr);
    $keys = array_keys($arr);
    $keyIndexes = array_flip($keys);
    
    $return = array();
    if (isset($keys[$keyIndexes[$key]-1]))
        $return[] = $keys[$keyIndexes[$key]-1];
    if (isset($keys[$keyIndexes[$key]+1]))
        $return[] = $keys[$keyIndexes[$key]+1];

    return $return;
}


function array_diff_once($array1, $array2) {

    foreach($array2 as $a) {
        $pos = array_search($a, $array1);
        if($pos !== false) {
            unset($array1[$pos]);
        }
    }

    return $array1;
}

function ordinalSuffix( $a )
{
  return $a.substr(date('jS', mktime(0,0,0,1,($a%10==0?9:($a%100>20?$a%10:$a%100)),2000)),-2);
}

function inrFormat($amount)
{
  return $amount;
  setlocale(LC_MONETARY, 'en_IN');
  $amount = money_format('%!i', $amount);
  return $amount;
}

function date_sort($a, $b) {
    return strtotime($a) - strtotime($b);
}

function unique_multidim_array($array, $key) { 
    $temp_array = array(); 
    $i = 0; 
    $key_array = array(); 
    
    foreach($array as $val) { 
        if (!in_array($val[$key], $key_array)) { 
            $key_array[$i] = $val[$key]; 
            $temp_array[$i] = $val; 
        } 
        $i++; 
    } 
    return $temp_array; 
} 
