@extends('frontend._layouts.default')

@section('main') 


<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/account">Account</a></li>
            <li class="active"><a href="#">Register</a></li>
        </ul>
    </div>
</div>

<div class="account">
    <div class="row">

        <div class="col-sm-12 col-md-12 col-lg-12 register">
			<h1>Register</h1>
            <hr/>
			<div class="block">
            	@notification()

				<?php echo Form::open(array('route' => 'account.register')); ?>

 				<div class="form-group">
                    <label for="middle-label">{!! trans('form.email') !!}</label>
                    {!! Form::email('email', null, array('required' => '', 'class' => "form-control")) !!}
                </div>

				<div class="form-group">
                	<label for="middle-label">{!! trans('form.password') !!}</label>
                    {!! Form::password('password', array('required' => '', 'class' => "form-control")) !!}
                </div>

 				<div class="form-group">
                    <label for="middle-label">{!! trans('form.firstname') !!}</label>
                    {!! Form::text('firstname', null, array('required' => '', 'class' => "form-control")) !!}
                </div>

                <div class="form-group">
                	<label for="middle-label">{!! trans('form.lastname') !!}</label>
                    {!! Form::text('lastname', null, array('required' => '', 'class' => "form-control")) !!}
                </div>

				<div class="form-group">
				    <label for="middle-label">{!! trans('form.zipcode') !!}</label>
				    {!! Form::text('zipcode', null, array('class' => 'zipcode form-control checkzipcode', 'data-url' => '/account/check-zipcode', 'required' => '', 'class' => "form-control")) !!}

				</div>

				<div class="form-group">
				    <label for="middle-label">{!! trans('form.housenumber') !!}</label>
				    {!! Form::text('housenumber', null, array('class' => 'housenumber form-control checkhousenumber', 'data-url' => '/account/check-zipcode', 'required' => '', 'class' => "form-control")) !!}

				</div>

	            <div class="form-group">
	                <label for="middle-label">{!! trans('form.houseletter') !!}</label>
	                {!! Form::text('housenumber_suffix', null, array('class' => 'form-control')) !!}

	            </div>

				<div class="form-group">
				    <label for="middle-label">{!! trans('form.street') !!}</label>
				    {!! Form::text('street', null, array('class' => 'fillstreet', 'required' => '', 'class' => "form-control")) !!}
				</div>

				<div class="form-group">
				    <label for="middle-label">{!! trans('form.city') !!}</label>
				    {!! Form::text('city', null, array('class' => 'fillcity', 'required' => '', 'class' => "form-control")) !!}
				</div>

				<div class="form-group">
					<label for="middle-label">{!! trans('form.company') !!}</label>
					{!! Form::text('company', null, array('class' => 'form-control')) !!}
				</div>


				<div class="form-group">
				    <label for="middle-label">{!! trans('form.country') !!}</label>


				    @if($sendingMethods AND $sendingMethods->first()->countryPrices())
				    {!! Form::select('country', $sendingMethods->first()->countryPrices->pluck('name', 'country_code'), 'nl', array('required' => '', 'class' => "form-control")) !!}


				    @else




				    {!! Form::select('country', array('nl' => 'Netherlands', 'be' => 'Belgium'), null, array('required' => '', 'class' => "form-control")) !!}
				    @endif


				</div> 


				<div class="form-group">
	
				   <label for="newsletter_subscription">
				   	 {!! Form::checkbox('newsletter_subscription', 1, true, array( "id" => "newsletter_subscription")) !!}
				    
				   	{!! trans('text.newsletter-subscribe-short-description') !!}
				   </label>
				    

				</div>	

				<div class="form-group">
	                    <button type="submit" class="btn btn-success">{!! trans('buttons.register') !!}</button>
	                </div>
	            </div>

			</form>
		</div>

	</div>

</div>

</section>


@stop