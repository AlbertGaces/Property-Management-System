<?php 
  include "include/config.php";
  $active = 'tenants';

  date_default_timezone_set('Asia/Manila');

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
  }

  if(isset($_POST['addNewTenant'])){
    $imgFolder = "images/residents/";
    $imgName = basename($_FILES["inputTenantPhoto"]["name"]);
    $imgDirectory = $imgFolder.$imgName;
    $imgType = pathinfo($imgDirectory,PATHINFO_EXTENSION);
    $imgValidExtension = array('jpg','png','jpeg');

    $tenantAddress = $_POST['selectTenantAddress'];

    $tenantFirstname = $_POST['inputTenantFirstname'];
    $tenantLastname = $_POST['inputTenantLastname'];
    $tenantMiddlename = $_POST['inputTenantMiddlename'];
    $tenantSuffix = $_POST['selectTenantSuffix'];

    $tenantBirthplace = $_POST['inputTenantBirthplace'];
    $tenantBirthdate = $_POST['inputTenantBirthdate'];
    $tenantSex = $_POST['selectTenantSex'];

    $tenantCivilStatus = $_POST['selectTenantCivilStatus'];
    $tenantReligion = $_POST['inputTenantReligion'];
    $tenantOccupation = $_POST['inputTenantOccupation'];

    $tenantContactNumber1 = $_POST['inputTenantContactNumber1'];
    $tenantContactNumber2 = $_POST['inputTenantContactNumber2'];

    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_lastname = '$tenantLastname' AND res_firstname = '$tenantFirstname' AND res_middle = '$tenantMiddlename' AND res_suffix = '$tenantSuffix'");
    if ($residentsTable->num_rows > 0) {
      $_SESSION['message'] = "<strong>Existing Tenant!</strong> This tenant is already existing in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      if(in_array($imgType, $imgValidExtension)){
          if ($_FILES['inputTenantPhoto']['size'] < 1000000) {
              if (move_uploaded_file($_FILES['inputTenantPhoto']['tmp_name'], $imgDirectory)) {
                  $residentsTable = $conn->query("INSERT INTO `tbl_residents`(`ID`, `res_lastname`, `res_firstname`, `res_middle`, `res_photo`, `res_suffix`, `res_contact_num_1`, `res_contact_num_2`, `res_birthdate`, `res_birthplace`, `res_civil_status`, `res_sex`, `res_religion`, `res_occupation`, `res_type`, `res_address`, `res_status`) VALUES (NULL,'$tenantLastname','$tenantFirstname','$tenantMiddlename','$imgName','$tenantSuffix','$tenantContactNumber1','$tenantContactNumber2','$tenantBirthdate','$tenantBirthplace','$tenantCivilStatus','$tenantSex','$tenantReligion','$tenantOccupation','Tenant','$tenantAddress','')") or die ($conn->error);

                  $_SESSION['message']= "<strong>New Tenant Added!</strong> $tenantFirstname $tenantLastname has been added into the residents list.";
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

  if(isset($_POST['editTenant'])){
    $editTenantID = $_POST['inputHiddenEditTenantID'];
    $editTenantAddress = $_POST['selectEditTenantAddress'];

    $editTenantFirstname = $_POST['inputEditTenantFirstname'];
    $editTenantLastname = $_POST['inputEditTenantLastname'];
    $editTenantMiddlename = $_POST['inputEditTenantMiddlename'];
    $editTenantSuffix = $_POST['selectEditTenantSuffix'];

    $editTenantBirthplace = $_POST['inputEditTenantBirthplace'];
    $editTenantBirthdate = $_POST['inputEditTenantBirthdate'];
    $editTenantSex = $_POST['selectEditTenantSex'];

    $editTenantCivilStatus = $_POST['selectEditTenantCivilStatus'];
    $editTenantReligion = $_POST['inputEditTenantReligion'];
    $editTenantOccupation = $_POST['inputEditTenantOccupation'];

    $editTenantContactNumber1 = $_POST['inputEditTenantContactNumber1'];
    $editTenantContactNumber2 = $_POST['inputEditTenantContactNumber2'];
    $editTenantResidentType = $_POST['selectEditTenantResidentType'];

    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_lastname = '$editTenantLastname' AND res_firstname = '$editTenantFirstname' AND res_middle = '$editTenantMiddlename' AND res_suffix = '$tenantSuffix' AND ID != '$editTenantID'");
    if ($residentsTable->num_rows > 0) {
      $_SESSION['message'] = "<strong>Duplicate Tenant Name!</strong> There is already an existing tenant with the same name in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      if ($_FILES['inputEditTenantPhoto']['size'] != 0) {
        $imgFolder = "images/residents/";
        $imgName = basename($_FILES["inputEditTenantPhoto"]["name"]);
        $imgDirectory = $imgFolder.$imgName;
        $imgType = pathinfo($imgDirectory,PATHINFO_EXTENSION);
        $imgValidExtension = array('jpg','png','jpeg');
  
        if(in_array($imgType, $imgValidExtension)){
          if ($_FILES['inputEditTenantPhoto']['size'] < 1000000) {
            if (move_uploaded_file($_FILES['inputEditTenantPhoto']['tmp_name'], $imgDirectory)) {
              $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_lastname`='$editTenantLastname',`res_firstname`='$editTenantFirstname',`res_middle`='$editTenantMiddlename',`res_photo`='$imgName',`res_suffix`='$editTenantSuffix',`res_contact_num_1`='$editTenantContactNumber1',`res_contact_num_2`='$editTenantContactNumber2',`res_birthdate`='$editTenantBirthdate',`res_birthplace`='$editTenantBirthplace',`res_civil_status`='$editTenantCivilStatus',`res_sex`='$editTenantSex',`res_religion`='$editTenantReligion',`res_occupation`='$editTenantOccupation',`res_address`='$editTenantAddress', `res_type`='$editTenantResidentType', `res_address`='$editTenantAddress' WHERE ID = '$editTenantID'") or die ($conn->error);
  
              $_SESSION['message']= "<strong>Tenant Details Updated!</strong> The information of $editTenantFirstname $editTenantLastname has been updated.";
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
        $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_lastname`='$editTenantLastname',`res_firstname`='$editTenantFirstname',`res_middle`='$editTenantMiddlename',`res_suffix`='$editTenantSuffix',`res_contact_num_1`='$editTenantContactNumber1',`res_contact_num_2`='$editTenantContactNumber2',`res_birthdate`='$editTenantBirthdate',`res_birthplace`='$editTenantBirthplace',`res_civil_status`='$editTenantCivilStatus',`res_sex`='$editTenantSex',`res_religion`='$editTenantReligion',`res_occupation`='$editTenantOccupation',`res_address`='$editTenantAddress', `res_type`='$editTenantResidentType', `res_address`='$editTenantAddress' WHERE ID = '$editTenantID'") or die ($conn->error);
  
        $_SESSION['message']= "<strong>Owner Details Updated!</strong> The information of $editTenantFirstname $editTenantLastname has been updated.";
        $_SESSION['message-type'] = "alert-success";
      }
    }
  }

  if(isset($_POST['deleteTenant'])){
    $deleteTenantID = $_POST['inputHiddenDeleteTenantID'];
    $deleteTenantFirstname = $_POST['inputHiddenDeleteTenantFirstname'];
    $deleteTenantLastname = $_POST['inputHiddenDeleteTenantLastname'];
    $currentTime = date("Y-m-d H:i:s");

    $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_status`='Archived', `res_date`='$currentTime' WHERE ID = '$deleteTenantID'");

    $_SESSION['message'] = "<strong>Owner Details Deleted!</strong> $deleteTenantFirstname $deleteTenantLastname has been removed.";
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

    <title>Tenants</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Tenants</h4>
          </div>
          <div>
              <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddNewTenant">
                  Add New Tenant
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
                  $residentsTableTenants = $conn->query("SELECT * FROM tbl_residents WHERE res_type = 'Tenant' AND res_status != 'Archived'");
                  if ($residentsTableTenants->num_rows > 0) {
                    while ($residentsTableTenantsRows = $residentsTableTenants->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle"><?php echo $residentsTableTenantsRows['ID'];?></td>
                          <td class="align-middle"><img src="images/residents/<?php echo $residentsTableTenantsRows['res_photo'];?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;"></td>
                          <td class="align-middle"><?php echo $residentsTableTenantsRows['res_lastname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableTenantsRows['res_firstname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableTenantsRows['res_middle'];?></td>
                          <td class="align-middle">
                            <?php 
                              $birthdate = new DateTime($residentsTableTenantsRows['res_birthdate']);
                              $currentTime = new DateTime();
                              $age = $currentTime->diff($birthdate);
                              echo $age->y;
                            ?>
                          </td>
                          <td class="align-middle"><?php echo $residentsTableTenantsRows['res_sex'];?></td>
                          <td class="align-middle"><?php echo $residentsTableTenantsRows['res_contact_num_1'];?></td>
                          <td class="align-middle">
                            <?php
                              $tenantAddress = $residentsTableTenantsRows['res_address'];
                              $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE ID = '$tenantAddress'");
                              if ($propertiesTable->num_rows > 0) {
                                while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                                  echo "Phase ".$propertiesTableRows['prop_phase']." Block ".$propertiesTableRows['prop_block']." Lot ".$propertiesTableRows['prop_lot'];
                                }
                              }
                            ?>
                          </td>
                          <td class="align-middle">
                            <button class="btn btn-sm btn-primary py-1" title="Edit" data-bs-toggle="modal" data-bs-target="#modalTenantEdit<?php echo $residentsTableTenantsRows['ID']?>"><i class="bi bi-pen"></i></button>
                            <button class="btn btn-sm btn-danger py-1" title="Delete" data-bs-toggle="modal" data-bs-target="#modalTenantDelete<?php echo $residentsTableTenantsRows['ID']?>"><i class="bi bi-trash"></i></button>
                          </td>

                          <!-- EDIT RESIDENT -->

                          <div class="modal fade" id="modalTenantEdit<?php echo $residentsTableTenantsRows['ID']?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-lg modal-dialog-centered">
                                  <div class="modal-content">
                                      <form action="" method="POST" enctype="multipart/form-data">
                                          <div class="modal-header">
                                              <h5 class="modal-title"><?php echo $residentsTableTenantsRows['res_firstname']." ".$residentsTableTenantsRows['res_lastname']?></h5>
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                          <div class="modal-body">
                                              <input type="hidden" name="inputHiddenEditTenantID" value="<?php echo $residentsTableTenantsRows['ID']?>"/>
                                              <div class="mb-3">
                                                <div class="row d-flex align-items-center">
                                                  <div class="col-2">
                                                      <img src="images/residents/<?php echo $residentsTableTenantsRows['res_photo']?>" class="rounded" style="width: 120px; height: 120px; object-fit:cover;">
                                                  </div>
                                                  <div class="col-5">
                                                    <label for="inputEditTenantPhoto" class="form-label">Photo</label>
                                                      <input type="file" class="form-control" id="inputEditTenantPhoto" name="inputEditTenantPhoto">
                                                  </div>
                                                  <div class="col-5">
                                                    <label for="selectEditTenantAddress" class="form-label">Address</label>
                                                    <select class="form-select" name="selectEditTenantAddress" id="selectEditTenantAddress" required>
                                                      <option value=''>Choose Address</option>
                                                      <?php
                                                        $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_type = 'Residential' AND prop_status != 'Archived' ORDER BY prop_phase, prop_block, prop_lot");
                                                        if ($propertiesTable->num_rows > 0) {
                                                          while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                                                            $propertiesOwner = $propertiesTableRows['prop_owner'];
                                                            $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE ID = '$propertiesOwner'");
                                                            if ($residentsTable->num_rows > 0) {
                                                              while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                                                                ?>
                                                                  <option value='<?php echo $propertiesTableRows['ID']?>' <?php if($propertiesTableRows['ID'] == $residentsTableTenantsRows['res_address']){echo "selected";}?>>
                                                                    <?php 
                                                                      if ($residentsTableRows['res_middle'] != NULL) {
                                                                        echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname']." ".$residentsTableRows['res_firstname'][0].".";
                                                                      }
                                                                      else {
                                                                        echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];
                                                                      }
                                                                    ?> - Phase <?php echo $propertiesTableRows['prop_phase']?> Block <?php echo $propertiesTableRows['prop_block']?> Lot <?php echo $propertiesTableRows['prop_lot']?>
                                                                  </option>
                                                                <?php
                                                              }
                                                            }
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
                                                    <label for="inputEditTenantFirstname" class="form-label">Firstname</label>
                                                    <input type="text" class="form-control" id="inputEditTenantFirstname" name="inputEditTenantFirstname" value="<?php echo $residentsTableTenantsRows['res_firstname'];?>" required>
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="inputEditTenantMiddlename" class="form-label">Middlename</label>
                                                    <input type="text" class="form-control" id="inputEditTenantMiddlename" name="inputEditTenantMiddlename" value="<?php echo $residentsTableTenantsRows['res_middle'];?>">
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="inputEditTenantLastname" class="form-label">Lastname</label>
                                                    <input type="text" class="form-control" id="inputEditTenantLastname" name="inputEditTenantLastname" value="<?php echo $residentsTableTenantsRows['res_lastname'];?>" required>
                                                  </div>
                                                  <div class="col-3">
                                                    <label for="selectEditTenantSuffix" class="form-label">Suffix</label>
                                                    <select class="form-select" name="selectEditTenantSuffix" id="selectEditTenantSuffix">
                                                      <option value=''>None</option>
                                                      <option value='Jr' <?php if($residentsTableTenantsRows['res_suffix'] == 'Jr'){echo "selected";}?>>Jr</option>
                                                      <option value='Sr' <?php if($residentsTableTenantsRows['res_suffix'] == 'Sr'){echo "selected";}?>>Sr</option>
                                                      <option value='III' <?php if($residentsTableTenantsRows['res_suffix'] == 'III'){echo "selected";}?>>III</option>
                                                      <option value='IV' <?php if($residentsTableTenantsRows['res_suffix'] == 'IV'){echo "selected";}?>>IV</option>
                                                    </select>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="mb-3">
                                                <div class="row">
                                                  <div class="col-4">
                                                    <label for="inputEditTenantBirthplace" class="form-label">Birthplace</label>
                                                    <input type="text" class="form-control" id="inputEditTenantBirthplace" name="inputEditTenantBirthplace" value="<?php echo $residentsTableTenantsRows['res_birthplace'];?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="inputEditTenantBirthdate" class="form-label">Birthdate</label>
                                                    <input type="date" class="form-control" id="inputEditTenantBirthdate" name="inputEditTenantBirthdate" value="<?php echo date("Y-m-d", strtotime($residentsTableTenantsRows['res_birthdate']));;?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="selectEditTenantSex" class="form-label">Sex</label>
                                                    <select class="form-select" name="selectEditTenantSex" id="selectEditTenantSex">
                                                      <option value='Male' <?php if($residentsTableTenantsRows['res_sex'] == 'Male'){echo "selected";}?>>Male</option>
                                                      <option value='Female' <?php if($residentsTableTenantsRows['res_sex'] == 'Female'){echo "selected";}?>>Female</option>
                                                    </select>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="mb-3">
                                                <div class="row">
                                                  <div class="col-4">
                                                    <label for="selectEditTenantCivilStatus" class="form-label">Civil Status</label>
                                                    <select class="form-select" name="selectEditTenantCivilStatus" id="selectEditTenantCivilStatus">
                                                      <option value='Single' <?php if($residentsTableTenantsRows['res_civil_status'] == 'Single'){echo "selected";}?>>Single</option>
                                                      <option value='Married' <?php if($residentsTableTenantsRows['res_civil_status'] == 'Married'){echo "selected";}?>>Married</option>
                                                      <option value='Divorced' <?php if($residentsTableTenantsRows['res_civil_status'] == 'Divorced'){echo "selected";}?>>Divorced</option>
                                                      <option value='Widowed' <?php if($residentsTableTenantsRows['res_civil_status'] == 'Widowed'){echo "selected";}?>>Widowed</option>
                                                    </select>
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="inputEditTenantReligion" class="form-label">Religion</label>
                                                    <input type="text" class="form-control" id="inputEditTenantReligion" name="inputEditTenantReligion" value="<?php echo $residentsTableTenantsRows['res_religion'];?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                  <label for="inputEditTenantOccupation" class="form-label">Occupation</label>
                                                    <input type="text" class="form-control" id="inputEditTenantOccupation" name="inputEditTenantOccupation" value="<?php echo $residentsTableTenantsRows['res_occupation'];?>" required>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="">
                                                <div class="row"> 
                                                  <div class="col-4">
                                                    <div class="d-flex">
                                                      <label for="inputEditTenantContactNumber1" class="form-label">Primary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                                                    </div>
                                                    <input type="text" class="form-control" id="inputEditTenantContactNumber1" name="inputEditTenantContactNumber1" pattern="^(09|\+639)\d{9}$" maxlength="11" placeholder="ex. 09123456789" value="<?php echo $residentsTableTenantsRows['res_contact_num_1'];?>" required>
                                                  </div>
                                                  <div class="col-4">
                                                    <div class="d-flex">
                                                      <label for="inputEditTenantContactNumber2" class="form-label">Secondary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                                                    </div>
                                                    <input type="text" class="form-control" id="inputEditTenantContactNumber2" name="inputEditTenantContactNumber2" pattern="^(09|\+639)\d{9}$" maxlength="11" placeholder="ex. 09123456789" value="<?php echo $residentsTableTenantsRows['res_contact_num_2'];?>">
                                                  </div>
                                                  <div class="col-4">
                                                    <label for="selectEditTenantResidentType" class="form-label">Resident Type</label>
                                                    <select class="form-select" name="selectEditTenantResidentType" id="selectEditTenantResidentType">
                                                      <option value='Owner' <?php if($residentsTableTenantsRows['res_type'] == 'Owner'){echo "selected";}?>>Owner</option>
                                                      <option value='Tenant' <?php if($residentsTableTenantsRows['res_type'] == 'Tenant'){echo "selected";}?>>Tenant</option>
                                                      <option value='Occupant' <?php if($residentsTableTenantsRows['res_type'] == 'Occupant'){echo "selected";}?>>Occupant</option>
                                                    </select>
                                                  </div>
                                                </div>
                                              </div>
                                          </div>
                                          <div class="modal-footer">
                                              <button type="submit" class="btn btn-primary" name="editTenant" id="editTenant">Update Details</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>

                          <!-- DELETE RESIDENT -->

                          <div class="modal fade" id="modalTenantDelete<?php echo $residentsTableTenantsRows['ID']?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="" method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete the records of <?php echo $residentsTableTenantsRows['res_firstname']." ".$residentsTableTenantsRows['res_lastname']?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="inputHiddenDeleteTenantID" value="<?php echo $residentsTableTenantsRows['ID']?>"/>
                                            <input type="hidden" name="inputHiddenDeleteTenantFirstname" value="<?php echo $residentsTableTenantsRows['res_firstname']?>"/>
                                            <input type="hidden" name="inputHiddenDeleteTenantLastname" value="<?php echo $residentsTableTenantsRows['res_lastname']?>"/>
                                            Are you sure that you want to delete the details of <?php echo $residentsTableTenantsRows['res_firstname']." ".$residentsTableTenantsRows['res_lastname']?> from the database?
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">No</button>
                                          <button type="submit" class="btn btn-primary" name="deleteTenant" id="deleteTenant">Yes</button>
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

            <div class="modal fade" id="modalAddNewTenant" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                  <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Adding New Tenant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-6">
                            <label for="inputTenantPhoto" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="inputTenantPhoto" name="inputTenantPhoto" required>
                          </div>
                          <div class="col-6">
                            <label for="selectTenantAddress" class="form-label">Address</label>
                            <select class="form-select" name="selectTenantAddress" id="selectTenantAddress" required>
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
                            <label for="inputTenantFirstname" class="form-label">Firstname</label>
                            <input type="text" class="form-control" id="inputTenantFirstname" name="inputTenantFirstname" required>
                          </div>
                          <div class="col-3">
                            <label for="inputTenantMiddlename" class="form-label">Middlename</label>
                            <input type="text" class="form-control" id="inputTenantMiddlename" name="inputTenantMiddlename">
                          </div>
                          <div class="col-3">
                            <label for="inputTenantLastname" class="form-label">Lastname</label>
                            <input type="text" class="form-control" id="inputTenantLastname" name="inputTenantLastname" required>
                          </div>
                          <div class="col-3">
                            <label for="selectTenantSuffix" class="form-label">Suffix</label>
                            <select class="form-select" name="selectTenantSuffix" id="selectTenantSuffix">
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
                            <label for="inputTenantBirthplace" class="form-label">Birthplace</label>
                            <input type="text" class="form-control" id="inputTenantBirthplace" name="inputTenantBirthplace" required>
                          </div>
                          <div class="col-4">
                            <label for="inputTenantBirthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="inputTenantBirthdate" name="inputTenantBirthdate" required>
                          </div>
                          <div class="col-4">
                            <label for="selectTenantSex" class="form-label">Sex</label>
                            <select class="form-select" name="selectTenantSex" id="selectTenantSex">
                              <option value='Male'>Male</option>
                              <option value='Female'>Female</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4">
                            <label for="selectTenantCivilStatus" class="form-label">Civil Status</label>
                            <select class="form-select" name="selectTenantCivilStatus" id="selectTenantCivilStatus">
                              <option value='Single'>Single</option>
                              <option value='Married'>Married</option>
                              <option value='Divorced'>Divorced</option>
                              <option value='Widowed'>Widowed</option>
                            </select>
                          </div>
                          <div class="col-4">
                            <label for="inputTenantReligion" class="form-label">Religion</label>
                            <input type="text" class="form-control" id="inputTenantReligion" name="inputTenantReligion" required>
                          </div>
                          <div class="col-4">
                          <label for="inputTenantOccupation" class="form-label">Occupation</label>
                            <input type="text" class="form-control" id="inputTenantOccupation" name="inputTenantOccupation" required>
                          </div>
                        </div>
                      </div>
                      <div class="">
                        <div class="row">
                          <div class="col-6">
                            <div class="d-flex">
                              <label for="inputTenantContactNumber1" class="form-label">Primary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                            </div>
                            <input type="text" class="form-control" id="inputTenantContactNumber1" name="inputTenantContactNumber1" pattern="^(09|\+639)\d{9}$" maxlength="11" placeholder="ex. 09123456789" required>
                          </div>
                          <div class="col-6">
                            <div class="d-flex">
                              <label for="inputTenantContactNumber2" class="form-label">Secondary Contact Number</label><span class="bi bi-info-circle fw-bold fs-5 ms-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="The contact number cannot contain letters, special characters, decimal numbers and limited to 11 digits only.">
                            </div>
                            <input type="text" class="form-control" id="inputTenantContactNumber2" name="inputTenantContactNumber2" pattern="^(09|\+639)\d{9}$" maxlength="11" placeholder="ex. 09123456789">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addNewTenant" id="addNewTenant">Add</button>
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