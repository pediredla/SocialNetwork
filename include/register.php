<?php

require_once '/opt/lampp/htdocs/MySpace/src/init.php';

//below is for get input from user.
/*
if(input::exists()){
	echo input::get('FName');
}*/

//var_dump(token::check(input::get('token')));


if(input::exists()){
	if(token::check(input::get('token'))){
	//	echo 'token is working!!';
			$validate=new validation();
			$validation=$validate->check($_POST,array(
					'emailAddr'=>array(
							'required'=>true,
							'min'=>2,
							'max'=>20,
							//'unique'=>'EmailAddress'
					),
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
					'DOB'=>array(
							'required'=>true
					),
					'UserID'=>array(
							'required'=>true,
							'min'=>2,
							'max'=>20,
							'unique'=>'UserID'
					),
					'Password'=>array(
							'required'=>true,
							'min'=>8,
							'max'=>20
					),
					'Gender'=>array(
							'required'=>true
					)
			));
			
			if($validation->passed()){
				$user=new user();
				//$gender = form_input($_POST["gender"]);
				//$salt=hash::salt(32);
				try{
					$user->create(array(
							'EmailAddress'=>input::get('emailAddr'),
							'FirstName'=>input::get('FirstName'),
							'LastName'=>input::get('LastName'),
							'DOB'=>input::get('DOB'),
							'UserID'=>input::get('UserID'),
							'Password'=>hash::make(trim(input::get('Password'))),
							'Gender'=>input::get('Gender'),
							'Group'=>1
					));
				
					//header('Location: index.php');
					//redirect::to('login.php');
					//echo $user->data()->Gender;
				} catch(Exception $e){
					echo ($e->getMessage());
				}
				
				session::flash('home','You have been registered and can login');
				redirect::to('login.php');
				//echo 'passed!';
				//session::flash('Sucess!!', 'Your are registered!');
			}else {
				foreach ($validation->errors() as $error)
					echo $error.'<br>';
			}
		}
	}
	
?>

<link href="<?php echo 'register.css';?>" rel='stylesheet'
	type='text/css'>

<div class="testbox">
	<h1>Registration</h1>

	<form action="" method="post">
		<div class="field">
			<label id="icon" for="emailAddr"><i class="icon-envelope "></i></label>
			<input type="text" name="emailAddr" id="emailAddr"
				placeholder="Email"
				value="<?php echo escape(input::get('EmailAddress')); ?>" required />
			<label id="icon" for="FirstName"><i class="icon-user"></i></label> <input
				type="text" name="FirstName" id="FirstName" placeholder="First Name"
				value="<?php echo escape(input::get('EmailAddress'));?>" required />
			<label id="icon" for="Lastname"><i class="icon-user"></i></label> <input
				type="text" name="LastName" id="LastName" placeholder="Last Name"
				required /> <label id="icon" for="DOB"><i class="icon-user"></i></label>
			<input type="text" name="DOB" id="DOB" placeholder="Date of Birth"
				required /> <label id="icon" for="UserID"><i class="icon-user"></i></label>
			<input type="text" name="UserID" id="UserID" placeholder="UserID"
				value="<?php echo escape(input::get('UserID')); ?>" required /> <label
				id="icon" for="Password"><i class="icon-shield"></i></label> <input
				type="Password" name="Password" id="Password" placeholder="Password"
				required /> <input type="radio" id="male" name="Gender"
				value="Male" checked="true" /> <label for="male" class="radio">Male</label>
			<input type="radio"  id="female" name="Gender"
				value="Female" /> <label for="female" class="radio">Female</label> <input
				type="hidden" name="token" value="<?php echo token::generate();?>">
			<input type="submit" value="Register" name="Register">
	
	</form>
</div>

