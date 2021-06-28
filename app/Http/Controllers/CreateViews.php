<?php

namespace App\Http\Controllers;

use Auth;
use View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


// use App\Http\Requests\Validations\CreateOrderRequest;
// use App\Http\Requests\Validations\FulfillOrderRequest;
// use App\Http\Requests\Validations\CustomerSearchRequest;
// use App\Services\PdfInvoice;
// use Konekt\PdfInvoice\InvoicePrinter;

class CreateViews extends Controller
{

  



    public function add_csv()
    {
         
       if (Auth::user()->isAdmin()) { 
       
            return view('admin.review.add_csv');
            
            
     } else {
     
         return redirect('/')->with('message', 'buhaha!');
    
     }
        
        
    }       
      

public function parse_csv ($csv_string, $delimiter = ",", $skip_empty_lines = true, $trim_fields = true)
{
    $enc = preg_replace('/(?<!")""/', '!!Q!!', $csv_string);
    $enc = preg_replace_callback(
        '/"(.*?)"/s',
        function ($field) {
            return urlencode(utf8_encode($field[1]));
        },
        $enc
    );
    $lines = preg_split($skip_empty_lines ? ($trim_fields ? '/( *\R)+/s' : '/\R+/s') : '/\R/s', $enc);
    return array_map(
        function ($line) use ($delimiter, $trim_fields) {
            $fields = $trim_fields ? array_map('trim', explode($delimiter, $line)) : explode($delimiter, $line);
            return array_map(
                function ($field) {
                    return str_replace('!!Q!!', '"', utf8_decode(urldecode($field)));
                },
                $fields
            );
        },
        $lines
    );
}



public function parse_ecsv($file, $options = null) {
    $delimiter = empty($options['delimiter']) ? "," : $options['delimiter'];
    $to_object = empty($options['to_object']) ? false : true;
  
    $str = $file;
  
    $lines = explode("\n", $str);
   // pr($lines);
    $field_names = explode($delimiter, array_shift($lines));
    foreach ($lines as $line) {
        // Skip the empty line
        if (empty($line)) continue;
        $fields = explode($delimiter, $line);
        $_res = $to_object ? new stdClass : array();
        foreach ($field_names as $key => $f) {
            if ($to_object) {
                $_res->{$f} = $fields[$key];
            } else {
                $_res[$f] = $fields[$key];
            }
        }
        $res[] = $_res;
    }
    return $res;
}


