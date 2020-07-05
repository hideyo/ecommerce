@extends('backend._layouts.default')

@section('main')
<div class="row">
    <div class="col-sm-3 col-md-2 sidebar">
        @include('backend._partials.product-category-tabs', array('productCategoryEdit' => true))
    </div>
    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <ol class="breadcrumb">
            <li><a href="{{ URL::route('dashboard.index') }}">Dashboard</a></li>
            <li><a href="{!! URL::route('product-category.index') !!}">Product categories</a></li>  
            <li><a href="{!! URL::route('product-category.edit', $productCategory->id) !!}">edit</a></li>
            <li class="active"><a href="{!! URL::route('product-category.edit', $productCategory->id) !!}">{!! $productCategory->title !!}</a></li>
            <li class="active">general</li>
            
        </ol>
        <h2>Productcategory <small>edit</small></h2>
        <hr/>
        @include('flash::message')
        <div class="row">
            <div class="col-md-12">
                {!! Form::model($productCategory, array('method' => 'put', 'route' => array('product-category.update', $productCategory->id), 'files' => true, 'class' => 'form-horizontal', 'data-toggle' => 'validator')) !!}

                <div class="form-group">
                    {!! Form::label('active', 'Active', array('class' => 'col-sm-3 control-label')) !!}
                    <div class="col-sm-5">
                        {!! Form::select('active', array('0' => 'No', '1' => 'Yes'), null, array('class' => 'form-control')) !!}
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

                        @if($productCategory->parent()->count()) 
                        {!! Form::select('parent_id', array($productCategory->parent()->first()->id => $productCategory->parent()->first()->title), null, array('class' => 'parent_id form-control')) !!}
                
                        @else 
                        {!! Form::select('parent_id', array(), null, array('class' => 'parent_id form-control')) !!}
                 
                        @endif                      
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('redirect_product_category_id', 'Redirect category', array('class' => 'col-sm-3 control-label')) !!}
           
                    <div class="col-sm-5">

                        @if($productCategory->redirect_product_category_id) 
                        {!! Form::select('redirect_product_category_id', array($productCategory->redirect_product_category_id => $productCategory->refProductCategory->title), null, array('class' => 'redirect_product_category_id form-control')) !!}
                
                        @else 
                        {!! Form::select('redirect_product_category_id', array(), null, array('class' => 'redirect_product_category_id form-control')) !!}
                 
                        @endif
                        

                    </div>


                </div>

                @include('backend._fields.seo-fields')

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