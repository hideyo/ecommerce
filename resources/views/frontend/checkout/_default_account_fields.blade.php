 <div class="form-group">        
	<label>{!! trans('form.firstname') !!}</label>
	{!! Form::text('firstname', null, array('required' => '', 'class' => 'form-control')) !!}
</div>
 <div class="form-group">     
    <label>{!! trans('form.lastname') !!}</label>
	{!! Form::text('lastname', null, array('required' => '', 'class' => 'form-control')) !!}
</div>

<div class="form-group">        
    <label>{!! trans('form.zipcode') !!}</label>
    {!! Form::text('zipcode', null, array('class' => 'zipcode form-control checkzipcode', 'data-url' => '/account/check-zipcode', 'required' => '', 'class' => 'form-control')) !!}
</div>

<div class="form-group"> 
    <label>{!! trans('form.housenumber') !!}</label>
    {!! Form::text('housenumber', null, array('class' => 'housenumber form-control checkhousenumber', 'data-url' => '/account/check-zipcode', 'required' => '', 'class' => 'form-control')) !!}
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
    {!! Form::select('country', array('nl' => 'Netherlands', 'be' => 'Belgium'), null, array('required' => '', 'class' => 'form-control')) !!}
</div>   