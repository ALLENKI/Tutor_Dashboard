@extends('backend.layouts.master')

@section('content')

<!-- Basic datatable -->

@if (Session::has('message'))
   <div class="alert alert-info">{{ Session::get('message') }}</div>
    @foreach($errors->all() as $error)
       <div class="alert alert-danger">{{ $error }}</div>
    @endforeach
@endif

<div class="panel panel-flat">

	<div class="panel-heading">
		<h5 class="panel-title">Edit PAYMENT - {{ $studentPaymentId->student->user->name }}</h5>
	</div>

    <div class="panel-body">
    <form  method="post" action="{{route('admin::users::payments.update',$studentPaymentId->id)}}" enctype="multipart/form-data">

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="row">
        <div class="form-group">
            <label class="col-sm-4 col-lg-2 control-label" for="paymentmethod">
                Payment Method
            </label>
            <div class="col-sm-8 col-lg-6">
                <select name="paymentmethod" value="" id="paymentmethod" class="form-control" placeholder="Name">
                        <option>{{$method}}</option>
                        <option>cash</option>
                        <option>cheque</option>
                        <option>online_payment</option>
                        <option>pending</option>
                        <option>online_transfer</option>
                </select>
            </div>
        </div>
        </div>

        <div class="row">
        <div class="form-group">
            <label class="col-sm-4 col-lg-2 control-label" for="amount">
                Amount
            </label>
            <div class="col-sm-8 col-lg-6">
            <input type="number" name="amount" id="amount" class="form-control" placeholder="amount" required="true" value="{{$amount}}">
            </div>
        </div>
        </div>

        <div class="row">
        <div class="form-group">
            <label class="col-sm-4 col-lg-2 control-label" for="remarks">
                Remarks
            </label>
            <div class="col-sm-8 col-lg-6">
            <input type="text" name="remarks" id="remarks" class="form-control" placeholder="remarks" required="true" value="{{$remarks}}">
            </div>
        </div>
        </div>


        <div class="row">
        <div class="form-group">
            <label class="col-sm-4 col-lg-2 control-label" for="invoice_no">
                Invoice No
            </label>
            <div class="col-sm-8 col-lg-6">
             <input type="text" name="invoice_no" id="invoice_no" class="form-control" placeholder="remarks" required="true" value="{{$invoiceNo}}">
            </div>
        </div>
        </div>

				<div class="row">
					<div class="form-group">
							<label class="col-sm-4 col-lg-2 control-label">Upload Invoice:</label>
				      <input name="invoice" class="file-input" type="file" accept="application/pdf">
					</div>
				</div>

        <hr>

        <div class = "text-right">
            <button type="submit" class="btn btn-primary legitRipple">
               Save
            </button>
        </div>
    </form>

    <form  method="post" action="{{route('admin::users::payments.destroy',$studentPaymentId->id)}}" >
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <div class="col-sm-2 col-lg-2">
        <div class = "text-left">
            <button type="submit" class="btn btn-danger legitRipple">
               Delete
            </button>
        </div>
        </div>
    </form>

    </div> <!-- panel body -->
    </div>


</div> <!-- panel flat -->
</div><!-- page content -->
@stop
