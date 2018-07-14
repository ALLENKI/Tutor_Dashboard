<?php

namespace Aham\Http\Controllers\V2\HubDB;

use Aham\Http\Controllers\Controller;
use Aham\Repositories\CourseCatalogRepository;
use League\Fractal;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Aham\TransformersV2\CourseCatalogTopicTransformer;
use Aham\TransformersV2\CourseCatalogCourseTransformer;
use Aham\Models\SQL\Topic;
use Aham\Models\SQL\Location;
use Aham\Models\SQL\StudentEnrollmentUnit;
use Aham\Models\SQL\CreditsUsed;
use Aham\Models\SQL\CreditsRefund;
use Aham\Models\SQL\AhamClass;
use Aham\Models\SQL\ClassTiming;
use Illuminate\Support\Collection;
use Carbon;
use DB;
use Input;

class EarningsController extends BaseController
{

    public function __construct()
    {

    }

    public function index($slug)
    {
        $location = Location::where('slug',$slug)->first();

        $dateRange = Input::get('dateRange');

        $start = Carbon::createFromTimestamp(strtotime($dateRange[0]));
        $end = Carbon::createFromTimestamp(strtotime($dateRange[1]));

        $classTimings = ClassTiming::where('date','>=',$start)
                                    ->where('date','<=',$end)
                                    ->where('location_id',$location->id)
                                    ->get();

        $classIds = array_unique($classTimings->pluck('class_id')->toArray());

        $enrollredUnits = StudentEnrollmentUnit::where('date','>=',$start)
                                                ->where('date','<=',$end)
                                                ->where('location_id',$location->id)
                                                ->get();


        $classes = AhamClass::with('enrollments.student','creditsUsed.bucket','creditsUsed.user','enrollments.unitEnrollments','enrollmentUnits','timings')
        ->whereIn('id',$classIds)
        ->get();

        // return array_diff($classIds,$classes->pluck('id')->toArray());

        $totals = [];
        $totals['final_total'] = 0;
        $totals['final_total_2'] = 0;
        $totals['purchased'] = 0;
        $totals['promotional'] = 0;
        $totals['hub_only'] = 0;
        // return $classWiseCreditsUsage;

        $reconEnrollmentFailed = [];
        $reconUsageFailed = [];
        $outliers = [];
        $classWiseCreditsUsage = [];

        foreach($classes as $class)
        {

            $creditsUsage = [];
            $totalEarned = 0;

            foreach($class->creditsUsed as $creditUsed)
            {
                $oneInstance = [
                    'user' => $creditUsed->user->name,
                    'credits' => $creditUsed->credits,
                    'credits_type' => $creditUsed->credits_type,
                    'refund_remaining' => $creditUsed->refund_remaining,
                    'credit_price' => $creditUsed->bucket->price_per_credit,
                    'earned' => round($creditUsed->refund_remaining*$creditUsed->bucket->price_per_credit)
                ];

                $creditsUsage[] = $oneInstance;

                $totalEarned = $totalEarned + $oneInstance['earned'];

                $totals[$creditUsed->credits_type] = $totals[$creditUsed->credits_type] + $oneInstance['earned'];
                $totals['final_total'] = $totals['final_total'] + $oneInstance['earned'];
            }



            $totals['final_total_2'] = $totals['final_total_2'] + $totalEarned;

            $eachClassRecon = [
                'class_id' => $class->id,
                'class' => $class->topic_name,
                'start_date' => $class->start_date ? $class->start_date->format('jS M Y H:i:m') : 'NA',
                'credits_usage' => $creditsUsage,
                'totalEarned' => $totalEarned
            ];

            // Get all enrollments
            $allStudentEnrollmentCount = $class->enrollments->count();

            // Credits to be deducted from the class
            $creditsShouldBeDeduted = ($allStudentEnrollmentCount * $class->charge_multiply)
                                     * $class->classUnits->count();

            $creditsDeductedAccordingToUnits = $class->enrollmentUnits->sum('credits_used');

            $creditsActuallyDeducted = $class->creditsUsed->sum('refund_remaining');
            $creditsTotallyDeducted = $class->creditsUsed->sum('credits');
            $creditsTotallyRefunded = $class->creditsRefunded->sum('credits');

            $eachClassRecon['creditsShouldBeDeduted'] = $creditsShouldBeDeduted;
            $eachClassRecon['creditsDeductedAccordingToUnits'] = $creditsDeductedAccordingToUnits;
            $eachClassRecon['creditsActuallyDeducted'] = $creditsActuallyDeducted;
            $eachClassRecon['creditsTotallyDeducted'] = $creditsTotallyDeducted;
            $eachClassRecon['creditsTotallyRefunded'] = $creditsTotallyRefunded;
            $eachClassRecon['timings'] = $class->timings()->pluck('date')->toArray();
            $eachClassRecon['outliers'] = $class->timings->filter(function($timing) use ($end, $start) {
                return $timing->date->gt($end) || $timing->date->lt($start);
            });

            if(count($eachClassRecon['outliers']))
            {
                $outliers = array_merge($outliers, ($eachClassRecon['outliers'])->toArray());
            }


            $classWiseCreditsUsage[] = $eachClassRecon; 

            if( ($creditsShouldBeDeduted != $creditsActuallyDeducted) || ($creditsShouldBeDeduted != $creditsDeductedAccordingToUnits) )
            {
                $enrollments = $class->enrollments()->get();

                $classEnrollments = [];

                foreach($enrollments as $enrollment)
                {
                    $classEnrollment = [
                        'student_id' => $enrollment->student->id,
                        'user_id' => $enrollment->student->user->id,
                        'student' => $enrollment->student->user->email,
                        'credits_should_deducted' => $class->charge_multiply * $class->classUnits->count(),
                        'class_units' => $class->classUnits->count(),
                        'enrollment_units' => $enrollment->unitEnrollments->count(),
                        'status' => $enrollment->status,
                        'credits_actually_deducted' => CreditsUsed::where('of_id',$class->id)->where('user_id',$enrollment->student->user->id)->sum('refund_remaining') 
                    ];

                    $classEnrollments[] = $classEnrollment;
                }

                $reconEnrollmentFailed[] = [
                    'class_id' => $class->id,
                    'total_enrollments' => $allStudentEnrollmentCount,
                    'credits_should_deducted' => $creditsShouldBeDeduted,
                    'credits_actually_deducted' => $creditsActuallyDeducted,
                    'enrollments' => $classEnrollments
                ];
            }

            if($creditsTotallyDeducted - $creditsActuallyDeducted != $creditsTotallyRefunded)
            {
                $reconUsageFailed[] = [
                    'class_id' => $class->id,
                    'creditsTotallyDeducted' => $creditsTotallyDeducted,
                    'creditsActuallyDeducted' => $creditsActuallyDeducted,
                    'creditsTotallyRefunded' => $creditsTotallyRefunded,
                ];
            }
        }

        return [
            'outliers' => $outliers,
            'start_date' => $start->format('jS M Y'),
            'end_date' => $end->format('jS M Y'),
            'credits_sum_from_enrollments' => $enrollredUnits->where('status','enrolled')->sum('credits_used'),
            'credits_total_from_enrollments' => $enrollredUnits->sum('credits_used'),
            'credits_sum_usage_table' => CreditsUsed::whereIn('of_id',$classIds)->sum('refund_remaining'),
            'credits_total_usage_table' => CreditsUsed::whereIn('of_id',$classIds)->sum('credits'),
            'refunds_from_usage_table' => CreditsRefund::whereIn('of_id',$classIds)->sum('credits'),
            'recon_enrollment_failed' => $reconEnrollmentFailed,
            'recon_usage_failed' => $reconUsageFailed,
            'classIds' => $classIds,
            'classWiseCreditsUsage' => $classWiseCreditsUsage,
            'totals' => $totals,
        ];

        return ['rajiv' => 'rajiv'];
    }

}
