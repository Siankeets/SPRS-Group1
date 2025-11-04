<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Help Center — Student Point-Reward System</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  *{box-sizing:border-box;margin:0;padding:0;font-family:'Inter',sans-serif}
  body{
    background:url('bg.jpg') no-repeat center center fixed;
    background-size:cover;
    display:flex;
    flex-direction:column;
    min-height:100vh;
    color:#fff;
  }

  header{
    background:#0f172a;
    padding:15px 25px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    box-shadow:0 4px 20px rgba(0,0,0,0.4);
    position:fixed;top:0;width:100%;z-index:10;
  }

  .logo{display:flex;align-items:center;gap:10px}
  .logo img{width:48px;height:48px;border-radius:50%}
  .header-title{font-weight:700;font-size:17px}
  .admin-profile{background:#1e293b;padding:8px 16px;border-radius:10px;display:flex;align-items:center;gap:10px}
  .admin-profile span{font-size:14px}

  .container{
    flex:1;margin-top:90px;padding:20px;
    display:flex;justify-content:center;
  }

  .chat-wrapper{
    width:100%;max-width:1100px;
    display:grid;grid-template-columns:300px 1fr;
    gap:18px;
    background:rgba(0,0,0,0.45);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:16px;
    box-shadow:0 8px 25px rgba(0,0,0,0.4);
    overflow:hidden;
  }

  /* LEFT SIDEBAR */
  .chat-list{
    background:rgba(255,255,255,0.05);
    border-right:1px solid rgba(255,255,255,0.08);
    display:flex;flex-direction:column;
  }

  .chat-header{
    padding:16px;
    border-bottom:1px solid rgba(255,255,255,0.1);
    font-weight:700;
    font-size:18px;
    text-align:center;
    background:rgba(255,255,255,0.05);
  }

  .student{
    padding:14px 16px;
    border-bottom:1px solid rgba(255,255,255,0.05);
    cursor:pointer;
    transition:0.25s;
    display:flex;align-items:center;gap:10px;
    position:relative;
  }
  .student:hover{background:rgba(255,255,255,0.08)}
  .student.active{background:rgba(59,130,246,0.4)}
  .student img{width:36px;height:36px;border-radius:50%;border:1px solid rgba(255,255,255,0.2)}
  .student .info{display:flex;flex-direction:column}
  .student .name{font-weight:600}
  .student .last-msg{font-size:13px;color:#cbd5e1}
  .badge{
    position:absolute;
    right:14px;
    background:red;
    color:white;
    font-size:11px;
    font-weight:700;
    padding:2px 6px;
    border-radius:12px;
  }

  /* CHAT AREA */
  .chat-area{
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    background:rgba(255,255,255,0.03);
  }

  .chat-header-bar{
    padding:14px 20px;
    border-bottom:1px solid rgba(255,255,255,0.1);
    display:flex;
    align-items:center;
    justify-content:space-between;
  }

  .chat-header-bar h3{font-weight:700;font-size:17px;margin:0;}
  .chat-header-bar a{
    background:#1d4ed8;
    padding:8px 12px;
    border-radius:8px;
    color:#fff;text-decoration:none;font-weight:600;
    transition:0.25s;
  }
  .chat-header-bar a:hover{background:#2563eb;}

  .messages{
    flex:1;
    padding:18px 20px;
    overflow-y:auto;
    max-height:450px;
    display:flex;
    flex-direction:column;
  }

  .msg{
    margin-bottom:14px;
    max-width:70%;
    padding:10px 14px;
    border-radius:10px;
    line-height:1.4;
    position:relative;
  }

  .msg.student{
    background:rgba(255,255,255,0.12);
    align-self:flex-start;
  }

  .msg.admin{
    background:linear-gradient(to right,#60a5fa,#3b82f6);
    align-self:flex-end;
  }

  .msg span{
    display:block;
    font-size:12px;
    margin-top:4px;
    color:#e2e8f0;
    opacity:0.8;
  }

  .unsent-btn{
    position:absolute;
    top:4px;right:8px;
    font-size:12px;
    color:#f8fafc;
    opacity:0;
    cursor:pointer;
    transition:opacity 0.2s;
  }
  .msg.admin:hover .unsent-btn{opacity:1;}

  .input-area{
    display:flex;
    gap:10px;
    padding:14px 18px;
    border-top:1px solid rgba(255,255,255,0.1);
    background:rgba(0,0,0,0.3);
  }

  .input-area input{
    flex:1;
    background:rgba(255,255,255,0.08);
    border:none;
    border-radius:10px;
    padding:12px;
    color:#fff;
    outline:none;
  }

  .input-area button{
    background:linear-gradient(to right,#60a5fa,#3b82f6);
    border:none;
    padding:12px 20px;
    border-radius:10px;
    color:#fff;
    font-weight:600;
    cursor:pointer;
    transition:0.25s;
  }

  .input-area button:hover{
    background:linear-gradient(to right,#3b82f6,#2563eb);
  }

  footer{
    background:#0f172a;
    text-align:center;
    padding:18px 10px;
    font-size:14px;
  }

  @media(max-width:880px){
    .chat-wrapper{grid-template-columns:1fr}
    .chat-list{display:none;}
  }
</style>
</head>
<body>

<header>
  <div class="logo">
    <img src="logorewards.jpg" alt="Logo">
    <div class="header-title">Student Point-Reward System</div>
  </div>
  <div class="admin-profile">👩‍💼 <span>Jane Admin</span></div>
</header>

<div class="container">
  <div class="chat-wrapper">
    <div class="chat-list">
      <div class="chat-header">Students</div>
      <div class="student active" data-id="john" onclick="openChat('john', event)">
        <img src="https://i.pravatar.cc/36?img=3">
        <div class="info"><div class="name">John Student</div><div class="last-msg">How can I redeem?</div></div>
      </div>
      <div class="student" data-id="maria" onclick="openChat('maria', event)">
        <img src="https://i.pravatar.cc/36?img=5">
        <div class="info"><div class="name">Maria Learner</div><div class="last-msg">My QR isn’t working</div></div>
        <div class="badge" id="badge-maria">1</div>
      </div>
      <div class="student" data-id="alex" onclick="openChat('alex', event)">
        <img src="https://i.pravatar.cc/36?img=8">
        <div class="info"><div class="name">Alex Student</div><div class="last-msg">Can I transfer points?</div></div>
      </div>
    </div>

    <div class="chat-area">
      <div class="chat-header-bar">
        <h3 id="chatName">John Student</h3>
        <a href="admin_dashboard.html">← Back to Dashboard</a>
      </div>

      <div class="messages" id="chatMessages"></div>

      <div class="input-area">
        <input type="text" id="messageInput" placeholder="Type a message..." />
        <button onclick="sendMessage()">Send</button>
      </div>
    </div>
  </div>
</div>

<footer>
  📧 sprsystem@gmail.com | ☎ 09123456789 <br>
  © 2025 Student Point-Reward System. All rights reserved.
</footer>

<script>
const chats = {
  john: [
    {from:'student',text:'Hi Admin, how can I redeem my reward points?',time:'10:20 AM'},
    {from:'admin',text:'Hello John! You can redeem them from the Rewards page.',time:'10:22 AM'}
  ],
  maria: [
    {from:'student',text:'My QR scan didn’t add any points.',time:'9:48 AM'},
    {from:'admin',text:'Hi Maria! Try refreshing and scanning again.',time:'9:50 AM'}
  ],
  alex: [
    {from:'student',text:'Can I transfer my points to a classmate?',time:'11:05 AM'},
    {from:'admin',text:'Sorry Alex, points cannot be transferred.',time:'11:07 AM'}
  ]
};

let currentChat = 'john';
renderMessages();

function openChat(name, e){
  currentChat = name;
  document.querySelectorAll('.student').forEach(s=>s.classList.remove('active'));
  e.currentTarget.classList.add('active');
  document.getElementById('chatName').innerText = name.charAt(0).toUpperCase() + name.slice(1) + ' Student';
  const badge = document.getElementById('badge-' + name);
  if(badge) badge.remove(); // remove notification badge once opened
  renderMessages();
}

function renderMessages(){
  const box = document.getElementById('chatMessages');
  box.innerHTML = '';
  chats[currentChat].forEach((m,i)=>{
    const div = document.createElement('div');
    div.className = 'msg ' + m.from;
    div.innerHTML = `
      ${m.text}
      <span>${m.from==='admin'?'Admin':'Student'} • ${m.time}</span>
      ${m.from==='admin'?'<div class="unsent-btn" onclick="unsendMessage('+i+')">🗑 Unsend</div>':''}
    `;
    box.appendChild(div);
  });
  box.scrollTop = box.scrollHeight;
}

function sendMessage(){
  const input = document.getElementById('messageInput');
  const text = input.value.trim();
  if(!text) return;
  const now = new Date();
  const time = now.getHours()+':'+String(now.getMinutes()).padStart(2,'0');
  chats[currentChat].push({from:'admin',text,time});
  input.value='';
  renderMessages();
}

function unsendMessage(index){
  if(confirm('Unsend this message?')){
    chats[currentChat].splice(index,1);
    renderMessages();
  }
}

// Simulate a new student message for demo:
setTimeout(()=>{
  chats.maria.push({from:'student',text:'Are my points gone?',time:'10:40 AM'});
  const badge = document.getElementById('badge-maria');
  if(!badge){
    const mariaCard = document.querySelector('.student[data-id="maria"]');
    const b = document.createElement('div');
    b.className='badge';
    b.id='badge-maria';
    b.innerText='1';
    mariaCard.appendChild(b);
  }
},8000);
</script>

</body>
</html>