@extends('backend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-tabs', array('productExtraFieldValue' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

        <ol class="breadcrumb">
            <li><a href="/"><i class="entypo-folder"></i>Dashboard</a></li>
            <li><a href="{!! URL::route('product.index') !!}">Product</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">edit</a></li>
            <li><a href="{!! URL::route('product.edit', $product->id) !!}">{!! $product->title !!}</a></li>
            <li class="active">extra fields</li>
        </ol>


        <h2>Product <small>extra fields</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

        @if($extraFields)
        {!! Form::open(array('route' => array('product.product-extra-field-value.store', $product->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Select value</th>
                    <th>Override value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($extraFields as $row)   
                <tr>
                    <td>
                        {!! $row->title !!}
                        {!! Form::hidden('rows['.$row->id.'][extra_field_id]', $row->id) !!}                        
                    </td>
                    <td>
                        @if($row->values->count())
                        @if(isset($populateData[$row->id]['extra_field_default_value_id']))
                        {!! Form::select('rows['.$row->id.'][extra_field_default_value_id]', array('' => '---select---') + $row->values()->get()->pluck('value', 'id')->toArray(), $populateData[$row->id]['extra_field_default_value_id'],  array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                        
                        @else
                        {!! Form::select('rows['.$row->id.'][extra_field_default_value_id]', array('' => '---select---') + $row->values()->get()->pluck('value', 'id')->toArray(), null,  array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
                        
                        @endif
                        @endif    
                    </td>

                    <td>
                        @if(isset($populateData[$row->id]['value']))
                        {!! Form::text('rows['.$row->id.'][value]', $populateData[$row->id]['value'], array('class' => 'form-control', 'data-validate' => 'required,number', 'data-message-required' => 'This is custom message for required field.')) !!}

                        @else
                        {!! Form::text('rows['.$row->id.'][value]', null, array('class' => 'form-control', 'data-validate' => 'required,number', 'data-message-required' => 'This is custom message for required field.')) !!}

                        @endif


                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="form-group">
            <div class="col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
            </div>
        </div>

        {!! Form::close() !!}
        @endif

    </div>
</div>
@stop