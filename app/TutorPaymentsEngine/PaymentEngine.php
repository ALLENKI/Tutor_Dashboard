<?php

namespace Aham\TutorPaymentsEngine;

use Aham\Models\SQL\TutorPayments;
use Aham\Models\SQL\ClassTiming;

class PaymentEngine
{


    public function calculatePayment($ahamClass,$tutor,$hub)
    {
        
        // Find tutor preferences on that hub and his/her timings.
        
        /*
        * case1: commission_type = 'percentage' , commisson_value = 20
        *        settlement_type = 'per_enrollment'
        *         min_enrollment,max_enrollment
        *      
        *        student enrollment count is less then min_enrollment then pay.
        *        pay = ( )
        *        student enrollment count is more then max_enrollment then pay.
        *        pay =  (per_max_enrollments_amount / 30) + pay;
        *       
        */

        /*
        * case2: commission_type = 'amount', commisson_value = 2000
        *        settlement_type = 'fixed' or 'per_enrollment'
        *      
        *        pay = (per_enrollment_amount / 30);
        *
        */
        

    }
    
}
