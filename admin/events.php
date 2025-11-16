<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
include('../db_connect.php');

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
<title>Manage Events</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root { --accent-blue: #2563eb; --accent-hover: #1d4ed8; }
* { box-sizing: border-box; }
body { font-family:'Inter',system-ui; background:url('images/bg.jpg') no-repeat center center fixed; background-size:cover; margin:0; color:#fff; line-height:1.4; min-height:100vh; display:flex; flex-direction:column; }

/* HEADER */
header { position:fixed; top:0; left:0; right:0; background:#1e293b; padding:12px 20px; display:flex; align-items:center; justify-content:space-between; box-shadow:0 6px 20px rgba(3,7,18,0.45); z-index:10; }
header .left { display:flex; align-items:center; gap:12px; }
header img { width:42px; height:42px; border-radius:10px; }
header h1 { font-size:16px; margin:0; color:#f2f6fb; font-weight:600; }
.back-btn { background:var(--accent-blue); color:white; text-decoration:none; padding:8px 16px; border-radius:6px; font-size:14px; font-weight:600; transition:0.2s; }
.back-btn:hover { background:var(--accent-hover); }

/* MAIN CONTENT */
.main-content { display:flex; gap:25px; max-width:1200px; margin:120px auto 40px; width:90%; flex-grow:1; }

/* FORM */
.form-card { background: rgba(0,0,0,0.6); border-radius:16px; padding:25px; width:35%; min-width:300px; box-shadow:0 8px 20px rgba(0,0,0,0.35); height: fit-content; }
.form-card h2 { text-align:center; margin-bottom:20px; font-weight:700; }
label { font-weight:600; margin-top:10px; display:block; }
input, textarea, select { width:100%; padding:10px; border-radius:8px; border:none; margin-top:6px; font-size:14px; }
textarea#description { height:100px; resize:none; }
button { background:var(--accent-blue); border:none; color:white; padding:10px 14px; border-radius:8px; margin-top:14px; cursor:pointer; font-weight:600; transition:0.2s ease; width:100%; }
button:hover { background:var(--accent-hover); }

/* TABLE */
.table-card { flex:1; background: rgba(0,0,0,0.6); border-radius:16px; padding:25px; box-shadow:0 8px 20px rgba(0,0,0,0.35); display:flex; flex-direction:column; }
.table-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; }
.table-header h2 { margin:0; font-weight:700; }
.search-bar { padding:8px 12px; border-radius:8px; border:none; font-size:14px; width:220px; }
.table-container { overflow-y:auto; max-height:420px; border-radius:10px; }
table { width:100%; border-collapse:collapse; text-align:center; }
th, td { padding:12px 10px; border-bottom:1px solid rgba(255,255,255,0.1); vertical-align:middle; }
th { background:rgba(255,255,255,0.15); font-weight:600; }
td { background:rgba(255,255,255,0.03); color:#e5e7eb; }
td.title-cell { text-align:left; font-weight:600; }
td.description-cell { text-align:left; color:#d1d5db; white-space:normal; word-wrap:break-word; max-width:500px; }
td.reward-type { font-weight:600; color:#fbbf24; } /* yellow highlight for reward type */
.btn-action { display:inline-block; padding:6px 10px; border-radius:6px; font-size:13px; color:#fff; font-weight:600; margin:0 2px; cursor:pointer; }
.edit-btn { background:#3b82f6; } .edit-btn:hover { background:#1d4ed8; }
.delete-btn { background:#ef4444; } .delete-btn:hover { background:#b91c1c; }

footer { width:100%; background:#1e293b; text-align:center; padding:20px 10px; margin-top:auto; }
footer div { color:#93c5fd; }

@media (max-width:900px){ .main-content{flex-direction:column;} .form-card,.table-card{width:100%;} .table-header{flex-direction:column;gap:10px;} }
@media (max-width:600px){ header{flex-direction:column;gap:10px;} header img{width:36px;height:36px;} header h1{font-size:15px;} .back-btn{font-size:13px;padding:7px 14px;} }
</style>
</head>
<body>
<header>
  <div class="left">
    <img src="images/logorewards.jpg" alt="SPRS Logo">
    <h1>Manage Events</h1>
  </div>
  <a href="staff_index.php" class="back-btn">⬅ Back to Dashboard</a>
</header>

<div class="main-content">
  <!-- Form -->
  <div class="form-card">
    <h2>Create / Edit Event</h2>
    <form id="eventForm">
      <input type="hidden" id="id" name="eventID">

      <label>Title</label>
      <input type="text" id="title" name="eventName" required>

      <label>Description</label>
      <textarea id="description" name="eventDescription" required></textarea>

     <label>Reward Type</label>
<select id="rewardType" name="rewardType" required>
  <option value="Points">Points</option>
  <option value="Certificates">Certificates</option>
  <option value="Vouchers">Vouchers</option>
</select>


      <label>Rewards</label>
      <input type="text" id="rewards" name="eventRewards" required placeholder="e.g. 50 points or 'Certificate'">

      <button type="submit">Save Event</button>
    </form>
  </div>

  <!-- Table -->
  <div class="table-card">
    <div class="table-header">
      <h2>Existing Events</h2>
      <input type="text" id="searchBar" class="search-bar" placeholder="Search events...">
    </div>
    <div class="table-container">
      <table>
<thead>
  <tr>
    <th style="width:20%;">Title</th>
    <th style="width:25%;">Description</th>
    <th style="width:10%;">Reward Type</th>
    <th style="width:10%;">Rewards</th>
    <th style="width:10%;">Registered</th>
    <th style="width:10%;">Attended</th>
    <th style="width:15%;">Actions</th>
  </tr>
</thead>

        <tbody id="eventList"></tbody>
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
  </div>
  <div style="font-size:13px;color:#fff;">© 2025 Student Point-Reward System. All rights reserved.</div>
</footer>

<script>
const form = document.getElementById('eventForm');
const list = document.getElementById('eventList');
const searchBar = document.getElementById('searchBar');

async function loadEvents() {
  try {
    const res = await fetch('manage_events.php?action=list');
    const data = await res.json();
    renderEvents(data || []);
  } catch (err) {
    console.error('Failed to load events:', err);
    list.innerHTML = '<tr><td colspan="5">Failed to load events.</td></tr>';
  }
}

function renderEvents(data) {
  list.innerHTML = '';
  if (!data.length) {
    list.innerHTML = '<tr><td colspan="7">No events found.</td></tr>';
    return;
  }

  data.forEach(e => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="title-cell">${escapeHtml(e.eventName)}</td>
      <td class="description-cell">${escapeHtml(e.eventDescription)}</td>
      <td class="reward-type">${escapeHtml(e.rewardType)}</td>
      <td>${escapeHtml(e.eventRewards)}</td>
      <td>${e.registeredCount || 0}</td>
      <td>${e.attendedCount || 0}</td>
      <td>
        <button class="btn-action edit-btn" onclick="editEvent('${e.eventID}')">Edit</button>
        <button class="btn-action delete-btn" onclick="delEvent('${e.eventID}')">Delete</button>
      </td>
    `;
    list.appendChild(tr);
  });
}


form.addEventListener('submit', async ev => {
  ev.preventDefault();
  const fd = new FormData(form);
  try {
    const res = await fetch('manage_events.php?action=save', { method: 'POST', body: fd });
    const msg = await res.json();
    alert(msg.message || 'Saved.');
    form.reset();
    loadEvents();
  } catch (err) { console.error(err); alert('Failed to save event.'); }
});

async function editEvent(id) {
  try {
    const res = await fetch('manage_events.php?action=get&id=' + encodeURIComponent(id));
    const e = await res.json();
    if (!e) return alert('Event not found.');
    document.getElementById('id').value = e.eventID;
    document.getElementById('title').value = e.eventName;
    document.getElementById('description').value = e.eventDescription;
    document.getElementById('rewardType').value = e.rewardType;
    document.getElementById('rewards').value = e.eventRewards;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  } catch (err) { console.error(err); alert('Failed to fetch event data.'); }
}

async function delEvent(id) {
  if (!confirm('Delete this event?')) return;
  try {
    const res = await fetch('manage_events.php?action=delete&id=' + encodeURIComponent(id));
    const msg = await res.json();
    alert(msg.message || 'Deleted.');
    loadEvents();
  } catch (err) { console.error(err); alert('Failed to delete event.'); }
}

searchBar.addEventListener('input', async () => {
  const q = searchBar.value.trim().toLowerCase();
  try {
    const res = await fetch('manage_events.php?action=list');
    const data = await res.json();
    const filtered = data.filter(ev =>
      (ev.eventName || '').toLowerCase().includes(q) ||
      (ev.eventDescription || '').toLowerCase().includes(q) ||
      (ev.eventRewards || '').toLowerCase().includes(q) ||
      (ev.rewardType || '').toLowerCase().includes(q)
    );
    renderEvents(filtered);
  } catch (err) { console.error(err); }
});

function escapeHtml(text) {
  if (text === null || text === undefined) return '';
  return String(text)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

loadEvents();
</script>
</body>
</html>
