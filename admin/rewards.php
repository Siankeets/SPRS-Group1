<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
include ('../db_connect.php');

// --- Ensure admin is logged in ---
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Manage Rewards — SPRS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
  /* === Theme tokens (matching events.php style) === */
  :root {
    --accent-blue: #2563eb;
    --accent-hover: #1d4ed8;
    --card-bg: rgba(0,0,0,0.6);
  }
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
  input[type="text"], input[type="number"], textarea, select {
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

  /* Table card (keeps your original look but polished) */
  .table-card { flex:1; display:flex; flex-direction:column; gap:8px; }
  .table-header { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
  .table-header h2 { margin:0; font-weight:700; color:#eef2ff; }
  .search-bar { padding:8px 12px; border-radius:8px; border:none; font-size:14px; width:220px; background: rgba(255,255,255,0.04); color:#fff; }

  .table-container { overflow:auto; max-height:460px; border-radius:10px; }
  table { width:100%; border-collapse:collapse; min-width:800px; }
  th, td { padding:12px 10px; border-bottom:1px solid rgba(255,255,255,0.06); text-align:center; vertical-align:middle; font-size:14px; }
  th { background: #1e293b; font-weight:700; color:#f8fafc; position:sticky; top:0; z-index:1; }
  td { background: rgba(255,255,255,0.02); color:#e6eef8; }
  td.title-cell, td.description-cell { text-align:left; }
  td.description-cell { color:#cbd5e1; max-width:420px; word-wrap:break-word; white-space:normal; }

  .reward-type { font-weight:700; color:#fbbf24; }

  .btn-action { display:inline-block; padding:6px 10px; border-radius:8px; font-size:13px; color:#fff; font-weight:600; margin:0 4px; cursor:pointer; border:none; }
  .edit-btn { background:#3b82f6; } .edit-btn:hover { background:#1d4ed8; }
  .delete-btn { background:#ef4444; } .delete-btn:hover { background:#b91c1c; }

  footer {
    background:#1e293b; padding:18px 10px; text-align:center; margin-top:auto; color:#c7e0ff;
    border-top:1px solid rgba(255,255,255,0.02);
  }
  footer .contact { display:flex; gap:12px; align-items:center; justify-content:center; flex-wrap:wrap; margin-bottom:8px; color:#93c5fd; }
  footer small { color:#fff; opacity:0.9; }

  /* small screens */
  @media (max-width:600px) {
    header { height:auto; padding:14px; flex-direction:column; gap:10px; align-items:flex-start; }
    .container { width:96%; margin-top:120px; }
    table { min-width:700px; }
  }

  /* subtle row hover to keep familiarity but nicer UX */
  tbody tr:hover td { background: rgba(255,255,255,0.035); }
</style>
</head>
<body>
<header>
  <div class="brand">
    <img src="images/logorewards.jpg" alt="SPRS logo">
    <h1>Manage Rewards</h1>
  </div>
  <a class="back-btn" href="staff_index.php" aria-label="Back to dashboard">⬅ Back to Dashboard</a>
</header>

<main class="container" role="main" aria-labelledby="pageTitle">
  <!-- Form card -->
  <section class="card form-card" aria-labelledby="formTitle">
    <h2 id="formTitle">Create / Edit Reward</h2>

    <form id="rewardForm" novalidate>
      <input type="hidden" id="id" name="rewardID" value="">

      <label for="title">Reward Name</label>
      <input id="title" name="rewardName" type="text" required autocomplete="off" placeholder="Reward name">

      <label for="description">Description</label>
      <textarea id="description" name="rewardDescription" required placeholder="Short description"></textarea>

      <label for="points">Points Required</label>
      <input id="points" name="rewardPointsRequired" type="number" min="500" max="5000" step="1" required placeholder="Points required (500–5000)">
      <div class="help">Enter a numeric points value between <strong>500</strong> and <strong>5000</strong>.</div>

      <label for="type">Reward Type</label>
      <select id="type" name="rewardType" required>
        <option value="Ticket">Ticket</option>
        <option value="Supplies">Supplies</option>
        <option value="Tshirts">Tshirts</option>
        <option value="IDs">IDs</option>
      </select>

      <button type="submit" class="primary" id="saveBtn">Save Reward</button>
    </form>
  </section>

  <!-- Table card -->
  <section class="card table-card" aria-labelledby="listTitle">
    <div class="table-header">
      <h2 id="listTitle">Existing Rewards</h2>
      <input id="searchBar" class="search-bar" type="search" placeholder="Search rewards..." aria-label="Search rewards">
    </div>

    <div class="table-container" role="region" aria-live="polite">
      <table aria-describedby="listTitle">
        <thead>
          <tr>
            <th style="width:28%;">Name</th>
            <th style="width:36%;">Description</th>
            <th style="width:12%;">Type</th>
            <th style="width:10%;">Points</th>
            <th style="width:14%;">Actions</th>
          </tr>
        </thead>
        <tbody id="rewardList">
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
    <span style="color:#ccc;">|</span>
    <div style="display:flex; align-items:center; gap:6px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#93c5fd" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 
                 3.657 9.128 8.438 9.878v-6.987H8.078v-2.89h2.36V9.797
                 c0-2.337 1.393-3.625 3.52-3.625.996 0 2.04.178 2.04.178v2.25
                 h-1.151c-1.137 0-1.492.705-1.492 1.43v1.716h2.54l-.406 2.89
                 h-2.134V21.9C18.343 21.128 22 16.991 22 12z"/>
      </svg>
      <a href="https://www.facebook.com/StudentPointRewardSystem" target="_blank" rel="noopener" style="color:#93c5fd; text-decoration:none;">
        Student Point Reward System
      </a>
    </div>
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

/* === Elements === */
const form = el('rewardForm');
const list = el('rewardList');
const searchBar = el('searchBar');
const pointsInput = el('points');
const saveBtn = el('saveBtn');

/* === Load rewards === */
async function loadRewards() {
  try {
    const res = await fetch('manage_events.php?action=listRewards');
    if (!res.ok) throw new Error('Network response not OK');
    const data = await res.json();
    renderRewards(Array.isArray(data) ? data : []);
  } catch (err) {
    console.error('Failed to load rewards:', err);
    list.innerHTML = '<tr><td colspan="5">Failed to load rewards.</td></tr>';
  }
}

/* === Render === */
function renderRewards(data) {
  list.innerHTML = '';
  if (!data.length) {
    list.innerHTML = '<tr><td colspan="5">No rewards found.</td></tr>';
    return;
  }

  data.forEach(r => {
    const imgFile = {
      Ticket: 'pass.png',
      Supplies: 'ntbk.png',
      Tshirts: 'tshirt.png',
      IDs: 'id.png',
    }[r.rewardType] || 'default.png';

    const typeColor = {
      Ticket: '#3b82f6',
      Supplies: '#22c55e',
      Tshirts: '#f59e0b',
      IDs: '#a855f7',
    }[r.rewardType] || '#64748b';

    const tr = document.createElement('tr');

    tr.innerHTML = `
      <td class="title-cell" style="text-align:left;">
        <div style="display:flex; align-items:center; gap:10px;">
          <img src="images/${escapeHtml(imgFile)}" alt="${escapeHtml(r.rewardType)}" style="width:45px;height:45px;border-radius:8px;object-fit:cover;">
          <div style="font-weight:600;font-size:15px;color:#fff;">${escapeHtml(r.rewardName)}</div>
        </div>
      </td>
      <td class="description-cell">${escapeHtml(r.rewardDescription)}</td>
      <td>
        <span style="
          display:inline-block;
          background:${typeColor};
          color:white;
          padding:6px 12px;
          border-radius:12px;
          font-size:13px;
          font-weight:600;
        ">${escapeHtml(r.rewardType)}</span>
      </td>
      <td>${escapeHtml(String(r.rewardPointsRequired || '0'))}</td>
      <td>
        <button class="btn-action edit-btn" type="button" onclick="editReward('${escapeHtml(r.rewardID)}')">Edit</button>
        <button class="btn-action delete-btn" type="button" onclick="delReward('${escapeHtml(r.rewardID)}')">Delete</button>
      </td>
    `;
    list.appendChild(tr);
  });
}

/* === Form submit with client-side validation === */
form.addEventListener('submit', async (ev) => {
  ev.preventDefault();

  // Validate numeric points between min and max
  const minVal = parseInt(pointsInput.getAttribute('min') || '500', 10);
  const maxVal = parseInt(pointsInput.getAttribute('max') || '5000', 10);
  const pointsVal = parseInt(pointsInput.value, 10);

  if (Number.isNaN(pointsVal)) {
    alert('Please enter a numeric points value.');
    pointsInput.focus();
    return;
  }
  if (pointsVal < minVal || pointsVal > maxVal) {
    alert(`Points must be between ${minVal} and ${maxVal}.`);
    pointsInput.focus();
    return;
  }

  const fd = new FormData(form);
  // ensure correct numeric string
  fd.set('rewardPointsRequired', String(pointsVal));

  try {
    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';

    const res = await fetch('manage_events.php?action=saveReward', { method: 'POST', body: fd });
    const json = await res.json().catch(() => ({}));
    alert(json.message || (res.ok ? 'Saved.' : 'Save failed.'));
    form.reset();
    loadRewards();
  } catch (err) {
    console.error('Save failed', err);
    alert('Failed to save reward.');
  } finally {
    saveBtn.disabled = false;
    saveBtn.textContent = 'Save Reward';
  }
});

/* === Edit reward (populate form) === */
async function editReward(id) {
  try {
    const res = await fetch(`manage_events.php?action=getReward&id=${encodeURIComponent(id)}`);
    if (!res.ok) throw new Error('Failed to fetch reward');
    const r = await res.json();
    if (!r) return alert('Reward not found.');

    el('id').value = r.rewardID || '';
    el('title').value = r.rewardName || '';
    el('description').value = r.rewardDescription || '';
    el('points').value = r.rewardPointsRequired ?? '';
    el('type').value = r.rewardType || 'Ticket';
    window.scrollTo({ top: 0, behavior: 'smooth' });
    el('title').focus();
  } catch (err) {
    console.error(err);
    alert('Failed to fetch reward data.');
  }
}

/* === Delete reward === */
async function delReward(id) {
  if (!confirm('Delete this reward?')) return;
  try {
    const res = await fetch(`manage_events.php?action=delReward&id=${encodeURIComponent(id)}`);
    const json = await res.json().catch(() => ({}));
    alert(json.message || (res.ok ? 'Deleted.' : 'Delete failed.'));
    loadRewards();
  } catch (err) {
    console.error(err);
    alert('Failed to delete reward.');
  }
}

/* === Search/filter === */
searchBar.addEventListener('input', async () => {
  const q = searchBar.value.trim().toLowerCase();
  try {
    const res = await fetch('manage_events.php?action=listRewards');
    if (!res.ok) throw new Error('Network response not OK');
    const data = await res.json();
    const filtered = (data || []).filter(r =>
      (r.rewardName || '').toLowerCase().includes(q) ||
      (r.rewardDescription || '').toLowerCase().includes(q) ||
      (r.rewardType || '').toLowerCase().includes(q) ||
      String(r.rewardPointsRequired || '').toLowerCase().includes(q)
    );
    renderRewards(filtered);
  } catch (err) {
    console.error('Search failed', err);
  }
});

/* === Initialize === */
loadRewards();
</script>
</body>
</html>
