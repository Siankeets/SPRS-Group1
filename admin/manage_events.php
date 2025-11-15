<?php
session_start();
include ('../db_connect.php');

header('Content-Type: application/json');
$act = $_GET['action'] ?? ($_POST['action'] ?? '');

// Event actions
if($act === 'list'){ //!WORKING! 
  $query = "SELECT * FROM schoolevents";
  $result = mysqli_query($conn, $query);
  
  $events = [];
  while ($row = mysqli_fetch_assoc($result)){
      $events[] = $row;
  }
  echo json_encode($events);
  exit;
}

elseif($act === 'get'){  //!WORKING!
  $id = $_GET['id']; // pull the id value from the hidden field using id attribute

  $stmt= mysqli_prepare($conn, "SELECT * FROM schoolevents WHERE eventID = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $reward = mysqli_fetch_assoc($result);
    if ($reward) {
        echo json_encode($reward);
    } else {
        echo json_encode(['error' => 'Event not found']);
    }
  exit;
}

elseif($act === 'delete' && isset($_GET['id'])) { //!WORKING!
  $id = $_GET['id']; // pull the id value from the hidden field using id attribute

  $stmt= mysqli_prepare($conn, "DELETE FROM schoolevents WHERE eventID = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);

  if (mysqli_stmt_affected_rows($stmt)> 0){
    echo json_encode(['message' => 'Event deleted successfully!']);
  }
  else {
    echo json_encode(['message' => 'No event found']);
  }
} 

elseif($act === 'save'){ //!WORKING! 
  // get data from form with POST by refencing the name attributes
  $id = $_POST['eventID']; 
  $title = $_POST['eventName'];
  $description = $_POST['eventDescription'];
  $requirements = $_POST['eventRequirements'];
  $rewards = $_POST['eventRewards'];

  if (empty($id)){ // no id detected = new event
    //new event, rewards is just description of whats being given out so its string.
    $stmt = mysqli_prepare($conn,"INSERT INTO schoolevents (eventName, eventDescription, eventRequirements, eventRewards) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $requirements, $rewards);
    mysqli_stmt_execute($stmt);
    $message = "Event added successfully!";
  }
  else {
    //update new event, get the POST'ed hidden $id value when edit is clicked in rewards management page.
    $stmt = mysqli_prepare($conn, "UPDATE schoolevents SET eventName = ?, eventDescription = ?, eventRequirements = ?, eventRewards = ? WHERE eventID = ?");
    mysqli_stmt_bind_param($stmt, "ssssi", $title, $description, $requirements, $rewards, $id);
    mysqli_stmt_execute($stmt);
    $message = "Event updated successfully!";
  }
  echo json_encode(['message'=>$message]);
  exit;
}

// Rewards actions // manage rewards, editing this
elseif ($act === 'listRewards') { //!WORKING!
  $query = "SELECT * FROM rewards";
  $result = mysqli_query($conn, $query);
  $rewards = [];
  while ($row = mysqli_fetch_assoc($result)) {
      $rewards[] = $row;
  }
  echo json_encode($rewards);
  exit;
}

elseif ($act === 'getReward') { //!WORKING!
  $id = $_GET['id'];
  $stmt = mysqli_prepare($conn, "SELECT * FROM rewards WHERE rewardID = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $reward = mysqli_fetch_assoc($result);
  echo json_encode($reward ?: ['error' => 'Reward not found']);
  exit;
}

elseif ($act === 'delReward' && isset($_GET['id'])) { //!WORKING!
  $id = $_GET['id'];
  $stmt = mysqli_prepare($conn, "DELETE FROM rewards WHERE rewardID = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  echo json_encode(['message' => mysqli_stmt_affected_rows($stmt) > 0
    ? 'Reward deleted successfully!'
    : 'No reward found']);
  exit;
}

elseif ($act === 'saveReward') { //!UPDATED with rewardType
  $id = $_POST['rewardID'];
  $title = $_POST['rewardName'];
  $description = $_POST['rewardDescription'];
  $points = $_POST['rewardPointsRequired'];
  $type = $_POST['rewardType']; // new dropdown input

  if (empty($id)) {
    $stmt = mysqli_prepare($conn, 
      "INSERT INTO rewards (rewardName, rewardDescription, rewardPointsRequired, rewardType)
       VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssis", $title, $description, $points, $type);
    mysqli_stmt_execute($stmt);
    $message = "Reward added successfully!";
  } else {
    $stmt = mysqli_prepare($conn, 
      "UPDATE rewards 
       SET rewardName = ?, rewardDescription = ?, rewardPointsRequired = ?, rewardType = ?
       WHERE rewardID = ?");
    mysqli_stmt_bind_param($stmt, "ssisi", $title, $description, $points, $type, $id);
    mysqli_stmt_execute($stmt);
    $message = "Reward updated successfully!";
  }

  echo json_encode(['message' => $message]);
  exit;
}

// old json write/read for databaseless testing
// function save($d,$f){file_put_contents($f,json_encode($d,JSON_PRETTY_PRINT));}
// function findById($arr,$id){foreach($arr as $a) if($a['id']===$id) return $a; return [];}
// function updateById(&$arr,$new){foreach($arr as &$a) if($a['id']===$new['id']){$a=$new;return;}}
?>