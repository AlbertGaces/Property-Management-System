<?php 
  include "include/config.php";
  $active = 'accounts';

  date_default_timezone_set('Asia/Manila');

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
  }

  if(isset($_POST['addNewAdmin'])){
    $imgFolder = "images/residents/";
    $imgName = basename($_FILES["inputAdminPhoto"]["name"]);
    $imgDirectory = $imgFolder.$imgName;
    $imgType = pathinfo($imgDirectory,PATHINFO_EXTENSION);
    $imgValidExtension = array('jpg','png','jpeg');

    $adminFirstname = $_POST['inputAdminFirstname'];
    $adminLastname = $_POST['inputAdminLastname'];
    $adminUsername = $_POST['inputAdminUsername'];
    $adminPassword = md5($_POST['inputAdminPassword']);
    $adminContactNumber = $_POST['inputAdminContactNumber'];
    $adminPosition = $_POST['selectAdminPosition'];


    $adminsTableUsername = $conn->query("SELECT * FROM tbl_admins WHERE admin_username = '$adminUsername'");
    if ($adminsTableUsername->num_rows > 0) {
      $_SESSION['message'] = "<strong>Invalid Username!</strong> The username $adminUsername has already been taken, try another one.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      $adminsTableName = $conn->query("SELECT * FROM tbl_admins WHERE admin_lastname = '$adminLastname' AND admin_firstname = '$adminFirstname'");
      if ($adminsTableName->num_rows > 0) {
        $_SESSION['message'] = "<strong>Existing Account Name!</strong> This admin is already existing in the database.";
        $_SESSION['message-type'] = "alert-danger";
      }
      else {
        if(in_array($imgType, $imgValidExtension)){
            if ($_FILES['inputAdminPhoto']['size'] < 1000000) {
                if (move_uploaded_file($_FILES['inputAdminPhoto']['tmp_name'], $imgDirectory)) {
                    $residentsTable = $conn->query("INSERT INTO `tbl_admins`(`ID`, `admin_lastname`, `admin_firstname`, `admin_photo`, `admin_username`, `admin_password`, `admin_contact_num`, `admin_position`) VALUES (NULL,'$adminLastname','$adminFirstname','$imgName','$adminUsername','$adminPassword','$adminContactNumber','$adminPosition')") or die ($conn->error);
  
                    $_SESSION['message']= "<strong>New Administrator Added!</strong> $adminFirstname $adminLastname has been added into the administrators list.";
                    $_SESSION['message-type'] = "alert-success";
                } 
                else {
                  $_SESSION['message']= "<strong>File Upload Error!</strong> There have been issues with uploading the photo.";
                  $_SESSION['message-type'] = "alert-danger";
                }
            }
            else {
              $_SESSION['message']= "<strong>File Too Large!</strong> File should not exceed 1 Megabyte.";
              $_SESSION['message-type'] = "alert-danger";
            }
        }
        else {
          $_SESSION['message']= "<strong>Invalid File Type!</strong> The photo should only be .jpg, .png or .jpeg.";
          $_SESSION['message-type'] = "alert-danger";
        }
      }
    }
  }

  if(isset($_POST['editAdmin'])){
    $editAdminID = $_POST['inputHiddenEditAdminID'];

    $editAdminFirstname = $_POST['inputEditAdminFirstname'];
    $editAdminLastname = $_POST['inputEditAdminLastname'];
    $editAdminPosition = $_POST['selectEditAdminPosition'];
    $editAdminContactNumber = $_POST['inputEditAdminContactNumber'];

    $imgFolder = "images/residents/";
    $imgName = basename($_FILES["inputEditAdminPhoto"]["name"]);
    $imgDirectory = $imgFolder.$imgName;
    $imgType = pathinfo($imgDirectory,PATHINFO_EXTENSION);
    $imgValidExtension = array('jpg','png','jpeg');

    $adminsTable = $conn->query("SELECT * FROM tbl_admins WHERE admin_firstname = '$editAdminFirstname' AND admin_lastname = '$editAdminLastname' AND ID != '$editAdminID'");
    if ($adminsTable->num_rows > 0) {
      $_SESSION['message'] = "<strong>Duplicate Admin Name!</strong> There is already an existing admin with the same name in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      if ($_FILES['inputEditAdminPhoto']['size'] != 0) {
        if(in_array($imgType, $imgValidExtension)){
          if ($_FILES['inputEditAdminPhoto']['size'] < 1000000) {
            if (move_uploaded_file($_FILES['inputEditAdminPhoto']['tmp_name'], $imgDirectory)) {
              $adminsTable = $conn->query("UPDATE `tbl_admins` SET `admin_lastname`='$editAdminLastname',`admin_firstname`='$editAdminFirstname',`admin_photo`='$imgName',`admin_contact_num`='$editAdminContactNumber',`admin_position`='$editAdminPosition' WHERE ID = '$editAdminID'") or die ($conn->error);
  
              $_SESSION['message']= "<strong>Admin Details Updated!</strong> The information of $editAdminFirstname $editAdminLastname has been updated.";
              $_SESSION['message-type'] = "alert-success";
            } 
            else {
              $_SESSION['message']= "<strong>File Upload Error!</strong> There have been issues with uploading the photo.";
              $_SESSION['message-type'] = "alert-danger";
            }
          }
          else {
            $_SESSION['message']= "<strong>File Too Large!</strong> File should not exceed 1 Megabyte.";
            $_SESSION['message-type'] = "alert-danger";
          }
        }
        else {
          $_SESSION['message']= "<strong>Invalid File Type!</strong> The photo should only be .jpg, .png or .jpeg.";
          $_SESSION['message-type'] = "alert-danger";
        }
      }
      else {
        $adminsTable = $conn->query("UPDATE `tbl_admins` SET `admin_lastname`='$editAdminLastname',`admin_firstname`='$editAdminFirstname',`admin_contact_num`='$editAdminContactNumber',`admin_position`='$editAdminPosition' WHERE ID = '$editAdminID'") or die ($conn->error);
  
        $_SESSION['message']= "<strong>Admin Details Updated!</strong> The information of $editAdminFirstname $editAdminLastname has been updated.";
        $_SESSION['message-type'] = "alert-success";
      }
    }
  }

  if(isset($_POST['deleteAdmin'])){
    $deleteAdminID = $_POST['inputHiddenDeleteAdminID'];
    $deleteAdminFirstname = $_POST['inputHiddenDeleteAdminFirstname'];
    $deleteAdminLastname = $_POST['inputHiddenDeleteAdminLastname'];
    $currentTime = date("Y-m-d H:i:s");

    $adminsTable = $conn->query("UPDATE `tbl_admins` SET `admin_status`='Archived', `admin_date`='$currentTime' WHERE ID = '$deleteAdminID'");

    $_SESSION['message'] = "<strong>Admin Details Deleted!</strong> $deleteAdminFirstname $deleteAdminLastname has been removed.";
    $_SESSION['message-type'] = "alert-success";
  }

  if(isset($_POST['changePasswordAdmin'])){
    $changePasswordAdminID = $_POST['inputHiddenChangePasswordAdminID'];
    $changePasswordAdminOldPassword = $_POST['inputHiddenChangePasswordAdminOldPassword'];
    $newPassword = $_POST['inputAdminNewPassword'];
    $confirmPassword = $_POST['inputAdminConfirmPassword'];

    if ($newPassword == $confirmPassword) {
      $newPasswordEncrypted = md5($newPassword);
      if ($newPasswordEncrypted != $changePasswordAdminOldPassword) {
        $adminsTable = $conn->query("UPDATE `tbl_admins` SET `admin_password`='$newPasswordEncrypted' WHERE ID = '$changePasswordAdminID'");

        $_SESSION['message'] = "<strong>Account Password Updated!</strong>";
        $_SESSION['message-type'] = "alert-success";
      }
      else {
        $_SESSION['message'] = "<strong>You have entered your old password!</strong>";
        $_SESSION['message-type'] = "alert-danger";
      }
    }
    else {
      $_SESSION['message'] = "<strong>Password Does Not Match!</strong>";
      $_SESSION['message-type'] = "alert-danger";
    }
  }

  if(isset($_POST['logOut'])){
    unset($_SESSION['ID']);
    unset($_SESSION['name']);
    unset($_SESSION['loggedIn']);
    header("Location: index.php");
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

    <title>Accounts</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Accounts</h4>
          </div>
          <div>
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddNewAdmin">
                  Add New Admin
              </button>
          </div>
        </div>
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
        <div class="card">
          <div class="card-body">
            <table class="datatable table table-hover responsive nowrap w-100">
              <thead>
                <th>ID</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Position</th>
                <th>Actions</th>
              </thead>
              <tbody>
                <?php
                  $adminsTable = $conn->query("SELECT * FROM tbl_admins WHERE admin_status != 'Archived'");
                  if ($adminsTable->num_rows > 0) {
                    while ($adminsTableRows = $adminsTable->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle"><?php echo $adminsTableRows['ID'];?></td>
                          <td class="align-middle"><img src="images/residents/<?php echo $adminsTableRows['admin_photo'];?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;"></td>
                          <td class="align-middle"><?php echo $adminsTableRows['admin_lastname'].", ".$adminsTableRows['admin_firstname'];?></td>
                          <td class="align-middle">
                            <?php 
                              if ($adminsTableRows['admin_position'] == 'BOD') {
                                echo "Board of Director";
                              }
                              else {
                                echo $adminsTableRows['admin_position'];
                              }
                            ?>
                          </td>
                          <td class="align-middle">
                            <button class="btn btn-sm btn-primary py-1" title="Edit" data-bs-toggle="modal" data-bs-target="#modalAdminEdit<?php echo $adminsTableRows['ID']?>"><i class="bi bi-pen"></i></button>
                            <?php
                              if ($adminsTableRows['ID'] == $_SESSION['ID']) {
                                ?>
                                  <button class="btn btn-sm btn-warning py-1" title="Change Password" data-bs-toggle="modal" data-bs-target="#modalAdminChangePassword<?php echo $adminsTableRows['ID']?>"><i class="bi bi-key-fill"></i></button>
                                <?php
                              }
                            ?>
                            <button class="btn btn-sm btn-danger py-1" title="Delete" data-bs-toggle="modal" data-bs-target="#modalAdminDelete<?php echo $adminsTableRows['ID']?>"><i class="bi bi-trash"></i></button>
                          </td>

                          <!-- EDIT ADMIN -->

                          <div class="modal fade" id="modalAdminEdit<?php echo $adminsTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-centered">
                                  <div class="modal-content">
                                      <form action="" method="POST" enctype="multipart/form-data">
                                          <div class="modal-header">
                                              <h5 class="modal-title"><?php echo $adminsTableRows['admin_firstname']." ".$adminsTableRows['admin_lastname']?></h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                            <input type="hidden" name="inputHiddenEditAdminID" value="<?php echo $adminsTableRows['ID']?>"/>
                                            <div class="mb-3">
                                              <div class="row d-flex align-items-center">
                                                <div class="col-2">
                                                    <img src="images/residents/<?php echo $adminsTableRows['admin_photo']?>" class="rounded" style="width: 120px; height: 120px; object-fit:cover;">
                                                </div>
                                                <div class="col-10">
                                                  <label for="inputEditAdminPhoto" class="form-label">Photo</label>
                                                  <input type="file" class="form-control" id="inputEditAdminPhoto" name="inputEditAdminPhoto">
                                                </div>
                                              </div>
                                            </div>
                                            <div class="mb-3">
                                              <div class="row row-cols-2">
                                                <div class="col">
                                                  <label for="inputEditAdminFirstname" class="form-label">Firstname</label>
                                                  <input type="text" class="form-control" id="inputEditAdminFirstname" name="inputEditAdminFirstname" value="<?php echo $adminsTableRows['admin_firstname'];?>" required>
                                                </div>
                                                <div class="col">
                                                  <label for="inputEditAdminLastname" class="form-label">Lastname</label>
                                                  <input type="text" class="form-control" id="inputEditAdminLastname" name="inputEditAdminLastname" value="<?php echo $adminsTableRows['admin_lastname'];?>" required>
                                                </div>
                                              </div>
                                            </div>
                                            <div class="row row-cols-2">
                                              <div class="col">
                                                  <label for="inputEditAdminContactNumber" class="form-label">Contact Number</label>
                                                  <input type="text" class="form-control" id="inputEditAdminContactNumber" name="inputEditAdminContactNumber" pattern="^(09|\+639)\d{9}$" value="<?php echo $adminsTableRows['admin_contact_num'];?>" required>
                                                </div>
                                              <div class="col">
                                                <label for="selectEditAdminPosition" class="form-label">Position</label>
                                                <select class="form-select" name="selectEditAdminPosition" id="selectEditAdminPosition" required>
                                                    <?php
                                                        $adminsTablePresident = $conn->query("SELECT COUNT(*) AS president_count FROM tbl_admins WHERE admin_position = 'President' AND admin_status != 'Archived'")->fetch_assoc()['president_count'];
                                                        if ($adminsTablePresident == 0) {
                                                            ?>
                                                                <option value='President'>President</option>
                                                            <?php
                                                        }

                                                        $adminsTableVP = $conn->query("SELECT COUNT(*) AS v_president_count FROM tbl_admins WHERE admin_position = 'Vice President' AND admin_status != 'Archived'")->fetch_assoc()['v_president_count'];
                                                        if ($adminsTableVP == 0) {
                                                            ?>
                                                                <option value='Vice President'>Vice President</option>
                                                            <?php
                                                        }

                                                        $adminsTableSecretary = $conn->query("SELECT COUNT(*) AS secretary_count FROM tbl_admins WHERE admin_position = 'Secretary' AND admin_status != 'Archived'")->fetch_assoc()['secretary_count'];
                                                        if ($adminsTableSecretary == 0) {
                                                            ?>
                                                                <option value='Secretary'>Secretary</option>
                                                            <?php
                                                        }

                                                        $adminsTableBOD = $conn->query("SELECT COUNT(*) AS BOD_count FROM tbl_admins WHERE admin_position = 'BOD' AND admin_status != 'Archived'")->fetch_assoc()['BOD_count'];
                                                        if ($adminsTableBOD <= 4) {
                                                            ?>
                                                                <option value='BOD'>Board of Directors (<?php echo 5-$adminsTableBOD;?> Available Slots)</option>
                                                            <?php
                                                        }

                                                        $adminsTableOfficeSecretary = $conn->query("SELECT COUNT(*) AS office_secretary_count FROM tbl_admins WHERE admin_position = 'Office Secretary' AND admin_status != 'Archived'")->fetch_assoc()['office_secretary_count'];
                                                        if ($adminsTableSecretary == 0) {
                                                            ?>
                                                                <option value='Office Secretary'>Office Secretary</option>
                                                            <?php
                                                        }

                                                        if ($adminsTablePresident != 0 && $adminsTableVP != 0 && $adminsTableSecretary != 0 && $adminsTableBOD >= 5 && $adminsTableSecretary != 0) {
                                                          ?>
                                                            <option value=''>=== No Available Administrator Position ===</option>
                                                          <?php
                                                        }
                                                    ?>
                                                </select>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                              <button type="submit" class="btn btn-primary" name="editAdmin" id="editAdmin">Update Details</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>

                          <!-- CHANGE PASSWORD -->

                          <div class="modal fade" id="modalAdminChangePassword<?php echo $adminsTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Change Your Password</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="inputHiddenChangePasswordAdminID" value="<?php echo $adminsTableRows['ID']?>"/>
                                            <input type="hidden" name="inputHiddenChangePasswordAdminOldPassword" value="<?php echo $adminsTableRows['admin_password']?>"/>
                                            <div class="row row-cols-2">
                                              <div class="col">
                                                <label for="inputAdminNewPassword" class="form-label">New Password</label>
                                                <input type="password" class="form-control" id="inputAdminNewPassword" name="inputAdminNewPassword" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}$" required>
                                              </div>
                                              <div class="col">
                                                <label for="inputAdminConfirmPassword" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" id="inputAdminConfirmPassword" name="inputAdminConfirmPassword" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,}$" required>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="submit" class="btn btn-primary" name="changePasswordAdmin" id="changePasswordAdmin">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          </div>

                          <!-- DELETE ADMIN -->

                          <div class="modal fade" id="modalAdminDelete<?php echo $adminsTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete the records of <?php echo $adminsTableRows['admin_firstname']." ".$adminsTableRows['admin_lastname']?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="inputHiddenDeleteAdminID" value="<?php echo $adminsTableRows['ID']?>"/>
                                            <input type="hidden" name="inputHiddenDeleteAdminFirstname" value="<?php echo $adminsTableRows['admin_firstname']?>"/>
                                            <input type="hidden" name="inputHiddenDeleteAdminLastname" value="<?php echo $adminsTableRows['admin_lastname']?>"/>
                                            Are you sure that you want to delete the details of <?php echo $adminsTableRows['admin_firstname']." ".$adminsTableRows['admin_lastname']?> from the database?
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">No</button>
                                          <button type="submit" class="btn btn-primary" name="deleteAdmin" id="deleteAdmin">Yes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          </div>
                        </tr>
                      <?php
                    }
                  }
                ?>
              </tbody>
            </table>

            <div class="modal fade" id="modalAddNewAdmin" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Adding New Admin Account</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <div class="row row-cols-2">
                          <div class="col">
                            <label for="inputAdminPhoto" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="inputAdminPhoto" name="inputAdminPhoto" required>
                          </div>
                          <div class="col">
                            <label for="selectAdminPosition" class="form-label">Position</label>
                            <select class="form-select" name="selectAdminPosition" id="selectAdminPosition" required>
                                <?php
                                    $adminsTablePresident = $conn->query("SELECT COUNT(*) AS president_count FROM tbl_admins WHERE admin_position = 'President' AND admin_status != 'Archived'")->fetch_assoc()['president_count'];
                                    if ($adminsTablePresident == 0) {
                                        ?>
                                            <option value='President'>President</option>
                                        <?php
                                    }

                                    $adminsTableVP = $conn->query("SELECT COUNT(*) AS v_president_count FROM tbl_admins WHERE admin_position = 'Vice President' AND admin_status != 'Archived'")->fetch_assoc()['v_president_count'];
                                    if ($adminsTableVP == 0) {
                                        ?>
                                            <option value='Vice President'>Vice President</option>
                                        <?php
                                    }

                                    $adminsTableSecretary = $conn->query("SELECT COUNT(*) AS secretary_count FROM tbl_admins WHERE admin_position = 'Secretary' AND admin_status != 'Archived'")->fetch_assoc()['secretary_count'];
                                    if ($adminsTableSecretary == 0) {
                                        ?>
                                            <option value='Secretary'>Secretary</option>
                                        <?php
                                    }

                                    $adminsTableBOD = $conn->query("SELECT COUNT(*) AS BOD_count FROM tbl_admins WHERE admin_position = 'BOD' AND admin_status != 'Archived'")->fetch_assoc()['BOD_count'];
                                    if ($adminsTableBOD <= 4) {
                                        ?>
                                            <option value='BOD'>Board of Directors (<?php echo 5-$adminsTableBOD;?> Available Slots)</option>
                                        <?php
                                    }

                                    $adminsTableOfficeSecretary = $conn->query("SELECT COUNT(*) AS office_secretary_count FROM tbl_admins WHERE admin_position = 'Office Secretary' AND admin_status != 'Archived'")->fetch_assoc()['office_secretary_count'];
                                    if ($adminsTableSecretary == 0) {
                                        ?>
                                            <option value='Office Secretary'>Office Secretary</option>
                                        <?php
                                    }

                                    if ($adminsTablePresident != 0 && $adminsTableVP != 0 && $adminsTableSecretary != 0 && $adminsTableBOD >= 5 && $adminsTableSecretary != 0) {
                                      ?>
                                        <option value=''>=== All Administrator Positions Taken ===</option>
                                      <?php
                                    }
                                ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <div class="row row-cols-3">
                          <div class="col">
                            <label for="inputAdminFirstname" class="form-label">Firstname</label>
                            <input type="text" class="form-control" id="inputAdminFirstname" name="inputAdminFirstname" required>
                          </div>
                          <div class="col">
                            <label for="inputAdminLastname" class="form-label">Lastname</label>
                            <input type="text" class="form-control" id="inputAdminLastname" name="inputAdminLastname" required>
                          </div>
                          <div class="col">
                            <label for="inputAdminContactNumber" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="inputAdminContactNumber" name="inputAdminContactNumber" pattern="^(09|\+639)\d{9}$" required>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <div class="row row-cols-2">
                          <div class="col">
                            <label for="inputAdminUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="inputAdminUsername" name="inputAdminUsername" required>
                          </div>
                          <div class="col">
                            <label for="inputAdminPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="inputAdminPassword" name="inputAdminPassword" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addNewAdmin" id="addNewAdmin">Add</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    
    <script src="js\jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/af-2.3.7/b-2.1.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.1/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/sr-1.0.1/datatables.min.js"></script>
    <script src="js\script.js"></script>
  </body>
</html>