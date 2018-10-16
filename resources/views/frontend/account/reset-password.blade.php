@extends('frontend._layouts.default')

@section('main') 

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/account">Account</a></li>
            <li class="active"><a href="#">Reset password</a></li>
        </ul>
    </div>
</div>

<div class="account">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-7 login">
  
            <h1>Reset password</h1>
            <hr/>
            <div class="block">

                <?php echo Form::open(array('url' => '/account/reset-password/'.$confirmationCode.'/'.$email, 'class' => 'form')); ?>

                    <div class="form-group">
                        <label for="middle-label">{!! trans('form.password') !!}</label>
                        {!! Form::password('password', array('required' => '', 'class' => "form-control")) !!}
                    </div>             

                    <div class="form-group">       
                        <button type="submit" class="btn btn-success form">Reset</button>
                    </div>

                </form>

            </div>

        </div>

    </div>
</div>
@stop