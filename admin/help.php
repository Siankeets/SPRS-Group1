<?php
session_start();
include('../db_connect.php');

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$staffID = $_SESSION['userID'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Help Desk - Staff</title>
<style>
body { margin: 0; font-family: Arial; display: flex; height: 100vh; overflow: hidden; }
#sidebar { width: 300px; background: #f2f2f2; padding: 10px; overflow-y: auto; border-right: 1px solid #ccc; }
#chatArea { flex: 1; display: flex; flex-direction: column; padding: 20px; background: #e5e5e5; }
.convo { padding: 10px; background: white; margin-bottom: 5px; cursor: pointer; border-radius: 8px; }
.convo.active { background: #d1e7fd; }
#chatBox { flex: 1; overflow-y: auto; background: #fff; padding: 15px; border-radius: 10px; margin-bottom: 10px; }
#inputBox { display: flex; gap: 10px; }
input { flex: 1; padding: 10px; }
button { padding: 10px; background: green; color: white; border: none; cursor: pointer; border-radius: 5px; }
.message.student { color: blue; margin-bottom: 5px; }
.message.staff { color: green; margin-bottom: 5px; text-align: right; }
.badge { color: blue; font-weight: bold; margin-right: 5px; }
</style>
</head>
<body>

<div id="sidebar">
    <h3>Students</h3>
    <div id="convoList"></div>
</div>

<div id="chatArea">
    <h2 id="chatHeader">Select a student</h2>
    <div id="chatBox"></div>
    <div id="inputBox">
        <input id="msgInput" placeholder="Type reply..." disabled>
        <button id="sendBtn" onclick="sendMessage()" disabled>Send</button>
    </div>
</div>

<script>
let currentID = null;
let currentStudent = null;

// Initial load + refresh every 2 seconds
async function loadConversations() {
    const res = await fetch("api/get_conversations.php");
    conversationList = await res.json();
    renderConversations();
}
setInterval(loadConversations, 2000);
loadConversations();

function renderConversations() {
    const box = document.getElementById("convoList");
    box.innerHTML = "";

    conversationList.forEach(c => {
        const div = document.createElement("div");
        div.className = "convo";

        if(currentStudent && currentStudent.studentID === c.studentID) {
            div.classList.add("active");
        }

        const badge = (c.last_sender === 'student') ? "<span class='badge'>●</span>" : "";
        div.innerHTML = `${badge}<b>${c.student_name}</b><br>
                         <small>${c.student_program} • ${c.student_department}</small><br>
                         <span>Status: ${c.status || 'No conversation'}</span>`;

        div.onclick = () => openChat(c.studentID, c.student_name, c.conversation_id);
        box.appendChild(div);
    });
}

async function openChat(studentID, studentName, conversation_id) {
    currentStudent = { studentID, studentName };
    currentID = conversation_id;

    document.getElementById("chatHeader").innerText = "Chat with " + studentName;
    document.getElementById("msgInput").disabled = false;
    document.getElementById("sendBtn").disabled = false;

    // If no conversation exists, create one
    if (!currentID) {
        const res = await fetch("api/take_conversations.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ studentID })
        });
        const data = await res.json();
        currentID = data.conversation_id;
    }

    loadMessages();
}

async function loadMessages() {
    if(!currentID) return;

    const res = await fetch(`api/get_messages.php?id=${currentID}`);
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
    if(!currentID) return;

    const msg = document.getElementById("msgInput").value.trim();
    if(msg === "") return;

    const res = await fetch("api/send_admin_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ conversation_id: currentID, message: msg })
    });

    const data = await res.json();
    if(data.success){
        document.getElementById("msgInput").value = "";
        loadMessages();
    } else {
        alert("Failed to send message: " + (data.error || "Unknown"));
    }
}

// Auto-refresh messages every 2 seconds if a conversation is selected
setInterval(() => { if(currentID) loadMessages(); }, 2000);

</script>

</body>
</html>