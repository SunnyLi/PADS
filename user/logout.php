<?php
require_once('../inc/header.php');

if (isset($_SESSION['uid'])):
	if(isset($_GET['s'])){
		session_destroy();
		echo 'You have signed out.';
		header('refresh:1;/user/login.php');
	}else{
		echo 'you might be a victim of XSS attack!';
	}
else:?>
	You are not signed in!<br />
	<a href="/user/login.php">Sign In</a>
<?php endif;

include_once('../inc/footer.php');
?>