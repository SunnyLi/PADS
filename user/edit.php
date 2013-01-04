<?php
    require_once('../inc/header.php');
	require_once('../sqldb/connect.php');
    
    $sql = db_connect('data', 'main');
	$sql->set_charset("utf8");
    $user = $sql->query("SELECT * FROM `user` WHERE `uid`='$uid' LIMIT 1");
    $user = $user->fetch_assoc();
    
    if (isset($_POST['name']) && isset($_POST['mail']) && isset($_POST['pass']) && isset($_POST['pass2'])){
        $name = $_POST['name'];
        $pass = $_POST['pass'];
        $pass2 = $_POST['pass2'];
        $mail = $_POST['mail'];

        if(!empty($name) && !empty($mail) && !empty($pass) && !empty($pass2)){
            if($pass === $pass2){
                $name = $sql->real_escape_string($name);
                $pass = $sql->real_escape_string($pass);
                $mail = $sql->real_escape_string($mail);
                
                if($sql->query("UPDATE `user` SET name = '$name', pass = MD5('$pass'), mail = '$mail' WHERE uid = $uid")){
                    echo 'info updated, please login again.';
                    session_destroy();
                    header('refresh:2;/user/login.php');
                }else{
                    echo 'Something went wrong! :(';
                    echo $sql->error;
                }
            }else{
                $emptyPass = '* password do not match!';
            }
        }else{
            if(empty($name)) 
                $emptyName = '* field cannot be empty';
            if(empty($pass)) 
                $emptyPass = '* field cannot be empty';
            if(empty($mail)) 
                $emptyMail = '* field cannot be empty';
        }
    }
if (isset($_SESSION['uid'])):?>
	<h3>Change Info</h3><hr />
	<form action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='POST'>
        <label>Username: </label><span><?= $user['user'] ?></span><br />
		<label>Name:</label><input type='text' name='name' value='<? echo isset($user['name']) ? $user['name'] : $user['user'] ?>'><?php if(isset($emptyName)) echo$emptyName?><br />
		<label>Email:</label><input type='email' name='mail' value='<?= $user['mail'] ?>'><?php if(isset($emptyMail)) echo$emptyMail?><br />
		<label>Password:</label><input type='password' name='pass'><?php if(isset($emptyPass)) echo$emptyPass?><br />
		<label>Confirm Pass:</label><input type='password' name='pass2'><?php if(isset($emptyPass)) echo$emptyPass?><br />
		<input type='submit' value='update' />
	</form>
<?php endif;
include_once('../inc/footer.php');
?>