<?php
session_start();
include('../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$studentID = $_SESSION['userID'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Help Desk - Student</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<style>
:root {
    --accent-blue: #2563eb;
    --accent-hover: #1d4ed8;
    --header-bg: #0f172a;
    --sidebar-bg: rgba(15,23,42,0.9);
    --chat-bg: rgba(0,0,0,0.55);
    --bubble-student: #2563eb;
    --bubble-staff: #10b981;
}

body {
    margin: 0;
    font-family: 'Inter', sans-serif;
    display: flex;
    height: 100vh;
    background: url('images/bg.jpg') center/cover no-repeat fixed;
    color: #fff;
}

/* HEADER */
header {
    position: fixed;
    top: 0; left: 0; right: 0;
    height: 60px;
    background: var(--header-bg);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    box-shadow: 0 6px 20px rgba(3,7,18,0.45);
    z-index: 10;
}
header h1 {
    font-size: 18px;
    font-weight: 700;
    margin: 0;
}
.back-btn {
    background: var(--accent-blue);
    color: #fff;
    padding: 8px 16px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
}
.back-btn:hover { background: var(--accent-hover); }

/* LAYOUT */
#sidebar {
    width: 280px;
    background: var(--sidebar-bg);
    padding: 20px;
    overflow-y: auto;
    border-right: 1px solid rgba(255,255,255,0.1);
}
#chatArea {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 80px 30px 20px 30px; /* leave space for header */
    background: var(--chat-bg);
}

/* Sidebar items */
#sidebar h3 { margin-top: 0; font-weight: 600; margin-bottom: 10px; }
#staffSearch {
    width: 100%;
    padding: 8px 10px;
    border-radius: 8px;
    border: none;
    margin-bottom: 15px;
    outline: none;
}
.convo {
    padding: 12px;
    background: rgba(255,255,255,0.05);
    margin-bottom: 10px;
    cursor: pointer;
    border-radius: 12px;
    transition: background 0.2s;
}
.convo:hover { background: rgba(37,99,235,0.2); }
.convo.active { background: rgba(37,99,235,0.6); }

/* Chat Area */
#chatHeader { margin-top: 0; margin-bottom: 15px; font-weight: 700; }

/* Messenger-style chat box */
#chatBox {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.message {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 20px;
    word-wrap: break-word;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    display: inline-block;
}

.message.student {
    background: var(--bubble-student);
    align-self: flex-end;
    text-align: right;
    border-bottom-right-radius: 4px;
}
.message.staff {
    background: var(--bubble-staff);
    align-self: flex-start;
    text-align: left;
    border-bottom-left-radius: 4px;
}

/* Input Box */
#inputBox {
    display: flex;
    gap: 10px;
}
#msgInput {
    flex: 1;
    padding: 12px;
    border-radius: 20px;
    border: none;
    outline: none;
}
#sendBtn {
    padding: 12px 20px;
    border-radius: 20px;
    border: none;
    background: var(--accent-blue);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
}
#sendBtn:hover { background: var(--accent-hover); }

/* Scrollbars */
#sidebar::-webkit-scrollbar,
#chatBox::-webkit-scrollbar {
    width: 6px;
}
#sidebar::-webkit-scrollbar-thumb,
#chatBox::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 3px;
}

/* Responsive */
@media (max-width: 768px) {
    #sidebar { display: none; }
    #chatArea { padding: 80px 15px 15px 15px; }
}
</style>

</head>
<body>

<header>
    <h1>Help Desk</h1>
    <a href="student_index.php" class="back-btn">⬅ Back to Dashboard</a>
</header>

<div id="sidebar">
    <h3>Staff</h3>
    <input type="text" id="staffSearch" placeholder="Search staff...">
    <div id="staffList"></div>
</div>

<div id="chatArea">
    <h2 id="chatHeader">Select a staff to chat</h2>
    <div id="chatBox"></div>
    <div id="inputBox">
        <input id="msgInput" placeholder="Type your message..." disabled>
        <button id="sendBtn" onclick="sendMessage()" disabled>Send</button>
    </div>
</div>

<script>
// Original JS kept as-is
let currentID = null;
let currentStaff = null;
let staffListData = [];

async function loadStaff() {
    const res = await fetch("api/get_staff.php");
    staffListData = await res.json();
    renderStaffList();
}

function renderStaffList() {
    const searchTerm = document.getElementById('staffSearch').value.toLowerCase();
    const box = document.getElementById("staffList");
    box.innerHTML = "";
    staffListData
        .filter(s => s.name.toLowerCase().includes(searchTerm))
        .forEach(staff => {
            const div = document.createElement("div");
            div.className = "convo";
            if(currentStaff && currentStaff.staffID === staff.id) div.classList.add('active');
            div.dataset.name = staff.name;
            div.innerHTML = `<b>${staff.name}</b><br><small>${staff.department}</small>`;
            div.onclick = () => openChat(staff.id, staff.name);
            box.appendChild(div);
        });
}

document.getElementById('staffSearch').addEventListener('input', renderStaffList);

async function openChat(staffID, staffName) {
    currentStaff = { staffID, staffName };
    const res = await fetch("api/get_create_conversation.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ staffID })
    });
    const data = await res.json();
    if(!data.success){
        alert("Error opening chat: " + (data.error || "unknown"));
        return;
    }
    currentID = data.conversation_id;
    document.getElementById("chatHeader").innerText = "Chat with " + staffName;
    document.getElementById("msgInput").disabled = false;
    document.getElementById("sendBtn").disabled = false;
    loadMessages();
}

setInterval(() => {
    loadStaff();
    if(currentID) loadMessages();
}, 2000);

async function loadMessages() {
    if(!currentID) return;
    const res = await fetch("api/get_admin_messages.php?id=" + currentID);
    const msgs = await res.json();
    const box = document.getElementById("chatBox");
    box.innerHTML = "";
    msgs.forEach(m => {
        const div = document.createElement("div");
        div.className = "message " + m.sender;
        div.innerHTML = `<b>${m.sender}:</b> ${m.message}`;
        box.appendChild(div);
    });
    box.scrollTop = box.scrollHeight;
}

async function sendMessage() {
    if(!currentID || !currentStaff || !currentStaff.staffID) {
        alert("Cannot send message: No staff selected");
        return;
    }
    const msg = document.getElementById("msgInput").value.trim();
    if(msg === "") return;
    const res = await fetch("api/send_student_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            conversation_id: currentID,
            staffID: currentStaff.staffID,
            message: msg
        })
    });
    const data = await res.json();
    if(data.success){
        document.getElementById("msgInput").value = "";
        loadMessages();
    } else {
        alert("Message failed: " + (data.error || "Unknown error"));
    }
}

loadStaff();
</script>

</body>
</html>
