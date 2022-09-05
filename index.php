<?php 
  include "include/config.php";
  $active = 'login';

  if(!isset($_SESSION)){
    session_start();
  }

  if(isset($_POST['btnLogin'])){
    $inputUsername = $_POST['inputUsername'];
    $inputPassword = md5($_POST['inputPassword']);

    $adminsTable = $conn->query("SELECT * FROM tbl_admins WHERE admin_username = '$inputUsername' AND admin_password = '$inputPassword'");
    if ($adminsTable->num_rows > 0) {
      while ($adminsTableRows = $adminsTable->fetch_assoc()) {
        $_SESSION['message'] = "<strong>Log In Successful!</strong><br>Welcome Back {$adminsTableRows['admin_firstname']} {$adminsTableRows['admin_lastname']}";
        $_SESSION['message-type'] = "alert-success";
  
        $_SESSION['ID'] = $adminsTableRows['ID'];
        $_SESSION['photo'] = $adminsTableRows['admin_photo'];
        $_SESSION['name'] = $adminsTableRows['admin_firstname']." ".$adminsTableRows['admin_lastname'];
        $_SESSION['loggedIn'] = 'true';

        header("Refresh:2; url=dashboard.php");
      }
    }
    else {
      $_SESSION['message'] = "<strong>Incorrect Account Credentials!</strong>";
      $_SESSION['message-type'] = "alert-danger";
    }
  }
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="icon" href="images/logo.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/af-2.3.7/b-2.1.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.1/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/sr-1.0.1/datatables.min.css"/>
    <link rel="stylesheet" href="css/style.css" />

    <title>Login</title>
  </head>
  
  <body>
    <div class="login-body">
      <div class="container">
        <div class="row row-cols-3 justify-content-end align-items-center" style="height: 100vh;">
          <div class="col">
            <?php
              if(isset($_SESSION['message'])){
                  ?>
                    <div class="alert <?php echo $_SESSION['message-type'];?> alert-dismissible fade show" role="alert">
                      <?php echo $_SESSION['message'];?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                  <?php
                  unset($_SESSION['message']);
                  unset($_SESSION['message-type']);
              }
            ?>
            <form action="" method="POST">
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="Username" required>
                    <label for="inputUsername">Username</label>
                </div>
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password" required>
                    <label for="inputPassword">Password</label>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <button type="submit" class="btn w-100 fw-bold" id="btnLogin" name="btnLogin" style="background-color: rgba(255, 133, 51);">Log In</button>
                    </div>
                </div>
                <br>
                <div class="row">
                  <div class="col">
                    <a href="forgot-password.php">
                        <button id="btnForgotPassword" type="button" class="btn w-100 fw-bold text-light">Forgot Password?</button>
                    </a>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <script src="js\jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/af-2.3.7/b-2.1.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.1/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/sr-1.0.1/datatables.min.js"></script>
    <script src="js\script.js"></script>
  </body>
</html>