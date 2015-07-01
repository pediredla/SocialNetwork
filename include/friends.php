<?php
require_once '/opt/lampp/htdocs/MySpace/src/init.php';
if (session::exists ( 'friends' )) {
	echo '<p>' . session::flash ( 'friends' ) . '</p>';
}

$user = new user ();
if ($user->isLoggedIn ()) {
	if ($user->ShowFriends ($user->data()->UserID)) {
		foreach ($user->friends() as $friend){
			//echo 'hi';
			//echo $friend->FriendsWith;
			?>
			<a 
			href="profile.php?User=<?php echo escape($friend->FriendsWith)?>"><?php echo escape($friend->FriendsWith)?> </a><br>
		<?php 
		}
	}
}

?>