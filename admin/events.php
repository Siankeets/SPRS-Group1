<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Manage Events — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
  :root {
    --accent-blue: #2563eb;
    --accent-hover: #1d4ed8;
  }

  body {
    font-family: 'Inter', sans-serif;
    background: url('images/bg.jpg') center/cover no-repeat fixed;
    margin: 0;
    color: #fff;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* Header */
  header {
    background: rgba(0,0,0,0.8);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 24px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.45);
  }
  header img {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    margin-right: 10px;
  }
  header h1 {
    font-size: 16px;
    color: #f2f6fb;
    margin: 0;
    font-weight: 600;
  }
  .header-left {
    display: flex;
    align-items: center;
  }

  .back-btn {
    background: var(--accent-blue);
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: 0.2s;
  }
  .back-btn:hover {
    background: var(--accent-hover);
  }

  /* Container */
  .container {
    max-width: 900px;
    width: 90%;
    margin: 80px auto;
    background: rgba(0, 0, 0, 0.55);
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    backdrop-filter: blur(10px);
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
  }
  button:hover { background: var(--accent-hover); }
  .delete { background: #dc2626; }
  .delete:hover { background: #b91c1c; }

  /* Table */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 25px;
    table-layout: fixed;
  }
  th, td {
    padding: 10px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    text-align: center;
    word-wrap: break-word;
  }
  th {
    background: rgba(255,255,255,0.1);
    font-weight: 600;
  }
  td button {
    margin: 2px;
    padding: 6px 10px;
    font-size: 13px;
  }

  /* Footer */
  footer {
    margin-top: auto;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    color: #e5e7eb;
    text-align: center;
    padding: 20px 12px;
    font-size: 14px;
    box-shadow: 0 -4px 10px rgba(0,0,0,0.3);
  }
  footer a {
    color: #60a5fa;
    text-decoration: none;
  }
  footer a:hover {
    text-decoration: underline;
  }

  /* Mobile Responsive */
  @media (max-width: 768px) {
    header {
      flex-direction: column;
      text-align: center;
      gap: 10px;
    }
    .container {
      margin: 60px 15px 30px;
      padding: 25px;
    }
    table, thead, tbody, th, td, tr {
      display: block;
    }
    thead { display: none; }
    tr {
      margin-bottom: 15px;
      background: rgba(255,255,255,0.05);
      border-radius: 10px;
      padding: 10px;
    }
    td {
      text-align: left;
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
    }
    td::before {
      content: attr(data-label);
      font-weight: 600;
      color: #93c5fd;
    }
    .back-btn {
      width: fit-content;
      margin: 0 auto;
    }
  }
</style>
</head>
<body>
  <header>
    <div class="header-left">
      <img src="images/logorewards.jpg" alt="SPRS Logo">
      <h1>Manage Events</h1>
    </div>
    <button class="back-btn" onclick="window.location.href='staff_index.php'">← Back to Dashboard</button>
  </header>

  <div class="container">
    <h2>Event Management</h2>
    <form id="eventForm">
      <input type="hidden" id="id" name="id">
      <label>Title</label>
      <input type="text" id="title" name="title" required>
      <label>Description</label>
      <textarea id="description" name="description" required></textarea>
      <label>Requirements</label>
      <textarea id="requirements" name="requirements" required></textarea>
      <label>Rewards</label>
      <input type="text" id="rewards" name="rewards" required>
      <button type="submit">Save Event</button>
    </form>

    <table>
      <thead>
        <tr>
          <th style="width:20%;">Title</th>
          <th style="width:25%;">Description</th>
          <th style="width:25%;">Requirements</th>
          <th style="width:15%;">Rewards</th>
          <th style="width:15%;">Actions</th>
        </tr>
      </thead>
      <tbody id="eventList"></tbody>
    </table>
  </div>

  <footer>
    Student Points Rewarding System <br>
    © 2025 All Rights Reserved <br>
    Contact: <a href="mailto:support@sprs.com">support@sprs.com</a>
  </footer>

<script>
const form=document.getElementById('eventForm');
const list=document.getElementById('eventList');

async function loadEvents(){
  const res=await fetch('manage_events.php?action=list');
  const data=await res.json();
  list.innerHTML='';
  data.forEach(e=>{
    const tr=document.createElement('tr');
    tr.innerHTML=`
      <td data-label="Title">${e.title}</td>
      <td data-label="Description">${e.description}</td>
      <td data-label="Requirements">${e.requirements}</td>
      <td data-label="Rewards">${e.rewards}</td>
      <td data-label="Actions">
        <button onclick="edit('${e.id}')">Edit</button>
        <button class="delete" onclick="del('${e.id}')">Delete</button>
      </td>`;
    list.appendChild(tr);
  });
}

form.addEventListener('submit',async e=>{
  e.preventDefault();
  const fd=new FormData(form);
  const res=await fetch('manage_events.php?action=save',{method:'POST',body:fd});
  const msg=await res.json();
  alert(msg.message);
  form.reset();
  loadEvents();
});

async function edit(id){
  const res=await fetch('manage_events.php?action=get&id='+id);
  const e=await res.json();
  document.getElementById('id').value=e.id;
  document.getElementById('title').value=e.title;
  document.getElementById('description').value=e.description;
  document.getElementById('requirements').value=e.requirements;
  document.getElementById('rewards').value=e.rewards;
}

async function del(id){
  if(!confirm('Delete event?'))return;
  const res=await fetch('manage_events.php?action=delete&id='+id);
  const msg=await res.json();
  alert(msg.message);
  loadEvents();
}

loadEvents();
</script>
</body>
</html>