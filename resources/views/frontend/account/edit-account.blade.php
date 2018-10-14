@extends('frontend._layouts.default')

@section('main') 

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <ul class="breadcrumb">
            <li><a href="/">Home</a></li>
            <li><a href="/account">Account</a></li>
            <li><a href="#">Overzicht</a></li>
        </ul>
    </div>
</div>

<div class="account">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-5">
            <div class="account-block">
                <h5>Account</h5>

                {!! Form::model($user, array('method' => 'post', 'url' => array('/account/edit-account'), 'class' => 'form', 'data-abide' => '', 'novalidate' => '')) !!}

                <div class="form-group">
                	<label for="middle-label">{!! trans('form.email') !!}</label>
                	{!! Form::email('email', null, array('required' => '', 'class' => "form-control")) !!}
                </div>

                <div class="form-group">
                	<label for="middle-label">{!! trans('form.password') !!}</label>
                	{!! Form::password('password', array('required' => '', 'class' => "form-control")) !!}
                </div>

                <div class="row">
                    <div class="small-15 columns text-right">
                        <button type="submit" class="btn btn-success">{!! trans('buttons.edit') !!}</button>
                    </div>
                </div>

                </form> 

            </div>

        </div>

        <div class="col-sm-12 col-md-12 col-lg-offset-2 col-lg-5 login">

            <div class="row">
                <div class="small-15 medium-10 large-7 columns">
                    <div class="address-block">
                        <h3>Factuuradres</h3>

                        <ul>
                            <li>{!! $user->clientBillAddress->firstname !!} {!! $user->clientBillAddress['lastname']  !!}</li>


                            <li>{!! $user->clientBillAddress['street']  !!} {!! $user->clientBillAddress['housenumber']  !!} {!! $user->clientBillAddress['housenumber_suffix']  !!}</li>
                            <li>{!! $user->clientBillAddress['zipcode']  !!} {!! $user->clientBillAddress['city']  !!}</li>
                            <li>
                                @if($user->clientBillAddress['country'] == 'nl')
                                Nederland
                                @elseif($user->clientBillAddress['country'] == 'be')
                                Belgie
                                @endif
                            </li>
                        </ul> 
                        <a href="/account/edit-address/bill" class="btn btn-success">Wijzig factuuradres</a>        
         
                    </div>
                </div>

                <div class="small-15 medium-10 large-7 columns">
                    <div class="address-block">
                        <h3>Afleveradres</h3>
          
                        <ul>
                            <li>{!! $user->clientDeliveryAddress->firstname !!} {!! $user->clientDeliveryAddress['lastname']  !!}</li>


                            <li>{!! $user->clientDeliveryAddress['street']  !!} {!! $user->clientDeliveryAddress['housenumber']  !!} {!! $user->clientDeliveryAddress['housenumber_suffix']  !!}</li>
                            <li>{!! $user->clientDeliveryAddress['zipcode']  !!} {!! $user->clientDeliveryAddress['city']  !!}</li>
                            <li>
                                @if($user->clientDeliveryAddress['country'] == 'nl')
                                Nederland
                                @elseif($user->clientDeliveryAddress['country'] == 'be')
                                Belgie
                                @endif
                            </li>
                        </ul> 
                        <a href="/account/edit-address/delivery" class="btn btn-success">Wijzig afleveradres</a>        
            
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@stop