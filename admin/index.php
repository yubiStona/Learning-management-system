<?php
require_once('../configs/config.php');
require_once('../configs/Database.php');
    session_start();
    if(isset($_SESSION['username']) && isset($_SESSION['is_admin'])){
        header("Location:".BASE_PATH."/admin/dashboard.php");
    }

    $db = new Database();
    $conn=$db->getConnection();
    if($_POST){
        $username=$_POST['username'];
        $password=md5($_POST['password']);

        $result=$conn->prepare("select * from tbl_users where username=:username and password=:password");

        $result->bindParam(':username',$username);
        $result->bindParam(':password',$password);
        $result->execute();

        $data=$result->fetch(PDO::FETCH_ASSOC);

        if($result->rowCount()>0){
            if($data['role']=='admin'){
                $_SESSION['username']=$username;
                $_SESSION['is_admin']=true;
                header("Location:".BASE_PATH."/admin/dashboard.php");
            }else{
                $_SESSION['username']=$username;
                $_SESSION['is_admin']=false;
                header("Location:/index.php");
            }
        }else{
            echo "<script>alert('Invalid Username or Password');</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
    <link rel="stylesheet" href="/admin/css/loginStyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" />
</head>
<body>
    <div class="login-container">
        <!-- Common Container for All Forms -->
        <div id="form-container">
            <!-- Login Form -->
            <form class="login-form" id="login-form" method="POST"> 
                <h2>Admin Login</h2>
                <div class="input-field">
                    <span class="material-icons-sharp">person</span>
                    <input type="text" placeholder="Username" name="username" required />
                </div>
                <div class="input-field">
                    <span class="material-icons-sharp">lock</span>
                    <input type="password" id="password" placeholder="Password" name="password" required />
                    <i class="fa-solid fa-eye" id="show-password"></i>
                </div>
                <button type="submit" class="btn primary-btn">Login</button>
                
            </form>

    </div>

    <script>
        // Hide/Show Password for Login Form
        const showPassword = document.querySelector("#show-password");
        const passwordField = document.querySelector("#password");
        showPassword.addEventListener("click", function () {
            if (passwordField.type === "password") {
                passwordField.type = "text";
                showPassword.classList.remove("fa-eye");
                showPassword.classList.add("fa-eye-slash");
                showPassword.style.color = "#7380ec";
            } else {
                passwordField.type = "password";
                showPassword.classList.remove("fa-eye-slash");
                showPassword.classList.add("fa-eye");
            }
        });
    </script>
</body>
</html>