@extends('frontend._layouts.default')

@section('main') 

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/account">Account</a></li>
            <li><a href="#">Login</a></li>
        </ul>
    </div>
</div>


 <div class="account">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-5 login">
            <h1>Login</h1>
            <hr/> 
            @notification()

            <div class="block">


            	<?php echo Form::open(array('route' => 'account.login', 'class' => 'form', 'data-toggle' => 'validator')); ?>

					<div class="form-group">
						<label>{!! trans('form.email') !!}</label>
						{!! Form::email('email', null, array('required' => '',  'class' => 'form-control')) !!}
					</div>


					<div class="form-group">
					    <label>{!! trans('form.password') !!}</label>
					    {!! Form::password('password', array('required' => '', 'class' => 'form-control')) !!}
					</div>

					<div class="form-group">
				        <a href="{!! URL::route('account.forgot.password') !!}" class="btn btn-link">forgot password?</a>
				        <button type="submit" class="btn btn-success">{!! trans('buttons.login') !!}</button>
					</div>
       
            	</form>

            </div>

        </div>

        <div class="col-sm-12 col-md-12 col-lg-offset-2 col-lg-5 login">
        	<h1>Register</h1>
            <hr/>
			<a href="/account/register" class="btn btn-success">click here</a>

        </div>

    </div>
</div>
@stop