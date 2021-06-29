<div class="row">
  <div class="col-md-8 nopadding-right">
    <div class="form-group">
      {!! Form::label('country', trans('app.country').'*', ['class' => 'with-help']) !!}
      {!! Form::text('country', null, ['class' => 'form-control', 'placeholder' => trans('app.country'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6 nopadding-right">
    <div class="form-group">
      {!! Form::label('code', trans('app.code').'*', ['class' => 'with-help']) !!}
      {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => trans('app.code'), 'required']) !!}
      <div class="help-block with-errors">{!! trans('help.site_code_exmaple') !!}</div>
    </div>
  </div>
  <div class="col-md-6 nopadding-left">
    <div class="form-group">
      {!! Form::label('site_url', trans('app.site_url').'*', ['class' => 'with-help']) !!}
      {!! Form::text('site_url', null, ['class' => 'form-control', 'placeholder' => trans('app.site_url'), 'required']) !!}
      <div class="help-block with-errors"></div>
    </div>
  </div>
</div>

<p class="help-block">* {{ trans('app.form.required_fields') }}</p>