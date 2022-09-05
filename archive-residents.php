<?php 
  include "include/config.php";
  $active = 'archive-residents';

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
  }

  if(isset($_POST['recoverResident'])){
    $recoverResidentID = $_POST['inputHiddenRecoverResidentID'];

    $residentsTable = $conn->query("UPDATE `tbl_residents` SET `res_status`='' WHERE ID = '$recoverResidentID'");

    $_SESSION['message'] = "<strong>Resident Information Recovered!</strong>";
    $_SESSION['message-type'] = "alert-success";
  }

  if(isset($_POST['logOut'])){
    unset($_SESSION['ID']);
    unset($_SESSION['name']);
    unset($_SESSION['loggedIn']);
    header("Location: index.php");
  }

  $residentsDeletion = $conn->query("SELECT * FROM tbl_residents WHERE res_status = 'Archived' AND res_date < now() - interval 30 DAY");
  if ($residentsDeletion->num_rows > 0) {  
      while ($residentsDeletionRows = $residentsDeletion->fetch_assoc()) {
          $ID = $residentsDeletionRows['ID'];
          $conn->query("DELETE FROM tbl_residents WHERE ID = '$ID'");
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

    <title>Archived Residents</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Archived Residents</h4>
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
                <th>Days Left</th>
                <th>ID</th>
                <th>Type</th>
                <th>Photo</th>
                <th>Lastname</th>
                <th>Firstname</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Contact Number</th>
                <th>Action</th>
              </thead>
              <tbody>
                <?php
                  $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_status = 'Archived'");
                  if ($residentsTable->num_rows > 0) {
                    while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle">
                            <?php
                              $currentDate = date("Y-m-d");
                              $deletionDate = date("Y-m-d",strtotime("+30 days",strtotime($residentsTableRows['res_date'])));
                              echo $daysLeft = abs(strtotime($currentDate) - strtotime($deletionDate))/(60 * 60 * 24);
                              echo " Days";
                            ?>
                          </td>
                          <td class="align-middle"><?php echo $residentsTableRows['ID'];?></td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_type'];?></td>
                          <td class="align-middle"><img src="images/residents/<?php echo $residentsTableRows['res_photo'];?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;"></td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_lastname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_firstname'];?></td>
                          <td class="align-middle">
                            <?php 
                              $birthdate = new DateTime($residentsTableRows['res_birthdate']);
                              $currentTime = new DateTime();
                              $age = $currentTime->diff($birthdate);
                              echo $age->y;
                            ?>
                          </td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_sex'];?></td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_contact_num_1'];?></td>
                          <td class="align-middle">
                            <button class="btn btn-sm btn-success py-1" title="Recover" data-bs-toggle="modal" data-bs-target="#modalResidentRecover<?php echo $residentsTableRows['ID']?>"><i class="bi bi-arrow-counterclockwise"></i></button>
                          </td>
                        </tr>

                        <!-- RECOVER RESIDENT -->

                        <div class="modal fade" id="modalResidentRecover<?php echo $residentsTableRows['ID']?>" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                  <form action="" method="POST">
                                      <div class="modal-header">
                                          <h5 class="modal-title">Recovering...</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          <input type="hidden" name="inputHiddenRecoverResidentID" value="<?php echo $residentsTableRows['ID']?>"/>
                                          Do you wish to recover the record of <?php echo $residentsTableRows['res_firstname']." ".$residentsTableRows['res_lastname'];?> from the database?
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">No</button>
                                        <button type="submit" class="btn btn-primary" name="recoverResident" id="recoverResident">Yes</button>
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