@extends('admin.layouts.master')

@section('content')


@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif





<div class="box">
<div class="box-header with-border">
<h3 class="box-title"><i class="fa fa-user"></i> Add reviews(CSV)</h3>
</div>
  
  
		    <div class="box-body">
	    		<div class="row">
	    		    
	    		    
  	<div class="col-md-3">


  	</div>

  	<div class="col-md-6">
        <form method="POST" action="/admin/reviews/add_csv_reivews" accept-charset="UTF-8" data-toggle="validator" novalidate="true" enctype="multipart/form-data">
            @csrf
      

                            <div class="form-group">
                              
     
                              
                              
                               <div class="form-group">
		      <label for="nice_name">Upload file (CSV,TXT)</label>
                                    <input id="csv_file" type="file" class="form-control" name="csv_file" required>
                              </div>  
                              
                              
                              		    <div class="form-group">
		      <label for="nice_name">Shop</label>
		       <input type="checkbox" id="vehicle1" name="shop" value="yep">
		    </div>          
          
                              
                              
                            </div>
		    


          
          
      
            <input class="btn btn-flat btn-new" type="submit" value="Insert">
         
          
        </form>
      
      
   
  	</div>

  	<div class="col-md-3">



	      	</div>
</div>	    	

<span class="spacer20"></span>
    		</div>
	  		</div>
	
	
	
@endsection
