@extends('_layouts.default')
@section('main')

<ol class="breadcrumb">
  <li><a href="{{ URL::route('index') }}"><i class="entypo-folder"></i>Dashboard</a>  </li>
  <li><a href="{{ URL::route('user.index') }}">Users</a></li>
  <li><a href="{{ URL::route('user.show', $user->id) }}">{{ $user->username }}</a></li>  
  <li class="active">dashboard</li>
</ol>

<h2>User <small>dashboard</small></h2>
<hr/>
@include('_partials.notifications')
        @include('flash::message')
@if (Session::get('error'))
    <div class="alert alert-error alert-danger">
        @if (is_array(Session::get('error')))
            {{ head(Session::get('error')) }}
        @endif
    </div>
@endif

<div class="row">
	<div class="col-md-12">
        <ul class="nav nav-tabs bordered" style="width:100%;"><!-- available classes "right-aligned" -->
            <li class="active">
                <a href="{{ URL::route('user.show', $user->id) }}">
                    <span class="visible-xs"><i class="entypo-gauge"></i></span>
                    <span class="hidden-xs">Dashboard</span>
                </a></li>
            <li>
                <a href="{{ URL::route('user.edit', $user->id) }}">
                    <span class="visible-xs"><i class="entypo-user"></i></span>
                    <span class="hidden-xs">Account</span>
                </a>
            </li>
            <li>
                <a href="{{ URL::route('user.{user_id}.user_profile_data.index', $user->id) }}">
                     <span class="visible-xs"><i class="entypo-home"></i></span>
                    <span class="hidden-xs">Address information</span>               
                </a>
            </li>
            <li>
                <a href="{{ URL::route('user.numbers', $user->id) }}">
                     <span class="visible-xs"><i class="entypo-phone"></i></span>
                    <span class="hidden-xs">Numbers</span>               
                </a>
            </li>

            <li>
                <a href="{{ URL::route('user.edit', $user->id) }}">
                     <span class="visible-xs"><i class="entypo-phone"></i></span>
                    <span class="hidden-xs">Products</span>               
                </a>
            </li>
        </ul>

		<div class="panel panel-primary tab-content">

   			<div class="panel-body">	
					
				<div class="col-md-4">


                    <div class="col-md-12">

                        <div class="tile-title tile-gray">
                            <a href="{{ URL::route('user.edit', $user->id) }}" >
                                <div class="icon">
                                    <img src="<?= $user->avatar->url() ?>" class="img-responsive ">
                                </div>
                                
                                <div class="title left">
                                    <h3>{{ $user->username }}</h3>
                                    <p>{{ $user->email }}</p>
                                </div>
                            </a>
                        
                        </div>

                    </div>





			
					
				</div>
                
                <div class="col-md-8">
                  
                    @foreach ($user_profile_data as $data)

                    <div class="col-md-6">

                        <a href="{{ URL::route('user.{user_id}.user_profile_data.edit', array('user_id' => $data->user_id, 'id' => $data->id)) }}">
                        <div class="tile-block tile-aqua">
                
                            <div class="tile-header">
                      
                                    {{ $data->type }}
                              
                            </div>
                
                            <div class="tile-content">
                                {{ $data->firstname }} {{ $data->lastname }}<br/>{{ $data->email }}<br/>{{ $data->phone }}<br/>
                                <p>{{ $data->street }} {{ $data->housenumber }}<br/>
                                {{ $data->zipcode }} {{ $data->city }}<br/>
                                {{ $data->country }}</p> 
                            </div>
                        </div>
                        </a>
                    </div>

                    @endforeach







                <div>


            </div>
    
        
        
        </div>

	</div>
</div>

@stop