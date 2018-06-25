@extends('frontend._layouts.default')

@section('main') 

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/account">Account</a></li>
            <li class="active"><a href="#">wachtwoord veranderen</a></li>
        </ul>
    </div>
</div>

<div class="account">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-7 login">
  
            <h1>Wachtwoord veranderen</h1>

            <div class="block">

                <?php echo Form::open(array('url' => '/account/reset-password/'.$confirmationCode.'/'.$email, 'class' => 'form')); ?>

                    <div class="form-control">
             
                        <label for="middle-label">{!! trans('form.password') !!}</label>
                        {!! Form::password('password', array('required' => '')) !!}
                         
                    </div>               

                    <div class="form-control">

                    	<button type="submit" class="button button-black">Change</button>
                 	
                 	</div>           

                </form>

            </div>

        </div>

    </div>
</div>
@stop