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

// Load students and conversations every 2 seconds
setInterval(loadConversations, 2000);
loadConversations();

async function loadConversations() {
    let res = await fetch("api/get_conversations.php");
    let list = await res.json();

    let box = document.getElementById("convoList");
    box.innerHTML = "";

    list.forEach(c => {
        let div = document.createElement("div");
        div.className = "convo";
        if(currentStudent && currentStudent.studentID === c.studentID) div.classList.add('active');

        let badge = (c.last_sender === 'student') ? "<span class='badge'>●</span>" : "";
        let statusText = c.conversation_status || "No conversation";

        div.innerHTML = `${badge}<b>${c.student_name}</b><br>
                         <small>${c.student_program} • ${c.student_department}</small><br>
                         <span>Status: ${statusText}</span>`;

        div.onclick = () => openChat(c.conversation_id, c.studentID, c.student_name);
        box.appendChild(div);
    });
}

async function openChat(conversation_id, studentID, studentName) {
    currentStudent = { studentID, studentName };

    if (!conversation_id) {
        // Create new conversation if none exists
        let res = await fetch("api/take_conversation.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ studentID })
        });
        let data = await res.json();
        conversation_id = data.conversation_id;
    }

    currentID = conversation_id;
    document.getElementById("chatHeader").innerText = "Chat with " + studentName;
    document.getElementById("msgInput").disabled = false;
    document.getElementById("sendBtn").disabled = false;

    loadMessages();
}

setInterval(() => {
    if(currentID) loadMessages();
}, 2000);

async function loadMessages() {
    if(!currentID || !currentStudent) return;

    let res = await fetch(`api/get_messages.php?id=${currentID}&studentID=${currentStudent.studentID}`);
    let msgs = await res.json();

    let box = document.getElementById("chatBox");
    box.innerHTML = "";

    msgs.forEach(m => {
        let div = document.createElement("div");
        div.className = "message " + m.sender;
        div.innerHTML = `<b>${m.sender}:</b> ${m.message}`;
        box.appendChild(div);
    });

    box.scrollTop = box.scrollHeight;
}


async function sendMessage() {
    if(!currentID) return;

    let msg = document.getElementById("msgInput").value.trim();
    if(msg === "") return;

    let res = await fetch("api/send_admin_messages.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            conversation_id: currentID,
            message: msg
        })
    });
    let data = await res.json();
    if(data.success){
        document.getElementById("msgInput").value = "";
        loadMessages();
    } else {
        alert("Message failed: " + (data.error || "Unknown error"));
    }
}
</script>
</body>
</html>
