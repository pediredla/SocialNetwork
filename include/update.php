<?php
require_once '/opt/lampp/htdocs/MySpace/src/init.php';

$user=new user();
//echo $user->data()->node_id;
if(!$user->isLoggedIn()){
	redirect::to('index.php');
}

if(input::exists()){
	if(token::check(input::get('token'))){
		//echo $user->data()->node_id;
		$validate=new validation();
		$validation=$validate->check($_POST,array(
				'FirstName'=>array(
						'required'=>true,
						'min'=>2,
						'max'=>20
				),
				'LastName'=>array(
						'required'=>true,
						'min'=>2,
						'max'=>20
				),
				'CurrentTown'=>array(
						'required'=>true,
						'min'=>2,
						'max'=>20
				),
				'Hometown'=>array(
						'required'=>true,
						'min'=>2,
						'max'=>20
				)
		));
		if($validation->passed()){
			try{
				$user->update(array(
						'FirstName'=>input::get('FirstName'),
						'LastName'=>input::get('LastName'),
						'CurrentTown'=>input::get('CurrentTown'),
						'Hometown'=>input::get('Hometown')
				));
				session::flash('home','Your details have been updated!!');
				redirect::to('index.php');
			}catch (Exception $e){
				die($e->getMessage());
			}
		} else {
			foreach ($validation->errors() as $error){
				echo $error,'<br>';
			}
		}
	}
	
}

?>
<!DOCTYPE Funspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<link href="<?php echo 'register.css';?>" rel='stylesheet' type='text/css'>
<form action="" method="post">
	<div class="field">
		<label for="FirstName">First Name</label>
		<input type="text" name="FirstName" value="<?php echo escape($user->data()->FirstName);?>">
		 
		<label for="LastName">Last Name</label>
		<input type="text" name="LastName" value="<?php echo escape($user->data()->LastName);?>">
		
		<label for="Hometown">Home Town</label>
		<input type="text" name="Hometown" value="<?php echo escape($user->data()->Hometown);?>">
		 
		<label for="CurrentTown">Current Town</label>
		<input type="text" name="CurrentTown" value="<?php echo escape($user->data()->CurrentTown);?>">
		
		<input type="submit" value="Update">
		<input type="hidden" name="token" value="<?php echo token::generate();?>">	
	</div>


</form>