<div class="row">        
	<div class="small-15 medium-12 large-6 columns">
		<label for="middle-label">{!! trans('form.firstname') !!}</label>
		{!! Form::text('firstname', null, array('required' => '')) !!}

		<span class="form-error">
			{!! trans('form.validation.required') !!}
		</span>
	</div>

	<div class="small-15 medium-12 large-9 columns">
		<label for="middle-label">{!! trans('form.lastname') !!}</label>
		{!! Form::text('lastname', null, array('required' => '')) !!}

		<span class="form-error">
			{!! trans('form.validation.required') !!}
		</span>
	</div>

</div>



<div class="row">        
	<div class="small-15 medium-12 large-15 columns">
		<label for="middle-label">{!! trans('form.company') !!}</label>
		{!! Form::text('company', null, array()) !!}
		<span class="form-error">
			{!! trans('form.validation.required') !!}
		</span>
	</div>
</div>


<div class="row">        
    <div class="small-15 medium-12 large-5 columns">
        <label for="middle-label">{!! trans('form.zipcode') !!}</label>
        {!! Form::text('zipcode', null, array('class' => 'zipcode form-control checkzipcode', 'data-url' => '/account/check-zipcode', 'required' => '')) !!}
        <span class="form-error">
            {!! trans('form.validation.required') !!}
        </span>
    </div>

    <div class="small-15 medium-12 large-5 columns">
        <label for="middle-label">{!! trans('form.housenumber') !!}</label>
        {!! Form::text('housenumber', null, array('class' => 'housenumber form-control checkhousenumber', 'data-url' => '/account/check-zipcode', 'required' => '')) !!}
        <span class="form-error">
            {!! trans('form.validation.required') !!}
        </span>
    </div>

    <div class="small-15 medium-12 large-5 columns">
        <label for="middle-label">{!! trans('form.houseletter') !!}</label>
        {!! Form::text('housenumber_suffix', null, array()) !!}
        <span class="form-error">
            {!! trans('form.validation.required') !!}
        </span>
    </div>
</div>

<div class="row">        
    <div class="small-15 medium-12 large-15 columns">
        <label for="middle-label">{!! trans('form.street') !!}</label>
        {!! Form::text('street', null, array('class' => 'fillstreet', 'required' => '')) !!}
        <span class="form-error">
            {!! trans('form.validation.required') !!}
        </span>
    </div>
</div>

<div class="row">        
    <div class="small-15 medium-12 large-15 columns">
        <label for="middle-label">{!! trans('form.city') !!}</label>
        {!! Form::text('city', null, array('class' => 'fillcity', 'required' => '')) !!}
        <span class="form-error">
            {!! trans('form.validation.required') !!}
        </span>
    </div>
</div>
 