<!DOCTYPE html>  
<html>  
<head> 
<link href="js/css/bootstrap.min.css" rel="stylesheet">
<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
</head>  
<body>    
  
<?php  
session_start();

if(!isset($_SESSION['csrf_token']))
{
    $_SESSION['csrf_token']=bin2hex(random_bytes(32));
}

$nameErr = $emailErr = $passwordErr = "";
  
//Input fields validation
if (isset($_POST['submit'])) {  
    
    if(!empty($_POST['csrftoken']) && $_POST['csrftoken'] == $_SESSION['csrf_token']){
        //String Validation  
        if (empty($_POST["username"])) {  
            $nameErr = "Name is required.<br>";  
        } else { 
            if (!preg_match("/^[a-zA-Z ]*$/",$_POST["username"])) {  
                $nameErr = "Only alphabets and white space are allowed.<br>";  
            }  
        }  
        
        //Email Validation   
        if (empty($_POST["email"])) {  
                $emailErr = "Email is required.<br>";  
        } else {  
                $email = $_POST ["email"];  
                $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";  
                if (!preg_match ($pattern, $email) ){  
                    $emailErr = "Email is not valid.<br>"; 
                } 
        }  


        //Number Validation  
        if (empty($_POST["password"])) {  
            $passwordErr = "Password is required.<br>";  
        } else {  
            if (!preg_match ("/^[a-zA-Z0-9]*$/", $_POST["password"]) ) {  
                $passwordErr = "alphanumeric value is allowed.<br>";  
            }  
        } 

        //database connection:
        require 'db_connect/dp.php';

        //Sanitize and validate input:
        $username=$_POST['username'];
        $email=$_POST['email'];
        $password=password_hash($_POST['password'],PASSWORD_BCRYPT);

        $sql = "SELECT username FROM user where username='$username'";
        $result = mysqli_query($conn,$sql);
        $check_unique_name = mysqli_fetch_object($result);

        if(!empty($username) && !empty($email) && !empty($password) || $check_unique_name->username != $username)
        {
            $insert_sql = "insert into user (username,email,password) values ('$username','".$email."','".$password."')";
            $insert_result = mysqli_query($conn,$insert_sql);

            echo "Registered successfully..! Now you can login. <button class='btn btn-primary'><a href='login.php' style='color: white;'>Go to Login</a></button>";
        }
        else{
            echo $username." - This username already registered";
        }

    }
    
}
?>  
<div class="container">
    <div class="card mt-5" style="width: 30rem;">
        <div class="card-body">
            <h5 class="card-title">Registration</h5>
                <form method="post" action="" autocomplete="off">
                <div class="form-group">
                    Username:   
                    <input type="text" class="form-control"  name="username" autocomplete="off">  
                    <span class="error" style='color:red'>* <?php echo $nameErr; ?> </span>
                </div>     
                <div class="form-group">  
                    E-mail:   
                    <input type="text" class="form-control"  name="email" autocomplete="off">  
                    <span class="error" style='color:red'>* <?php echo $emailErr; ?> </span>  
                </div> 
                <div class="form-group"> 
                    Password   
                    <input type="text" class="form-control"  name="password" autocomplete="off">  
                    <span class="error" style='color:red'>* <?php echo $passwordErr; ?> </span> 
                </div>     
                <input type="hidden" name='csrftoken' value='<?php echo htmlspecialchars($_SESSION['csrf_token']);?>'>                   
                <input type="submit" name="submit" value="Submit"  class="btn btn-success">                             
            </form>  
        </div>
    </div>
</div>

</body>  
</html>  