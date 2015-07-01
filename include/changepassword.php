<?php
require_once '/opt/lampp/htdocs/MySpace/src/init.php';

$user=new user();

if(!$user->isLoggedIn()){
	redirect::to('index.php');
}
if(input::exists()){
	if(token::check(input::get('token'))){

		$validate=new validation();
		$validation=$validate->check($_POST,array(
				'Password'=> array(
						'required'=>true,
						'min'=>8
				),
				'Npassword'=>array(
						'required'=>true,
						'min'=>8
				),
				'Rpassword'=>array(
						'required'=>true,
						'min'=>8,
						'matches'=>'Npassword'
				)
		));
		if($validation->passed()){
			if(hash::make(input::get('Password'))!==$user->data()->Password){
				echo 'your old password did not match';
			} else{
				if($user->update(array(
						'Password'=>hash::make(input::get('Npassword'))
				))){
					session::flash('home','Your password have been updated!!');
					redirect::to('index.php');
				}
			}
		}
	}
}
?>
<link href="<?php echo 'register.css';?>" rel='stylesheet' type='text/css'>
<form action="" method="post">
  <div class="field">
  <label id="icon" for="Password"><i class="icon-shield"></i></label>
 		<input type="Password" name="Password" id="Password" placeholder="CurrentPassword" required/>
  		<label id="icon" for="Password"><i class="icon-shield"></i></label>
  		
 		<input type="Password" name="Npassword" id="Password" placeholder="Password" required/>
 		
 		<label id="icon" for="Password"><i class="icon-shield"></i></label>
 		<input type="Password" name="Rpassword" id="Password" placeholder="Password-again" required/>	
  </div>
  	<input type="hidden" name="token" value="<?php echo token::generate();?>">
   	<input type="submit" value="Change">	
</form>
  