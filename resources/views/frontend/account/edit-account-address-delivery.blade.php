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
                <h3>Account</h3>
                @notification('foundation')
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Email:</td>
                            <td>{!! $user->email !!}</td>
                        </tr>
                        <tr>
                            <td>Wachtwoord:</td>
                            <td>*****</td>
                        </tr>
                    </tbody>
                </table>   

                <a href="/account/edit-account" class="btn float-right btn-info">Wijzig gegevens</a>        
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
                                @if($user->clientBillAddress->countryObject)
                                @if($user->clientBillAddress->countryObject->iso_3166_2 == 'NL')
                                Nederland
                                @elseif($user->clientBillAddress->countryObject->iso_3166_2 == 'BE')
                                Belgie
                                @else
                                {!! $user->clientBillAddress->countryObject->name !!}
                                @endif
                                @endif
                            </li>
                            <li>{!! $user->clientBillAddress['phone']  !!}</li>
                        </ul> 
                        <a href="/account/edit-address/bill" class="button button-simple">Wijzig factuuradres</a>        
         
                    </div>
                </div>

                <div class="small-15 medium-10 large-7 columns">
                    <div class="address-block">
                        <h3>Afleveradres</h3>
          
                        @notification()
                        {!! Form::model($user->clientDeliveryAddress, array('method' => 'post', 'url' => array('/account/edit-address/delivery'), 'files' => true, 'class' => 'box login')) !!}

                        @include('frontend.account._default_account_fields')       

                        <a href="/account" class="button button-grey">Annuleer</a>
                        <button type="submit" class="button btn-default">Wijzig</button>

                        {!! Form::close() !!}             
            
                    </div>
                </div>
            </div>

            


        </div>



    </div>



    

</div>
@stop