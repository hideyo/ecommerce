@extends('frontend._layouts.default')

@section('main') 

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/account">Account</a></li>
            <li class="active"><a href="#">forgot password</a></li>
        </ul>
    </div>
</div>


 <div class="account">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-6 login">
  
            <h1>Forgot password</h1>
            <hr/>
            <div class="block">

                @notification()
                <?php echo Form::open(array('route' => 'account.forgot.password', 'class' => 'form', 'data-abide' => '', 'novalidate' => '')); ?>

                    <div class="form-group">
                            <label>{!! trans('form.email') !!}</label>
                            {!! Form::email('email', null, array('class' => 'form-control')) !!}
                    </div>                 
                 
                    <div class="form-group">       
                        <button type="submit" class="btn btn-success form">Request</button>
                    </div>

                </form>
            </div>

        </div>

    </div>
</div>
@stop