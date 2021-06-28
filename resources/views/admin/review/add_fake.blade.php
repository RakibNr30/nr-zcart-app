@extends('admin.layouts.master')

@section('content')


@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif





<div class="box">
	  			    <div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-user"></i> Add reviews</h3>
		    </div>
		    <div class="box-body">
	    		<div class="row">
	    		    
	    		    
  	<div class="col-md-3">


  	</div>

  	<div class="col-md-6">
        <form method="POST" action="/admin/reviews/add_fake_reivews" accept-charset="UTF-8" id="form" data-toggle="validator" novalidate="true">
            @csrf
      

		    <div class="form-group">
		      <label for="name">User id</label>
		      <input class="form-control" placeholder="Enter here User id" required="" name="user_id" type="text" value="1">
		      <div class="help-block with-errors"></div>
		    </div>
		    
		    
		    <div class="form-group">
		      <label for="nice_name">Rating</label>
		      <input class="form-control" placeholder="From  0 to 5  (you can put here ' 0 1 2 3 4 5 ')" name="rating" type="text" value="">
		    </div>
		    

		    <div class="form-group">
		      <label for="nice_name">Product id / Shop id</label>
		      <input class="form-control" placeholder="Enter here product id or shop id" name="product_id" type="text" value="">
		    </div>
		    
	
		    <div class="form-group">
		      <label for="nice_name">Time</label>
		      <input class="form-control" placeholder="2021-05-24 16:52:06" name="time_set" type="text" value="">
		    </div>
		    	

			<div class="form-group">
			  <label for="description">Comment</label>
			  <textarea class="form-control summernote-without-toolbar" rows="2" placeholder="Biography" name="comment" cols="50" style="display: block;"></textarea>
			</div>
			
	
		    <div class="form-group">
		      <label for="nice_name">Shop</label>
		       <input type="checkbox" id="vehicle1" name="shop" value="yep">
		    </div>          
          
          
   
          
            <input class="btn btn-flat btn-new" type="submit" value="Insert">
        </form>
        <div class="spacer30"></div>
  	</div>

  	<div class="col-md-3">



	      	</div>
</div>	    	

<span class="spacer20"></span>
    		</div>
	  		</div>
	
	
	
@endsection
