<?php
require_once '/opt/lampp/htdocs/MySpace/src/init.php';

if (session::exists ( 'home' )) {
	echo '<p>' . session::flash ( 'home' ) . '</p>';
}
$user = new user ();
if ($user->isLoggedIn ()) {
	redirect::to ( 'index.php' );
} else {
	if (input::exists ()) {
		if (token::check ( input::get ( 'token' ) )) {
			$validate = new validation ();
			$validation = $validate->check ( $_POST, array (
					'UserID' => array (
							'required' => true 
					),
					'Password' => array (
							'required' => true 
					) 
			) );
			if ($validation->passed ()) {
				$user = new user ();
				
				$remember = (input::get ( 'remember' ) === 'on') ? true : false;
				//echo hash::make ( input::get ( 'Password' ) );
				$login = $user->login ( input::get ( 'UserID' ), input::get ( 'Password' ) );
				
				if ($login) {
					redirect::to ( 'index.php' );
				} else {
					echo '<p>' . 'Invalid credentials, please try again' . '</p>';
				}
			} else {
				foreach ( $validation->errors () as $error ) {
					echo $error . '<br>';
				}
			}
		}
	}
}
?>

<link href="<?php echo 'login.css';?>" rel='stylesheet' type='text/css'>

<div class="testbox">
	<h1>login</h1>
	<form action="login.php" method="post">
		<div class="field">
			<label id="icon" for="UserID"><i class="icon-user"></i></label> <input
				type="text" name="UserID" id="UserID" placeholder="UserID"
				autocomplete="off" required /> <label id="icon" for="password"><i
				class="icon-shield"></i></label> <input type="Password"
				name="Password" id="Password" placeholder="Password"
				autocomplete="off" required />
		</div>
		<div class="field">
			<label for="remember"></lable> <input type="checkbox" name="remember"
				id="remember">Remember me</input> </label>   <a href="register.php">  New
				User </a>
		</div>
		<div class="field"></div>
		` <input type="hidden" name="token"
			value="<?php echo token::generate();?>"> <input type="submit"
			value="Login">
	</form>
</div>