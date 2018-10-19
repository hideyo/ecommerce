@extends('backend._layouts.default')

@section('main')
<div class="row rowTopPadding">
    <div class="col-sm-3 col-md-2 sidebar">
        <ul class="nav nav-sidebar">
            <li><a href="{!! URL::route('product-category.index') !!}">Overview <span class="sr-only">(current)</span></a></li>
            <li class="active"><a href="{!! URL::route('product-category.create') !!}">Create</a></li>
            <li><a href="{{ URL::route('product-category.tree') }}">Tree</a></li>
        </ul>
    </div>
    <div class="col-sm-9 col-md-10 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('product-category.index') !!}">Product categories</a></li>  
            <li class="active">create</li>
        </ol>

        <h2>Product category <small>create</small></h2>
        <hr/>
        {!! Notification::showAll() !!}

    {!! Form::open(array('route' => array('product-category.store'), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}
        <input type="hidden" name="_token" value="{!! Session::token() !!}">

        <div class="form-group">
            {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), '0', array('class' => 'form-control')) !!}
            </div>
        </div>
        
        <div class="form-group">
            {!! Form::label('title', 'Title', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::text('title', null, 
                array(
                    'class' => 'form-control', 
                    'minlength' => 4, 
                    'maxlength' => 65, 
                    'data-error' => trans('validation.between.numeric', ['attribute' => 'title', 'min' => 4, 'max' => 65]), 
                    'required' => 'required'
                )) !!}
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('short_description', 'Short Description', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::textarea('short_description', null, array('class' => 'form-control')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('description', 'Description', array('class' => 'col-sm-3 control-label')) !!}

            <div class="col-sm-5">
                {!! Form::textarea('description', null, array('class' => 'form-control ckeditor')) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('parent_id', 'Parent', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('parent_id', array(), null, array('class' => 'parent_id form-control')) !!}             
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('redirect_product_category_id', 'Redirect category', array('class' => 'col-sm-3 control-label')) !!}
            <div class="col-sm-5">
                {!! Form::select('redirect_product_category_id', array(), null, array('class' => 'redirect_product_category_id form-control')) !!}                        
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                {!! Form::submit('Save', array('class' => 'btn btn-default')) !!}
                <a href="{!! URL::route('product-category.index') !!}" class="btn btn-large">Cancel</a>
            </div>
        </div>

    {!! Form::close() !!}
    </div>
</div>



<script type="text/javascript">
    $(document).ready( function () {
        function repoFormatResult(repo) {


            return repo.title;
        }

        function repoFormatSelection(repo) {
            return repo.title;
        }





        $(".parent_id, .redirect_product_category_id").select2({
            minimumInputLength: 1,
            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                url: "{!! URL::route('product-category.ajax_categories') !!}",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    q: params.term, // search term
                    page: params.page
                  };
                },
                processResults: function (data, page) {


                 // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to alter the remote JSON data
                    return { results: data };
                },
                cache: true
            },   
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1,
            allowClear: true,
            placeholder: "Select a category",            
            templateResult: repoFormatResult, // omitted for brevity, see the source of this page
            templateSelection: repoFormatSelection,  // omitted for brevity, see the source of this page 
         });
    });
</script>

@stop
