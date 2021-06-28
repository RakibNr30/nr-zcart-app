@extends('admin.layouts.master')



@section('content')

<div class="container">


@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button>  
        <strong>{{ $message }}</strong>
</div>
@endif


@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
  <button type="button" class="close" data-dismiss="alert">×</button>  
        <strong>{{ $message }}</strong>
</div>
@endif



  <div class="row">

    <div class="col-md-12">
      <div class="well well-sm">

          <form action="/send_email" method="post" class="form-horizontal">
           @csrf
          <fieldset>
            <legend class="text-center">Send email</legend>
    
            <!-- Name input-->
            <div class="form-group">
              <label class="col-md-3 control-label" for="name">Subject</label>
              <div class="col-md-9">
                <input id="name" name="subject" type="text" placeholder="Your Subject" class="form-control">
              </div>
            </div>
   
            <div class="form-group">
              <label class="col-md-3 control-label" for="email">To</label>
              <div class="col-md-9">
                <input id="email" name="to_email" type="text" placeholder="To " class="form-control">
              </div>
            </div>
     
    
            <!-- Message body -->
            <div class="form-group">
              <label class="col-md-3 control-label" for="message">Your message</label>
              <div class="col-md-9">
                <textarea class="form-control" id="message" name="message" placeholder="Please enter your message here..." rows="5"></textarea>
              </div>
            </div>
    
            <!-- Form actions -->
            <div class="form-group">
              <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary btn-lg">Send email</button>
              </div>
            </div>
          </fieldset>
          
          </form>
          
        </div>
    </div>
  </div>
</div>
@endsection
