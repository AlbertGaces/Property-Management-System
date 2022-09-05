<?php 
  include "include/config.php";
  $active = 'occupants';

  date_default_timezone_set('Asia/Manila');

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
  }

  if(isset($_POST['addNewOccupant'])){
    $imgFolder = "images/residents/";
    $imgName = basename($_FILES["inputOccupantPhoto"]["name"]);
    $imgDirectory = $imgFolder.$imgName;
    $imgType = pathinfo($imgDirectory,PATHINFO_EXTENSION);
    $imgValidExtension = array('jpg','png','jpeg');

    $occupantAddress = $_POST['selectOccupantAddress'];

    $occupantFirstname = $_POST['inputOccupantFirstname'];
    $occupantLastname = $_POST['inputOccupantLastname'];
    $occupantMiddlename = $_POST['inputOccupantMiddlename'];
    $occupantSuffix = $_POST['selectOccupantSuffix'];

    $occupantBirthplace = $_POST['inputOccupantBirthplace'];
    $occupantBirthdate = $_POST['inputOccupantBirthdate'];
    $occupantSex = $_POST['selectOccupantSex'];

    $occupantCivilStatus = $_POST['selectOccupantCivilStatus'];
    $occupantReligion = $_POST['inputOccupantReligion'];
    $occupantOccupation = $_POST['inputOccupantOccupation'];

    $occupantContactNumber1 = $_POST['inputOccupantContactNumber1'];
    $occupantContactNumber2 = $_POST['inputOccupantContactNumber2'];

    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_lastname = '$occupantLastname' AND res_firstname = '$occupantFirstname' AND res_middle = '$occupantMiddlename' AND res_suffix = '$tenantSuffix'");
    if ($residentsTable->num_rows > 0) {
      $_SESSION['message'] = "<strong>Existing Occupant!</strong> This occupant is already existing in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      if(in_array($imgType, $imgValidExtension)){
          if ($_FILES['inputOccupantPhoto']['size'] < 1000000) {
              if (move_uploaded_file($_FILES['inputOccupantPhoto']['tmp_name'], $imgDirectory)) {
                  $residentsTable = $conn->query("INSERT INTO `tbl_residents`(`ID`, `res_lastname`, `res_firstname`, `res_middle`, `res_photo`, `res_suffix`, `res_contact_num_1`, `res_contact_num_2`, `res_birthdate`, `res_birthplace`, `res_civil_status`, `res_sex`, `res_religion`, `res_occupation`, `res_type`, `res_address`, `res_status`) VALUES (NULL,'$occupantLastname','$occupantFirstname','$occupantMiddlename','$imgName','$occupantSuffix','$occupantContactNumber1','$occupantContactNumber2','$occupantBirthdate','$occupantBirthplace','$occupantCivilStatus','$occupantSex','$occupantReligion','$occupantOccupation','Occupant','$occupantAddress','')") or die ($conn->error);

                  $_SESSION['message']= "<strong>New Occupant Added!</strong> $occupantFirstname $occupantLastname has been added into the residents list.";
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

  if(isset($_POST['editOccupant'])){
    $editOccupantID = $_POST['inputHiddenEditOccupantID'];
    $editOccupantAddress = $_POST['selectEditOccupantAddress'];

    $editOccupantFirstname = $_POST['inputEditOccupantFirstname'];
    $editOccupantLastname = $_POST['inputEditOccupantLastname'];
    $editOccupantMiddlename = $_POST['inputEditOccupantMiddlename'];
    $editOccupantSuffix = $_POST['selectEditOccupantSuffix'];

    $editOccupantBirthplace = $_POST['inputEditOccupantBirthplace'];
    $editOccupantBirthdate = $_POST['inputEditOccupantBirthdate'];
    $editOccupantSex = $_POST['selectEditOccupantSex'];

    $editOccupantCivilStatus = $_POST['selectEditOccupantCivilStatus'];
    $editOccupantReligion = $_POST['inputEditOccupantReligion'];
    $editOccupantOccupation = $_POST['inputEditOccupantOccupation'];

    $editOccupantContactNumber1 = $_POST['inputEditOccupantContactNumber1'];
    $editOccupantContactNumber2 = $_POST['inputEditOccupantContactNumber2'];

    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_lastname = '$editOccupantLastname' AND res_firstname = '$editOccupantFirstname' AND res_middle = '$editOccupantMiddlename' AND res_suffix = '$tenantSuffix' AND ID != '$editOccupantID'");
    if ($residentsTable->num_rows > 0) {
      $_SESSION['message'] = "<strong>Duplicate Occupant Name!</strong> There is already an existing occupant with the same name in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      if ($_FILES['inputEditOccupantPhoto']['size'] != 0) {
        $imgFolder = "images/residents/";
        $imgName = basename($_FILES["inputEditOccupantPhoto"]["name"]);
        $imgDirectory = $imgFolder.$imgName;
        $imgType = pathinfo($imgDirectory,PATHINFO_EXTENSION);
        $imgValidExtension = array('jpg','png','jpeg');
  
        if(in_array($imgType, $imgValidExtension)){
          if ($_FILES['inputEditOccupantPhoto']['size'] < 1000000) {
            if (move_uploaded_file($_FILES['inputEditOccupantPhoto']['tmp_name'], $imgDirectory)) {
              $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_lastname`='$editOccupantLastname',`res_firstname`='$editOccupantFirstname',`res_middle`='$editOccupantMiddlename',`res_photo`='$imgName',`res_suffix`='$editOccupantSuffix',`res_contact_num_1`='$editOccupantContactNumber1',`res_contact_num_2`='$editOccupantContactNumber2',`res_birthdate`='$editOccupantBirthdate',`res_birthplace`='$editOccupantBirthplace',`res_civil_status`='$editOccupantCivilStatus',`res_sex`='$editOccupantSex',`res_religion`='$editOccupantReligion',`res_occupation`='$editOccupantOccupation',`res_address`='$editOccupantAddress' WHERE ID = '$editOccupantID'") or die ($conn->error);
  
              $_SESSION['message']= "<strong>Occupant Details Updated!</strong> The information of $editOccupantFirstname $editOccupantLastname has been updated.";
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
        $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_lastname`='$editOccupantLastname',`res_firstname`='$editOccupantFirstname',`res_middle`='$editOccupantMiddlename',`res_suffix`='$editOccupantSuffix',`res_contact_num_1`='$editOccupantContactNumber1',`res_contact_num_2`='$editOccupantContactNumber2',`res_birthdate`='$editOccupantBirthdate',`res_birthplace`='$editOccupantBirthplace',`res_civil_status`='$editOccupantCivilStatus',`res_sex`='$editOccupantSex',`res_religion`='$editOccupantReligion',`res_occupation`='$editOccupantOccupation',`res_address`='$editOccupantAddress' WHERE ID = '$editOccupantID'") or die ($conn->error);
  
        $_SESSION['message']= "<strong>Owner Details Updated!</strong> The information of $editOwnerFirstname $editOwnerLastname has been updated.";
        $_SESSION['message-type'] = "alert-success";
      }
    }
  }

  if(isset($_POST['deleteOccupant'])){
    $deleteOccupantID = $_POST['inputHiddenDeleteOccupantID'];
    $deleteOccupantFirstname = $_POST['inputHiddenDeleteOccupantFirstname'];
    $deleteOccupantLastname = $_POST['inputHiddenDeleteOccupantLastname'];
    $currentTime = date("Y-m-d H:i:s");

    $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_status`='Archived', `res_date`='$currentTime' WHERE ID = '$deleteOccupantID'");

    $_SESSION['message'] = "<strong>Owner Details Deleted!</strong> $deleteOccupantFirstname $deleteOccupantLastname has been removed.";
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

    <title>Occupants</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Occupants</h4>
          </div>
          <div>
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddNewOccupant">
                  Add New Occupant
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
                <th>Address</th>
                <th>Actions</th>
              </thead>
              <tbody>
                <?php
                  $residentsTableOccupants = $conn->query("SELECT * FROM tbl_residents WHERE res_type = 'Occupant' AND res_status != 'Archived'");
                  if ($residentsTableOccupants->num_rows > 0) {
                    while ($residentsTableOccupantsRows = $residentsTableOccupants->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle"><?php echo $residentsTableOccupantsRows['ID'];?></td>
                          <td class="align-middle"><img src="images/residents/<?php echo $residentsTableOccupantsRows['res_photo'];?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;"></td>
                          <td class="align-middle"><?php echo $residentsTableOccupantsRows['res_lastname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableOccupantsRows['res_firstname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableOccupantsRows['res_middle'];?></td>
                          <td class="align-middle">
                            <?php 
                              $birthdate = new DateTime($residentsTableOccupantsRows['res_birthdate']);
                              $currentTime = new DateTime();
                              $age = $currentTime->diff($birthdate);
                              echo $age->y;
                            ?>
                          </td>
                          <td class="align-middle"><?php echo $residentsTableOccupantsRows['res_sex'];?></td>
                          <td class="align-middle"><?php echo $residentsTableOccupantsRows['res_contact_num_1'];?></td>
                          <td class="align-middle">
                            <?php
                              $occupantAddress = $residentsTableOccupantsRows['res_address'];
                              $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE ID = '$occupantAddress'");
                              if ($propertiesTable->num_rows > 0) {
                                while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                                  echo "Phase ".$propertiesTableRows['prop_phase']." Block ".$propertiesTableRows['prop_block']." Lot ".$propertiesTableRows['prop_lot'];
                                }
                              }
                            ?>
                          </td>
                          <td class="align-middle">
                            <button class="btn btn-sm btn-primary py-1" title="Edit" data-bs-toggle="modal" data-bs-target="#modalOccupantEdit<?php echo $residentsTableOccupantsRows['ID']?>"><i class="bi bi-pen"></i></button>
                            <button class="btn btn-sm btn-danger py-1" title="Delete" data-bs-toggle="modal" data-bs-target="#modalOccupantDelete<?php echo $residentsTableOccupantsRows['ID']?>"><i class="bi bi-trash"></i></button>
                          </td>

                          <!-- EDIT RESIDENT -->

                          <div class="modal fade" id="modalOccupantEdit<?php echo $residentsTableOccupantsRows['ID']?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-centered">
                                  <div class="modal-content">
                                      <form action="" method="POST" enctype="multipart/form-data">
                                          <div class="modal-header">
                                              <h5 class="modal-title"><?php echo $residentsTableOccupantsRows['res_firstname']." ".$residentsTableOccupantsRows['res_lastname']?></h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                              <input type="hidden" name="inputHiddenEditOccupantID" value="<?php echo $residentsTableOccupantsRows['ID']?>"/>
                                              <div class="mb-3">
                                                <div class="row d-flex align-items-center">
                                                  <div class="col-2">
                                                      <img src="images/residents/<?php echo $residentsTableOccupantsRows['res_photo']?>" class="rounded" style="width: 120px; height: 120px; object-fit:cover;">
                                                  </div>
                                                  <div class="col-5">
                                                    <label for="inputEditOccupantPhoto" class="form-label">Photo</label>
                                                      <input type="file" class="form-control" id="inputEditOccupantPhoto" name="inputEditOccupantPhoto">
                                                  </div>
                                                  <div class="col-5">
                                                    <label for="selectEditOccupantAddress" class="form-label">Address</label>
                                                    <select class="form-select" name="selectEditOccupantAddress" id="selectEditOccupantAddress" required>
                                                      <option value=''>Choose Address</option>
                                                      <?php
                                                        $propertiesTable = $conn->query("SELECT * FROM tbl_properties INNER JOIN tbl_residents ON tbl_properties.prop_owner = tbl_residents.ID WHERE prop_type = 'Residential' ORDER BY prop_phase, prop_block, prop_lot");
                                                        if ($propertiesTable->num_rows > 0) {
                                                          while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                                                            ?>
                                                              <option value='<?php echo $propertiesTableRows['ID']?>' <?php if($propertiesTableRows['ID'] == $residentsTableOccupantsRows['res_address']){echo "selected";}?>>
                                                                <?php 
                                                                  if ($propertiesTableRows['res_middle'] != NULL) {
                                                                    echo $propertiesTableRows['res_lastname'].", ".$propertiesTableRows['res_firstname']." ".$propertiesTableRows['res_middle'][0].".";
                                                                  }
                                                                  else {
                                                                    echo $propertiesTableRows['res_lastname'].", ".$propertiesTableRows['res_firstname'];
                                                                  }
                                                                ?> - Phase <?php echo $propertiesTableRows['prop_phase']?> Block <?php echo $propertiesTableRows['prop_block']?> Lot <?php echo $propertiesTableRows['prop_lot']?>
                                                              </option>
                                                            <?php
                                                          }
                                                        }
                                                      ?>
                                                    </select>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="mb-3">
                                                <div class="row">
                                                  <div class="col-3">
                                                    <label for="inputEditOccupantFirstname" class="form-label">Firstname</label>
                                                    <input type="text" class="form-control" id="inputEditOccupantFirstname" name="inputEditOccupantFirstname" value="<?php echo $residentsTableOccupantsRows['res_firstname'];?>" required>
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="inputEditOccupantMiddlename" class="form-label">Middlename</label>
                                                    <input type="text" class="form-control" id="inputEditOccupantMiddlename" name="inputEditOccupantMiddlename" value="<?php echo $residentsTableOccupantsRows['res_middle'];?>">
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="inputEditOccupantLastname" class="form-label">Lastname</label>
                                                    <input type="text" class="form-control" id="inputEditOccupantLastname" name="inputEditOccupantLastname" value="<?php echo $residentsTableOccupantsRows['res_lastname'];?>" required>
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="selectEditOccupantSuffix" class="form-label">Suffix</label>
                                                    <select class="form-select" name="selectEditOccupantSuffix" id="selectEditOccupantSuffix">
                                                      <option value=''>None</option>
                                                      <option value='Jr' <?php if($residentsTableOccupantsRows['res_suffix'] == 'Jr'){echo "selected";}?>>Jr</option>
                                                      <option value='Sr' <?php if($residentsTableOccupantsRows['res_suffix'] == 'Sr'){echo "selected";}?>>Sr</option>
                                                      <option value='III' <?php if($residentsTableOccupantsRows['res_suffix'] == 'III'){echo "selected";}?>>III</option>
                                                      <option value='IV' <?php if($residentsTableOccupantsRows['res_suffix'] == 'IV'){echo "selected";}?>>IV</option>
                                                    </select>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="mb-3">
                                                <div class="row">
                                                  <div class="col-4">
                                                    <label for="inputEditOccupantBirthplace" class="form-label">Birthplace</label>
                                                    <input type="text" class="form-control" id="inputEditOccupantBirthplace" name="inputEditOccupantBirthplace" value="<?php echo $residentsTableOccupantsRows['res_birthplace'];?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="inputEditOccupantBirthdate" class="form-label">Birthdate</label>
                                                    <input type="date" class="form-control" id="inputEditOccupantBirthdate" name="inputEditOccupantBirthdate" value="<?php echo date("Y-m-d", strtotime($residentsTableOccupantsRows['res_birthdate']));;?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="selectEditOccupantSex" class="form-label">Sex</label>
                                                    <select class="form-select" name="selectEditOccupantSex" id="selectEditOccupantSex">
                                                      <option value='Male' <?php if($residentsTableOccupantsRows['res_sex'] == 'Male'){echo "selected";}?>>Male</option>
                                                      <option value='Female' <?php if($residentsTableOccupantsRows['res_sex'] == 'Female'){echo "selected";}?>>Female</option>
                                                    </select>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="mb-3">
                                                <div class="row">
                                                  <div class="col-4">
                                                    <label for="selectEditOccupantCivilStatus" class="form-label">Civil Status</label>
                                                    <select class="form-select" name="selectEditOccupantCivilStatus" id="selectEditOccupantCivilStatus">
                                                      <option value='Single' <?php if($residentsTableOccupantsRows['res_civil_status'] == 'Single'){echo "selected";}?>>Single</option>
                                                      <option value='Married' <?php if($residentsTableOccupantsRows['res_civil_status'] == 'Married'){echo "selected";}?>>Married</option>
                                                      <option value='Divorced' <?php if($residentsTableOccupantsRows['res_civil_status'] == 'Divorced'){echo "selected";}?>>Divorced</option>
                                                      <option value='Widowed' <?php if($residentsTableOccupantsRows['res_civil_status'] == 'Widowed'){echo "selected";}?>>Widowed</option>
                                                    </select>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="inputEditOccupantReligion" class="form-label">Religion</label>
                                                    <input type="text" class="form-control" id="inputEditOccupantReligion" name="inputEditOccupantReligion" value="<?php echo $residentsTableOccupantsRows['res_religion'];?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                  <label for="inputEditOccupantOccupation" class="form-label">Occupation</label>
                                                    <input type="text" class="form-control" id="inputEditOccupantOccupation" name="inputEditOccupantOccupation" value="<?php echo $residentsTableOccupantsRows['res_occupation'];?>" required>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="">
                                                <div class="row">
                                                  <div class="col-6">
                                                    <div class="d-flex">
                                                      <label for="inputEditOccupantContactNumber1" class="form-label">Primary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                                                    </div>
                                                    <input type="text" class="form-control" id="inputEditOccupantContactNumber1" name="inputEditOccupantContactNumber1" pattern="^(09|\+639)\d{9}$" value="<?php echo $residentsTableOccupantsRows['res_contact_num_1'];?>" maxlength="11" placeholder="ex. 09123456789" required>
                                                  </div>
                                                  <div class="col-6">
                                                    <div class="d-flex">
                                                      <label for="inputEditOccupantContactNumber2" class="form-label">Secondary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                                                    </div>
                                                    <input type="text" class="form-control" id="inputEditOccupantContactNumber2" name="inputEditOccupantContactNumber2" pattern="^(09|\+639)\d{9}$" value="<?php echo $residentsTableOccupantsRows['res_contact_num_2'];?>" maxlength="11" placeholder="ex. 09123456789">
                                                  </div>
                                                </div>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                              <button type="submit" class="btn btn-primary" name="editOccupant" id="editOccupant">Update Details</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>

                          <!-- DELETE RESIDENT -->

                          <div class="modal fade" id="modalOccupantDelete<?php echo $residentsTableOccupantsRows['ID']?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete the records of <?php echo $residentsTableOccupantsRows['res_firstname']." ".$residentsTableOccupantsRows['res_lastname']?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="inputHiddenDeleteOccupantID" value="<?php echo $residentsTableOccupantsRows['ID']?>"/>
                                            <input type="hidden" name="inputHiddenDeleteOccupantFirstname" value="<?php echo $residentsTableOccupantsRows['res_firstname']?>"/>
                                            <input type="hidden" name="inputHiddenDeleteOccupantLastname" value="<?php echo $residentsTableOccupantsRows['res_lastname']?>"/>
                                            Are you sure that you want to delete the details of <?php echo $residentsTableOccupantsRows['res_firstname']." ".$residentsTableOccupantsRows['res_lastname']?> from the database?
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">No</button>
                                          <button type="submit" class="btn btn-primary" name="deleteOccupant" id="deleteOccupant">Yes</button>
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

            <div class="modal fade" id="modalAddNewOccupant" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Adding New Occupant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-6">
                            <label for="inputOccupantPhoto" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="inputOccupantPhoto" name="inputOccupantPhoto" required>
                          </div>
                          <div class="col-6">
                            <label for="selectOccupantAddress" class="form-label">Address</label>
                            <select class="form-select" name="selectOccupantAddress" id="selectOccupantAddress" required>
                              <option value=''>Choose Address</option>
                              <?php
                                $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_type = 'Residential' AND prop_status != 'Archived' ORDER BY prop_phase, prop_block, prop_lot");
                                if ($propertiesTable->num_rows > 0) {
                                  while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                                    $propertyOwnerID = $propertiesTableRows['prop_owner'];
                                    $residentsTableName = $conn->query("SELECT * FROM tbl_residents WHERE ID = '$propertyOwnerID'")->fetch_assoc();
                                    ?>
                                      <option value='<?php echo $propertiesTableRows['ID']?>'>
                                        <?php 
                                          if ($residentsTableName['res_middle'] != NULL) {
                                            echo $residentsTableName['res_lastname'].", ".$residentsTableName['res_firstname']." ".$residentsTableName['res_middle'][0].".";
                                          }
                                          else {
                                            echo $residentsTableName['res_lastname'].", ".$residentsTableName['res_firstname'];
                                          }
                                        ?> 
                                        - Phase <?php echo $propertiesTableRows['prop_phase']?> Block <?php echo $propertiesTableRows['prop_block']?> Lot <?php echo $propertiesTableRows['prop_lot']?>
                                      </option>
                                    <?php
                                  }
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-3">
                            <label for="inputOccupantFirstname" class="form-label">Firstname</label>
                            <input type="text" class="form-control" id="inputOccupantFirstname" name="inputOccupantFirstname" required>
                          </div>
                          <div class="col-3">
                            <label for="inputOccupantMiddlename" class="form-label">Middlename</label>
                            <input type="text" class="form-control" id="inputOccupantMiddlename" name="inputOccupantMiddlename">
                          </div>
                          <div class="col-3">
                            <label for="inputOccupantLastname" class="form-label">Lastname</label>
                            <input type="text" class="form-control" id="inputOccupantLastname" name="inputOccupantLastname" required>
                          </div>
                          <div class="col-3">
                            <label for="selectOccupantSuffix" class="form-label">Suffix</label>
                            <select class="form-select" name="selectOccupantSuffix" id="selectOccupantSuffix">
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
                            <label for="inputOccupantBirthplace" class="form-label">Birthplace</label>
                            <input type="text" class="form-control" id="inputOccupantBirthplace" name="inputOccupantBirthplace" required>
                          </div>
                          <div class="col-4">
                            <label for="inputOccupantBirthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="inputOccupantBirthdate" name="inputOccupantBirthdate" required>
                          </div>
                          <div class="col-4">
                            <label for="selectOccupantSex" class="form-label">Sex</label>
                            <select class="form-select" name="selectOccupantSex" id="selectOccupantSex">
                              <option value='Male'>Male</option>
                              <option value='Female'>Female</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4">
                            <label for="selectOccupantCivilStatus" class="form-label">Civil Status</label>
                            <select class="form-select" name="selectOccupantCivilStatus" id="selectOccupantCivilStatus">
                              <option value='Single'>Single</option>
                              <option value='Married'>Married</option>
                              <option value='Divorced'>Divorced</option>
                              <option value='Widowed'>Widowed</option>
                            </select>
                          </div>
                          <div class="col-4">
                            <label for="inputOccupantReligion" class="form-label">Religion</label>
                            <input type="text" class="form-control" id="inputOccupantReligion" name="inputOccupantReligion" required>
                          </div>
                          <div class="col-4">
                          <label for="inputOccupantOccupation" class="form-label">Occupation</label>
                            <input type="text" class="form-control" id="inputOccupantOccupation" name="inputOccupantOccupation" required>
                          </div>
                        </div>
                      </div>
                      <div class="">
                        <div class="row">
                          <div class="col-6">
                            <div class="d-flex">
                              <label for="inputOccupantContactNumber1" class="form-label">Primary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                            </div>
                            <input type="text" class="form-control" id="inputOccupantContactNumber1" name="inputOccupantContactNumber1" pattern="^(09|\+639)\d{9}$" maxlength="11" placeholder="ex. 09123456789" required>
                          </div>
                          <div class="col-6">
                            <div class="d-flex">
                              <label for="inputOccupantContactNumber2" class="form-label">Secondary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                            </div>
                            <input type="text" class="form-control" id="inputOccupantContactNumber2" name="inputOccupantContactNumber2" pattern="^(09|\+639)\d{9}$" maxlength="11" placeholder="ex. 09123456789">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addNewOccupant" id="addNewOccupant">Add</button>
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