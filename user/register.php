<?php
require_once('../inc/header.php');

if (isset($_POST['user']) && isset($_POST['mail']) && isset($_POST['pass']) && isset($_POST['pass2'])){
	$user = $_POST['user'];
	$pass = $_POST['pass'];
	$pass2 = $_POST['pass2'];
	$mail = $_POST['mail'];

	if(!empty($user) && !empty($mail) && !empty($pass) && !empty($pass2)){
		if($pass === $pass2){
			require_once('../sqldb/connect.php');
			$sql = db_connect('ppl', 'main');
			
			$user = $sql->real_escape_string($user);
			$pass = $sql->real_escape_string($pass);
			$mail = $sql->real_escape_string($mail);
			
			if($result = $sql->query("SELECT uid FROM `user` WHERE user='$user' LIMIT 1")){		// case insensitive compare
				if(!$result->fetch_row()){
					if($sql->query("INSERT INTO `user` (`user`, `pass`, `mail`) VALUES ('$user', MD5('$pass'), '$mail')")){
						echo 'registered! proceeding to sign in.';
						header('refresh:2;/user/login.php');
					}else{
						echo 'Something went wrong! :(d';
					}
				}else{
					echo 'Username already in use! Please try another username.';
				}
			}else{
				echo $sql->error;
			}
		}else{
			$emptyPass = '* password do not match!';
		}
	}else{
		if(empty($user))
			$emptyUser = '<span style="color: red">* field cannot be empty</span>';
		if(empty($pass)) 
			$emptyPass = '* field cannot be empty';
		if(empty($mail)) 
			$emptyMail = '* field cannot be empty';
	}
}

if (!isset($_SESSION['uid'])):?>
	<h3>Sign Up</h3><hr />
	<form action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='POST'>
		<label>Username:</label><input type='text' name='user'><?php if(isset($emptyUser)) echo$emptyUser?><br />
		<label>Email:</label><input type='email' name='mail'><?php if(isset($emptyMail)) echo$emptyMail?><br />
		<label>Password:</label><input type='password' name='pass'><?php if(isset($emptyPass)) echo$emptyPass?><br />
		<label>Confirm Pass:</label><input type='password' name='pass2'><?php if(isset($emptyPass)) echo$emptyPass?><br />
		<input type='submit' value='post' />
		<a href="/user/login.php">Sign In</a>
	</form>
<?php else:
	header('refresh:5;/');?>
	You have already registered!!!<br />
	You will be redirect to the homepage.
<?php endif;

include_once('../inc/footer.php');
?>