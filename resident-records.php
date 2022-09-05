<?php 
  include "include/config.php";
  $active = 'resident-records';

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

    <title>Resident Records</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Resident Records</h4>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <table class="datatable table table-hover responsive nowrap w-100">
              <thead>
                <th>ID</th>
                <th>Type</th>
                <th>Photo</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Contact Number</th>
              </thead>
              <tbody>
                <?php
                  $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived'");
                  if ($residentsTable->num_rows > 0) {
                    while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                      ?> 
                        <tr>
                          <td class="align-middle"><?php echo $residentsTableRows['ID'];?></td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_type'];?></td>
                          <td class="align-middle"><img src="images/residents/<?php echo $residentsTableRows['res_photo'];?>" class="rounded" style="width: 50px; height: 50px; object-fit: cover;"></td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_lastname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_firstname'];?></td>
                          <td class="align-middle"><?php echo $residentsTableRows['res_middle'];?></td>
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
                        </tr>
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