<?php
session_start();
include ('../db_connect-test.php');

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

// elseif($act === 'get' && isset($_GET['id'])) echo json_encode(findById($data['events'], $_GET['id']));
elseif($act === 'get'){  //!WORKING!
  $id = $_GET['id']; // pull the id value from the hidden field using id attribute

  $stmt= mysqli_prepare($conn, "SELECT * FROM schoolevents WHERE eventID = ?"); //changed Id to ID
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
  // get data from form with POST using name attribute
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


// Rewards actions // manage rewards
elseif($act === 'listRewards'){ //!WORKING! 
  // pull all rewards from table and insert into $rewards array then echo it
  $query = "SELECT * FROM rewards";
  $result = mysqli_query($conn, $query);
  
  $rewards = [];
  while ($row = mysqli_fetch_assoc($result)){
      $rewards[] = $row;
  }
  echo json_encode($rewards);
  exit;
}

elseif($act === 'getReward'){  //!WORKING!
  $id = $_GET['id']; // pull the id value from the hidden field using id attribute

  $stmt= mysqli_prepare($conn, "SELECT * FROM rewards WHERE rewardID = ?"); //changed Id to ID
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $reward = mysqli_fetch_assoc($result);
    if ($reward) {
        echo json_encode($reward);
    } else {
        echo json_encode(['error' => 'Reward not found']);
    }
  exit;
}

elseif($act === 'delReward' && isset($_GET['id'])) { //!WORKING!
  $id = $_GET['id']; // pull the id value from the hidden field using id attribute

  $stmt= mysqli_prepare($conn, "DELETE FROM rewards WHERE rewardID = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);

  if (mysqli_stmt_affected_rows($stmt)> 0){
    echo json_encode(['message' => 'Reward deleted successfully!']);
  }
  else {
    echo json_encode(['message' => 'No reward found']);
  }
}

elseif($act === 'saveReward'){ //!WORKING! 
  // get data from form with POST using name attribute
  $id = $_POST['rewardID']; //changed Id to ID
  $title = $_POST['rewardName'];
  $description = $_POST['rewardDescription'];
  $points = $_POST['rewardPointsRequired'];

  if (empty($id)){ // no id detected = new reward
    //new reward , Line 67 "insert data type SSI -> s = string,text,var(char), etc + i = integer)"
    $stmt = mysqli_prepare($conn,"INSERT INTO rewards (rewardName, rewardDescription, rewardPointsRequired) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssi", $title, $description, $points);
    mysqli_stmt_execute($stmt);
    $message = "Reward added successfully!";
  }
  else {
    //update new reward, get the POST'ed hidden $id value when edit is clicked in rewards management page.
    $stmt = mysqli_prepare($conn, "UPDATE rewards SET rewardName = ?, rewardDescription = ?, rewardPointsRequired = ? WHERE rewardID = ?");
    mysqli_stmt_bind_param($stmt, "ssii", $title, $description, $points, $id);
    mysqli_stmt_execute($stmt);
    $message = "Reward updated successfully!";
  }
  echo json_encode(['message'=>$message]);
  exit;
}
else echo json_encode(['error'=>'Invalid action']);
?>