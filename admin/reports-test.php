<?php 
header('Content-Type: text/html; charset=utf-8');
session_start();
include ('../db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>System Reports — Admin</title>
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
  .back-btn:hover { background: var(--accent-hover); }

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

  /* Table */
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    table-layout: fixed;
  }
  th, td {
    padding: 12px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    text-align: left;
    word-wrap: break-word;
  }
  th {
    background: rgba(255,255,255,0.1);
    font-weight: 600;
  }
  tr:hover {
    background: rgba(255,255,255,0.05);
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

  <!-- HEADER -->
  <header>
    <div class="header-left">
      <img src="images/logorewards.jpg" alt="SPRS Logo">
      <h1>System Reports</h1>
    </div>
    <button class="back-btn" onclick="window.location.href='staff_index.php'">← Back to Dashboard</button>
  </header>

  <!-- MAIN CONTENT -->
  <div class="container">
    <h2>System Reports Overview</h2>
    <div id="reportChart" style="text-align:center; margin-bottom: 30px;"></div>
    <!-- <table>
      <thead>
        <tr>
          <th>Report Type</th>
          <th>Description</th>
          <th>Status</th>
          <th>Last Updated</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td data-label="Report Type">Student Activity</td>
          <td data-label="Description">Tracks point distribution per student</td>
          <td data-label="Status">Active</td>
          <td data-label="Last Updated">Nov 4, 2025</td>
        </tr>
        <tr>
          <td data-label="Report Type">Reward Claims</td>
          <td data-label="Description">Shows list of redeemed rewards</td>
          <td data-label="Status">Active</td>
          <td data-label="Last Updated">Nov 3, 2025</td>
        </tr>
        <tr>
          <td data-label="Report Type">Event Participation</td>
          <td data-label="Description">Displays event attendance</td>
          <td data-label="Status">Active</td>
          <td data-label="Last Updated">Nov 2, 2025</td>
        </tr>
      </tbody>
    </table> -->
  </div>

  <!-- FOOTER -->
  <footer>
    Student Points Rewarding System <br>
    © 2025 All Rights Reserved <br>
    Contact: <a href="mailto:support@sprs.com">support@sprs.com</a>
  </footer>

  <script src="reportsVisual.js"> </script>
</body>
</html>