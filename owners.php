<?php 
  include "include/config.php";
  $active = 'owners';

  date_default_timezone_set('Asia/Manila');

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
  }

  if(isset($_POST['addNewOwner'])){
    $imgFolder = "images/residents/";
    $imgName = basename($_FILES["inputOwnerPhoto"]["name"]);
    $imgDirectory = $imgFolder.$imgName;
    $imgType = pathinfo($imgDirectory,PATHINFO_EXTENSION);
    $imgValidExtension = array('jpg','png','jpeg');

    $ownerFirstname = $_POST['inputOwnerFirstname'];
    $ownerLastname = $_POST['inputOwnerLastname'];
    $ownerMiddlename = $_POST['inputOwnerMiddlename'];
    $ownerSuffix = $_POST['selectOwnerSuffix'];

    $ownerBirthplace = $_POST['inputOwnerBirthplace'];
    $ownerBirthdate = $_POST['inputOwnerBirthdate'];
    $ownerSex = $_POST['selectOwnerSex'];

    $ownerCivilStatus = $_POST['selectOwnerCivilStatus'];
    $ownerReligion = $_POST['inputOwnerReligion'];
    $ownerOccupation = $_POST['inputOwnerOccupation'];

    $ownerContactNumber1 = $_POST['inputOwnerContactNumber1'];
    $ownerContactNumber2 = $_POST['inputOwnerContactNumber2'];

    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_lastname = '$ownerLastname' AND res_firstname = '$ownerFirstname' AND res_middle = '$ownerMiddlename' AND res_suffix = '$ownerSuffix'");
    if ($residentsTable->num_rows > 0) {
      $_SESSION['message'] = "<strong>Existing Owner!</strong> This owner is already existing in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      if(in_array($imgType, $imgValidExtension)){
          if ($_FILES['inputOwnerPhoto']['size'] < 1000000) {
              if (move_uploaded_file($_FILES['inputOwnerPhoto']['tmp_name'], $imgDirectory)) {
                  $residentsTable = $conn->query("INSERT INTO `tbl_residents`(`ID`, `res_lastname`, `res_firstname`, `res_middle`, `res_photo`, `res_suffix`, `res_contact_num_1`, `res_contact_num_2`, `res_birthdate`, `res_birthplace`, `res_civil_status`, `res_sex`, `res_religion`, `res_occupation`, `res_type`, `res_address`, `res_status`) VALUES (NULL,'$ownerLastname','$ownerFirstname','$ownerMiddlename','$imgName','$ownerSuffix','$ownerContactNumber1','$ownerContactNumber2','$ownerBirthdate','$ownerBirthplace','$ownerCivilStatus','$ownerSex','$ownerReligion','$ownerOccupation','Owner','','')") or die ($conn->error);

                  $_SESSION['message']= "<strong>New Owner Added!</strong> $ownerFirstname $ownerLastname has been added into the residents list.";
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

  if(isset($_POST['editOwner'])){
    $editOwnerID = $_POST['inputHiddenEditOwnerID'];

    $editOwnerFirstname = $_POST['inputEditOwnerFirstname'];
    $editOwnerLastname = $_POST['inputEditOwnerLastname'];
    $editOwnerMiddlename = $_POST['inputEditOwnerMiddlename'];
    $editOwnerSuffix = $_POST['selectEditOwnerSuffix'];

    $editOwnerBirthplace = $_POST['inputEditOwnerBirthplace'];
    $editOwnerBirthdate = $_POST['inputEditOwnerBirthdate'];
    $editOwnerSex = $_POST['selectEditOwnerSex'];

    $editOwnerCivilStatus = $_POST['selectEditOwnerCivilStatus'];
    $editOwnerReligion = $_POST['inputEditOwnerReligion'];
    $editOwnerOccupation = $_POST['inputEditOwnerOccupation'];

    $editOwnerContactNumber1 = $_POST['inputEditOwnerContactNumber1'];
    $editOwnerContactNumber2 = $_POST['inputEditOwnerContactNumber2'];

    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_lastname = '$editOwnerLastname' AND res_firstname = '$editOwnerFirstname' AND res_middle = '$editOwnerMiddlename' AND res_suffix = '$ownerSuffix' AND ID != '$editOwnerID'");
    if ($residentsTable->num_rows > 0) {
      $_SESSION['message'] = "<strong>Duplicate Owner Name!</strong> There is already an existing owner with the same name in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      if ($_FILES['inputEditOwnerPhoto']['size'] != 0) {
        $imgFolder = "images/residents/";
        $imgName = basename($_FILES["inputEditOwnerPhoto"]["name"]);
        $imgDirectory = $imgFolder.$imgName;
        $imgType = pathinfo($imgDirectory,PATHINFO_EXTENSION);
        $imgValidExtension = array('jpg','png','jpeg');
  
        if(in_array($imgType, $imgValidExtension)){
          if ($_FILES['inputEditOwnerPhoto']['size'] < 1000000) {
            if (move_uploaded_file($_FILES['inputEditOwnerPhoto']['tmp_name'], $imgDirectory)) {
              $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_lastname`='$editOwnerLastname',`res_firstname`='$editOwnerFirstname',`res_middle`='$editOwnerMiddlename',`res_photo`='$imgName',`res_suffix`='$editOwnerSuffix',`res_contact_num_1`='$editOwnerContactNumber1',`res_contact_num_2`='$editOwnerContactNumber2',`res_birthdate`='$editOwnerBirthdate',`res_birthplace`='$editOwnerBirthplace',`res_civil_status`='$editOwnerCivilStatus',`res_sex`='$editOwnerSex',`res_religion`='$editOwnerReligion',`res_occupation`='$editOwnerOccupation' WHERE ID = '$editOwnerID'") or die ($conn->error);
  
              $_SESSION['message']= "<strong>Owner Details Updated!</strong> The information of $editOwnerFirstname $editOwnerLastname has been updated.";
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
        $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_lastname`='$editOwnerLastname',`res_firstname`='$editOwnerFirstname',`res_middle`='$editOwnerMiddlename',`res_suffix`='$editOwnerSuffix',`res_contact_num_1`='$editOwnerContactNumber1',`res_contact_num_2`='$editOwnerContactNumber2',`res_birthdate`='$editOwnerBirthdate',`res_birthplace`='$editOwnerBirthplace',`res_civil_status`='$editOwnerCivilStatus',`res_sex`='$editOwnerSex',`res_religion`='$editOwnerReligion',`res_occupation`='$editOwnerOccupation' WHERE ID = '$editOwnerID'") or die ($conn->error);
  
        $_SESSION['message']= "<strong>Owner Details Updated!</strong> The information of $editOwnerFirstname $editOwnerLastname has been updated.";
        $_SESSION['message-type'] = "alert-success";
      }
    }
  }

  if(isset($_POST['deleteOwner'])){
    $deleteOwnerID = $_POST['inputHiddenDeleteOwnerID'];
    $deleteOwnerFirstname = $_POST['inputHiddenDeleteOwnerFirstname'];
    $deleteOwnerLastname = $_POST['inputHiddenDeleteOwnerLastname'];
    $currentTime = date("Y-m-d H:i:s");

    $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_owner = '$deleteOwnerID'");
    if ($propertiesTable->num_rows > 0) {
      $_SESSION['message'] = "<strong>Property Owner!</strong> $deleteOwnerFirstname $deleteOwnerLastname is currently owning a property. Try to switch the owner of the property to someone else first.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_status`='Archived', `res_date`='$currentTime' WHERE ID = '$deleteOwnerID'");

      $_SESSION['message'] = "<strong>Owner Details Deleted!</strong> $deleteOwnerFirstname $deleteOwnerLastname has been removed.";
      $_SESSION['message-type'] = "alert-success";
    }
  }

  if(isset($_POST['btnOwnerResidingProperty'])){
    $ownerID = $_POST['inputHiddenViewOwnerID'];
    $ownerResidingProperty = $_POST['ownerResidingProperty'];

    $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_address` = '$ownerResidingProperty' WHERE ID = '$ownerID'") or die ($conn->error);
  
    $_SESSION['message']= "<strong>Owner Residence Updated!</strong>";
    $_SESSION['message-type'] = "alert-success";
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

    <title>Owners</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Owners</h4>
          </div>
          <div>
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddNewOwner">
                  Add New Owner
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
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Contact Number</th>
                <th>Properties</th>
                <th>Actions</th>
              </thead>
              <tbody>
                <?php
                  $residentsTableOwners = $conn->query("SELECT * FROM tbl_residents WHERE res_type = 'Owner' AND res_status != 'Archived'");
                  if ($residentsTableOwners->num_rows > 0) {
                    while ($residentsTableOwnersRows = $residentsTableOwners->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle"><?php echo $residentsTableOwnersRows['ID'];?></td>
                          <td class="align-middle"><img src="images/residents/<?php echo $residentsTableOwnersRows['res_photo'];?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;"></td>
                          <td class="align-middle"><?php echo $residentsTableOwnersRows['res_lastname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableOwnersRows['res_firstname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableOwnersRows['res_middle'];?></td>
                          <td class="align-middle">
                            <?php 
                              $birthdate = new DateTime($residentsTableOwnersRows['res_birthdate']);
                              $currentTime = new DateTime();
                              $age = $currentTime->diff($birthdate);
                              echo $age->y;
                            ?>
                          </td>
                          <td class="align-middle"><?php echo $residentsTableOwnersRows['res_sex'];?></td>
                          <td class="align-middle"><?php echo $residentsTableOwnersRows['res_contact_num_1'];?></td>
                          <td class="align-middle">
                            <div class="d-flex justify-content-between">
                              <?php 
                                $residentID = $residentsTableOwnersRows['ID'];
                                $propertiesTableCount = $conn->query("SELECT COUNT(*) AS prop_count FROM tbl_properties WHERE prop_owner = '$residentID' AND prop_status != 'Archived'")->fetch_assoc()['prop_count'];
                                if ($propertiesTableCount != 0) {
                                  echo $propertiesTableCount;
                                  ?>
                                    <button class="btn btn-sm btn-warning py-1" title="View" data-bs-toggle="modal" data-bs-target="#modalOwnerView<?php echo $residentsTableOwnersRows['ID']?>"><i class="bi bi-eye-fill"></i></button>
                                  <?php
                                }
                                else {
                                  echo 0;
                                }
                              ?>
                            </div>
                          </td>
                          <td class="align-middle">
                            <button class="btn btn-sm btn-primary py-1" title="Edit" data-bs-toggle="modal" data-bs-target="#modalOwnerEdit<?php echo $residentsTableOwnersRows['ID']?>"><i class="bi bi-pen"></i></button>
                            <?php 
                                $residentID = $residentsTableOwnersRows['ID'];
                                $propertiesTableCount = $conn->query("SELECT COUNT(*) AS prop_count FROM tbl_properties WHERE prop_owner = '$residentID'")->fetch_assoc()['prop_count'];
                                if ($propertiesTableCount == 0) {
                                  ?>
                                    <button class="btn btn-sm btn-danger py-1" title="Delete" data-bs-toggle="modal" data-bs-target="#modalOwnerDelete<?php echo $residentsTableOwnersRows['ID']?>"><i class="bi bi-trash"></i></button>
                                  <?php
                                }
                              ?>
                          </td>

                          <!-- VIEW PROPERTIES -->

                          <div class="modal fade" id="modalOwnerView<?php echo $residentsTableOwnersRows['ID']?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <form action="" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Owned properties of <?php echo $residentsTableOwnersRows['res_firstname']." ".$residentsTableOwnersRows['res_lastname']?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <input type="hidden" name="inputHiddenViewOwnerID" value="<?php echo $residentsTableOwnersRows['ID']?>"/>
                                      <div class="row mb-2">
                                        <div class="col-2">
                                          
                                        </div>
                                        <div class="col-2 fw-bold text-center">
                                          Phase
                                        </div>
                                        <div class="col-2 fw-bold text-center">
                                          Block
                                        </div>
                                        <div class="col-2 fw-bold text-center">
                                          Lot
                                        </div>
                                        <div class="col-4 fw-bold text-center">
                                          Type
                                        </div>
                                      </div>
                                      <?php
                                        $residentID = $residentsTableOwnersRows['ID'];
                                        $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_owner = '$residentID' AND prop_status != 'Archived' ORDER BY prop_type DESC, prop_phase, prop_block, prop_lot");
                                        if ($propertiesTable->num_rows > 0) {
                                          $propertyCount = 0;
                                          while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                                            $propertyCount++;
                                            ?>
                                              <div class="row mb-2 <?php if ($residentsTableOwnersRows['res_address'] == $propertiesTableRows['ID']) {echo "table-row-primary rounded";}?>">
                                                <div class="col-2">
                                                  <?php
                                                    if ($propertiesTableRows['prop_type'] == 'Commercial') {
                                                      ?>
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="hidden">
                                                          <label class="form-check-label" for="ownerResidingProperty<?php echo $propertiesTableRows['ID']?>">
                                                            # <?php echo $propertyCount;?>
                                                          </label>
                                                        </div>
                                                      <?php
                                                    }
                                                    else {
                                                      ?>
                                                        <div class="form-check">
                                                          <input class="form-check-input" type="radio" value="<?php echo $propertiesTableRows['ID']?>" name="ownerResidingProperty" id="ownerResidingProperty<?php echo $propertiesTableRows['ID']?>" <?php if ($residentsTableOwnersRows['res_address'] == $propertiesTableRows['ID']) {echo "checked";}?>>
                                                          <label class="form-check-label" for="ownerResidingProperty<?php echo $propertiesTableRows['ID']?>">
                                                            # <?php echo $propertyCount;?>
                                                          </label>
                                                        </div>
                                                      <?php
                                                    }
                                                  ?>
                                                </div>
                                                <div class="col-2 text-center">
                                                  <?php echo $propertiesTableRows['prop_phase'];?>
                                                </div>
                                                <div class="col-2 text-center">
                                                  <?php echo $propertiesTableRows['prop_block'];?>
                                                </div>
                                                <div class="col-2 text-center">
                                                  <?php echo $propertiesTableRows['prop_lot'];?>
                                                </div>
                                                <div class="col-4 text-center">
                                                  <?php echo $propertiesTableRows['prop_type'];?>
                                                </div>
                                              </div>
                                            <?php
                                          }
                                        }
                                      ?>
                                      <hr>
                                      <div class="row p-1 <?php if ($residentsTableOwnersRows['res_address'] == 0) {echo "table-row-primary rounded";}?>">
                                        <div class="col-12">
                                          <div class="form-check">
                                            <input class="form-check-input" type="radio" name="ownerResidingProperty" id="ownerResidingPropertyDefault<?php echo $residentsTableOwnersRows['ID']?>" <?php if ($residentsTableOwnersRows['res_address'] == 0) {echo "checked";}?>>
                                            <label class="form-check-label" for="ownerResidingPropertyDefault<?php echo $residentsTableOwnersRows['ID']?>">
                                              Currently Residing outside of Mahogany Village
                                            </label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-primary" name="btnOwnerResidingProperty" id="btnOwnerResidingProperty">Save</button>
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                  </form>
                                </div>
                            </div>
                          </div>

                          <!-- EDIT RESIDENT -->

                          <div class="modal fade" id="modalOwnerEdit<?php echo $residentsTableOwnersRows['ID']?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-centered">
                                  <div class="modal-content">
                                      <form action="" method="POST" enctype="multipart/form-data">
                                          <div class="modal-header">
                                              <h5 class="modal-title"><?php echo $residentsTableOwnersRows['res_firstname']." ".$residentsTableOwnersRows['res_lastname']?></h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                              <input type="hidden" name="inputHiddenEditOwnerID" value="<?php echo $residentsTableOwnersRows['ID']?>"/>
                                              <div class="mb-3">
                                                <div class="row d-flex align-items-center">
                                                  <div class="col-2">
                                                      <img src="images/residents/<?php echo $residentsTableOwnersRows['res_photo']?>" class="rounded" style="width: 120px; height: 120px; object-fit:cover;">
                                                  </div>
                                                  <div class="col-10">
                                                    <label for="inputEditOwnerPhoto" class="form-label">Photo</label>
                                                    <input type="file" class="form-control" id="inputEditOwnerPhoto" name="inputEditOwnerPhoto">
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="mb-3">
                                                <div class="row">
                                                  <div class="col-3">
                                                    <label for="inputEditOwnerFirstname" class="form-label">Firstname</label>
                                                    <input type="text" class="form-control" id="inputEditOwnerFirstname" name="inputEditOwnerFirstname" value="<?php echo $residentsTableOwnersRows['res_firstname'];?>" required>
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="inputEditOwnerMiddlename" class="form-label">Middlename</label>
                                                    <input type="text" class="form-control" id="inputEditOwnerMiddlename" name="inputEditOwnerMiddlename" value="<?php echo $residentsTableOwnersRows['res_middle'];?>">
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="inputEditOwnerLastname" class="form-label">Lastname</label>
                                                    <input type="text" class="form-control" id="inputEditOwnerLastname" name="inputEditOwnerLastname" value="<?php echo $residentsTableOwnersRows['res_lastname'];?>" required>
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="selectEditOwnerSuffix" class="form-label">Suffix</label>
                                                    <select class="form-select" name="selectEditOwnerSuffix" id="selectEditOwnerSuffix">
                                                      <option value=''>None</option>
                                                      <option value='Jr' <?php if($residentsTableOwnersRows['res_suffix'] == 'Jr'){echo "selected";}?>>Jr</option>
                                                      <option value='Sr' <?php if($residentsTableOwnersRows['res_suffix'] == 'Sr'){echo "selected";}?>>Sr</option>
                                                      <option value='III' <?php if($residentsTableOwnersRows['res_suffix'] == 'III'){echo "selected";}?>>III</option>
                                                      <option value='IV' <?php if($residentsTableOwnersRows['res_suffix'] == 'IV'){echo "selected";}?>>IV</option>
                                                    </select>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="mb-3">
                                                <div class="row">
                                                  <div class="col-4">
                                                    <label for="inputEditOwnerBirthplace" class="form-label">Birthplace</label>
                                                    <input type="text" class="form-control" id="inputEditOwnerBirthplace" name="inputEditOwnerBirthplace" value="<?php echo $residentsTableOwnersRows['res_birthplace'];?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="inputEditOwnerBirthdate" class="form-label">Birthdate</label>
                                                    <input type="date" class="form-control" id="inputEditOwnerBirthdate" name="inputEditOwnerBirthdate" value="<?php echo date("Y-m-d", strtotime($residentsTableOwnersRows['res_birthdate']));;?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="selectEditOwnerSex" class="form-label">Sex</label>
                                                    <select class="form-select" name="selectEditOwnerSex" id="selectEditOwnerSex">
                                                      <option value='Male' <?php if($residentsTableOwnersRows['res_sex'] == 'Male'){echo "selected";}?>>Male</option>
                                                      <option value='Female' <?php if($residentsTableOwnersRows['res_sex'] == 'Female'){echo "selected";}?>>Female</option>
                                                    </select>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="mb-3">
                                                <div class="row">
                                                  <div class="col-4">
                                                    <label for="selectEditOwnerCivilStatus" class="form-label">Civil Status</label>
                                                    <select class="form-select" name="selectEditOwnerCivilStatus" id="selectEditOwnerCivilStatus">
                                                      <option value='Single' <?php if($residentsTableOwnersRows['res_civil_status'] == 'Single'){echo "selected";}?>>Single</option>
                                                      <option value='Married' <?php if($residentsTableOwnersRows['res_civil_status'] == 'Married'){echo "selected";}?>>Married</option>
                                                      <option value='Divorced' <?php if($residentsTableOwnersRows['res_civil_status'] == 'Divorced'){echo "selected";}?>>Divorced</option>
                                                      <option value='Widowed' <?php if($residentsTableOwnersRows['res_civil_status'] == 'Widowed'){echo "selected";}?>>Widowed</option>
                                                    </select>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="inputEditOwnerReligion" class="form-label">Religion</label>
                                                    <input type="text" class="form-control" id="inputEditOwnerReligion" name="inputEditOwnerReligion" value="<?php echo $residentsTableOwnersRows['res_religion'];?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                  <label for="inputEditOwnerOccupation" class="form-label">Occupation</label>
                                                    <input type="text" class="form-control" id="inputEditOwnerOccupation" name="inputEditOwnerOccupation" value="<?php echo $residentsTableOwnersRows['res_occupation'];?>" required>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="">
                                                <div class="row">
                                                  <div class="col-6">
                                                    <div class="d-flex">
                                                      <label for="inputEditOwnerContactNumber1" class="form-label">Primary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                                                    </div>
                                                    <input type="text" class="form-control" id="inputEditOwnerContactNumber1" name="inputEditOwnerContactNumber1" pattern="^(09|\+639)\d{9}$" value="<?php echo $residentsTableOwnersRows['res_contact_num_1'];?>" maxlength="11" placeholder="ex. 09123456789" required>
                                                  </div>
                                                  <div class="col-6">
                                                    <div class="d-flex">
                                                      <label for="inputEditOwnerContactNumber2" class="form-label">Secondary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                                                    </div>
                                                    <input type="text" class="form-control" id="inputEditOwnerContactNumber2" name="inputEditOwnerContactNumber2" pattern="^(09|\+639)\d{9}$" value="<?php echo $residentsTableOwnersRows['res_contact_num_2'];?>" maxlength="11" placeholder="ex. 09123456789">
                                                  </div>
                                                </div>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                              <button type="submit" class="btn btn-primary" name="editOwner" id="editOwner">Update Details</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>

                          <!-- DELETE RESIDENT -->

                          <div class="modal fade" id="modalOwnerDelete<?php echo $residentsTableOwnersRows['ID']?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete the records of <?php echo $residentsTableOwnersRows['res_firstname']." ".$residentsTableOwnersRows['res_lastname']?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="inputHiddenDeleteOwnerID" value="<?php echo $residentsTableOwnersRows['ID']?>"/>
                                            <input type="hidden" name="inputHiddenDeleteOwnerFirstname" value="<?php echo $residentsTableOwnersRows['res_firstname']?>"/>
                                            <input type="hidden" name="inputHiddenDeleteOwnerLastname" value="<?php echo $residentsTableOwnersRows['res_lastname']?>"/>
                                            Are you sure that you want to delete the details of <?php echo $residentsTableOwnersRows['res_firstname']." ".$residentsTableOwnersRows['res_lastname']?> from the database?
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">No</button>
                                          <button type="submit" class="btn btn-primary" name="deleteOwner" id="deleteOwner">Yes</button>
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

            <div class="modal fade" id="modalAddNewOwner" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Adding New Owner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <div class="row">
                          <div class="col">
                            <label for="inputOwnerPhoto" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="inputOwnerPhoto" name="inputOwnerPhoto" required>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-3">
                            <label for="inputOwnerFirstname" class="form-label">Firstname</label>
                            <input type="text" class="form-control" id="inputOwnerFirstname" name="inputOwnerFirstname" required>
                          </div>
                          <div class="col-3">
                            <label for="inputOwnerMiddlename" class="form-label">Middlename</label>
                            <input type="text" class="form-control" id="inputOwnerMiddlename" name="inputOwnerMiddlename">
                          </div>
                          <div class="col-3">
                            <label for="inputOwnerLastname" class="form-label">Lastname</label>
                            <input type="text" class="form-control" id="inputOwnerLastname" name="inputOwnerLastname" required>
                          </div>
                          <div class="col-3">
                            <label for="selectOwnerSuffix" class="form-label">Suffix</label>
                            <select class="form-select" name="selectOwnerSuffix" id="selectOwnerSuffix">
                              <option value=''>None</option>
                              <option value='Jr'>Jr</option>
                              <option value='Sr'>Sr</option>
                              <option value='III'>III</option>
                              <option value='IV'>IV</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4">
                            <label for="inputOwnerBirthplace" class="form-label">Birthplace</label>
                            <input type="text" class="form-control" id="inputOwnerBirthplace" name="inputOwnerBirthplace" required>
                          </div>
                          <div class="col-4">
                            <label for="inputOwnerBirthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="inputOwnerBirthdate" name="inputOwnerBirthdate" required>
                          </div>
                          <div class="col-4">
                            <label for="selectOwnerSex" class="form-label">Sex</label>
                            <select class="form-select" name="selectOwnerSex" id="selectOwnerSex">
                              <option value='Male'>Male</option>
                              <option value='Female'>Female</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4">
                            <label for="selectOwnerCivilStatus" class="form-label">Civil Status</label>
                            <select class="form-select" name="selectOwnerCivilStatus" id="selectOwnerCivilStatus">
                              <option value='Single'>Single</option>
                              <option value='Married'>Married</option>
                              <option value='Divorced'>Divorced</option>
                              <option value='Widowed'>Widowed</option>
                            </select>
                          </div>
                          <div class="col-4">
                            <label for="inputOwnerReligion" class="form-label">Religion</label>
                            <input type="text" class="form-control" id="inputOwnerReligion" name="inputOwnerReligion" required>
                          </div>
                          <div class="col-4">
                          <label for="inputOwnerOccupation" class="form-label">Occupation</label>
                            <input type="text" class="form-control" id="inputOwnerOccupation" name="inputOwnerOccupation" required>
                          </div>
                        </div>
                      </div>
                      <div class="">
                        <div class="row">
                          <div class="col-6">
                            <div class="d-flex">
                              <label for="inputOwnerContactNumber1" class="form-label">Primary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                            </div>
                            <input type="text" class="form-control" id="inputOwnerContactNumber1" name="inputOwnerContactNumber1" pattern="^(09|\+639)\d{9}$" maxlength="11" placeholder="ex. 09123456789" required>
                          </div>
                          <div class="col-6">
                            <div class="d-flex">
                              <label for="inputOwnerContactNumber2" class="form-label">Secondary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                            </div>
                            <input type="text" class="form-control" id="inputOwnerContactNumber2" name="inputOwnerContactNumber2" pattern="^(09|\+639)\d{9}$" maxlength="11" placeholder="ex. 09123456789">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addNewOwner" id="addNewOwner">Add</button>
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