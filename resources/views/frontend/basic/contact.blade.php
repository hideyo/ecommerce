@extends('frontend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li class="active"><a href="#">Contact</a></li>
        </ul>
    </div>
</div>


<div class="row">
    
    <div class="col-lg-2">
        <div class="sidebar-content">
          @if(HtmlBlockHelper::findByPosition("footer-contact"))  
            {!! HtmlBlockHelper::findByPosition("footer-contact") !!}
            @endif 
        </div>
    </div>

    <div class="col-lg-offset-2 col-lg-8 contact-block">

        <h1>Contact</h1>
        <div class="block">
            @notification()
        </div>
        
        <div class="block">

            {!! Form::open(array('method' => 'put', 'route' => array('contact'), 'class' => 'form', 'data-toggle' => 'validator')) !!}
                  
                <div class="form-group">                    
                    <label>{!! trans('form.email') !!}</label>
                    {!! Form::email('email', null, array('required' => '', 'class' => 'form-control')) !!}
                </div>

                <div class="form-group">
                    <label>{!! trans('form.name') !!}</label>
                    {!! Form::text('name', null, array('required' => '','class' => 'form-control')) !!}
                </div>
                
                <div class="form-group">
                    <label>{!! trans('form.message') !!}</label>
                    {!! Form::textarea('message', null, array('required' => '', 'class' => 'form-control')) !!}
                </div>

                <div class="form-group"> 
                    <button type="submit" class="btn btn-success">{!! trans('buttons.send') !!}</button>
                </div>

            </form>
       </div>
    </div>
</div>
@stop