<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Rewards</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<style>
  :root {
    --accent-blue: #2563eb;
    --accent-hover: #1d4ed8;
  }

  body {
    font-family: 'Inter', sans-serif;
    background: url('bg.jpg') center/cover no-repeat fixed;
    margin: 0;
    color: #fff;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }

  /* HEADER */
  header {
    position: fixed;
    top: 0; left: 0; right: 0;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(10px);
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 100;
    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
  }

  header img {
    width: 42px; height: 42px;
    border-radius: 8px;
  }

  .header-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  header h1 {
    font-size: 18px;
    margin: 0;
    color: #f1f5f9;
    font-weight: 600;
  }

  .back-btn {
    background: var(--accent-blue);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
    font-size: 14px;
  }

  .back-btn:hover {
    background: var(--accent-hover);
  }

  /* CONTAINER */
  .container {
    max-width: 900px;
    margin: 120px auto 40px;
    background: rgba(0,0,0,0.55);
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    flex-grow: 1;
  }

  h2 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 700;
  }

  label {
    font-weight: 600;
    margin-top: 10px;
    display: block;
  }

  input, textarea {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: none;
    margin-top: 6px;
    font-size: 14px;
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
  }

  button:hover { background: var(--accent-hover); }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
    text-align: center;
  }

  th, td {
    padding: 12px 10px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }

  th {
    background: rgba(255,255,255,0.15);
    font-weight: 600;
  }

  td {
    background: rgba(255,255,255,0.05);
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

  /* FOOTER */
  footer {
    background: rgba(0,0,0,0.85);
    text-align: center;
    padding: 25px 10px;
    color: #cbd5e1;
    font-size: 14px;
    margin-top: auto;
    backdrop-filter: blur(8px);
  }

  footer p {
    margin: 5px 0;
  }

  footer a {
    color: #60a5fa;
    text-decoration: none;
  }

  footer a:hover {
    text-decoration: underline;
  }

  @media (max-width: 700px) {
    header {
      flex-direction: column;
      text-align: center;
      gap: 8px;
    }

    .container { 
      margin: 100px 15px 30px;
      padding: 20px;
    }

    .back-btn {
      font-size: 13px;
      padding: 7px 12px;
    }

    table, thead, tbody, th, td, tr {
      display: block;
      text-align: left;
    }

    th { display: none; }
    td {
      border: none;
      position: relative;
      padding-left: 50%;
      margin-bottom: 12px;
    }
    td::before {
      content: attr(data-label);
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      font-weight: 600;
      color: #93c5fd;
    }
  }
</style>
</head>

<body>
  <header>
    <div class="header-left">
      <img src="logorewards.jpg" alt="Logo">
      <h1>Manage Rewards</h1>
    </div>
    <button class="back-btn" onclick="window.location.href='index.php'">← Back to Dashboard</button>
  </header>

  <div class="container">
    <h2>Rewards Management</h2>

    <form id="rewardForm">
      <input type="hidden" id="id" name="id">
      <label>Reward Name</label>
      <input type="text" id="title" name="title" required>

      <label>Description</label>
      <textarea id="description" name="description" required></textarea>

      <label>Points Required</label>
      <input type="number" id="points" name="points" required>

      <button type="submit">Save Reward</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Points</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="rewardList"></tbody>
    </table>
  </div>

  <footer>
    <p><strong>Student Points Rewarding System</strong></p>
    <p>© 2025 All Rights Reserved</p>
    <p>Contact: <a href="mailto:support@sprs.com">support@sprs.com</a></p>
  </footer>

<script>
const form = document.getElementById('rewardForm');
const list = document.getElementById('rewardList');

async function loadRewards(){
  const res = await fetch('manage_events.php?action=listRewards');
  const data = await res.json();
  list.innerHTML = '';

  data.forEach(r => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td data-label="Name">${r.title}</td>
      <td data-label="Description">${r.description}</td>
      <td data-label="Points">${r.points}</td>
      <td data-label="Actions">
        <button class="btn-action edit-btn" onclick="editReward('${r.id}')">Edit</button>
        <button class="btn-action delete-btn" onclick="delReward('${r.id}')">Delete</button>
      </td>
    `;
    list.appendChild(tr);
  });
}

form.addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData(form);
  const res = await fetch('manage_events.php?action=saveReward', {
    method: 'POST',
    body: fd
  });
  const msg = await res.json();
  alert(msg.message);
  form.reset();
  loadRewards();
});

async function editReward(id){
  const res = await fetch('manage_events.php?action=getReward&id='+id);
  const r = await res.json();
  document.getElementById('id').value = r.id;
  document.getElementById('title').value = r.title;
  document.getElementById('description').value = r.description;
  document.getElementById('points').value = r.points;
}

async function delReward(id){
  if(!confirm("Delete this reward?")) return;
  const res = await fetch('manage_events.php?action=delReward&id='+id);
  const msg = await res.json();
  alert(msg.message);
  loadRewards();
}

loadRewards();
</script>
</body>
</html>
