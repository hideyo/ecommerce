@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li class="active"><a href="{{ URL::route('order.index') }}">Overview <span class="sr-only">(current)</span></a></li>

        </ul>
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{{ URL::route('order.index') }}">Order</a></li>  
            <li class="active">print</li>
        </ol>

        <a href="{{ URL::route('order.index') }}" class="btn btn-danger pull-right" aria-label="Left Align"><span class="glyphicon glyphicon-return" aria-hidden="true"></span> Back to overview</a>

        <h2>Order <small>print</small></h2>
        <hr/>
        @include('flash::message')

     	{!! Form::open(array('route' => array('order.print'), 'files' => true, 'class' => 'form-horizontal form-groups-bordered validate print-form', "data-url" => "/admin/order/print/products")) !!}



        <div class="form-group">   
            {!! Form::label('order_status_id', 'Status', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
        		{!! Form::select('order_status_id', array('' => '--Select--') + $orderStatuses->toArray(), null, array('class' => 'print-input print-input-select form-control')) !!}
			</div>
        </div>
        

            <div class="form-group">
                {!! Form::label('start_date', 'start date', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('start_date', null, array('class' => 'datepicker form-control print-input-date', 'data-sign' => '&euro;')) !!}
                </div>
            </div>

            <div class="form-group">
                {!! Form::label('end_date', 'end date', array('class' => 'col-sm-3 control-label')) !!}
                <div class="col-sm-5">
                    {!! Form::text('end_date', null, array('class' => 'datepicker form-control print-input-date', 'data-sign' => '&euro;')) !!}
                </div>
            </div>

         {!! Form::close() !!}

     	{!! Form::open(array('route' => array('order.download.print'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

        <div class="form-group">   
            {!! Form::label('type', 'Print type', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::select('type', array('one-pdf' => 'One pdf', 'product-list' => 'Product list'), null, array('class' => 'print-input print-input-select form-control')) !!}
            </div>
        </div>

        <div class="form-group">   
            {!! Form::label('products', 'Orders', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">

		        <div class="selected-orders">
			        <table>
			        	<tbody>

			        		<tr>
			        			<td>no selection</td>
			        		</tr>
			        	</tbody>
			        </table>
		    	</div>


            </div>

        </div>





        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
				{!! Form::submit('Print all', array('class' => 'print-subbmit btn btn-default')) !!}
                <a href="{!! URL::route('order.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>

        {!! Form::close() !!}
     
   </div>
</div>
@stop