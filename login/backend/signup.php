<?php
session_start();
if (isset($_POST['signup'])) {
    
    $Conn = mysqli_connect('localhost','root','','oit_db');
    $uname = mysqli_real_escape_string($Conn, $_POST['uname']);
    $uemail = mysqli_real_escape_string($Conn, $_POST['uemail']);
    $pass = mysqli_real_escape_string($Conn, $_POST['pass']);
    $cpass = mysqli_real_escape_string($Conn, $_POST['cpass']);
    $uid = rand();

    if (empty($uname)) {
        $_SESSION['p_msg'] = "Username is *required!";
        header("Location: ../register.php");
    } else {
        if (empty($uemail)) {
            $_SESSION['p_msg'] = "Your email address is *required!";
            header("Location: ../register.php");    
        } else {
            if (empty($pass)) {
                $_SESSION['p_msg'] = "Your password is *required!";
                header("Location: ../register.php");        
            } else {
                if (empty($cpass)) {
                    $_SESSION['p_msg'] = "Please confirm your password!";
                    header("Location: ../register.php");            
                } else {
                    if ($pass !== $cpass) {
                        $_SESSION['p_msg'] = "Password Mismatch!";
                        header("Location: ../register.php");                
                    } else {
                        if (!filter_var($uemail, FILTER_VALIDATE_EMAIL)) {
                            $_SESSION['p_msg'] = "Invalid Email Address!";
                            header("Location: ../register.php");                    
                        } else {
                            $pass = md5($pass);
                            $insert = mysqli_query($Conn, "INSERT INTO `users`(`user_id`, `uname`, `uemail`, `pass`) VALUES ('$uid','$uname','$uemail','$pass')");
                            if ($insert) {
                                $_SESSION['alert_msg'] = "Registration was successful! Please proceed to login";
                                header("Location: ../index.php");
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
