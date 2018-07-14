@extends('dashboard.student.layouts.master')

@section('content')

<div class="page-header page-header-default page-header-xs">
    <div class="page-header-content">
        <div class="page-title">
            <h5><i class="icon-coins position-left"></i> <span class="text-semibold">Credits</span> - Statement </h5>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Statement</h5>
            </div>


        </div>
    </div>
</div>
@stop

@section('scripts')
@parent
<script type="text/javascript">
    $(function(){
        // $('select').select2();
    });     
</script>
@stop