<?php require_once '/opt/lampp/htdocs/MySpace/src/init.php';?>

<link href="<?php echo 'login.css';?>" rel='stylesheet' type='text/css'>

<div class="testbox">
	<h1>Reset Password</h1>
	<form action="" method="post">
		<div class="field">
			<label id="icon" for="UserID"><i class="icon-user"></i></label> <input
				type="text" name="UserID" id="UserID" placeholder="UserID"
				autocomplete="off" required /> <label id="icon" for="password"><i
				class="icon-shield"></i></label> <input type="text" name="emailAddr"
				id="Password" placeholder="Email Address" autocomplete="off"
				required />
			<div class="field"></div>
			` <input type="hidden" name="token"
				value="<?php echo token::generate();?>"> <input type="submit"
				value="Login">
	
	</form>
</div>

<?php

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
					'emailAddr' => array (
							'required' => true 
					) 
			) );
			if ($validation->passed ()) {
				$user = new user ();
				$id = $user->find ( input::get ( 'UserID' ) );
				$newobj = new user ( $id );
				if (($newobj->find ( input::get ( 'UserID' ) )) && ($newobj->data ()->EmailAddress)) {
					
					?>
<form action="" method="post">
	<div class="field">
		<label id="icon" for="Password"><i class="icon-user"></i></label> <input
			type="password" name="NPassword" id="UserID"
			placeholder="New Password" autocomplete="off" required /> <label
			id="icon" for="password"><i class="icon-shield"></i></label> <input
			type="password" name="RPassword" id="Password" placeholder="Re-enter"
			autocomplete="off" required />
<?php
					$validate = new validation ();
					$validation = $validate->check ( $_POST, array (
							'Npassword' => array (
									'required' => true,
									'min' => 8 
							),
							'Rpassword' => array (
									'required' => true,
									'min' => 8,
									'matches' => 'Npassword' 
							) 
					) );
					if ($validation->passed ()) {
						if (hash::make ( input::get ( 'NPassword' ) ) !== $user->data ()->Password) {
							echo 'your passwords does not match';
						} else {
							$user->update ( array (
									'Password' => hash::make ( input::get ( 'Npassword' ) ) 
							) );
						}
					}
				} else {
					echo 'Invalid User ID and email address!!';
				}
			}
		}
	}
}
				
?>