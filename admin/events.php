<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
include('../db_connect.php');

// set timezone to Manila
//date_default_timezone_set('Asia/Manila');

// --- Ensure staff is logged in ---
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Manage Events — SPRS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
  :root { --accent-blue: #2563eb; --accent-hover: #1d4ed8; --card-bg: rgba(0,0,0,0.6); }
  * { box-sizing: border-box; }
  html,body { height:100%; margin:0; font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; color:#fff; }
  body {
    background: url('images/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    display:flex; flex-direction:column; min-height:100vh; line-height:1.4;
  }

  /* Header */
  header {
    position:fixed; inset:0 0 auto 0; height:64px;
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 20px; gap:12px;
    background:#1e293b; box-shadow:0 6px 20px rgba(3,7,18,0.45); z-index:20;
  }
  header .brand { display:flex; align-items:center; gap:12px; }
  header img { width:42px; height:42px; border-radius:8px; object-fit:cover; }
  header h1 { margin:0; font-size:16px; font-weight:700; color:#f8fafc; }
  .back-btn {
    background:var(--accent-blue); color:#fff; text-decoration:none; padding:8px 16px; border-radius:8px;
    font-weight:600; display:inline-block; transition:background .15s ease; box-shadow:0 6px 16px rgba(37,99,235,0.12);
  }
  .back-btn:hover { background:var(--accent-hover); }

  /* Main layout */
  .container { max-width:1200px; width:92%; margin:100px auto 40px; display:flex; gap:24px; flex:1; }
  @media (max-width:900px) { .container { flex-direction:column; margin-top:120px; } }

  /* Card */
  .card { background:var(--card-bg); border-radius:16px; padding:22px; box-shadow:0 8px 20px rgba(0,0,0,0.35); }
  .form-card { width:360px; min-width:300px; }
  .form-card h2 { margin-top:0; margin-bottom:16px; font-size:18px; font-weight:700; text-align:center; color:#eef2ff; }

  label { display:block; font-weight:600; margin-top:12px; font-size:13px; color:#e6eef8; }
  input[type="text"], input[type="number"], input[type="date"], textarea, select {
    width:100%; padding:10px; margin-top:8px; border-radius:8px; border:none; font-size:14px;
    background: rgba(255,255,255,0.04); color:#e6eef8; outline:none;
  }
  textarea { min-height:100px; resize:vertical; }
  input[readonly] { cursor:not-allowed; opacity:0.9; }

  .help { font-size:12px; color:#c7d2fe; margin-top:6px; }

  button.primary {
    width:100%; margin-top:16px; padding:10px 14px; border-radius:10px; border:none; cursor:pointer;
    background:var(--accent-blue); color:#fff; font-weight:700; font-size:15px; transition:background .15s;
  }
  button.primary:hover { background:var(--accent-hover); }

  /* Table card */
  .table-card { flex:1; display:flex; flex-direction:column; gap:8px; }
  .table-header { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
  .table-header h2 { margin:0; font-weight:700; color:#eef2ff; }
  .search-bar { padding:8px 12px; border-radius:8px; border:none; font-size:14px; width:220px; background: rgba(255,255,255,0.04); color:#fff; }

  .table-container { overflow:auto; max-height:460px; border-radius:10px; }
  table { width:100%; border-collapse:collapse; min-width:800px; }
  th, td { padding:12px 10px; border-bottom:1px solid rgba(255,255,255,0.06); text-align:center; vertical-align:middle; font-size:14px; }
  th { background: #1e293b; font-weight:700; color:#f8fafc; position:sticky; top:0; z-index:10; }
  td { background: rgba(255,255,255,0.02); color:#e6eef8; }
  td.title-cell, td.description-cell { text-align:left; }
  td.description-cell { color:#cbd5e1; max-width:420px; word-wrap:break-word; white-space:normal; }

  .reward-type { font-weight:700; color:#fbbf24; }

  .btn-action { display:inline-block; padding:6px 10px; border-radius:8px; font-size:13px; color:#fff; font-weight:600; margin:0 4px; cursor:pointer; border:none; }
  .edit-btn { background:#3b82f6; } .edit-btn:hover { background:#1d4ed8; }
  .delete-btn { background:#ef4444; } .delete-btn:hover { background:#b91c1c; }
  .qr-btn { background:#10b981; } .qr-btn:hover { background:#059669; }

  footer {
    background:#1e293b; padding:18px 10px; text-align:center; margin-top:auto; color:#c7e0ff;
    border-top:1px solid rgba(255,255,255,0.02);
  }
  footer .contact { display:flex; gap:12px; align-items:center; justify-content:center; flex-wrap:wrap; margin-bottom:8px; color:#93c5fd; }
  footer small { color:#fff; opacity:0.9; }

  /* Small screens */
  @media (max-width:600px) {
    header { height:auto; padding:14px; flex-direction:column; gap:10px; align-items:flex-start; }
    .container { width:96%; margin-top:120px; }
    table { min-width:700px; }
  }
</style>
</head>
<body>
<header>
  <div class="brand">
    <img src="images/logorewards.jpg" alt="SPRS logo">
    <h1>Manage Events</h1>
  </div>
  <a class="back-btn" href="staff_index.php" aria-label="Back to dashboard">⬅ Back to Dashboard</a>
</header>

<main class="container" role="main" aria-labelledby="pageTitle">
  <!-- Form card -->
  <section class="card form-card" aria-labelledby="formTitle">
    <h2 id="formTitle">Create / Edit Event</h2>

    <form id="eventForm" novalidate>
      <input type="hidden" id="id" name="eventID" value="">

      <label for="title">Title</label>
      <input id="title" name="eventName" type="text" required autocomplete="off" placeholder="Event title">

      <label for="description">Description</label>
      <textarea id="description" name="eventDescription" required placeholder="Short description"></textarea>

      <label for="rewardType">Reward Type</label>
      <!-- Locked to Points only -->
      <input id="rewardType" name="rewardType" type="text" value="Points" readonly aria-readonly="true">

      <label for="rewards">Rewards (Points: 100–200)</label>
      <!-- Option 2: Number input with min/max -->
      <input id="rewards" name="eventRewards" type="number" required min="100" max="200" step="1" placeholder="Enter points (100–200)" inputmode="numeric" pattern="[0-9]*">
      <div class="help">Enter points between <strong>100</strong> and <strong>200</strong>. (Only numeric values allowed.)</div>

      <label for="eventDate">Event Date</label>
      <input id="eventDate" name="eventDate" type="date" required>

      <label for="eventImage">Event Image</label>
	  <input type="file" id="eventImage" name="eventImage" accept="image/*">
              <!-- Preview image container  when editing event-->
        <div id="imagePreviewContainer" style="margin-top:10px; text-align:center; display:none;">
            <img id="imagePreview" src="" alt="Event Image" 
                 style="max-width:100%; border-radius:10px; margin-top:10px;">
        </div>
        
      <button type="submit" class="primary" id="saveBtn">Save Event</button>
    </form>
  </section>

  <!-- Table card -->
  <section class="card table-card" aria-labelledby="listTitle">
    <div class="table-header">
      <h2 id="listTitle">Existing Events</h2>
      <input id="searchBar" class="search-bar" type="search" placeholder="Search events..." aria-label="Search events">
    </div>

    <div class="table-container" role="region" aria-live="polite">
      <table aria-describedby="listTitle">
        <thead>
          <tr>
            <th style="width:18%;">Title</th>
            <th style="width:28%;">Description</th>
            <th style="width:8%;">Reward</th>
            <th style="width:8%;">Points</th>
            <th style="width:10%;">Event Date</th>
            <th style="width:8%;">Registered</th>
            <th style="width:8%;">Attended</th>
            <th style="width:10%;">Image</th>  
            <th style="width:12%;">Actions</th>
          </tr>
        </thead>
        <tbody id="eventList">
          <!-- JS injects rows here -->
        </tbody>
      </table>
    </div>
  </section>
</main>

<footer>
  <div class="contact">
    <div>📧 sprsystem@gmail.com</div>
    <span style="color:#ccc;">|</span>
    <div>📞 09123456789</div>
  </div>
  <small>© 2025 Student Point-Reward System. All rights reserved.</small>
</footer>

<script>
/* === Utilities === */
const $ = sel => document.querySelector(sel);
const el = id => document.getElementById(id);

function escapeHtml(text) {
  if (text === null || text === undefined) return '';
  return String(text)
    .replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;')
    .replaceAll('"','&quot;').replaceAll("'", '&#039;');
}

function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  if (isNaN(d)) return dateStr;
  return d.toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
}

/* === Elements === */
const form = el('eventForm');
const list = el('eventList');
const searchBar = el('searchBar');
const rewardsInput = el('rewards');
const rewardTypeInput = el('rewardType');
const saveBtn = el('saveBtn');
    
/* Keep reward type locked to "Points" */
rewardTypeInput.value = 'Points';
rewardTypeInput.setAttribute('readonly', 'readonly');
rewardTypeInput.setAttribute('aria-readonly', 'true');

/* === Load events === */
async function loadEvents() {
  try {
    const res = await fetch('manage_events.php?action=list');
    if (!res.ok) throw new Error('Network response not OK');
    const data = await res.json();
    renderEvents(Array.isArray(data) ? data : []);
  } catch (err) {
    console.error('Failed to load events:', err);
    list.innerHTML = '<tr><td colspan="8">Failed to load events.</td></tr>';
  }
}

/* === Render === */
function renderEvents(data) {
  list.innerHTML = '';
  if (!data.length) {
    list.innerHTML = '<tr><td colspan="8">No events found.</td></tr>';
    return;
  }

  data.forEach(e => {
    const tr = document.createElement('tr');

    const title = escapeHtml(e.eventName || '');
    const desc = escapeHtml(e.eventDescription || '');
    const rType = escapeHtml(e.rewardType || 'Points');
    const points = escapeHtml(String(e.eventRewards || ''));
    const date = formatDate(e.eventDate || '');
    const img = e.eventImage ? `eventImage/${escapeHtml(e.eventImage)}?t=${Date.now()}` : '';
    const registered = Number(e.registeredCount) || 0;
    const attended = Number(e.attendedCount) || 0;
    const id = escapeHtml(String(e.eventID || ''));
	
    tr.innerHTML = `
      <td class="title-cell">${title}</td>
      <td class="description-cell">${desc}</td>
      <td class="reward-type">${rType}</td>
      <td>${points}</td>
      <td>${date}</td>
      <td>${registered}</td>
      <td>${attended}</td>
      <td>
        ${img ? `<img src="${img}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">`
              : '—'}
  	  </td>
      <td>
        <button class="btn-action edit-btn" type="button" onclick="editEvent('${id}')">Edit</button>
        <button class="btn-action delete-btn" type="button" onclick="delEvent('${id}')">Delete</button>
        <button class="btn-action qr-btn" type="button" onclick="downloadQR('${encodeURIComponent(id)}')">⬇ Download QR</button>
      </td>
    `;
    list.appendChild(tr);
  });
}

/* === Form submit with client-side validation === */
form.addEventListener('submit', async (ev) => {
  ev.preventDefault();

  // Basic validation for numeric points between 100 and 200
  const pointsVal = parseInt(rewardsInput.value, 10);
  if (Number.isNaN(pointsVal)) {
    alert('Please enter a numeric points value (100–200).');
    rewardsInput.focus();
    return;
  }
  if (pointsVal < 100 || pointsVal > 200) {
    alert('Points must be between 100 and 200 only.');
    rewardsInput.focus();
    return;
  }

  // Ensure eventDate is not empty
  const dateVal = el('eventDate').value;
  if (!dateVal) {
    alert('Please select an event date.');
    el('eventDate').focus();
    return;
  }

  // Prepare FormData and submit to your existing backend endpoint (unchanged)
  const fd = new FormData(form);

  // Guarantee rewardType sent is "Points"
  fd.set('rewardType', 'Points');
  // Guarantee eventRewards is numeric string
  fd.set('eventRewards', String(pointsVal));

  try {
    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';

    const res = await fetch('manage_events.php?action=save', { method: 'POST', body: fd });
    const json = await res.json().catch(() => ({}));

    alert(json.message || (res.ok ? 'Event saved.' : 'Save failed.'));
    form.reset();
    // reset locked rewardType & default value
    rewardTypeInput.value = 'Points';

        // clear image preview
    imagePreview.src = '';
    imagePreviewContainer.style.display = 'none';
    // safely clear file input if present
    const imageInput = document.getElementById('eventImage');
    if (imageInput) imageInput.value = '';

    loadEvents();
    window.scrollTo({ top:0, behavior:'smooth' });
  } catch (err) {
    console.error('Save failed', err);
    alert('Failed to save event.');
  } finally {
    saveBtn.disabled = false;
    saveBtn.textContent = 'Save Event';
  }
});

/* === Edit event (populate form) === */
async function editEvent(id) {
  try {
    const res = await fetch(`manage_events.php?action=get&id=${encodeURIComponent(id)}`);
    if (!res.ok) throw new Error('Failed to fetch');
    const e = await res.json();
    if (!e) return alert('Event not found.');

    el('id').value = e.eventID || '';
    el('title').value = e.eventName || '';
    el('description').value = e.eventDescription || '';
    // locked reward type
    rewardTypeInput.value = 'Points';
    // set numeric reward value if available
    el('rewards').value = e.eventRewards ?? '';
    // date -> ensure format yyyy-mm-dd (server may return full datetime)
    el('eventDate').value = e.eventDate ? (e.eventDate.split ? e.eventDate.split(' ')[0] : e.eventDate) : '';
        // Event Image preview
    if (e.eventImage) {
        const imgSrc = 'eventImage/' + e.eventImage + '?t=' + Date.now();
        el('imagePreview').src = imgSrc;
        el('imagePreviewContainer').style.display = 'block';
    } else {
        el('imagePreviewContainer').style.display = 'none';
    }

    window.scrollTo({ top: 0, behavior: 'smooth' });
    el('title').focus();
  } catch (err) {
    console.error(err);
    alert('Failed to fetch event data.');
  }
}

/* === Delete event === */
async function delEvent(id) {
  if (!confirm('Delete this event?')) return;
  try {
    const res = await fetch(`manage_events.php?action=delete&id=${encodeURIComponent(id)}`);
    const json = await res.json().catch(() => ({}));
    alert(json.message || (res.ok ? 'Deleted.' : 'Delete failed.'));
    loadEvents();
  } catch (err) {
    console.error(err);
    alert('Failed to delete event.');
  }
}

/* === Download QR === */
function downloadQR(encodedEventID) {
  // encodedEventID is already encoded above, decode for building URL safety
  const eventID = decodeURIComponent(encodedEventID);
  const qrData = `https://${window.location.host}/student/mark_attendance.php?eventID=${encodeURIComponent(eventID)}`;
  const qrURL = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qrData)}`;

  const link = document.createElement('a');
  link.href = qrURL;
  link.download = `event_${eventID}_QR.png`;
  document.body.appendChild(link);
  link.click();
  link.remove();
}

/* === Search/filter === */
searchBar.addEventListener('input', async () => {
  const q = searchBar.value.trim().toLowerCase();
  try {
    const res = await fetch('manage_events.php?action=list');
    const data = await res.json();
    const filtered = (data || []).filter(ev =>
      (ev.eventName || '').toLowerCase().includes(q) ||
      (ev.eventDescription || '').toLowerCase().includes(q) ||
      (String(ev.eventRewards || '')).toLowerCase().includes(q) ||
      (ev.rewardType || '').toLowerCase().includes(q) ||
      (ev.eventDate || '').toLowerCase().includes(q)
    );
    renderEvents(filtered);
  } catch (err) {
    console.error('Search failed', err);
  }
});

/* === Initialize === */
loadEvents();
</script>
</body>
</html>
