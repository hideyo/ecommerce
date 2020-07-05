@extends('backend._layouts.login')

@section('main')

<div class="container">


    <div class="row">
        <div class="col-sm-4 col-md-4 col-lg-4 col-md-offset-4 col-sm-offset-4 col-lg-offset-4">
            <div class="login">
                <h1>Login</h1>
                
                {!! Form::open(array('class' => 'form-signin', 'data-toggle' => 'validator')) !!}
                @include('flash::message')
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="inputEmail" class="sr-only">Email address</label>
                    {!! Form::text('email',  null, array('required' => '', 'class' =>'form-control', 'placeholder'=>'Email')) !!}
                </div> 

                <div class="form-group">
                    <label for="inputPassword" class="sr-only">Password</label>
                    {!! Form::password('password', array('required' => '', 'class' =>'form-control', 'placeholder'=>'Password')) !!}
                </div>           
                <button type="submit" class="btn btn-default">Login</button> 
                {!! Form::close() !!}
            </div>
            <p class="text-center">
                <small>Hideyo.io</small>
            </p>
        </div>
    </div>

</div> <!-- /container -->

@stop
