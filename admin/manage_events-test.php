<?php
include ('../db_connect-test.php'); //testing db connection
session_start();

header('Content-Type: application/json');
$file = __DIR__.'/events.json';
if(!file_exists($file)) file_put_contents($file, json_encode(['events'=>[],'rewards'=>[]]));
$data = json_decode(file_get_contents($file), true);
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
elseif($act === 'listRewards'){ //edited for admin
  // pull all rewards from table
  $stmt = $pdo->query("SELECT * FROM rewards");
  $rewards = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($rewards);
  exit;
}

      // ($act === 'getReward' && isset($_GET['id'])) echo json_encode(findById($data['rewards'], $_GET['id']));
elseif($_GET['action'] == 'getReward'){  //edited for admin 
  $id = $_GET['id'];
  $stmt= $pdo->prepare("SELECT * FROM rewards WHERE rewardID = ?");
  $stmt->execute([$id]);
  $reward = $stmt->fetch([PDO::FETCH_ASSOC]);
  echo json_encode($reward);
}

elseif($act === 'delReward' && isset($_GET['id'])) { //edited for admin
  // $data['rewards']=array_values(array_filter($data['rewards'],fn($r)=>$r['id']!==$_GET['id']));
  // save($data,$file); echo json_encode(['message'=>'Reward deleted']);
  $id = $_GET['id'];
  $stmt= $pdo->prepare("DELETE FROM rewards WHERE rewardID = ?");
  $stmt->execute([$rewardID]);
  echo json_encode(['message' => 'Reward deleted successfully!']);
}

elseif($act === 'saveReward'){ //edited for admin 
  // add in a reward to  the table
  $id = $_POST['id'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $points = $_POST['points'];

  if (empty($id)){
    //new reward 
    $stmt = $pdo->prepare("INSERT INTO rewards (rewardName, rewardDescription, rewardPoints) VALUES (?,?,?)");
    $stmt->execute([$title, $description, $points]);
    $message = "Reward added successfully!";
  }
  else {
    $stmt = $pdo->prepare("UPDATE rewards SET rewardName = ?, rewardDescription = ?, rewardPoints = ? WHERE rewardID = ?");
    $stmt->execute([$title, $description, $points]);
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