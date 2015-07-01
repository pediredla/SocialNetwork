<?php
require_once '/opt/lampp/htdocs/MySpace/src/init.php';
if (session::exists ( 'profile' )) {
	echo '<p>' . session::flash ( 'profile' ) . '</p>';
}
$user = new user ();
if ($user->isLoggedIn ()) {
	
	// $Lname=input::get('LastName');
	// echo $Fname." ".$Lname ;
	/*
	 * if ($user->hasPermission ( 'admin' )) {
	 * echo 'You are an admin';
	 * }
	 */
	// $currentID=$_SESSION['UserID'];
	// echo $currentID;
	$UserID2 = input::get ( 'User' );
	// $user= new user($UserID);
	
	if (! $UserID2 == input::get ( 'User' )) {
		redirect::to ( 'index.php' );
	} else {
		$user2 = new user ( $UserID2 );
		if (! $user2->exists ()) {
			redirect::to ( '404' );
		} else {
			$data = $user2->data ();
			?>
<link href="<?php echo 'style.css';?>" rel='stylesheet' type='text/css'>

<h3><?php echo escape($data->UserID);?></h3>
<p>FirstName: <?php echo escape($data->FirstName);?></p>
<p>LastName: <?php echo escape($data->LastName);?></p>
<?php
			echo 'Mutual Friends:' . '<br>';
			if ($user->ShowMutualFriends ( $user->data ()->UserID, $data->UserID )) {
				foreach ( $user->mutual () as $mutual ) {
					?>
<a href="profile.php?User=<?php echo $mutual->FriendsWith;?>"><?php echo $mutual->FriendsWith;?></a>
<?php
					// make changes to navigate and show in block
				}
			}
			
			if (($user2->isFriend ( $user->data ()->UserID, $data->UserID ))) {
				
				?>
<form method="post">
	<div>
		<textarea rows="5" cols="50" name="text"></textarea>
		<input type="submit" name="post" value="Post" />
	</div>
</form>
<?php
				$post = $user->WritePost ( $user->data ()->UserID, $data->UserID, input::get ( 'text' ) );
				if ($post) {
					session::flash ( 'profile', 'Has been posted!' );
					redirect::to ( 'profile.php' );
				}
				
				if ($user2->ShowPosts ( $user2->data ()->UserID )) {
					foreach ( $user2->posts () as $post ) {
						// echo '<p>' . $post->Text . '<br>' . 'posted by ' . '<a href="profile.php?User=' . $post->PostedBy . '"/>' . $post->PostedBy . '</a></p>';
						?>
<p class="post"><?php echo escape($post->Text);?></p>
posted by
<a href="profile.php?User=<?php echo escape($post->PostedBy);?>"><?php echo escape($post->PostedBy)?></a>

<?php
						
						if ($user2->ShowComments ( $post->PostID )) {
							foreach ( $user2->comments () as $comment ) {
								// echo '<br>' . $comment->Text . '<br>' . 'commented by ' . '<a href="profile.php?User=' . $comment->CommentedBy . '"/>' . $comment->CommentedBy . '</a>';
								?>
<p class="comment"><?php echo escape($comment[1]->Text)?></p>
commented by
<a
	href="profile.php?User=<?php echo escape($comment [1]->CommentedBy);?>"><?php echo escape($comment[1]->CommentedBy);?></a>

<?php
							}
						}
					}
				}
			} else {
				?>
<form method="post">
	<div>
		<input type="submit" name="addFriend" value="Add Friend"
			onclick="<?php ?>" />
	</div>
</form>
<?php
				if (input::exists ()) {
					$sendRequest = $user2->AddFriend ( $user->data ()->UserID, $data->UserID );
					if ($sendRequest) {
						session::flash ( 'profile', 'Friend Request has been sent!' );
						// redirect::to ( 'profile.php' );
					}
				}
				?>
<?php
			}
		}
	}
}
?>
