<?php
session_start();
include ('../db_connect-test.php');

header('Content-Type: application/json');
// $file = __DIR__.'/events.json'; // json read/write, databaseless testing. delete after event and rewards are working.
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
elseif($act === 'listRewards'){ //revised for admin
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

elseif($_GET['action'] == 'getReward'){  //revised for admin 
  $id = $_GET['id']; // pull the id value from the hidden field

  $stmt= mysqli_prepare($conn, "SELECT * FROM rewards WHERE rewardID = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $reward = mysqli_fetch_assoc($result);
  echo json_encode($reward);
}

elseif($act === 'delReward' && isset($_GET['id'])) { //revised for admin
  $id = $_GET['id'];

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

elseif($act === 'saveReward'){ //revised for admin 
  // get data from form with POST
  $id = $_POST['id'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $points = $_POST['points'];

  if (empty($id)){ // hidden id field means if you save a new reward, it detects this no id and starts this section
    //new reward , Line 67 "insert data type SSI -> s = string,text,var(char), etc + i = integer)"
    $stmt = mysqli_prepare($conn,"INSERT INTO rewards (rewardName, rewardDescription, rewardPoints) VALUES (?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssi", $title, $description, $points);
    mysqli_stmt_execute($stmt);
    $message = "Reward added successfully!";
  }
  else {
    //update new reward, get the POST'ed hidden $id value when edit is clicked in rewards management page.
    $stmt = mysqli_prepare($conn, "UPDATE rewards SET rewardName = ?, rewardDescription = ?, rewardPoints = ? WHERE rewardID = ?");
    mysqli_stmt_bind_param($stmt, "ssii", $title, $description, $points, $id);
    mysqli_stmt_execute($stmt);
    $message = "Reward updated successfully!";
  }
  echo json_encode(['message'=>$message]);
  exit;
}
else echo json_encode(['error'=>'Invalid action']);

function save($d,$f){file_put_contents($f,json_encode($d,JSON_PRETTY_PRINT));}
function findById($arr,$id){foreach($arr as $a) if($a['id']===$id) return $a; return [];}
function updateById(&$arr,$new){foreach($arr as &$a) if($a['id']===$new['id']){$a=$new;return;}}
?>