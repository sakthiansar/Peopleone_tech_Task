<?php
    session_start();
?>

<!DOCTYPE html>
<html>
<head>  
<link href="js/css/bootstrap.min.css" rel="stylesheet">
<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php

if(!isset($_SESSION['csrf_token']))
{
    $_SESSION['csrf_token']=bin2hex(random_bytes(32));
}
// define variables to empty values  
$nameErr = $passwordErr = "";
if (isset($_POST['login'])) {  
    
    if(!empty($_POST['csrftoken']) && $_POST['csrftoken'] == $_SESSION['csrf_token']){
    
        if (empty($_POST["username_or_password"])) {  
            $nameErr = "Name is required.<br>";  
        } 

        if (empty($_POST["password"])) {  
            $passwordErr = "Password is required.<br>";  
        } 

        require "db_connect/dp.php";

        $username_or_password=$_POST['username_or_password'];
        $password=$_POST['password'];

        $stmt=$conn->prepare("select * from user where username=? or email=?");
        $stmt->bind_param("ss",$username_or_password,$username_or_password);
        $stmt->execute();
        $result=$stmt->get_result();

        if($result->num_rows>0){
            $user=$result->fetch_assoc();

            if(password_verify($password,$user['password'])){
                $_SESSION['user_id']=$user['id'];
                $_SESSION['username']=$user['id'];

                header("Location:logout.php");
            }else{
                echo'Invaild password';
            }
        }else{
            echo"No user found with that name. <button class='btn btn-primary'><a href='register.php' style='color: white;'>Go to Register</a></button>";
        }
    }  
}
?>

<div class="container">
    <div class="card mt-5" style="width: 30rem;">
        <div class="card-body">
            <h5 class="card-title">Login</h5>
            <form  action="" method="POST" autocomplete="off">
                <div class="form-group">
                    <input type="text" class="form-control" name='username_or_password' placeholder="username" autocomplete="off">
                    <span class="error" style='color:red'>* <?php echo $nameErr; ?> </span>
                </div><br/>
                <div class="form-group">
                    <input type="password" class="form-control" name='password' placeholder="password" autocomplete="off">
                    <span class="error" style='color:red'>* <?php echo $passwordErr; ?> </span>
                <div>
                <div class="checkbox">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                </div><br/>
                <input type="hidden" name='csrftoken' value='<?php echo htmlspecialchars($_SESSION['csrf_token']);?>'>
                <button type='submit' name='login'  class="btn btn-success">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>