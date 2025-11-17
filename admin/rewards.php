<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
include ('../db_connect.php');
// --- Ensure admin is logged in ---
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Rewards</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
  :root {
    --accent-blue: #2563eb;
    --accent-hover: #1d4ed8;
  }

  * { box-sizing: border-box; }

  body {
    font-family: 'Inter', system-ui;
    background: url('images/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    color: #fff;
    line-height: 1.4;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* HEADER */
  header {
    position: fixed;
    top: 0; left: 0; right: 0;
    background: #1e293b;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 6px 20px rgba(3, 7, 18, 0.45);
    z-index: 10;
  }

  header .left {
    display: flex;
    align-items: center;
    gap: 12px;
  }

  header img { width: 42px; height: 42px; border-radius: 10px; }
  header h1 { font-size: 16px; margin: 0; color: #f2f6fb; font-weight: 600; }

  .back-btn {
    background: var(--accent-blue);
    color: white;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s;
  }
  .back-btn:hover { background: var(--accent-hover); }

  /* MAIN CONTENT */
  .main-content {
    display: flex;
    gap: 25px;
    max-width: 1200px;
    margin: 120px auto 40px;
    width: 90%;
    flex-grow: 1;
  }

  /* FORM */
  .form-card {
    background: rgba(0,0,0,0.6);
    border-radius: 16px;
    padding: 25px;
    width: 35%;
    min-width: 300px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.35);
    height: fit-content;
  }

  .form-card h2 {
    text-align: center;
    margin-bottom: 20px;
    font-weight: 700;
  }

  label {
    font-weight: 600;
    margin-top: 10px;
    display: block;
  }

  input, textarea, select {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: none;
    margin-top: 6px;
    font-size: 14px;
  }

  textarea#description {
  height: 100px; /* fixed height */
  resize: none;  /* prevent manual resizing */
}

  button {
    background: var(--accent-blue);
    border: none;
    color: white;
    padding: 10px 14px;
    border-radius: 8px;
    margin-top: 14px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s ease;
    width: 100%;
  }
  button:hover { background: var(--accent-hover); }

  /* TABLE SECTION */
  .table-card {
    flex: 1;
    background: rgba(0,0,0,0.6);
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.35);
    display: flex;
    flex-direction: column;
  }

  .table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }

  .table-header h2 { margin: 0; font-weight: 700; }
  .search-bar { padding: 8px 12px; border-radius: 8px; border: none; font-size: 14px; width: 200px; }

  .table-container {
    overflow-y: auto;
    max-height: 420px;
    border-radius: 10px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
  }

  th, td {
    padding: 12px 10px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    vertical-align: middle;
  }

  th { background: rgba(255,255,255,0.15); font-weight: 600; }
  td { background: rgba(255,255,255,0.05); }

  td img {
    border-radius: 10px;
    object-fit: cover;
    transition: transform 0.2s ease;
    vertical-align: middle;
  }
  td img:hover { transform: scale(1.05); }

  td.description-cell {
    text-align: left;
    color: #d1d5db;
    white-space: normal;
    word-wrap: break-word;
    max-width: 500px;
  }

  .btn-action {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
    color: #fff;
    font-weight: 600;
    margin: 0 2px;
  }

  .edit-btn { background: #3b82f6; }
  .edit-btn:hover { background: #1d4ed8; }
  .delete-btn { background: #ef4444; }
  .delete-btn:hover { background: #b91c1c; }

  footer {
    width: 100%;
    background: #1e293b;
    text-align: center;
    padding: 20px 10px;
    margin-top: auto;
  }
  footer div { color: #93c5fd; }

  @media (max-width: 900px) {
    .main-content { flex-direction: column; }
    .form-card, .table-card { width: 100%; }
    .table-header { flex-direction: column; gap: 10px; }
  }

  @media (max-width: 600px) {
    header { flex-direction: column; gap: 10px; }
    header img { width: 36px; height: 36px; }
    header h1 { font-size: 15px; }
    .back-btn { font-size: 13px; padding: 7px 14px; }
  }
</style>
</head>

<body>
<header>
  <div class="left">
    <img src="images/logorewards.jpg" alt="SPRS Logo">
    <h1>Manage Rewards</h1>
  </div>
  <a href="staff_index.php" class="back-btn">⬅ Back to Dashboard</a>
</header>

<div class="main-content">
  <!-- Form -->
  <div class="form-card">
  <h2>Create / Edit Reward</h2>
  <form id="rewardForm">
    <input type="hidden" id="id" name="rewardID">
    <label>Reward Name</label>
    <input type="text" id="title" name="rewardName" required>
    
    <label>Description</label>
    <textarea id="description" name="rewardDescription" required></textarea>
    
<label>Points Required</label>
<input type="number" id="points" name="rewardPointsRequired" min="0" max="500" required>

    
    <label>Reward Type</label>
    <select id="type" name="rewardType" required>
      <option value="Ticket">Ticket</option>
      <option value="Supplies">Supplies</option>
      <option value="Tshirts">Tshirts</option>
      <option value="IDs">IDs</option>
    </select>
    
    <button type="submit">Save Reward</button>
  </form>
</div>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <h2>Existing Rewards</h2>
      <input type="text" id="searchBar" class="search-bar" placeholder="Search rewards...">
    </div>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Type</th>
            <th>Points</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="rewardList"></tbody>
      </table>
    </div>
  </div>
</div>

<footer>
  <div style="font-weight:700; font-size:16px; margin-bottom:12px;">Contact Us:</div>
  <div style="font-size:13px; display:flex; justify-content:center; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px; color:#93c5fd;">
    <div>📧 sprsystem@gmail.com</div>
    <span style="color:#ccc;">|</span>
    <div>📞 09123456789</div>
    <span style="color:#ccc;">|</span>
    <!-- Facebook -->
    <div style="display:flex; align-items:center; gap:6px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#93c5fd" viewBox="0 0 24 24">
        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 
                 3.657 9.128 8.438 9.878v-6.987H8.078v-2.89h2.36V9.797
                 c0-2.337 1.393-3.625 3.52-3.625.996 0 2.04.178 2.04.178v2.25
                 h-1.151c-1.137 0-1.492.705-1.492 1.43v1.716h2.54l-.406 2.89
                 h-2.134V21.9C18.343 21.128 22 16.991 22 12z"/>
      </svg>
      <a href="https://www.facebook.com/StudentPointRewardSystem" target="_blank" style="color:#93c5fd; text-decoration:none;">
        Student Point Reward System
      </a>
    </div>
  </div>
  <div style="font-size:13px;color:#fff;">
    © 2025 Student Point-Reward System. All rights reserved.
  </div>
</footer>

<script>
const form = document.getElementById('rewardForm');
const list = document.getElementById('rewardList');
const searchBar = document.getElementById('searchBar');

async function loadRewards() {
  const res = await fetch('manage_events.php?action=listRewards');
  const data = await res.json();
  renderRewards(data);
}

function renderRewards(data) {
  list.innerHTML = '';
  data.forEach(r => {
    let imgFile = {
      Ticket: 'pass.png',
      Supplies: 'ntbk.png',
      Tshirts: 'tshirt.png',
      IDs: 'id.png',
    }[r.rewardType] || 'default.png';

    let typeColor = {
      Ticket: '#3b82f6',
      Supplies: '#22c55e',
      Tshirts: '#f59e0b',
      IDs: '#a855f7',

    }[r.rewardType] || '#64748b';

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td style="text-align:left;">
        <div style="display:flex; align-items:center; gap:10px;">
          <img src="images/${imgFile}" alt="${r.rewardType}" style="width:45px;height:45px;">
          <div style="font-weight:600;font-size:15px;">${r.rewardName}</div>
        </div>
      </td>
      <td class="description-cell">${r.rewardDescription}</td>
      <td>
        <span style="
          display:inline-block;
          background:${typeColor};
          color:white;
          padding:4px 10px;
          border-radius:12px;
          font-size:13px;
          font-weight:600;
        ">${r.rewardType}</span>
      </td>
      <td>${r.rewardPointsRequired}</td>
      <td>
        <button class="btn-action edit-btn" onclick="editReward('${r.rewardID}')">Edit</button>
        <button class="btn-action delete-btn" onclick="delReward('${r.rewardID}')">Delete</button>
      </td>
    `;
    list.appendChild(tr);
  });
}

form.addEventListener('submit', async e => {
  e.preventDefault();
  
  const pointsValue = parseInt(document.getElementById('points').value);
  if (pointsValue < 0 || pointsValue > 500) {
    alert("Points must be between 0 and 500.");
    return;
  }

  const fd = new FormData(form);
  const res = await fetch('manage_events.php?action=saveReward', { method: 'POST', body: fd });
  const msg = await res.json();
  alert(msg.message);
  form.reset();
  loadRewards();
});

async function editReward(id){
  const res = await fetch('manage_events.php?action=getReward&id=' + id);
  const r = await res.json();
  document.getElementById('id').value = r.rewardID;
  document.getElementById('title').value = r.rewardName;
  document.getElementById('description').value = r.rewardDescription;
  document.getElementById('points').value = r.rewardPointsRequired;
  document.getElementById('type').value = r.rewardType;
}

async function delReward(id){
  if(!confirm("Delete this reward?")) return;
  const res = await fetch('manage_events.php?action=delReward&id='+id);
  const msg = await res.json();
  alert(msg.message);
  loadRewards();
}

searchBar.addEventListener('input', async () => {
  const query = searchBar.value.toLowerCase();
  const res = await fetch('manage_events.php?action=listRewards');
  const data = await res.json();
  const filtered = data.filter(r =>
    r.rewardName.toLowerCase().includes(query) ||
    r.rewardDescription.toLowerCase().includes(query)
  );
  renderRewards(filtered);
});

loadRewards();
</script>
</body>
</html>
