<?php 
  include "include/config.php";
  $active = 'messaging';

  if(!isset($_SESSION)){
    session_start();
  }

  if(!$_SESSION['loggedIn']){
    header("Location: index.php");
  }

  date_default_timezone_set('Asia/Manila');

  function itexmo($number,$message,$apicode,$password) {
    $ch = curl_init();
    $itexmo = array('1' => $number, '2' => $message, '3' => $apicode, 'passwd' => $password);
    curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($itexmo));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    return curl_exec ($ch);
    curl_close ($ch); 
  }

  // POWER INTERRUPTION

  if(isset($_POST['submitPowerInterruptionManual'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $recipientContactNumber = $_POST['inputPowerInterruptionManualNumber'];
    $startTime = date("g:i A", strtotime($_POST['startTimePowerInterruptionManual']));
    $endTime = date("g:i A", strtotime($_POST['endTimePowerInterruptionManual']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }

    $message = "$greetings!, this is the Mahogany Villas HOA. This is to inform you that there will be a Power Interruption that will happen today from $startTime to $endTime. We suggest that you charge up your devices on or before the stated time of interruption. Sorry for the inconvenience";
    $recipientNumbers=explode(" ",$recipientContactNumber);

    foreach($recipientNumbers as $numbers) {
      itexmo($numbers,$message,$apicode,$password);
    }

    $_SESSION['message'] = "<strong>Power Interruption Notification Distributed to the custom recipients!</strong>";
    $_SESSION['message-type'] = "alert-success";
  }

  if(isset($_POST['submitPowerInterruptionIndividual'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $checkedRecipients = $_POST['checkPowerInterruptionIndividual'];
    $startTime = date("g:i A", strtotime($_POST['startTimePowerInterruptionIndividual']));
    $endTime = date("g:i A", strtotime($_POST['endTimePowerInterruptionIndividual']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }

    if (isset($checkedRecipients)) {
      foreach ($checkedRecipients as $residentID){
        $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE ID = '$residentID'")->fetch_assoc();
        $residentName = $residentsTable['res_firstname']." ".$residentsTable['res_lastname']; 
        $residentNumber = $residentsTable['res_contact_num_1'];

        $message = "$greetings $residentName!, this is the Mahogany Villas HOA. This is to inform you that there will be a Power Interruption that will happen today from $startTime to $endTime. We suggest that you charge up your devices on or before the stated time of interruption. Sorry for the inconvenience";
        itexmo($residentNumber,$message,$apicode,$password);
      }
      $_SESSION['message'] = "<strong>Power Interruption Notification Distributed to the selected recipients!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else {
      $_SESSION['message'] = "<strong>There are no selected recipients!</strong>";
      $_SESSION['message-type'] = "alert-danger";
    }
  }

  if(isset($_POST['submitPowerInterruptionGroups'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $sendNotification = $_POST['radioPowerInterruptionGroups'];
    $startTime = date("g:i A", strtotime($_POST['startTimePowerInterruptionGroups']));
    $endTime = date("g:i A", strtotime($_POST['endTimePowerInterruptionGroups']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }
    
    if ($sendNotification == "Everyone") {
      $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived'");
      if ($residentsTable->num_rows > 0) {
        while ($residentsTableRows = $residentsTable->fetch_assoc()) {
          $name = $residentsTableRows['res_firstname']." ".$residentsTableRows['res_lastname'];
          $contactNumber = $residentsTableRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. This is to inform you that there will be a Power Interruption that will happen today from $startTime to $endTime. We suggest that you charge up your devices on or before the stated time of interruption. Sorry for the inconvenience";

          itexmo($contactNumber,$message,$apicode,$password);
        }
      }
      $_SESSION['message'] = "<strong>Power Interruption Notification Distributed to All Residents!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Owners") {
      $residentsTableOwners = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Owner'");
      if ($residentsTableOwners->num_rows > 0) {
        while ($residentsTableOwnersRows = $residentsTableOwners->fetch_assoc()) {
          $name = $residentsTableOwnersRows['res_firstname']." ".$residentsTableOwnersRows['res_lastname'];
          $contactNumber = $residentsTableOwnersRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. This is to inform you that there will be a Power Interruption that will happen today from $startTime to $endTime. We suggest that you charge up your devices on or before the stated time of interruption. Sorry for the inconvenience";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Power Interruption Notification Distributed to All Property Owners!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Tenants") {
      $residentsTableTenants = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Tenant'");
      if ($residentsTableTenants->num_rows > 0) {
        while ($residentsTableTenantsRows = $residentsTableTenants->fetch_assoc()) {
          $name = $residentsTableTenantsRows['res_firstname']." ".$residentsTableTenantsRows['res_lastname'];
          $contactNumber = $residentsTableTenantsRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. This is to inform you that there will be a Power Interruption that will happen today from $startTime to $endTime. We suggest that you charge up your devices on or before the stated time of interruption. Sorry for the inconvenience";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Power Interruption Notification Distributed to All Tenants!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Occupants") {
      $residentsTableOccupants = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Occupants'");
      if ($residentsTableOccupants->num_rows > 0) {
        while ($residentsTableOccupantsRows = $residentsTableOccupants->fetch_assoc()) {
          $name = $residentsTableOccupantsRows['res_firstname']." ".$residentsTableOccupantsRows['res_lastname'];
          $contactNumber = $residentsTableOccupantsRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. This is to inform you that there will be a Power Interruption that will happen today from $startTime to $endTime. We suggest that you charge up your devices on or before the stated time of interruption. Sorry for the inconvenience";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Power Interruption Notification Distributed to All Occupants!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
  }

  // WATER INTERRUPTION

  if(isset($_POST['submitWaterInterruptionManual'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $recipientContactNumber = $_POST['inputWaterInterruptionManualNumber'];
    $startTime = date("g:i A", strtotime($_POST['startTimeWaterInterruptionManual']));
    $endTime = date("g:i A", strtotime($_POST['endTimeWaterInterruptionManual']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }

    $message = "$greetings!, this is the Mahogany Villas HOA. This is to inform you that there will be a Water Interruption that will happen today from $startTime to $endTime. We suggest that you fill and stock up your water supply on or before the stated time of interruption. Sorry for the inconvenience";
    $recipientNumbers=explode(" ",$recipientContactNumber);

    foreach($recipientNumbers as $numbers) {
      itexmo($numbers,$message,$apicode,$password);
    }

    $_SESSION['message'] = "<strong>Water Interruption Notification Distributed to the custom recipients!</strong>";
    $_SESSION['message-type'] = "alert-success";
  }

  if(isset($_POST['submitWaterInterruptionIndividual'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $checkedRecipients = $_POST['checkWaterInterruptionIndividual'];
    $startTime = date("g:i A", strtotime($_POST['startTimeWaterInterruptionIndividual']));
    $endTime = date("g:i A", strtotime($_POST['endTimeWaterInterruptionIndividual']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }

    if (isset($checkedRecipients)) {
      foreach ($checkedRecipients as $residentID){
        $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE ID = '$residentID'")->fetch_assoc();
        $residentName = $residentsTable['res_firstname']." ".$residentsTable['res_lastname']; 
        $residentNumber = $residentsTable['res_contact_num_1'];

        $message = "$greetings $residentName!, this is the Mahogany Villas HOA. This is to inform you that there will be a Water Interruption that will happen today from $startTime to $endTime. We suggest that you fill and stock up your water supply on or before the stated time of interruption. Sorry for the inconvenience";
        itexmo($residentNumber,$message,$apicode,$password);
      }
      $_SESSION['message'] = "<strong>Water Interruption Notification Distributed to the selected recipients!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else {
      $_SESSION['message'] = "<strong>There are no selected recipients!</strong>";
      $_SESSION['message-type'] = "alert-danger";
    }
  }

  if(isset($_POST['submitWaterInterruptionGroups'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $sendNotification = $_POST['radioWaterInterruptionGroups'];
    $startTime = date("g:i A", strtotime($_POST['startTimeWaterInterruptionGroups']));
    $endTime = date("g:i A", strtotime($_POST['endTimeWaterInterruptionGroups']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }
    
    if ($sendNotification == "Everyone") {
      $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived'");
      if ($residentsTable->num_rows > 0) {
        while ($residentsTableRows = $residentsTable->fetch_assoc()) {
          $name = $residentsTableRows['res_firstname']." ".$residentsTableRows['res_lastname'];
          $contactNumber = $residentsTableRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. This is to inform you that there will be a Water Interruption that will happen today from $startTime to $endTime. We suggest that you fill and stock up your water supply on or before the stated time of interruption. Sorry for the inconvenience";

          itexmo($contactNumber,$message,$apicode,$password);
        }
      }
      $_SESSION['message'] = "<strong>Water Interruption Notification Distributed to All Residents!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Owners") {
      $residentsTableOwners = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Owner'");
      if ($residentsTableOwners->num_rows > 0) {
        while ($residentsTableOwnersRows = $residentsTableOwners->fetch_assoc()) {
          $name = $residentsTableOwnersRows['res_firstname']." ".$residentsTableOwnersRows['res_lastname'];
          $contactNumber = $residentsTableOwnersRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. This is to inform you that there will be a Water Interruption that will happen today from $startTime to $endTime. We suggest that you fill and stock up your water supply on or before the stated time of interruption. Sorry for the inconvenience";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Water Interruption Notification Distributed to All Property Owners!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Tenants") {
      $residentsTableTenants = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Tenant'");
      if ($residentsTableTenants->num_rows > 0) {
        while ($residentsTableTenantsRows = $residentsTableTenants->fetch_assoc()) {
          $name = $residentsTableTenantsRows['res_firstname']." ".$residentsTableTenantsRows['res_lastname'];
          $contactNumber = $residentsTableTenantsRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. This is to inform you that there will be a Water Interruption that will happen today from $startTime to $endTime. We suggest that you fill and stock up your water supply on or before the stated time of interruption. Sorry for the inconvenience";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Water Interruption Notification Distributed to All Tenants!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Occupants") {
      $residentsTableOccupants = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Occupants'");
      if ($residentsTableOccupants->num_rows > 0) {
        while ($residentsTableOccupantsRows = $residentsTableOccupants->fetch_assoc()) {
          $name = $residentsTableOccupantsRows['res_firstname']." ".$residentsTableOccupantsRows['res_lastname'];
          $contactNumber = $residentsTableOccupantsRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. This is to inform you that there will be a Water Interruption that will happen today from $startTime to $endTime. We suggest that you fill and stock up your water supply on or before the stated time of interruption. Sorry for the inconvenience";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Water Interruption Notification Distributed to All Occupants!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
  }

  // CUSTOM ANNOUCEMENT

  if(isset($_POST['submitCustomAnnouncementManual'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $recipientContactNumber = $_POST['inputcustomAnnouncementNumberManual'];
    $topic = $_POST['inputCustomAnnouncementTopicManual'];
    $details = $_POST['inputCustomAnnouncementDetailsManual'];
    $date = date("F j, Y", strtotime($_POST['inputCustomAnnouncementDateManual']));
    $time = date("g:i A", strtotime($_POST['inputCustomAnnouncementTimeManual']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }

    $message = "$greetings!, this is the Mahogany Villas HOA. It is about the $topic, $details. Event Time: $date $time";
    $recipientNumbers=explode(" ",$recipientContactNumber);

    foreach($recipientNumbers as $numbers) {
      itexmo($numbers,$message,$apicode,$password);
    }

    $_SESSION['message'] = "<strong>Custom Announcement about the $topic has been distributed to the custom recipients!</strong>";
    $_SESSION['message-type'] = "alert-success";
  }

  if(isset($_POST['submitCustomAnnouncementIndividual'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $checkedRecipients = $_POST['checkCustomAnnouncementIndividual'];
    $topic = $_POST['inputCustomAnnouncementTopicIndividual'];
    $details = $_POST['inputCustomAnnouncementDetailsIndividual'];
    $date = date("F j, Y", strtotime($_POST['inputCustomAnnouncementDateIndividual']));
    $time = date("g:i A", strtotime($_POST['inputCustomAnnouncementTimeIndividual']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }

    if (isset($checkedRecipients)) {
      foreach ($checkedRecipients as $residentID){
        $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE ID = '$residentID'")->fetch_assoc();
        $residentName = $residentsTable['res_firstname']." ".$residentsTable['res_lastname']; 
        $residentNumber = $residentsTable['res_contact_num_1'];

        $message = "$greetings $residentName!, this is the Mahogany Villas HOA. It is about the $topic, $details. Event Time: $date $time";
        itexmo($residentNumber,$message,$apicode,$password);
      }
      $_SESSION['message'] = "<strong>Custom Announcement about the $topic has been distributed to the selected recipients!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else {
      $_SESSION['message'] = "<strong>There are no selected recipients!</strong>";
      $_SESSION['message-type'] = "alert-danger";
    }
  }

  if(isset($_POST['submitCustomAnnouncementGroups'])){
    $apicode = "ST-AMIEL253756_MSAHL";
    $password ="nqdsg!u923";

    $sendNotification = $_POST['radioCustomAnnouncementGroups'];
    $topic = $_POST['inputCustomAnnouncementTopicGroups'];
    $details = $_POST['inputCustomAnnouncementDetailsGroups'];
    $date = date("F j, Y", strtotime($_POST['inputCustomAnnouncementDateGroups']));
    $time = date("g:i A", strtotime($_POST['inputCustomAnnouncementTimeGroups']));

    $hour = date('H', time());
    if($hour > 0 && $hour <= 11) {
      $greetings = "Good Morning";
    }
    else if($hour > 11 && $hour <= 16) {
      $greetings = "Good Afternoon";
    }
    else if($hour > 16 && $hour <= 23) {
      $greetings = "Good Evening";
    }
    
    if ($sendNotification == "Everyone") {
      $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived'");
      if ($residentsTable->num_rows > 0) {
        while ($residentsTableRows = $residentsTable->fetch_assoc()) {
          $name = $residentsTableRows['res_firstname']." ".$residentsTableRows['res_lastname'];
          $contactNumber = $residentsTableRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. It is about the $topic, $details. Event Time: $date $time";

          itexmo($contactNumber,$message,$apicode,$password);
        }
      }
      $_SESSION['message'] = "<strong>Custom Announcement about the $topic has been distributed to All Residents!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Owners") {
      $residentsTableOwners = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Owner'");
      if ($residentsTableOwners->num_rows > 0) {
        while ($residentsTableOwnersRows = $residentsTableOwners->fetch_assoc()) {
          $name = $residentsTableOwnersRows['res_firstname']." ".$residentsTableOwnersRows['res_lastname'];
          $contactNumber = $residentsTableOwnersRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. It is about the $topic, $details. Event Time: $date $time";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Custom Announcement about the $topic has been distributed to All Property Owners!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Tenants") {
      $residentsTableTenants = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Tenant'");
      if ($residentsTableTenants->num_rows > 0) {
        while ($residentsTableTenantsRows = $residentsTableTenants->fetch_assoc()) {
          $name = $residentsTableTenantsRows['res_firstname']." ".$residentsTableTenantsRows['res_lastname'];
          $contactNumber = $residentsTableTenantsRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. It is about the $topic, $details. Event Time: $date $time";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Custom Announcement about the $topic has been distributed to All Tenants!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
    else if ($sendNotification == "Occupants") {
      $residentsTableOccupants = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' AND res_type = 'Occupants'");
      if ($residentsTableOccupants->num_rows > 0) {
        while ($residentsTableOccupantsRows = $residentsTableOccupants->fetch_assoc()) {
          $name = $residentsTableOccupantsRows['res_firstname']." ".$residentsTableOccupantsRows['res_lastname'];
          $contactNumber = $residentsTableOccupantsRows['res_contact_num_1'];
          $message = "$greetings $name!, this is the Mahogany Villas HOA. It is about the $topic, $details. Event Time: $date $time";

          itexmo($contactNumber,$message,$apicode, $password);
        }
      }
      $_SESSION['message'] = "<strong>Custom Announcement about the $topic has been distributed to All Occupants!</strong>";
      $_SESSION['message-type'] = "alert-success";
    }
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

    <title>Messaging</title>
  </head>
  
  <body>
    <?php include "include/navigation.php";?>

    <main>
      <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center mb-3">
          <div class="me-auto">
            <h4 class="m-0">Messaging</h4>
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
        <div class="row row-cols-4 g-2 text-center">
          <div class="col">
            <div class="card p-3">
              <i class="bi bi-lightning-charge-fill fs-1 text-danger"></i>
              <div class="card-body">
                <h5 class="card-title">Power Interruption</h5>
                <p class="card-text">Send a power interruption notice to the residents across the entire subdivision.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPowerInterruptionChoices">
                  Choose Recipients
                </button>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card p-3">
              <i class="bi bi-droplet-half fs-1 text-danger"></i>
              <div class="card-body">
                <h5 class="card-title">Water Interruption</h5>
                <p class="card-text">Send a water interruption notice to the residents to certain phase of the subdivision.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalWaterInterruptionChoices">
                  Choose Recipients
                </button>
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card p-3">
              <i class="bi bi-gear-fill fs-1 text-primary"></i>
              <div class="card-body">
                <h5 class="card-title">Custom Announcement</h5>
                <p class="card-text">Send a custom announcement to the residents.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCustomAnnouncementChoices">
                  Choose Recipients
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- POWER INTERRUPTION -->

        <div class="modal fade" id="modalPowerInterruptionChoices" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Choose a method...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row row-cols-3 g-2">
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalPowerInterruptionManual">Manual</button>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalPowerInterruptionIndividual">Individual</button>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalPowerInterruptionGroups">Groups</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalPowerInterruptionManual" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Send Notification To...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col">
                      <label for="inputPowerInterruptionManualNumber" class="form-label">Contact Number</label>
                      <input type="text" class="form-control" id="inputPowerInterruptionManualNumber" name="inputPowerInterruptionManualNumber" required>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="startTimePowerInterruptionManual">Start Time</label>
                      <input type="time" class="form-control" id="startTimePowerInterruptionManual" name="startTimePowerInterruptionManual" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="endTimePowerInterruptionManual">End Time</label>
                      <input type="time" class="form-control" id="endTimePowerInterruptionManual" name="endTimePowerInterruptionManual" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalPowerInterruptionChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitPowerInterruptionManual" id="submitPowerInterruptionManual">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalPowerInterruptionIndividual" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-md modal-dialog-centered">
            <div class="modal-content">
              <form id="formPowerInterruptionIndividual" action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Send Notification To...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="startTimePowerInterruptionIndividual">Start Time</label>
                      <input type="time" class="form-control" id="startTimePowerInterruptionIndividual" name="startTimePowerInterruptionIndividual" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="endTimePowerInterruptionIndividual">End Time</label>
                      <input type="time" class="form-control" id="endTimePowerInterruptionIndividual" name="endTimePowerInterruptionIndividual" required>
                    </div>
                  </div>
                  <hr>
                  <div class="row text-center">
                    <div class="col-5 fw-bold">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkAllPowerInterruptionIndividual">
                        <label class="form-check-label" for="checkAllPowerInterruptionIndividual">
                          Name
                        </label>
                      </div>
                    </div>
                    <div class="col fw-bold">
                      Contact Number
                    </div>
                    <div class="col fw-bold">
                      Address
                    </div>
                  </div>
                  <?php
                    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' ORDER BY res_lastname");
                    if ($residentsTable->num_rows > 0){
                      while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                        ?>
                          <div class="row text-center">
                            <div class="col-5">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?php echo $residentsTableRows['ID']?>" id="checkPowerInterruptionIndividual<?php echo $residentsTableRows['ID']?>" name="checkPowerInterruptionIndividual[]">
                                <label class="form-check-label" for="checkPowerInterruptionIndividual<?php echo $residentsTableRows['ID']?>">
                                  <?php 
                                    if ($residentsTableRows['res_middle'] != NULL) {
                                      echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname']." ".$residentsTableRows['res_middle'][0].".";
                                    }
                                    else {
                                      echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];
                                    }
                                  ?>
                                </label>
                              </div>
                            </div>
                            <div class="col">
                              <?php echo $residentsTableRows['res_contact_num_1'];?>
                            </div>
                            <div class="col">
                              <?php 
                                $residentAddress = $residentsTableRows['res_address'];
                                $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE ID = '$residentAddress'")->fetch_assoc();
                                if ($propertiesTable != 0) {
                                  echo "Phase ".$propertiesTable['prop_phase']." Block ".$propertiesTable['prop_block']." Lot ".$propertiesTable['prop_lot'];
                                }
                                else {
                                  echo "-";
                                }
                              ?>
                            </div>
                          </div>
                        <?php
                      }
                    }
                    else {
                      ?>
                        <div class="row text-center">
                          <div class="col-12">
                            No Available Residents
                          </div>
                        </div>
                      <?php
                    }
                  ?>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalPowerInterruptionChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitPowerInterruptionIndividual" id="submitPowerInterruptionIndividual">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="modalPowerInterruptionGroups" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Send Notification To...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row row-cols-4">
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioPowerInterruptionGroups" id="radioPowerInterruptionGroupsEveryone" value="Everyone" required>
                        <label class="form-check-label" for="radioPowerInterruptionGroupsEveryone">Everyone</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioPowerInterruptionGroups" id="radioPowerInterruptionGroupsOwners" value="Owners" required>
                        <label class="form-check-label" for="radioPowerInterruptionGroupsOwners">Owners</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioPowerInterruptionGroups" id="radioPowerInterruptionGroupsTenants" value="Tenants" required>
                        <label class="form-check-label" for="radioPowerInterruptionGroupsTenants">Tenants</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioPowerInterruptionGroups" id="radioPowerInterruptionGroupsOccupants" value="Occupants" required>
                        <label class="form-check-label" for="radioPowerInterruptionGroupsOccupants">Occupants</label>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="startTimePowerInterruptionGroups">Start Time</label>
                      <input type="time" class="form-control" id="startTimePowerInterruptionGroups" name="startTimePowerInterruptionGroups" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="endTimePowerInterruptionGroups">End Time</label>
                      <input type="time" class="form-control" id="endTimePowerInterruptionGroups" name="endTimePowerInterruptionGroups" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalPowerInterruptionChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitPowerInterruptionGroups" id="submitPowerInterruptionGroups">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- WATER INTERRUPTION -->

        <div class="modal fade" id="modalWaterInterruptionChoices" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Choose a method...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row row-cols-3 g-2">
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalWaterInterruptionManual">Manual</button>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalWaterInterruptionIndividual">Individual</button>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalWaterInterruptionGroups">Groups</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalWaterInterruptionManual" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Send Notification To...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col">
                      <label for="inputWaterInterruptionManualNumber" class="form-label">Contact Number</label>
                      <input type="text" class="form-control" id="inputWaterInterruptionManualNumber" name="inputWaterInterruptionManualNumber" required>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="startTimeWaterInterruptionManual">Start Time</label>
                      <input type="time" class="form-control" id="startTimeWaterInterruptionManual" name="startTimeWaterInterruptionManual" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="endTimeWaterInterruptionManual">End Time</label>
                      <input type="time" class="form-control" id="endTimeWaterInterruptionManual" name="endTimeWaterInterruptionManual" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalWaterInterruptionChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitWaterInterruptionManual" id="submitWaterInterruptionManual">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalWaterInterruptionIndividual" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-md modal-dialog-centered">
            <div class="modal-content">
              <form id="formWaterInterruptionIndividual" action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Send Notification To...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="startTimeWaterInterruptionIndividual">Start Time</label>
                      <input type="time" class="form-control" id="startTimeWaterInterruptionIndividual" name="startTimeWaterInterruptionIndividual" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="endTimeWaterInterruptionIndividual">End Time</label>
                      <input type="time" class="form-control" id="endTimeWaterInterruptionIndividual" name="endTimeWaterInterruptionIndividual" required>
                    </div>
                  </div>
                  <hr>
                  <div class="row text-center">
                    <div class="col-5 fw-bold">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkAllWaterInterruptionIndividual">
                        <label class="form-check-label" for="checkAllWaterInterruptionIndividual">
                          Name
                        </label>
                      </div>
                    </div>
                    <div class="col fw-bold">
                      Contact Number
                    </div>
                    <div class="col fw-bold">
                      Address
                    </div>
                  </div>
                  <?php
                    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' ORDER BY res_lastname");
                    if ($residentsTable->num_rows > 0){
                      while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                        ?>
                          <div class="row text-center">
                            <div class="col-5">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?php echo $residentsTableRows['ID']?>" id="checkWaterInterruptionIndividual<?php echo $residentsTableRows['ID']?>" name="checkWaterInterruptionIndividual[]">
                                <label class="form-check-label" for="checkWaterInterruptionIndividual<?php echo $residentsTableRows['ID']?>">
                                  <?php 
                                    if ($residentsTableRows['res_middle'] != NULL) {
                                      echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname']." ".$residentsTableRows['res_middle'][0].".";
                                    }
                                    else {
                                      echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];
                                    }
                                  ?>
                                </label>
                              </div>
                            </div>
                            <div class="col">
                              <?php echo $residentsTableRows['res_contact_num_1'];?>
                            </div>
                            <div class="col">
                              <?php 
                                $residentAddress = $residentsTableRows['res_address'];
                                $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE ID = '$residentAddress'")->fetch_assoc();
                                if ($propertiesTable != 0) {
                                  echo "Phase ".$propertiesTable['prop_phase']." Block ".$propertiesTable['prop_block']." Lot ".$propertiesTable['prop_lot'];
                                }
                                else {
                                  echo "-";
                                }
                              ?>
                            </div>
                          </div>
                        <?php
                      }
                    }
                    else {
                      ?>
                        <div class="row text-center">
                          <div class="col-12">
                            No Available Residents
                          </div>
                        </div>
                      <?php
                    }
                  ?>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalWaterInterruptionChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitWaterInterruptionIndividual" id="submitWaterInterruptionIndividual">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="modalWaterInterruptionGroups" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Send Notification To...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row row-cols-4">
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioWaterInterruptionGroups" id="radioWaterInterruptionGroupsEveryone" value="Everyone" required>
                        <label class="form-check-label" for="radioWaterInterruptionGroupsEveryone">Everyone</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioWaterInterruptionGroups" id="radioWaterInterruptionGroupsOwners" value="Owners" required>
                        <label class="form-check-label" for="radioWaterInterruptionGroupsOwners">Owners</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioWaterInterruptionGroups" id="radioWaterInterruptionGroupsTenants" value="Tenants" required>
                        <label class="form-check-label" for="radioWaterInterruptionGroupsTenants">Tenants</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioWaterInterruptionGroups" id="radioWaterInterruptionGroupsOccupants" value="Occupants" required>
                        <label class="form-check-label" for="radioWaterInterruptionGroupsOccupants">Occupants</label>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-6">
                      <label class="form-label" for="startTimeWaterInterruptionGroups">Start Time</label>
                      <input type="time" class="form-control" id="startTimeWaterInterruptionGroups" name="startTimeWaterInterruptionGroups" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="endTimeWaterInterruptionGroups">End Time</label>
                      <input type="time" class="form-control" id="endTimeWaterInterruptionGroups" name="endTimeWaterInterruptionGroups" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalPowerInterruptionChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitWaterInterruptionGroups" id="submitWaterInterruptionGroups">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- CUSTOM ANNOUNCEMENT -->

        <div class="modal fade" id="modalCustomAnnouncementChoices" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Choose a method...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row row-cols-3 g-2">
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalCustomAnnouncementManual">Manual</button>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalCustomAnnouncementIndividual">Individual</button>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalCustomAnnouncementGroups">Groups</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalCustomAnnouncementManual" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Compose an announcement...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col">
                      <label for="inputcustomAnnouncementNumberManual" class="form-label">Contact Number</label>
                      <input type="text" class="form-control" id="inputcustomAnnouncementNumberManual" name="inputcustomAnnouncementNumberManual" required>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label class="form-label" for="inputCustomAnnouncementTopicManual">Topic</label>
                      <input type="text" class="form-control" id="inputCustomAnnouncementTopicManual" name="inputCustomAnnouncementTopicManual" maxlength="50" required>
                    </div>
                    <div class="col-12 mb-3">
                      <label class="form-label d-flex justify-content-between" for="inputCustomAnnouncementDetailsManual">Details<span id='textCounterManual' class="fw-bold"></span></label>
                      <textarea class="form-control" id="inputCustomAnnouncementDetailsManual" name="inputCustomAnnouncementDetailsManual" maxlength="400" required></textarea>
                    </div>
                  </div> 
                  <div class="row">
                    <div class="col-6 mb-3">
                      <label class="form-label" for="inputCustomAnnouncementDateManualManual">Date</label>
                      <input type="date" class="form-control" id="inputCustomAnnouncementDateManual" name="inputCustomAnnouncementDateManual" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="inputCustomAnnouncementTimeManualManual">Time</label>
                      <input type="time" class="form-control" id="inputCustomAnnouncementTimeManual" name="inputCustomAnnouncementTimeManual" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalCustomAnnouncementChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitCustomAnnouncementManual" id="submitCustomAnnouncementManual">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalCustomAnnouncementIndividual" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-md modal-dialog-centered">
            <div class="modal-content">
              <form id="formCustomAnnouncementIndividual" action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Compose an announcement...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label class="form-label" for="inputCustomAnnouncementTopicIndividual">Topic</label>
                      <input type="text" class="form-control" id="inputCustomAnnouncementTopicIndividual" name="inputCustomAnnouncementTopicIndividual" maxlength="50" required>
                    </div>
                    <div class="col-12 mb-3">
                      <label class="form-label d-flex justify-content-between" for="inputCustomAnnouncementDetailsIndividual">Details<span id='textCounterIndividual' class="fw-bold"></span></label>
                      <textarea class="form-control" id="inputCustomAnnouncementDetailsIndividual" name="inputCustomAnnouncementDetailsIndividual" maxlength="400" required></textarea>
                    </div>
                  </div> 
                  <div class="row">
                    <div class="col-6 mb-3">
                      <label class="form-label" for="inputCustomAnnouncementDateIndividual">Date</label>
                      <input type="date" class="form-control" id="inputCustomAnnouncementDateIndividual" name="inputCustomAnnouncementDateIndividual" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="inputCustomAnnouncementTimeIndividual">Time</label>
                      <input type="time" class="form-control" id="inputCustomAnnouncementTimeIndividual" name="inputCustomAnnouncementTimeIndividual" required>
                    </div>
                  </div>
                  <hr>
                  <div class="row text-center">
                    <div class="col-5 fw-bold">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="checkAllCustomAnnouncementIndividual">
                        <label class="form-check-label" for="checkAllCustomAnnouncementIndividual">
                          Name
                        </label>
                      </div>
                    </div>
                    <div class="col fw-bold">
                      Contact Number
                    </div>
                    <div class="col fw-bold">
                      Address
                    </div>
                  </div>
                  <?php
                    $residentsTable = $conn->query("SELECT * FROM tbl_residents WHERE res_status != 'Archived' ORDER BY res_lastname");
                    if ($residentsTable->num_rows > 0){
                      while ($residentsTableRows = $residentsTable->fetch_assoc()) {
                        ?>
                          <div class="row text-center">
                            <div class="col-5">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?php echo $residentsTableRows['ID']?>" id="checkCustomAnnouncementIndividual<?php echo $residentsTableRows['ID']?>" name="checkCustomAnnouncementIndividual[]">
                                <label class="form-check-label" for="checkCustomAnnouncementIndividual<?php echo $residentsTableRows['ID']?>">
                                  <?php 
                                    if ($residentsTableRows['res_middle'] != NULL) {
                                      echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname']." ".$residentsTableRows['res_middle'][0].".";
                                    }
                                    else {
                                      echo $residentsTableRows['res_lastname'].", ".$residentsTableRows['res_firstname'];
                                    }
                                  ?>
                                </label>
                              </div>
                            </div>
                            <div class="col">
                              <?php echo $residentsTableRows['res_contact_num_1'];?>
                            </div>
                            <div class="col">
                              <?php 
                                $residentAddress = $residentsTableRows['res_address'];
                                $propertiesTable = $conn->query("SELECT * FROM tbl_properties WHERE ID = '$residentAddress'")->fetch_assoc();
                                if ($propertiesTable != 0) {
                                  echo "Phase ".$propertiesTable['prop_phase']." Block ".$propertiesTable['prop_block']." Lot ".$propertiesTable['prop_lot'];
                                }
                                else {
                                  echo "-";
                                }
                              ?>
                            </div>
                          </div>
                        <?php
                      }
                    }
                    else {
                      ?>
                        <div class="row text-center">
                          <div class="col-12">
                            No Available Residents
                          </div>
                        </div>
                      <?php
                    }
                  ?>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalCustomAnnouncementChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitCustomAnnouncementIndividual" id="submitCustomAnnouncementIndividual">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="modalCustomAnnouncementGroups" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
              <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Compose an announcement...</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row row-cols-4">
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioCustomAnnouncementGroups" id="radioCustomAnnouncementGroupsEveryone" value="Everyone" required>
                        <label class="form-check-label" for="radioCustomAnnouncementGroupsEveryone">Everyone</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioCustomAnnouncementGroups" id="radioCustomAnnouncementGroupsOwners" value="Owners" required>
                        <label class="form-check-label" for="radioCustomAnnouncementGroupsOwners">Owners</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioCustomAnnouncementGroups" id="radioCustomAnnouncementGroupsTenants" value="Tenants" required>
                        <label class="form-check-label" for="radioCustomAnnouncementGroupsTenants">Tenants</label>
                      </div>
                    </div>
                    <div class="col d-flex justify-content-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="radioCustomAnnouncementGroups" id="radioCustomAnnouncementGroupsOccupants" value="Occupants" required>
                        <label class="form-check-label" for="radioCustomAnnouncementGroupsOccupants">Occupants</label>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-12 mb-3">
                      <label class="form-label" for="inputCustomAnnouncementTopicGroups">Topic</label>
                      <input type="text" class="form-control" id="inputCustomAnnouncementTopicGroups" name="inputCustomAnnouncementTopicGroups" maxlength="50" required>
                    </div>
                    <div class="col-12 mb-3">
                      <label class="form-label d-flex justify-content-between" for="inputCustomAnnouncementDetailsGroups">Details<span id='textCounterGroups' class="fw-bold"></span></label>
                      <textarea class="form-control" id="inputCustomAnnouncementDetailsGroups" name="inputCustomAnnouncementDetailsGroups" maxlength="400" required></textarea>
                    </div>
                  </div> 
                  <div class="row">
                    <div class="col-6 mb-3">
                      <label class="form-label" for="inputCustomAnnouncementDateGroups">Date</label>
                      <input type="date" class="form-control" id="inputCustomAnnouncementDateGroups" name="inputCustomAnnouncementDateGroups" required>
                    </div>
                    <div class="col-6">
                      <label class="form-label" for="inputCustomAnnouncementTimeGroups">Time</label>
                      <input type="time" class="form-control" id="inputCustomAnnouncementTimeGroups" name="inputCustomAnnouncementTimeGroups" required>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalCustomAnnouncementChoices">Back</button>
                  <button type="submit" class="btn btn-primary" name="submitCustomAnnouncementGroups" id="submitCustomAnnouncementGroups">Continue</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </main>

    

    
    
    <script src="js\jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/af-2.3.7/b-2.1.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.1/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/sr-1.0.1/datatables.min.js"></script>
    <script src="js\script.js"></script>
    <script>
        $(document).ready(function() {
          $("#formPowerInterruptionIndividual #checkAllPowerInterruptionIndividual").click(function() {
              $("#formPowerInterruptionIndividual input[type='checkbox']").prop('checked',this.checked);
          });

          $("#formWaterInterruptionIndividual #checkAllWaterInterruptionIndividual").click(function() {
              $("#formWaterInterruptionIndividual input[type='checkbox']").prop('checked',this.checked);
          });

          $("#formCustomAnnouncementIndividual #checkAllCustomAnnouncementIndividual").click(function() {
              $("#formCustomAnnouncementIndividual input[type='checkbox']").prop('checked',this.checked);
          });
        });

        var textCounterManual = document.getElementById ('textCounterManual'),
          textAreaManual = document.getElementById ('inputCustomAnnouncementDetailsManual');
          textCounterManual.innerHTML = textAreaManual.value.length;
          textAreaManual.addEventListener ('keyup', function (e) {
          textCounterManual.innerHTML = textAreaManual.value.length;
        });

        var textCounterIndividual = document.getElementById ('textCounterIndividual'),
          textAreaIndividual = document.getElementById ('inputCustomAnnouncementDetailsIndividual');
          textCounterIndividual.innerHTML = textAreaIndividual.value.length;
          textAreaIndividual.addEventListener ('keyup', function (e) {
          textCounterIndividual.innerHTML = textAreaIndividual.value.length;
        });

        var textCounterGroups = document.getElementById ('textCounterGroups'),
          textAreaGroups = document.getElementById ('inputCustomAnnouncementDetailsGroups');
          textCounterGroups.innerHTML = textAreaGroups.value.length;
          textAreaGroups.addEventListener ('keyup', function (e) {
          textCounterGroups.innerHTML = textAreaGroups.value.length;
        });
    </script>
  </body>
</html>