<?php 
  include "include/config.php";
  $active = 'about';

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

    <title>About</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">About</h4>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="text-center">
              <img src="images/logo-lg.png" alt="" class="w-25 h-25" style="object-fit: cover;">
              <br><br>
              <span class="lead fw-bold">Mahogany Villas Home Owners Association</span>
              <div class="text-center">
                <span class="bi bi-geo-alt-fill fs-5"></span>&emsp;
                <span class="fs-6">Mahogany Villas, Barangay Looc, Calamba City, Laguna, 4027 Calamba, Philippines</span>
              </div>
              <div class="text-center">
                <span class="bi bi-telephone-fill fs-5"></span>&emsp;
                <span class="fs-6">0919 614 0015</span>
              </div>
            </div>
            <br><br><br>
            <div class="row">
              <div class="text-center">
                <span class="bi bi-info-circle-fill fs-4"></span><br>
                <span class="fs-6">
                  Mahogany Villas is a semi-private subdivision located in Barangay Looc, Calamba City, Laguna. Mahogany Villas has over 400 households that are living there for almost 10 years. The cool climate, peaceful setting, and functional amenities offered at an affordable price make Mahogany Villas an absolute grand property. The Mahogany Villas has a homeowner association whose mission is to maintain the quality of the community and the value of the homes within it. A homeowner’s association, or HOA, is a legally incorporated organization of the homeowners in a subdivision, whose mission is to maintain the quality of the community and the value of the homes within it. Mahogany Villas HOA is composing of President, Vice-President, Secretary, Treasurer, and Board of Directors, who are often originally assigned to market, and manage several administrative works including the legalities, monthly contributions, and census in the subdivision.
                </span>
              </div>
            </div>
            <br><br><br>
            <div class="row text-center">
              <h3>Home Owners Officers</h3>
            </div>
            <br>
            <div class="row justify-content-center">
              <div class="col-3">
                <div class="card w-100">
                  <?php
                    $adminsTablePresident = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'President' AND admin_status != 'Archived' LIMIT 1");
                    if ($adminsTablePresident->num_rows > 0) {
                      $adminsTablePresidentRow = $adminsTablePresident->fetch_assoc();
                      ?>
                        <img src="images/residents/<?php echo $adminsTablePresidentRow['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px;">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTablePresidentRow['admin_firstname']." ".$adminsTablePresidentRow['admin_lastname'];?></h5>
                          <h6 class="card-title">President</h6>
                        </div>
                      <?php
                    }
                    else {
                      ?>
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">President</h6>
                        </div>
                      <?php
                    }
                  ?>
                </div>
              </div>
            </div>
            <br>
            <div class="row justify-content-center">
              <div class="col-3">
                <div class="card w-100">
                  <?php
                    $adminsTableVicePresident = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'Vice President' AND admin_status != 'Archived' LIMIT 1");
                    if ($adminsTableVicePresident->num_rows > 0) {
                      $adminsTableVicePresidentRow = $adminsTableVicePresident->fetch_assoc();
                      ?>
                        <img src="images/residents/<?php echo $adminsTableVicePresidentRow['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTableVicePresidentRow['admin_firstname']." ".$adminsTableVicePresidentRow['admin_lastname'];?></h5>
                          <h6 class="card-title">Vice President</h6>
                        </div>
                      <?php
                    }
                    else {
                      ?>
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">Vice President</h6>
                        </div>
                      <?php
                    }
                  ?>
                </div>
              </div>
            </div>
            <br>
            <div class="row justify-content-center">
              <div class="col-3">
                <div class="card w-100">
                  <?php
                    $adminsTableSecretary = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'Secretary' AND admin_status != 'Archived' LIMIT 1");
                    if ($adminsTableSecretary->num_rows > 0) {
                      $adminsTableSecretaryRow = $adminsTableSecretary->fetch_assoc();
                      ?>
                        <img src="images/residents/<?php echo $adminsTableSecretaryRow['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTableSecretaryRow['admin_firstname']." ".$adminsTableSecretaryRow['admin_lastname'];?></h5>
                          <h6 class="card-title">Secretary</h6>
                        </div>
                      <?php
                    }
                    else {
                      ?>
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">Secretary</h6>
                        </div>
                      <?php
                    }
                  ?>
                </div>
              </div>
            </div>
            <br>
            <div class="row row-cols-5 g-2">
              <div class="col">
                <?php
                  $adminsTableBOD1 = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'BOD' AND admin_status != 'Archived' LIMIT 1");
                  if ($adminsTableBOD1->num_rows > 0) {
                    $adminsTableBOD1Row = $adminsTableBOD1->fetch_assoc();
                    ?>
                      <div class="card w-100">
                        <img src="images/residents/<?php echo $adminsTableBOD1Row['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTableBOD1Row['admin_firstname']." ".$adminsTableBOD1Row['admin_lastname'];?></h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                  else {
                    ?>
                      <div class="card w-100">
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                ?>
              </div>
              <div class="col">
                <?php
                  $adminsTableBOD2 = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'BOD' AND admin_status != 'Archived' LIMIT 1 OFFSET 1");
                  if ($adminsTableBOD2->num_rows > 0) {
                    $adminsTableBOD2Row = $adminsTableBOD2->fetch_assoc();
                    ?>
                      <div class="card w-100">
                        <img src="images/residents/<?php echo $adminsTableBOD2Row['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTableBOD2Row['admin_firstname']." ".$adminsTableBOD2Row['admin_lastname'];?></h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                  else {
                    ?>
                      <div class="card w-100">
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                ?>
              </div>
              <div class="col">
                <?php
                  $adminsTableBOD3 = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'BOD' AND admin_status != 'Archived' LIMIT 1 OFFSET 2");
                  if ($adminsTableBOD3->num_rows > 0) {
                    $adminsTableBOD3Row = $adminsTableBOD3->fetch_assoc();
                    ?>
                      <div class="card w-100">
                        <img src="images/residents/<?php echo $adminsTableBOD3Row['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTableBOD3Row['admin_firstname']." ".$adminsTableBOD3Row['admin_lastname'];?></h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                  else {
                    ?>
                      <div class="card w-100">
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                ?>
              </div>
              <div class="col">
                <?php
                  $adminsTableBOD4 = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'BOD' AND admin_status != 'Archived' LIMIT 1 OFFSET 3");
                  if ($adminsTableBOD4->num_rows > 0) {
                    $adminsTableBOD4Row = $adminsTableBOD4->fetch_assoc();
                    ?>
                      <div class="card w-100">
                        <img src="images/residents/<?php echo $adminsTableBOD4Row['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTableBOD4Row['admin_firstname']." ".$adminsTableBOD4Row['admin_lastname'];?></h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                  else {
                    ?>
                      <div class="card w-100">
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                ?>
              </div>
              <div class="col">
                <?php
                
                  $adminsTableBOD5 = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'BOD' AND admin_status != 'Archived' LIMIT 1 OFFSET 4");
                  if ($adminsTableBOD5->num_rows > 0) {
                    $adminsTableBOD5Row = $adminsTableBOD5->fetch_assoc();
                    ?>
                      <div class="card w-100">
                        <img src="images/residents/<?php echo $adminsTableBOD5Row['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTableBOD5Row['admin_firstname']." ".$adminsTableBOD5Row['admin_lastname'];?></h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                  else {
                    ?>
                      <div class="card w-100">
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">Board of Director</h6>
                        </div>
                      </div>
                    <?php
                  }
                ?>
              </div>
            </div>
            <br>
            <div class="row justify-content-center">
              <div class="col-3">
                <div class="card w-100">
                  <?php
                    $adminsTableOfficeSecretary = $conn->query("SELECT * FROM tbl_admins WHERE admin_position = 'Office Secretary' AND admin_status != 'Archived' LIMIT 1");
                    if ($adminsTableOfficeSecretary->num_rows > 0) {
                      $adminsTableOfficeSecretaryRow = $adminsTableOfficeSecretary->fetch_assoc();
                      ?>
                        <img src="images/residents/<?php echo $adminsTableOfficeSecretaryRow['admin_photo'];?>" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text"><?php echo $adminsTableOfficeSecretaryRow['admin_firstname']." ".$adminsTableOfficeSecretaryRow['admin_lastname'];?></h5>
                          <h6 class="card-title">Office Secretary</h6>
                        </div>
                      <?php
                    }
                    else {
                      ?>
                        <img src="images/vacant-position.jpeg" class="card-img-top" style="object-fit: cover; max-height: 200px">
                        <div class="card-body text-center">
                          <h5 class="card-text">To Be Announced...</h5>
                          <h6 class="card-title">Office Secretary</h6>
                        </div>
                      <?php
                    }
                  ?>
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