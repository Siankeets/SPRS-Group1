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
<style>
body { margin: 0; font-family: Arial; display: flex; height: 100vh; overflow: hidden; }
#sidebar { width: 300px; background: #f2f2f2; padding: 10px; overflow-y: auto; border-right: 1px solid #ccc; }
#chatArea { flex: 1; display: flex; flex-direction: column; padding: 20px; background: #e5e5e5; }
.convo { padding: 10px; background: white; margin-bottom: 5px; cursor: pointer; border-radius: 8px; }
.convo.active { background: #d1e7fd; }
#chatBox { flex: 1; overflow-y: auto; background: #fff; padding: 15px; border-radius: 10px; margin-bottom: 10px; }
#inputBox { display: flex; gap: 10px; }
input { flex: 1; padding: 10px; }
button { padding: 10px; background: blue; color: white; border: none; cursor: pointer; border-radius: 5px; }
.message.student { text-align: right; color: blue; margin-bottom: 5px; }
.message.staff { text-align: left; color: green; margin-bottom: 5px; }
</style>
</head>
<body>

<div id="sidebar">
    <h3>Staff</h3>
    <input type="text" id="staffSearch" placeholder="Search staff..." style="width: 100%; padding: 5px; margin-bottom: 5px;">
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
let currentID = null;
let currentStaff = null;
let staffListData = [];

// Load staff list
async function loadStaff() {
    const res = await fetch("api/get_staff.php");
    staffListData = await res.json();
    renderStaffList();
}

// Render staff list with optional search
function renderStaffList() {
    const searchTerm = document.getElementById('staffSearch').value.toLowerCase();
    const box = document.getElementById("staffList");
    box.innerHTML = "";

    staffListData
        .filter(s => s.name.toLowerCase().includes(searchTerm))
        .forEach(staff => {
            const div = document.createElement("div");
            div.className = "convo";

            // Preserve active staff
            if(currentStaff && currentStaff.staffID === staff.id) div.classList.add('active');

            div.dataset.name = staff.name;
            div.innerHTML = `<b>${staff.name}</b><br><small>${staff.department}</small>`;
            div.onclick = () => openChat(staff.id, staff.name);
            box.appendChild(div);
        });
}


// Search input event
document.getElementById('staffSearch').addEventListener('input', renderStaffList);

async function openChat(staffID, staffName) {
    currentStaff = { staffID, staffName };

    // Fetch conversation or create one
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



// Auto-refresh staff list and messages every 2 seconds
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




// Initial load
loadStaff();
</script>

</body>
</html>