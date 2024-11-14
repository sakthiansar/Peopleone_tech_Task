<!DOCTYPE html>
<html>
<head>  
<link href="js/css/bootstrap.min.css" rel="stylesheet">
<script src="js/bootstrap/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php
    session_start();

    // If the form is submitted, destroy the session
    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    echo "Login successful! Welcome, You have been logged out.";
?>

<!-- Create a form with a logout button -->
<form method="POST">
    <button type="submit" name="logout" class="btn btn-danger">Log out</button>
</form>

</body>
</html>