  public function add_csv_reivews(Request $request) {



 if(Auth::user()->isAdmin()) { 




$texqw = $request->file('csv_file')->get();

//$fullcsv = $this->parse_csv($texqw, ";");  
//$fullcsv = array_map('str_getcsv', str_getcsv($texqw,"\n"));
  
  
$fullcsv = self::parse_ecsv($texqw, array("delimiter" => ";"));    

$type_of_s = $request->input('shop');    
  
  




  
for ($i=0; $i < count($fullcsv); $i++) {




        $us_id = trim($fullcsv[$i]['user']);
        $rating = trim($fullcsv[$i]['star']);
		$prod = trim($fullcsv[$i]['product']);  
		$tmqwew = trim($fullcsv[$i]['time']); 
        $comm = trim($fullcsv[$i]['comment']);


              
      
        $prldqw2 = str_replace(array("<p>","</p>"), array("",""), $comm);
        
        
        if ($type_of_s == 'yep')$type = "App\Shop";
        if ($type_of_s != 'yep')$type = "App\Inventory";
               
        
        $fed_id = 1;
        $appr = 1;
        $spam = 0;
 
 
 


$prqd21sdq = array("0", "1", "2", "3", "4", "5");


if (!in_array($rating, $prqd21sdq)) {

	return back()->with('message', ' Star must be from 0 to 5');
	
} else if (!is_numeric($us_id)) {

	return back()->with('message', ' user id must be a number');

}  else if (strlen($comm)<5) {


	return back()->with('message', ' comment cant be lesser than 5 symbol ');

}  else if (!is_numeric($prod)) {

	return back()->with('message', ' product/shop id must be a number');

}   else if (strlen($tmqwew)<19) {


	return back()->with('message', ' you should pick up time correctly ');

} else {








$countp = \DB::table('shops')->where('id', $prod)->count();       
  
  
$countu = \DB::table('customers')->where('id', $us_id)->count();       
    
       
if ($countp == 0) { 

return back()->with('message', ' product/shop id doesn\'t exist in our database ! ');

} else if ($countu == 0) { 

return back()->with('message', ' user id doesn\'t exist in our database ! ');

} else {   



if ($type_of_s == 'yep') {

\DB::insert('insert into `feedbacks` (customer_id, rating, comment, feedbackable_id, feedbackable_type, approved, spam, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?)',  [$us_id, $rating, $prldqw2, $prod, $type, $appr, $spam, $tmqwew, $tmqwew]);



} else {

$arwd12 = \DB::table('inventories')->where('product_id', $prod)->first();
 
\DB::insert('insert into `feedbacks` (customer_id, rating, comment, feedbackable_id, feedbackable_type, approved, spam, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?)',  [$us_id, $rating, $prldqw2, $arwd12->id, $type, $appr, $spam, $tmqwew, $tmqwew]); 




}






}



}  
 
 
 


}



return back()->with('message', 'workss!');

      


  
  
     

} else {
           
          return redirect('/')->with('message', 'buhaha!');
 }


} 


  
  ////////////////////////////
  
   
    public function addfake()
    {
        if(Auth::user()->isAdmin()) { 
            return view('admin.review.add_fake');
        } else {
           return redirect('/')->with('message', 'buhaha!');
        }
    }       
       
    
 public function add_fake_reivews(Request $request) {



 if(Auth::user()->isAdmin()) { 

        $rating = $request->input('rating');
        $us_id = $request->input('user_id');
        $comm = $request->input('comment');
        $prod = $request->input('product_id');        
        $tmqwew = $request->input('time_set');       
        $prldqw2 = str_replace(array("<p>","</p>"), array("",""), $comm);
 
 
 
        $type_of_s = $request->input('shop');         
        
        
        $id_of_product = 2; // product id
        
        $fed_id = 1;
        
     
        
        if ($type_of_s == 'yep')$type = "App\Shop";
        if ($type_of_s != 'yep')$type = "App\Inventory";
        
        
        
        
        $appr = 1;
        $spam = 0;
        $timer = date("Y-m-d G:i:s");
        $upd =  date("Y-m-d G:i:s");




$prqd21sdq = array("0", "1", "2", "3", "4", "5");


if (!in_array($rating, $prqd21sdq)) {

	return back()->with('message', ' Star must be from 0 to 5');
	
} else if (!is_numeric($us_id)) {

	return back()->with('message', ' user id must be a number');

}  else if (strlen($comm)<5) {


	return back()->with('message', ' comment cant be lesser than 5 symbol ');

}  else if (!is_numeric($prod)) {

	return back()->with('message', ' product/shop id must be a number');

}   else if (strlen($tmqwew)<19) {


	return back()->with('message', ' you should pick up time correctly ');

} else {      
       
       
       
$countp = \DB::table('inventories')->where('product_id', $prod)->count();       
  
  
$countu = \DB::table('customers')->where('id', $us_id)->count();       
    
 
 /*      
if ($countp == 0) { 

return back()->with('message', ' product id doesn\'t exist in our database ! ');

} else if ($countu == 0) { 

return back()->with('message', ' user id doesn\'t exist in our database ! ');

} else {       
   
   */  
     
if ($type_of_s == 'yep') {


\DB::insert('insert into `feedbacks` (customer_id, rating, comment, feedbackable_id, feedbackable_type, approved, spam, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?)',  [$us_id, $rating, $prldqw2, $prod, $type, $appr, $spam, $tmqwew, $tmqwew]);


} else {        
        
$arwd12 = \DB::table('inventories')->where('product_id', $prod)->first();
       
   
 //$results = DB::select('select * from users where id = ?', [1]);  
   
   
\DB::insert('insert into `feedbacks` (customer_id, rating, comment, feedbackable_id, feedbackable_type, approved, spam, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?)',  [$us_id, $rating, $prldqw2, $arwd12->id, $type, $appr, $spam, $tmqwew, $tmqwew]);
    
    
}    
    
    
    
        
        

  
return back()->with('message', ' WORKS! ');

  //}
  
  
       
}

} else {
           
           return redirect('/')->with('message', 'buhaha!');
 }


} 
  



}

?>
