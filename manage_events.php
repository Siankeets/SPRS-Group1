<?php
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

// Rewards actions
elseif($act === 'listRewards') echo json_encode($data['rewards']);
elseif($act === 'getReward' && isset($_GET['id'])) echo json_encode(findById($data['rewards'], $_GET['id']));
elseif($act === 'delReward' && isset($_GET['id'])) {
  $data['rewards']=array_values(array_filter($data['rewards'],fn($r)=>$r['id']!==$_GET['id']));
  save($data,$file); echo json_encode(['message'=>'Reward deleted']);
}
elseif($act === 'saveReward'){
  $id = $_POST['id'] ?? '';
  $reward = ['id'=>$id ?: uniqid('rw_'), 'title'=>$_POST['title'], 'description'=>$_POST['description'], 'points'=>$_POST['points']];
  if($id) updateById($data['rewards'],$reward); else $data['rewards'][]=$reward;
  save($data,$file); echo json_encode(['message'=>'Reward saved']); exit;
}
else echo json_encode(['error'=>'Invalid action']);

function save($d,$f){file_put_contents($f,json_encode($d,JSON_PRETTY_PRINT));}
function findById($arr,$id){foreach($arr as $a) if($a['id']===$id) return $a; return [];}
function updateById(&$arr,$new){foreach($arr as &$a) if($a['id']===$new['id']){$a=$new;return;}}
?>