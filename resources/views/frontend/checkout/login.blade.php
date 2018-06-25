@extends('frontend._layouts.default')

@section('main')

<div class="row">
    <div class="col-sm-12 col-md-12">
        <ol class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="#">Checkout</a></li>
            <li><a href="#">Login</a></li>
        </ol>
    </div>
</div>

<div class="main-cart main-cart-login">
    <div class="row">


        @if ($errors->register)
        <div class="col-lg-12  ">
        @notification()
        </div>
        @endif
    

        <div class="col-lg-4  ">
            <div class="summary-cart-reload" data-url="/cart/summary-reload" >
                @include('frontend.checkout._summary')
            </div>
        </div>      
    
        <div class=" col-lg-3 ">
       
            <h3>Existing client</h3>  

            {!!  Form::open(array('url' => '/cart/checkout-login', 'class' => 'form', 'data-toggle' => 'validator')) !!}

                <div class="form-group">         
                    <label>{!! trans('form.email') !!}</label>
                    {!! Form::email('email', null, array('required' => '', 'class' => 'form-control')) !!}
                </div>

                <div class="form-group">
         
                    <label>{!! trans('form.password') !!}</label>
                    {!! Form::password('password', array('required' => '', 'class' => 'form-control')) !!}
                       
                </div>
              
                <div class="form-group text-right">
                    <a href="{!! URL::route('account.forgot.password') !!}" class="btn btn-link">forgot password?</a>
                    <button type="submit" class="btn btn-success">Login</button>
         
                </div>



            {!! Form::close() !!}
              
        </div>

        <div class="col-lg-offset-1  col-lg-4">
            <h3>New client</h3>  



            <?php echo Form::open(array('url' => '/cart/checkout-register', 'class' => 'form', 'data-toggle' => 'validator')); ?>

                <div class="form-group">
                        <label>{!! trans('form.email') !!}</label>
                        {!! Form::email('email', null, array('required' => '', 'class' => 'form-control')) !!}

             
                </div>

                
                    <div class="form-group">
                        <label>{!! trans('form.firstname') !!}</label>
                        {!! Form::text('firstname', null, array('required' => '', 'class' => "form-control")) !!}
                   

                    </div>

                    <div class="form-group">
                        <label>{!! trans('form.lastname') !!}</label>
                                {!! Form::text('lastname', null, array('required' => '', 'class' => "form-control")) !!}
                   

                    </div>





                <div class="form-group">
                        <label>{!! trans('form.zipcode') !!}</label>
                        {!! Form::text('zipcode', null, array('class' => 'zipcode form-control checkzipcode', 'data-url' => '/account/check-zipcode', 'required' => '')) !!}

                    </div>

                             <div class="form-group">
                        <label>{!! trans('form.housenumber') !!}</label>
                        {!! Form::text('housenumber', null, array('class' => 'housenumber form-control checkhousenumber', 'data-url' => '/account/check-zipcode', 'required' => '')) !!}

                    </div>

                                 <div class="form-group">
                        <label>{!! trans('form.houseletter') !!}</label>
                        {!! Form::text('housenumber_suffix', null, array('class' => 'form-control')) !!}

                    </div>
           

<div class="form-group">
                        <label>{!! trans('form.street') !!}</label>
                        {!! Form::text('street', null, array('class' => 'fillstreet', 'required' => '', 'class' => 'form-control')) !!}
                 

              
                </div>

<div class="form-group">
                        <label>{!! trans('form.city') !!}</label>
                        {!! Form::text('city', null, array('class' => 'fillcity', 'required' => '', 'class' => 'form-control')) !!}

                  
                </div>

<div class="form-group">
                        <label>{!! trans('form.country') !!}</label>


                            @if(app('cart')->getConditionsByType('sending_method_country_price')->count())
                            {!! Form::select('sending_method_country_price_id', 
                            app('cart')->getConditionsByType('sending_method_country_price')->first()->getAttributes()['data']['country_list']->toArray(), 
                            app('cart')->getConditionsByType('sending_method_country_price')->first()->getAttributes()['data']['sending_method_country_price_id'], 
                            array('required' => '', 'class' => 'form-control')) !!}
                            
                            @else
                             {!! Form::select('country', array('nl' => 'Netherlands', 'be' => 'Belgium'), null, array('required' => '', 'class' => 'form-control')) !!}
                            @endif

                </div>    

                 <h5>Newsletter</h5>
<div class="form-group">
                         <label>{!! Form::checkbox('newsletter_subscription', 1, true, array( "id" => "newsletter_subscription")) !!}
                        subscribe</label>
                  
                
              
                </div>
                           

                <h5>Account</h5>
                <p>Register account and get special offers.</p>

<div class="form-group">
                        <label>{!! trans('form.password') !!}</label>
                        {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'wachtwoord')) !!}
           

    
                </div>


                <button type="submit" class="btn btn-success">Create</button>

            </form>
        </div>
    </div>
</div>
@stop