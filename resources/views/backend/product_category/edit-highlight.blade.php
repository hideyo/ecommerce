@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-category-tabs', array('productCategoryHighlight' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('product-category.index') !!}">Product categories</a></li>  
            <li><a href="{!! URL::route('product-category.edit', $productCategory->id) !!}">edit</a></li>
            <li class="active"><a href="{!! URL::route('product-category.edit', $productCategory->id) !!}">{!! $productCategory->title !!}</a></li>
            <li class="active">general</li>
            
        </ol>
        <h2>Productcategory <small>highlight</small></h2>
        <hr/>
        @include('flash::message')
        <div class="row">
            <div class="col-md-12">
                {!! Form::model($productCategory, array('method' => 'put', 'route' => array('product-category.update', $productCategory->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

                {!! Form::hidden('parent_id', null, array('class' => 'parent_id form-control')) !!}
                
                {!! Form::hidden('highlight', 1) !!}
                <div class="form-group">
                    {!! Form::label('product_category_highlight_title', 'Highlight title', array('class' => 'col-sm-3 control-label')) !!}

                    <div class="col-sm-5">
                        {!! Form::text('product_category_highlight_title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a name')) !!}
                    </div>
                </div>


		        <div class="form-group">
		            {!! Form::label('highlightProducts', 'Products', array('class' => 'col-sm-3 control-label')) !!}
		            <div class="col-sm-5">
		                {!! Form::multiselect2('highlightProducts[]', $products->toArray(), $productCategory->productCategoryHighlightProduct()->pluck('product_id')->toArray()) !!}
		            </div>
		        </div>

                <div class="form-group">
                    {!! Form::label('product_overview_title', 'Highlight title', array('class' => 'col-sm-3 control-label')) !!}

                    <div class="col-sm-5">
                        {!! Form::text('product_overview_title', null, array('class' => 'form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.', 'placeholder' => 'type a name')) !!}
                    </div>
                </div>


                <div class="form-group">
                    {!! Form::label('product_overview_description', 'Short Description', array('class' => 'col-sm-3 control-label')) !!}

                    <div class="col-sm-5">
                        {!! Form::textarea('product_overview_description', null, array('class' => 'ckeditor form-control', 'required' => 'required', 'data-message-required' => 'This is custom message for required field.')) !!}
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
                function formatRepo(repo) {


                    return repo.title;
                }

          function formatRepoSelection (repo) {
            return repo.title || repo.text;
          }


        $(".parent_id, .redirect_product_category_id").select2({
          ajax: {
            url: "{!! URL::route('product-category.ajax_categories') !!}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
              return {
                q: params.term, // search term
                selectedId: {{ $productCategory->id }}
              };
            },
            processResults: function (data, page) {
              // parse the results into the format expected by Select2.
              // since we are using custom formatting functions we do not need to
              // alter the remote JSON data
              return {
                results: data
              };
            },
            cache: true
          },
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1,
        allowClear: true,
        placeholder: "Select a category",  
          
          templateResult: formatRepo, // omitted for brevity, see the source of this page
          templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });
            });
        </script>
    </div>
</div>
@stop