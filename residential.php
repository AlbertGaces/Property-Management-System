<?php 
  include "include/config.php";
  $active = 'residential';

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
  }

  if(isset($_POST['addNewResidentialProperty'])){
    $residentialOwner = $_POST['selectResidentialOwner'];

    $residentialPhase = $_POST['selectResidentialPhase'];
    $residentialBlock = $_POST['inputResidentialBlock'];
    $residentialLot = $_POST['inputResidentialLot'];

    $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_phase = '$residentialPhase' AND prop_block = '$residentialBlock' AND prop_lot = '$residentialLot'");
    if ($propertiesTable->num_rows > 0) {
      $_SESSION['message']= "<strong>Existing Property!</strong> This property address is already existing in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      $propertiesTable = $conn->query("INSERT INTO `tbl_properties`(`ID`, `prop_owner`, `prop_type`, `prop_phase`, `prop_block`, `prop_lot`, `prop_status`, `prop_description`) VALUES (NULL,'$residentialOwner','Residential','$residentialPhase','$residentialBlock','$residentialLot','','')");

      $_SESSION['message']= "<strong>New Residential Property Added!</strong> The property Phase $residentialPhase Block $residentialBlock Lot $residentialLot has been added into the properties list.";
      $_SESSION['message-type'] = "alert-success";
    }
  }

  if(isset($_POST['editResidentialProperty'])){
    $residentialID = $_POST['inputHiddenEditResidentialID'];
    $residentialOwner = $_POST['selectEditResidentialOwner'];
    $residentialType = $_POST['selectEditResidentialType'];

    $propertiesTableUpdate = $conn->query("UPDATE `tbl_properties` SET `prop_owner`='$residentialOwner',`prop_type`='$residentialType' WHERE ID = '$residentialID'");

    $_SESSION['message']= "<strong>Property Details Updated!</strong>";
    $_SESSION['message-type'] = "alert-success";
  }

  if(isset($_POST['deleteResidentialProperty'])){
    $deleteResidentialID = $_POST['inputHiddenDeleteResidentialPropertyID'];
    $currentTime = date("Y-m-d H:i:s");

    $propertiesTable = $conn->query("UPDATE `tbl_properties` SET `prop_status`='Archived', `prop_date`='$currentTime' WHERE ID = '$deleteResidentialID'");

    $_SESSION['message'] = "<strong>Commercial Property Deleted!</strong>";
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

    <title>Residential Properties</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Residential Properties</h4>
          </div>
          <div>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddNewResidentialProperty">
                Add New Property
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
                <th>Address</th>
                <th>Owner</th>
                <th>Occupants</th>
                <th>Actions</th>
              </thead>
              <tbody>
                <?php
                  $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_type = 'Residential' AND prop_status != 'Archived'");
                  if ($propertiesTable->num_rows > 0) {
                    while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle"><?php echo $propertiesTableRows['ID'];?></td>
                          <td class="align-middle">Phase <?php echo $propertiesTableRows['prop_phase'];?> Block <?php echo $propertiesTableRows['prop_block'];?> Lot <?php echo $propertiesTableRows['prop_lot'];?></td>
                          <td class="align-middle">
                            <?php 
                              $propertyOwner = $propertiesTableRows['prop_owner'];
                              $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE ID = '$propertyOwner'");
                              if ($residentsTable->num_rows > 0) {
                                while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                                  if ($residentsTableRows['res_middle'] != NULL) {
                                    echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname']." ".$residentsTableRows['res_middle'][0].".";
                                  }
                                  else {
                                    echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];
                                  }
                                }
                              }
                            ?>
                          </td>
                          <td class="align-middle">
                            <div class="d-flex justify-content-between">
                              <?php
                                $propertyID = $propertiesTableRows['ID'];
                                echo $residentsTableCount = $conn->query("SELECT COUNT(*) as prop_occupants FROM tbl_residents WHERE res_address = '$propertyID'")->fetch_assoc()['prop_occupants'];
                                if ($residentsTableCount > 0) {
                                    ?>
                                        <button class="btn btn-sm btn-warning py-1" title="View" data-bs-toggle="modal" data-bs-target="#modalResidentialView<?php echo $propertiesTableRows['ID']?>"><i class="bi bi-eye-fill"></i></button>
                                    <?php
                                }
                              ?>
                          </div>
                          </td>
                          <td class="align-middle">
                            <button class="btn btn-sm btn-primary py-1" title="Edit" data-bs-toggle="modal" data-bs-target="#modalResidentialEdit<?php echo $propertiesTableRows['ID']?>"><i class="bi bi-pen"></i></button>
                            <?php
                              $propertyID = $propertiesTableRows['ID'];
                              $residentsTableCount = $conn->query("SELECT COUNT(*) as prop_occupants FROM tbl_residents WHERE res_address = '$propertyID'")->fetch_assoc()['prop_occupants'];
                              if ($residentsTableCount == 0) {
                                  ?>
                                    <button class="btn btn-sm btn-danger py-1" title="Delete" data-bs-toggle="modal" data-bs-target="#modalResidentialDelete<?php echo $propertiesTableRows['ID']?>"><i class="bi bi-trash"></i></button>
                                  <?php
                              }
                            ?>
                          </td>
                        </tr>

                        <!-- VIEW OCCUPANTS RESIDENTIAL PROPERTY -->

                        <div class="modal fade" id="modalResidentialView<?php echo $propertiesTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-lg modal-dialog-centered">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title">Occupants of Phase <?php echo $propertiesTableRows['prop_phase']?> Block <?php echo $propertiesTableRows['prop_block']?> Lot <?php echo $propertiesTableRows['prop_lot']?></h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="row text-center mb-2">
                                      <div class="col-1 fw-bold">
                                        #
                                      </div>
                                      <div class="col fw-bold">
                                        Type
                                      </div>
                                      <div class="col fw-bold">
                                        Name
                                      </div>
                                      <div class="col fw-bold">
                                        Sex
                                      </div>
                                      <div class="col fw-bold">
                                        Contact Number
                                      </div>
                                    </div>
                                    <?php
                                      $propertyID = $propertiesTableRows['ID'];
                                      $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_address = '$propertyID' AND res_status != 'Archived'");
                                      if ($residentsTable->num_rows > 0) {
                                        $residentCount = 0;
                                        while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                                          $residentCount++;
                                          ?>
                                            <div class="row text-center">
                                              <div class="col-1">
                                                <?php echo $residentCount;?>
                                              </div>
                                              <div class="col">
                                                <?php echo $residentsTableRows['res_type'];?>
                                              </div>
                                              <div class="col">
                                                <?php 
                                                  if ($residentsTableRows['res_middle'] != NULL) {
                                                    echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname']." ".$residentsTableRows['res_middle'][0].".";
                                                  }
                                                  else {
                                                    echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];
                                                  }
                                                ?>
                                              </div>
                                              <div class="col">
                                                <?php echo $residentsTableRows['res_sex'];?>
                                              </div>
                                              <div class="col">
                                                <?php echo $residentsTableRows['res_contact_num_1'];?>
                                              </div>
                                            </div>
                                          <?php
                                        }
                                      }
                                    ?>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
                                  </div>
                              </div>
                          </div>
                        </div>

                        <!-- EDIT RESIDENTIAL PROPERTY -->

                        <div class="modal fade" id="modalResidentialEdit<?php echo $propertiesTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <form action="" method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Details of Phase <?php echo $propertiesTableRows['prop_phase']?> Block <?php echo $propertiesTableRows['prop_block']?> Lot <?php echo $propertiesTableRows['prop_lot']?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <input type="hidden" name="inputHiddenEditResidentialID" value="<?php echo $propertiesTableRows['ID']?>"/>
                                  <div class="">
                                    <div class="row">
                                      <div class="col">
                                        <label for="selectEditResidentialOwner" class="form-label">Property Owner</label>
                                        <select class="form-select" name="selectEditResidentialOwner" id="selectEditResidentialOwner">
                                          <?php
                                            $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_type = 'Owner' AND res_status != 'Archived'");
                                            if ($residentsTable->num_rows > 0) {
                                              while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                                                ?>
                                                  <option value='<?php echo $residentsTableRows['ID'];?>' <?php if($residentsTableRows['ID'] == $propertiesTableRows['prop_owner']){echo "selected";}?>><?php echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];?></option>
                                                <?php
                                              }
                                            }
                                            else {
                                              ?>
                                                <option value=''>No available Owners</option>
                                              <?php
                                            }
                                          ?>
                                        </select>
                                      </div>
                                      <div class="col">
                                        <label for="selectEditResidentialType" class="form-label">Property Type</label>
                                        <select class="form-select" name="selectEditResidentialType" id="selectEditResidentialType" 
                                          <?php 
                                            $propertyID = $propertiesTableRows['ID'];
                                            $residentsTableCount = $conn->query("SELECT COUNT(*) as prop_occupants FROM tbl_residents WHERE res_address = '$propertyID'")->fetch_assoc()['prop_occupants'];
                                            if ($residentsTableCount > 0) {
                                                echo "disabled";
                                            }
                                          ?>
                                        >
                                          <option value='Residential' <?php if($propertiesTableRows['prop_type'] == 'Residential'){echo "selected";}?>>Residential</option>
                                          <option value='Commercial' <?php if($propertiesTableRows['prop_type'] == 'Commercial'){echo "selected";}?>>Commercial</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="editResidentialProperty" id="editResidentialProperty">Update Details</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <!-- DELETE RESIDENTIAL PROPERTY -->

                        <div class="modal fade" id="modalResidentialDelete<?php echo $propertiesTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                  <form action="" method="POST">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Delete the property Phase <?php echo $propertiesTableRows['prop_phase']." Block ".$propertiesTableRows['prop_block']." Lot ".$propertiesTableRows['prop_lot']?></h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          <input type="hidden" name="inputHiddenDeleteResidentialPropertyID" value="<?php echo $propertiesTableRows['ID']?>"/>
                                          Are you sure that you want to delete the record of the property Phase <?php echo $propertiesTableRows['prop_phase']." Block ".$propertiesTableRows['prop_block']." Lot ".$propertiesTableRows['prop_lot'];?> from the database?
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">No</button>
                                        <button type="submit" class="btn btn-primary" name="deleteResidentialProperty" id="deleteResidentialProperty">Yes</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                        </div>
                      <?php
                    }
                  }
                ?>
              </tbody>
            </table>

            <div class="modal fade" id="modalAddNewResidentialProperty" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Adding New Residential Property</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <div class="row">
                          <div class="col">
                            <label for="selectResidentialOwner" class="form-label">Property Owner</label>
                            <select class="form-select" name="selectResidentialOwner" id="selectResidentialOwner">
                              <?php
                                $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_type = 'Owner' AND res_status != 'Archived'");
                                if ($residentsTable->num_rows > 0) {
                                  while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                                    ?>
                                      <option value='<?php echo $residentsTableRows['ID'];?>'>
                                        <?php 
                                          if ($residentsTableRows['res_middle'] != NULL) {
                                            echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname']." ".$residentsTableRows['res_middle'][0].".";
                                          }
                                          else {
                                            echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];
                                          }
                                        ?>
                                      </option>
                                    <?php
                                  }
                                }
                                else {
                                  ?>
                                    <option value=''>No available Owners</option>
                                  <?php
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="">
                        <div class="row">
                          <div class="col-4">
                            <label for="selectResidentialPhase" class="form-label">Phase</label>
                            <select class="form-select" name="selectResidentialPhase" id="selectResidentialPhase">
                              <option value='1'>Phase 1</option>
                              <option value='2a'>Phase 2a</option>
                              <option value='2b'>Phase 2b</option>
                              <option value='3'>Phase 3</option>
                            </select>
                          </div>
                          <div class="col-4">
                            <label for="inputResidentialBlock" class="form-label">Block</label>
                            <input type="number" class="form-control" id="inputResidentialBlock" name="inputResidentialBlock" min='1' max='99' step="1" required>
                          </div>
                          <div class="col-4">
                            <label for="inputResidentialLot" class="form-label">Lot</label>
                            <input type="number" class="form-control" id="inputResidentialLot" name="inputResidentialLot" min='1' max='99' step="1" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addNewResidentialProperty" id="addNewResidentialProperty">Add</button>
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