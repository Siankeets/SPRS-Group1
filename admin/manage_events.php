<?php
session_start();
include ('../db_connect.php');

header('Content-Type: application/json');
// $file = __DIR__.'/events.json'; // old json read for databaseless testing
// if(!file_exists($file)) file_put_contents($file, json_encode(['events'=>[],'rewards'=>[]]));
// $data = json_decode(file_get_contents($file), true);
$act = $_GET['action'] ?? ($_POST['action'] ?? '');

// Event actions
if($act === 'list') echo json_encode($data['events']);
elseif($act === 'get' && isset($_GET['id'])) echo json_encode(findById($data['events'], $_GET['id']));
elseif($act === 'delete' && isset($_GET['id'])) {
  $data['events'] = array_values(array_filter($data['events'], fn($e)=>$e['id']!==$_GET['id']));
  save($data,$file); echo json_encode(['message'=>'Event deleted']);
}
elseif($act === 'save'){
  $id = $_POST['id'] ?? '';
  $event = ['id'=>$id ?: uniqid('ev_'), 'title'=>$_POST['title'], 'description'=>$_POST['description'], 'requirements'=>$_POST['requirements'], 'rewards'=>$_POST['rewards']];
  if($id) updateById($data['events'],$event); else $data['events'][]=$event;
  save($data,$file); echo json_encode(['message'=>'Event saved']); exit;
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