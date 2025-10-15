<?php
    session_start();
    if (isset($_POST['log'])) {
        
	$conn = mysqli_connect('localhost','root','','oit_db');
        $uname = mysqli_real_escape_string($conn, $_POST['uname']);
        $pass = mysqli_real_escape_string($conn, $_POST['pass']);
        $pass = md5($pass);

        $select = mysqli_query($conn, "SELECT * FROM `users` WHERE `uname`='$uname' AND `pass`='$pass'");
        if (mysqli_num_rows($select) > 0) {
            $_SESSION['alert_msg'] = "Login was successful!";
            $fetch = mysqli_fetch_array($select);
            $_SESSION['u_log'] = $fetch['user_id'];
            header("Location: ../../index.php");
        }else{
            $_SESSION['p_msg'] = "User records not found!";
            header("Location: ../index.php");
        }
    }
?>