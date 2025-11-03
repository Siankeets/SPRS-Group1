<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Student Point-Reward System — Dashboard</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg-overlay: rgba(0,0,0,0.68);
      --muted: #b5bcc8;
      --accent-1: #93c5fd;
      --accent-2: #3b82f6;
      --panel-text: #071033;
      --btn-blue: #3498db;
      --btn-blue-dark: #2980b9;
      --glass: rgba(0,0,0,0.40);
      --glass-strong: rgba(0,0,0,0.55);
      --success: #10b981;
      --transition: 240ms cubic-bezier(.2,.9,.3,1);
    }

    *{box-sizing:border-box}
    html,body{height:100%;margin:0;display:flex;flex-direction:column;}
    body {
      font-family: 'Inter', system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: url('bg.jpg') no-repeat center center fixed;
      background-size: cover;
      color: #f2f6fb;
      line-height: 1.35;
    }

    .container {flex:1; padding: 80px 18px 20px; display: flex; flex-direction: column;}
    header {
      position: fixed; top: 0; left: 0; right: 0; width: 100%; z-index: 100;
      padding: 8px 18px; display: flex; justify-content: space-between; align-items: center;
      background-color: #1e293b; color: #fff;
      box-shadow: 0 4px 16px rgba(3, 7, 18, 0.4);
    }

    .brand{display:flex;gap:14px;align-items:center}
    .logo img{width:46px;height:46px;border-radius:8px;object-fit:cover;display:block}
    .title-wrap h1{font-size:16px;margin:0;font-weight:600}
    .title-wrap p{margin:2px 0 0;color:var(--muted);font-size:12px}

    /* 🔹 Updated Header Profile Card */
    .profile-info {
      display: flex;
      align-items: center;
      gap: 12px;
      background: rgba(255, 255, 255, 0.08);
      padding: 8px 16px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }

    .profile-info .avatar {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: #fff;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-weight: 700;
      font-size: 14px;
    }

    .profile-info .user-details {
      display: flex;
      flex-direction: column;
      line-height: 1.1;
    }

    .profile-info .user-details strong {
      font-size: 14px;
    }

    .profile-info .user-details span {
      font-size: 12px;
      color: #ccc;
    }

    .profile-info .credits {
      background: linear-gradient(135deg, #10b981, #059669);
      color: #fff;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 10px;
      font-size: 12px;
    }

    .main{display:grid;grid-template-columns:260px 1fr;gap:16px;flex:1}
    .sidebar{min-height:380px;background: var(--glass);border-radius:12px;padding:14px;border:1px solid rgba(255,255,255,0.04);box-shadow:0 6px 18px rgba(2,6,23,0.45);display:flex;flex-direction:column;}
    .profile{display:flex;gap:12px;align-items:center;margin-bottom:12px}
    .avatar{width:56px;height:56px;border-radius:10px;background: rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;border:1px solid rgba(255,255,255,0.06);}
    .profile .meta{display:flex;flex-direction:column}
    .profile .meta .name{font-weight:700}
    .profile .meta .role{font-size:13px;color:var(--muted)}
    .credits-card{margin-bottom:14px;padding:12px;border-radius:10px;background: linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;text-align:center;font-weight:700;box-shadow:0 8px 18px rgba(3,7,18,0.45);display:flex;align-items:center;justify-content:center;gap:10px;font-size:15px;}
    .credits-card svg{width:20px;height:20px;opacity:0.98}
    .buttons{display:grid;gap:10px;margin-top:8px}
    .menu-btn{display:flex;gap:12px;align-items:center;padding:12px;border-radius:10px;background:var(--glass);border:1px solid rgba(255,255,255,0.03);color:#fff;cursor:pointer;text-align:left;transition:var(--transition);}
    .menu-btn:hover{transform:translateY(-3px);background:var(--glass-strong);box-shadow:0 10px 24px rgba(2,6,23,0.35)}
    .menu-btn svg{width:18px;height:18px;flex:0 0 18px;opacity:0.95}
    .menu-btn .text{display:flex;flex-direction:column}
    .menu-btn .text .title{font-weight:600}
    .menu-btn .text .sub{font-size:12px;color:var(--muted);margin-top:2px}

    .content{padding:14px;border-radius:12px;background: var(--glass);border:1px solid rgba(255,255,255,0.04);box-shadow:0 6px 18px rgba(3,7,18,0.45);position:relative;}
    .hero{display:flex;align-items:center;justify-content:space-between;padding:18px;border-radius:12px;border:1px solid rgba(255,255,255,0.15);background:rgba(255,255,255,0.06);}
    .hero .info{max-width:70%}
    .hero h2{margin:0;font-size:20px;font-weight:700}
    .hero p{margin:6px 0 0;color:#fff;text-shadow:0 1px 4px rgba(2,6,23,0.6)}
    .grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:30px;}
    .panel{padding:60px 28px;border-radius:20px;background:linear-gradient(135deg,var(--accent-1),var(--accent-2));color:var(--panel-text);box-shadow:0 10px 28px rgba(2,6,23,0.3);text-align:center;transition:var(--transition);cursor:pointer;}
    .panel:hover{transform:translateY(-6px) scale(1.03);box-shadow:0 14px 36px rgba(2,6,23,0.45);}
    .panel svg{width:70px;height:70px;margin-bottom:16px;opacity:0.95;}
    .panel h3{margin:0 0 10px 0;font-size:24px;font-weight:700;}
    .panel p{margin:0;color:rgba(2,6,23,0.85);font-weight:600;font-size:16px;}
    footer {width:100%;background:#1e293b;text-align:center;padding:20px 10px;margin-top:auto;}
    @media (max-width:940px){.main{grid-template-columns:1fr;gap:12px}.grid{grid-template-columns:1fr}}
    @media (max-width:600px){header{padding:6px 14px}.logo img{width:42px;height:42px}.title-wrap h1{font-size:15px}.title-wrap p{font-size:11px}.container{padding-top:95px!important;padding-left:14px;padding-right:14px;padding-bottom:20px;}}

    /* ✅ Logout modal */
    .logout-modal {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    .logout-box {
      background: var(--glass);
      border: 1px solid rgba(255,255,255,0.2);
      border-radius: 12px;
      padding: 25px 30px;
      text-align: center;
      color: #fff;
      box-shadow: 0 6px 18px rgba(2,6,23,0.6);
    }
    .logout-box h4 {margin-bottom: 20px;}
    .logout-buttons {display: flex; justify-content: center; gap: 12px;}
    .logout-buttons button {
      padding: 8px 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }
    .confirm-logout {background: #dc3545; color: #fff;}
    .cancel-logout {background: #6c757d; color: #fff;}
  </style>
</head>

<body>
  <header>
    <div class="brand">
      <div class="logo"><img src="logorewards.jpg" alt="SPRS Logo"></div>
      <div class="title-wrap">
        <h1>Student Point-Reward System</h1>
        <p class="lead">QR Attendance • SMS Auth • Email Notifications</p>
      </div>
    </div>
    <!-- 🔹 New header profile info -->
    <div class="profile-info">
      <div class="avatar">ST</div>
      <div class="user-details">
        <strong>John Student</strong>
        <span>Student</span>
      </div>
      <div class="credits">Credits: <span id="headerCredits">120</span></div>
    </div>
  </header>

  <div class="container">
    <div class="main">
      <aside class="sidebar">
        <div class="profile">
          <div class="avatar">ST</div>
          <div class="meta"><div class="name">John Student</div><div class="role">Student</div></div>
        </div>
        <div class="credits-card"><div>Credits: <span id="credits">120</span></div></div>
        <nav class="buttons" id="menu"></nav>
      </aside>

      <section class="content">
        <div class="hero">
          <div class="info">
            <h2 id="dashboardTitle">Welcome, John</h2>
            <p id="heroDesc">This dashboard shows your credits, rewards, and events. Use the menu to redeem or view inventory.</p>
          </div>
          <div><div style="font-size:13px;color:var(--muted)">Role:</div><div style="font-weight:700">Student</div></div>
        </div>

      </section>
    </div>
  </div>
</body>
</html>


<footer>
  <div style="font-weight:700; font-size:16px; margin-bottom:12px;">Contact Us:</div>
  <div style="font-size:13px; display:flex; justify-content:center; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px; color:#93c5fd;">
    <div>📧 sprsystem@gmail.com</div>
    <span style="color:#ccc;">|</span>
    <div>📞 09123456789</div>
    <span style="color:#ccc;">|</span>
    <div style="display:flex; align-items:center; gap:6px;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#93c5fd" viewBox="0 0 24 24">
        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 
                 3.657 9.128 8.438 9.878v-6.987H8.078v-2.89h2.36V9.797
                 c0-2.337 1.393-3.625 3.52-3.625.996 0 2.04.178 2.04.178v2.25
                 h-1.151c-1.137 0-1.492.705-1.492 1.43v1.716h2.54l-.406 2.89
                 h-2.134V21.9C18.343 21.128 22 16.991 22 12z"/>
      </svg>
      <a href="https://www.facebook.com/StudentPointRewardSystem" target="_blank" style="color:#93c5fd; text-decoration:none;">
        Student Point Reward System
      </a>
    </div>
  </div>
  <div style="font-size:13px;color:#fff;">© 2025 Student Point-Reward System. All rights reserved.</div>
</footer>

<!-- ✅ Logout Modal -->
<div class="logout-modal" id="logoutModal">
  <div class="logout-box">
    <h4>Are you sure you want to logout?</h4>
    <div class="logout-buttons">
      <button class="confirm-logout" id="confirmLogout">Logout</button>
      <button class="cancel-logout" id="cancelLogout">Cancel</button>
    </div>
  </div>
</div>

<script>
  const creditsEl = document.getElementById('credits');
  let credits = 120;
  creditsEl.textContent = credits;

  const studentMenu = [
    { key: 'redeem', title: 'Redeem', sub: 'Redeem rewards with credits', icon: 'gift' },
    { key: 'inventory', title: 'Inventory', sub: 'Track redeemed rewards', icon: 'box' },
    { key: 'events', title: 'Events List', sub: 'View upcoming events', icon: 'calendar' },
    { key: 'help', title: 'Help Center', sub: 'Get assistance and FAQs', icon: 'help' },
    { key: 'logout', title: 'Logout', sub: 'Sign out', icon: 'logout' }
  ];

  // SVG icons map (inline)
  function iconSVG(name) {
    const map = {
      user: `<svg viewBox="0 0 24 24" fill="none"><path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12z" fill="#fff"/><path d="M4.2 20.6c0-3.2 2.6-5.8 5.8-5.8h4c3.2 0 5.8 2.6 5.8 5.8" stroke="#fff" stroke-width="0.6" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
      gift:`<svg viewBox="0 0 24 24" fill="none"><path d="M20 12v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8" stroke="#fff" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 7v-1a2 2 0 0 0-2-2h-3.5c.6 0 1.1.3 1.5.8.6.8.4 1.9-.5 2.5L12 11" stroke="#fff" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 7v-1a2 2 0 0 1 2-2h3.5" stroke="#fff" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
      box:`<svg viewBox="0 0 24 24" fill="none"><path d="M21 16V8a1 1 0 0 0-.5-.86L12.5 2.5a1 1 0 0 0-.99 0L3.5 7.14A1 1 0 0 0 3 8v8a1 1 0 0 0 .5.86L11.5 21.5a1 1 0 0 0 .99 0L20.5 16.86A1 1 0 0 0 21 16z" stroke="#fff" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
      calendar:`<svg viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="#fff" stroke-width="1.2"/><path d="M16 3v4M8 3v4" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/></svg>`,
      help:`<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#fff" stroke-width="1.2"/><path d="M12 17h.01M12 13a2 2 0 0 1 2-2c0-1.1-.9-2-2-2s-2 .9-2 2" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>`,
      logout:`<svg viewBox="0 0 24 24" fill="none"><path d="M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 17l5-5-5-5" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12H9" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>`
    };
    return map[name] || '';
  }

  /* build menu dynamically */
  const menu = document.getElementById('menu');
 studentMenu.forEach(item=>{
  const btn = document.createElement('button');
  btn.className = 'menu-btn';
  btn.setAttribute('data-key', item.key);
  btn.innerHTML = `<span class="icon">${iconSVG(item.icon)}</span>
                   <span class="text"><span class="title">${item.title}</span><span class="sub">${item.sub}</span></span>`;
  btn.addEventListener('click', ()=> handleMenu(item.key));
  menu.appendChild(btn);
});


  /* panel appear animation (stagger) */
  window.addEventListener('load', ()=>{
    const panels = document.querySelectorAll('[data-anim]');
    panels.forEach((p, i)=>{
      setTimeout(()=> p.classList.add('visible'), 120 * i);
    });
  });

  /* simple modal control */
  const modal = document.getElementById('modalBackdrop');
  document.getElementById('cancelModal').addEventListener('click', ()=> closeModal());
  document.getElementById('saveModal').addEventListener('click', ()=>{
    alert('Saved (placeholder)');
    closeModal();
  });

  function openModal(title){
    document.getElementById('modalTitle').textContent = title;
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden','false');
  }
  function closeModal(){
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden','true');
  }

  /* menu handler */
  function handleMenu(key){
    switch(key){
      case 'redeem':
         window.location.href = 'redeem.php';
        break;
      case 'inventory':
         window.location.href = 'inventory.php';
        break;
      case 'events':
       window.location.href = 'event.php';
        break;
      case 'help':
          window.location.href = 'help.php';
        break;
      case 'logout':
        if(confirm('Logout?')) {
          alert('Logged out (placeholder)');
        }
        break;
      default:
        openModal('Info');
    }
  }

  function setCredits(n){
    credits = Number(n) || 0;
    creditsEl.textContent = credits;
  }

  window.setCredits = setCredits;
</script>
</body>
</html>
