<?php 
  include "include/config.php";
  $active = 'property-records';

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
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

    <title>Property Records</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Property Records</h4>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <table class="datatable table table-hover responsive nowrap w-100">
              <thead>
                <th>ID</th>
                <th>Address</th>
                <th>Owner</th>
                <th>Occupants</th>
                <th>Type</th>
              </thead>
              <tbody>
                <?php
                  $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE prop_status != 'Archived'");
                  if ($propertiesTable->num_rows > 0) {
                    while ($propertiesTableRows = $propertiesTable->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle"><?php echo $propertiesTableRows['ID'];?></td>
                          <td class="align-middle">Phase <?php echo $propertiesTableRows['prop_phase'];?> Block <?php echo $propertiesTableRows['prop_block'];?> Lot <?php echo $propertiesTableRows['prop_lot'];?></td>
                          <td class="align-middle">
                            <?php
                                $propertyOwnerID = $propertiesTableRows['prop_owner'];
                                $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE ID = '$propertyOwnerID' AND res_status != 'Archived'");
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
                                  if ($propertiesTableRows['prop_type'] == 'Residential') {
                                    $propertyID = $propertiesTableRows['ID'];
                                    echo $residentsTableCount = $conn->query("SELECT COUNT(*) as prop_occupants FROM tbl_residents WHERE res_address = '$propertyID' AND res_status != 'Archived'")->fetch_assoc()['prop_occupants'];
                                    if ($residentsTableCount > 0) {
                                        ?>
                                            <button class="btn btn-sm btn-warning py-1" title="View" data-bs-toggle="modal" data-bs-target="#modalViewOccupants<?php echo $propertiesTableRows['ID']?>"><i class="bi bi-eye-fill"></i></button>
                                        <?php
                                    }
                                  }
                                  else {
                                    echo "-";
                                  }
                                ?>
                            </div>
                          </td>
                          <td class="align-middle"><?php echo $propertiesTableRows['prop_type'];?></td>
                        </tr>

                        <div class="modal fade" id="modalViewOccupants<?php echo $propertiesTableRows['ID']?>" tabindex="-1" aria-hidden="true">
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
                      <?php
                    }
                  }
                ?>
              </tbody>
            </table>
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