@extends('dashboard.student.layouts.master')

@section('content')

<div class="page-header page-header-default page-header-xs">
    <div class="page-header-content">
        <div class="page-title">
            <h5><i class="icon-coins position-left"></i> <span class="text-semibold">Learner Credits</span> - Add </h5>
        </div>
    </div>
</div>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Buy Credits</h5>
    </div>

    <div class="panel-body">

        <?php 

            $columnSizes = [
              'sm' => [4, 8],
              'lg' => [2, 10]
            ];

        ?>

        {!! BootForm::openHorizontal($columnSizes)->action(route('student::credits.pay')) !!}


        {!! BootForm::select('Credits *', 'credits')
                    ->options([10 => 10,25 => 25,50 => 50,75 => 75,100 => 100])
                    ->attribute('required','true')
                    ->attribute('id','credits')
        !!}

        {!! BootForm::select('Coupon', 'coupon')
                ->options(['' => 'Please Select'] + $coupons)
              ->placeholder('Coupon')
              ->attribute('id','coupon')
        !!}


        <div class="text-right">
            <button type="submit" class="btn btn-primary">Buy Credits <i class="icon-arrow-right14 position-right"></i></button>
        </div>

        {!! BootForm::close() !!}
    </div>

</div>

{{--
<div class="row">
    <div class="col-md-6">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Coupons</h5>
            </div>

            <table class="table table-responsive">
                <thead>
                    <th>Coupons</th>
                    <th>Offer</th>
                </thead>

                <tbody>
                    @foreach($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->coupon }}</td>
                        <td>{{ $coupon->present()->descriptionStyled }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
--}}
@stop

@section('scripts')
@parent
<script type="text/javascript">
    $(function(){
        // $('select').select2();
    });     
</script>
@stop