<?php 
  include "include/config.php";
  $active = 'commercial';

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
  }

  if(isset($_POST['addNewCommercialProperty'])){
    $commercialOwner = $_POST['selectCommercialOwner'];
    $commercialDescription = $_POST['inputCommercialDescription'];

    $commercialPhase = $_POST['selectCommercialPhase'];
    $commercialBlock = $_POST['inputCommercialBlock'];
    $commercialLot = $_POST['inputCommercialLot'];

    $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_phase = '$commercialPhase' AND prop_block = '$commercialBlock' AND prop_lot = '$commercialLot'");
    if ($propertiesTable->num_rows > 0) {
      $_SESSION['message']= "<strong>Existing Property!</strong> This property address is already existing in the database.";
      $_SESSION['message-type'] = "alert-danger";
    }
    else {
      $propertiesTable = $conn->query("INSERT INTO `tbl_properties`(`ID`, `prop_owner`, `prop_type`, `prop_phase`, `prop_block`, `prop_lot`, `prop_status`, `prop_description`) VALUES (NULL,'$commercialOwner','Commercial','$commercialPhase','$commercialBlock','$commercialLot','','$commercialDescription')");

      $_SESSION['message']= "<strong>New Commercial Property Added!</strong> The property Phase $commercialPhase Block $commercialBlock Lot $commercialLot has been added into the properties list.";
      $_SESSION['message-type'] = "alert-success";
    }
  }

  if(isset($_POST['editCommercialProperty'])){
    $commercialID = $_POST['inputHiddenEditCommercialID'];
    $commercialOwner = $_POST['selectEditCommercialOwner'];
    $commercialType = $_POST['selectEditCommercialType'];
    $commercialDescription = $_POST['inputEditCommercialDescription'];

    $propertiesTableUpdate = $conn->query("UPDATE `tbl_properties` SET `prop_owner`='$commercialOwner',`prop_type`='$commercialType', `prop_description`='$commercialDescription' WHERE ID = '$commercialID'");

    $_SESSION['message']= "<strong>Property Details Updated!</strong>";
    $_SESSION['message-type'] = "alert-success";
  }

  if(isset($_POST['deleteCommercialProperty'])){
    $deleteCommercialID = $_POST['inputHiddenDeleteCommercialPropertyID'];
    $currentTime = date("Y-m-d H:i:s");

    $propertiesTable = $conn->query("UPDATE `tbl_properties` SET `prop_status`='Archived', `prop_date`='$currentTime' WHERE ID = '$deleteCommercialID'");

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

    <title>Commercial Properties</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Commercial Properties</h4>
          </div>
          <div>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddNewCommercialProperty">
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
                <th>Description</th>
                <th>Owner</th>
                <th>Actions</th>
              </thead>
              <tbody>
                <?php
                  $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_type = 'Commercial' AND prop_status != 'Archived'");
                  if ($propertiesTable->num_rows > 0) {
                    while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle"><?php echo $propertiesTableRows['ID'];?></td>
                          <td class="align-middle">Phase <?php echo $propertiesTableRows['prop_phase'];?> Block <?php echo $propertiesTableRows['prop_block'];?> Lot <?php echo $propertiesTableRows['prop_lot'];?></td>
                          <td><?php echo $propertiesTableRows['prop_description'];?></td>
                          <td class="align-middle">
                            <?php 
                              $propertyOwner = $propertiesTableRows['prop_owner'];
                              $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE ID = '$propertyOwner'");
                              if ($residentsTable->num_rows > 0) {
                                while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                                  echo $residentsTableRows['res_lastname'].", ",$residentsTableRows['res_firstname'];
                                }
                              }
                            ?>
                          </td>
                          </td>
                          <td class="align-middle">
                            <button class="btn btn-sm btn-primary py-1" title="Edit" data-bs-toggle="modal" data-bs-target="#modalCommercialEdit<?php echo $propertiesTableRows['ID']?>"><i class="bi bi-pen"></i></button>
                            <button class="btn btn-sm btn-danger py-1" title="Delete" data-bs-toggle="modal" data-bs-target="#modalCommercialDelete<?php echo $propertiesTableRows['ID']?>"><i class="bi bi-trash"></i></button>
                          </td>
                        </tr>

                        <!-- EDIT COMMERCIAL PROPERTY -->

                        <div class="modal fade" id="modalCommercialEdit<?php echo $propertiesTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <form action="" method="POST" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Details of Phase <?php echo $propertiesTableRows['prop_phase']?> Block <?php echo $propertiesTableRows['prop_block']?> Lot <?php echo $propertiesTableRows['prop_lot']?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <input type="hidden" name="inputHiddenEditCommercialID" value="<?php echo $propertiesTableRows['ID']?>"/>
                                  <div class="mb-3">
                                    <div class="row">
                                      <div class="col">
                                        <label for="selectEditCommercialOwner" class="form-label">Property Owner</label>
                                        <select class="form-select" name="selectEditCommercialOwner" id="selectEditCommercialOwner">
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
                                        <label for="selectEditCommercialType" class="form-label">Property Type</label>
                                        <select class="form-select" name="selectEditCommercialType" id="selectEditCommercialType">
                                          <option value='Residential' <?php if($propertiesTableRows['prop_type'] == 'Residential'){echo "selected";}?>>Residential</option>
                                          <option value='Commercial' <?php if($propertiesTableRows['prop_type'] == 'Commercial'){echo "selected";}?>>Commercial</option>
                                        </select>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="mb-3">
                                    <div class="row">
                                      <div class="col-12">
                                        <label for="inputEditCommercialDescription" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="inputEditCommercialDescription" id="inputEditCommercialDescription" value="<?php echo $propertiesTableRows['prop_description']?>" required>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" name="editCommercialProperty" id="editCommercialProperty">Update Details</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <!-- DELETE COMMERCIAL PROPERTY -->

                        <div class="modal fade" id="modalCommercialDelete<?php echo $propertiesTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                  <form action="" method="POST">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Delete the property Phase <?php echo $propertiesTableRows['prop_phase']." Block ".$propertiesTableRows['prop_block']." Lot ".$propertiesTableRows['prop_lot']?></h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          <input type="hidden" name="inputHiddenDeleteCommercialPropertyID" value="<?php echo $propertiesTableRows['ID']?>"/>
                                          Are you sure that you want to delete the record of the property Phase <?php echo $propertiesTableRows['prop_phase']." Block ".$propertiesTableRows['prop_block']." Lot ".$propertiesTableRows['prop_lot'];?> from the database?
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">No</button>
                                        <button type="submit" class="btn btn-primary" name="deleteCommercialProperty" id="deleteCommercialProperty">Yes</button>
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

            <div class="modal fade" id="modalAddNewCommercialProperty" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Adding New Commercial Property</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <div class="row">
                          <div class="col">
                            <label for="selectCommercialOwner" class="form-label">Property Owner</label>
                            <select class="form-select" name="selectCommercialOwner" id="selectCommercialOwner">
                              <?php
                                $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_type = 'Owner' AND res_status != 'Archived' ORDER BY res_lastname");
                                if ($residentsTable->num_rows > 0) {
                                  while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                                    ?>
                                      <option value='<?php echo $residentsTableRows['ID'];?>'><?php echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];?></option>
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
                      <div class="mb-3">
                        <div class="row">
                          <div class="col-4">
                            <label for="selectCommercialPhase" class="form-label">Phase</label>
                            <select class="form-select" name="selectCommercialPhase" id="selectCommercialPhase">
                              <option value='1'>Phase 1</option>
                              <option value='2a'>Phase 2a</option>
                              <option value='2b'>Phase 2b</option>
                              <option value='3'>Phase 3</option>
                            </select>
                          </div>
                          <div class="col-4">
                            <label for="inputCommercialBlock" class="form-label">Block</label>
                            <input type="number" class="form-control" id="inputCommercialBlock" name="inputCommercialBlock" min='1' max='99' step="1" required>
                          </div>
                          <div class="col-4">
                            <label for="inputCommercialLot" class="form-label">Lot</label>
                            <input type="number" class="form-control" id="inputCommercialLot" name="inputCommercialLot" min='1' max='99' step="1" required>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <label for="inputCommercialDescription" class="form-label">Description</label>
                          <input type="text" class="form-control" name="inputCommercialDescription" id="inputCommercialDescription" required>
                        </div>
                      </div> 
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="addNewCommercialProperty" id="addNewCommercialProperty">Add</button>
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