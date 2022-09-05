<?php 
  include "include/config.php";
  $active = 'login';

  if(!isset($_SESSION)){
    session_start();
  }

  $usernameForm = '';
  $verificationForm = '';

  function itexmo($number,$message,$apicode,$password) {
    $ch = curl_init();
    $itexmo = array('1' => $number, '2' => $message, '3' => $apicode, 'passwd' => $password);
    curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($itexmo));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return curl_exec ($ch);
    curl_close ($ch); 
  }

  if(isset($_POST['submitUsername'])){
    $inputUsername = $_POST['inputUsername'];
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $adminsTable = $conn->query("SELECT * FROM tbl_admins WHERE admin_username = '$inputUsername' AND admin_status != 'Archived'");
    if ($adminsTable->num_rows > 0) {
        $usernameForm = "Hidden";
        $adminsTableRow = $adminsTable->fetch_assoc();
        $contactNumber = $adminsTableRow['admin_contact_num'];
        $adminName = $adminsTableRow['admin_firstname']." ".$adminsTableRow['admin_lastname'];
        $verificationCode = rand(100000,999999);
        $_SESSION['verificationCode'] = $verificationCode;
        $_SESSION['changeID'] = $adminsTableRow['ID'];

        $message = "Hello $adminName!, $verificationCode is your verification code.";

        itexmo($contactNumber,$message,$apicode,$password);
    }
    else {
        $_SESSION['message'] = "<strong>Invalid Username!</strong>";
        $_SESSION['message-type'] = "alert-danger";
    }
  }

  if(isset($_POST['submitVerification'])){
    $inputVerification = $_POST['inputVerification'];

    if ($_SESSION['verificationCode'] == $inputVerification) {
        header("Location: change-password.php");
    }
    else {
        $_SESSION['message'] = "<strong>Incorrect Verification Code!</strong>";
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
        <div class="row row-cols-3 justify-content-center align-items-center" style="height: 100vh;">
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
            <form action="" method="POST" <?php if ($usernameForm == 'Hidden') {echo "class='hidden'";}?>>
                <label class="text-dark rounded p-2 text-center w-100" style="background-color: rgba(255, 133, 51);"><i class="bi bi-info-circle-fill fs-4"></i><br>Enter your username below</label>
                <br><br>
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" name="inputUsername" id="inputUsername" placeholder="Username" required>
                    <label for="inputUsername">Username</label>
                </div>
                <div class="row">
                    <div class="col mb-3">
                        <button type="submit" id="submitUsername" name="submitUsername" class="btn w-100 fw-bold" style="background-color: rgba(255, 133, 51);">Next</button>
                    </div>
                </div>
            </form>

            <form action="" method="POST" <?php if ($usernameForm != 'Hidden') {echo "class='hidden'";} else if ($verificationForm == 'Hidden'){echo "class='hidden'";}?>>
                <label class="text-dark rounded p-2 text-center" style="background-color: rgba(255, 133, 51);"><i class="bi bi-info-circle-fill fs-4"></i><br>Enter the verification code that has been sent to your phone number.</label>
                <br><br>
                <div class="form-floating mb-4">
                    <input type="text" class="form-control" name="inputVerification" id="inputVerification" placeholder="Verification Code" required>
                    <label for="inputVerification">Verification Code</label>
                </div>
                <div class="row">
                    <div class="col mb-3">
                    <button type="submit" id="submitVerification" name="submitVerification" class="btn w-100 fw-bold" style="background-color: rgba(255, 133, 51);">Next</button>
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