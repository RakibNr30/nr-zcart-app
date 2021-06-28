<?php

namespace App\Http\Controllers;

use Mail;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MailController extends Controller {


public function show() {
   
  return view('admin.mail.mail_sender'); 
   
}


  

public function sendEmail(Request $request) {



$subject = $request->input('subject');   
$email = $request->input('to_email');   
$message = $request->input('message');   



if (!filter_var($email, FILTER_VALIDATE_EMAIL))  {

return back()->with('error', ' email was entered incorrectly ');
 
 
} else if (strlen($subject)<3) {


	return back()->with('error', ' subject lenght must be more than 3 symbol ');

} else if (strlen($message)<3) {


	return back()->with('error', ' message lenght must be more than 3 symbol ');

} else {



$data = array('text' => $message);
   


Mail::send('mail', $data, function($mess) use ($email,$subject) {
      
	$mess->to($email, '')->subject($subject);
	$mess->from(env('MAIL_FROM_ADDRESS'), 'From '.$_SERVER['HTTP_HOST']);
         
});
 
 
	return back()->with('message', 'workss!');


}

  
}


public function sender($email, $subject, $message, array $data){






}



}
