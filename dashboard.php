<?php 
  include "include/config.php";
  $active = 'dashboard';

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

    <title>Dashboard</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4>Dashboard</h4>
          </div>
          <h6 id="liveClock"></h6>
        </div>
        <div class="row row-cols-5 g-3">
          <div class="col">
            <div class="bg-dark-75 p-2 rounded border border-2 text-light">
              <div class="inner">
                <h3>
                  <?php
                    echo $conn->query("SELECT COUNT(*) AS owner_count FROM tbl_residents WHERE res_type = 'Owner' AND res_status != 'Archived'")->fetch_assoc()['owner_count'];
                  ?>
                </h3>
                <p class="m-0">Owners</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="bg-dark-75 p-2 rounded border border-2 text-light">
              <div class="inner">
                <h3>
                  <?php
                    echo $conn->query("SELECT COUNT(*) AS tenant_count FROM tbl_residents WHERE res_type = 'Tenant' AND res_status != 'Archived'")->fetch_assoc()['tenant_count'];
                  ?>
                </h3>
                <p class="m-0">Tenants</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="bg-dark-75 p-2 rounded border border-2 text-light">
              <div class="inner">
                <h3>
                  <?php
                    echo $conn->query("SELECT COUNT(*) AS occupant_count FROM tbl_residents WHERE res_type = 'Occupant' AND res_status != 'Archived'")->fetch_assoc()['occupant_count'];
                  ?>
                </h3>
                <p class="m-0">Occupants</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="bg-dark-75 p-2 rounded border border-2 text-light">
              <div class="inner">
                <h3>
                  <?php
                    $unitsCount = $conn->query("SELECT COUNT(*) AS units_count FROM tbl_properties WHERE prop_status != 'Archived' AND prop_owner != ''")->fetch_assoc()['units_count'];
                    echo $unitsCount;
                  ?>
                </h3>
                <p class="m-0">Occupied Units</p>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="bg-dark-75 p-2 rounded border border-2 text-light">
              <div class="inner">
                <h3>
                  <?php
                    $unitsCount = $conn->query("SELECT COUNT(*) AS units_count FROM tbl_properties WHERE prop_status != 'Archived' AND prop_owner != ''")->fetch_assoc()['units_count'];
                    echo 1018 - $unitsCount;
                  ?>
                </h3>
                <p class="m-0">Vacant Units</p>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-4">
        <div class="row row-cols-3 g-2 d-flex justify-content-evenly">
          <div class="col">
            <h5 class="text-center">Residents</h5>
            <canvas id="residentsChart"></canvas>
          </div>
          <div class="col">
            <h5 class="text-center">Properties</h5>
            <canvas id="propertiesChart"></canvas>
          </div>
        </div>
      </div>
    </main>
    
    <script src="js\jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/af-2.3.7/b-2.1.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.1/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/sr-1.0.1/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js\script.js"></script>
    <script>
      const residentsChartData = {
        labels: [
          'Owners',
          'Tenants',
          'Occupants'
        ],
        datasets: [{
          label: 'Home Owners',
          data: [
            <?php echo $conn->query("SELECT COUNT(*) AS owners_count FROM tbl_residents WHERE res_type = 'Owner' AND res_status != 'Archived'")->fetch_assoc()["owners_count"];?>,
            <?php echo $conn->query("SELECT COUNT(*) AS tenants_count FROM tbl_residents WHERE res_type = 'Tenant' AND res_status != 'Archived'")->fetch_assoc()["tenants_count"];?>,
            <?php echo $conn->query("SELECT COUNT(*) AS occupants_count FROM tbl_residents WHERE res_type = 'Occupant' AND res_status != 'Archived'")->fetch_assoc()["occupants_count"];?>
          ],
          backgroundColor: [
            'orange',
            'rgb(54, 162, 235)',
            'rgb(255, 99, 132)'
          ],
          hoverOffset: 4
        }]
      };

      const residentsChartConfig = {
        type: 'pie',
        data: residentsChartData,
      };
      
      const propertiesChartData = {
        labels: [
          'Occupied',
          'Vacant'
        ],
        datasets: [{
          label: 'Home Owners',
          data: [
            <?php
              $unitsCount = $conn->query("SELECT COUNT(*) AS units_count FROM tbl_properties WHERE prop_status != 'Archived' AND prop_owner != ''")->fetch_assoc()['units_count'];
              echo $unitsCount;
            ?>,
            <?php
              $unallocatedUnits = $conn->query("SELECT COUNT(*) AS unit_count FROM tbl_properties WHERE prop_status = 'Archived'")->fetch_assoc()['unit_count'];
              echo 1018-$unallocatedUnits;
            ?>
          ],
          backgroundColor: [
            'orange',
            'rgb(54, 162, 235)'
          ],
          hoverOffset: 4
        }]
      };

      const propertiesChartConfig = {
        type: 'pie',
        data: propertiesChartData,
      };
    </script>
    <script>
      const residentsChart = new Chart(
        document.getElementById('residentsChart'),
        residentsChartConfig
      );

      const propertiesChart = new Chart(
        document.getElementById('propertiesChart'),
        propertiesChartConfig
      );
    </script>
    <script>
      $(document).ready(function() {
          setInterval(liveClock, 1000);
      });

      function liveClock() {
          $.ajax({
              url: 'include/liveclock.php',
              success: function(data) {
                  $('#liveClock').html(data);
              },
          });
      }
    </script>
  </body>
</html>
