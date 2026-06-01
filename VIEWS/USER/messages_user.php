<?php
session_start();
include '../../BACKEND/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginbaru.php");
    exit;
}

$user_name = $_SESSION['first_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesan Saya — Pawerti</title>
  <link rel="stylesheet" href="../../CSS/website.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #8B2500;
      --bg: #F9F5F0;
      --border: #E8DDD0;
    }
    *, *::before, *::after { box-sizing: border-box; }
    body { background: var(--bg); font-family: 'Poppins', sans-serif; margin: 0; overflow: hidden; }
    
    .mail-container {
      display: flex;
      height: 100vh;
      width: 100vw;
      background: white;
      overflow: hidden;
    }

    /* Sidebar List */
    .mail-sidebar {
      width: 400px;
      min-width: 300px;
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      background: #fff;
    }
    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .sidebar-header h2 { font-size: 18px; color: var(--primary); }
    
    .mail-list {
      flex: 1;
      overflow-y: auto;
    }
    .mail-item {
      padding: 15px 20px;
      border-bottom: 1px solid #f0f0f0;
      cursor: pointer;
      transition: background 0.2s;
      position: relative;
    }
    .mail-item:hover { background: #fafafa; }
    .mail-item.active { background: #fff5f2; border-left: 4px solid var(--primary); }
    .mail-item.unread { font-weight: 700; }
    .mail-item.unread::after {
      content: '';
      position: absolute;
      right: 15px; top: 50%;
      width: 8px; height: 8px;
      background: #ff4d4d;
      border-radius: 50%;
    }
    .mail-subject { font-size: 14px; margin-bottom: 4px; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mail-preview { font-size: 12px; color: #777; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .mail-date { font-size: 11px; color: #999; margin-top: 5px; }

    /* Content Area */
    .mail-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      background: white;
    }
    .content-header {
      padding: 20px 30px;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .content-body {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
    }
    .message-view {
      max-width: 1000px;
      margin: 0 auto;
      padding-bottom: 50px;
    }
    .view-subject { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 20px; }
    
    .bubble {
      padding: 20px;
      border-radius: 12px;
      margin-bottom: 20px;
      position: relative;
    }
    .bubble-user { background: #f5f5f5; border: 1px solid #eee; }
    .bubble-admin { background: #fff5f2; border: 1px solid #ffe0d6; }
    
    .bubble-meta { font-size: 12px; color: #999; margin-bottom: 10px; display: flex; justify-content: space-between; }
    .bubble-text { font-size: 15px; line-height: 1.6; color: #444; }
    .sender-name { font-weight: 600; color: var(--primary); }

    .empty-state {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100%;
      color: #ccc;
    }
    .empty-state i { font-size: 60px; margin-bottom: 20px; }

    .back-home {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--primary);
      text-decoration: none;
      font-weight: 500;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<?php include '../components/navbar.php' ?> 

<div class="mail-container">
  <!-- Sidebar -->
  <div class="mail-sidebar">
    <div class="sidebar-header">
      <h2>Kotak Pesan</h2>
      <a href="index.php" title="Kembali ke Beranda" style="color: var(--primary);"><i class="fas fa-home"></i></a>
    </div>
    <div class="mail-list" id="mailList">
      <!-- Dibatasi via JS -->
    </div>
  </div>

  <!-- Content -->
  <div class="mail-content" id="mailContent">
    <div class="empty-state">
      <i class="fas fa-envelope-open"></i>
      <p>Pilih pesan untuk membaca balasan</p>
    </div>
  </div>
</div>

<script>
  let allMessages = [];

  function loadMessages() {
    fetch('../../BACKEND/user_messages_api.php?action=list')
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          allMessages = data.data;
          renderList();
        }
      });
  }

  function renderList() {
    const list = document.getElementById('mailList');
    if (allMessages.length === 0) {
      list.innerHTML = '<div style="padding:20px; text-align:center; color:#999;">Belum ada pesan.</div>';
      return;
    }

    list.innerHTML = '';
    allMessages.forEach(msg => {
      const isUnread = msg.balasan && msg.is_read == 0;
      const item = document.createElement('div');
      item.className = `mail-item ${isUnread ? 'unread' : ''}`;
      item.onclick = () => viewMessage(msg.id);
      item.id = `msg-item-${msg.id}`;
      item.innerHTML = `
        <div class="mail-subject">${msg.subjek}</div>
        <div class="mail-preview">${msg.pesan}</div>
        <div class="mail-date">${msg.msg_date}</div>
      `;
      list.appendChild(item);
    });
  }

  function viewMessage(id) {
    const msg = allMessages.find(m => m.id == id);
    if (!msg) return;

    // Mark as active in UI
    document.querySelectorAll('.mail-item').forEach(i => i.classList.remove('active'));
    document.getElementById(`msg-item-${id}`).classList.add('active');

    const content = document.getElementById('mailContent');
    content.innerHTML = `
      <div class="content-body">
        <div class="message-view">
          <div class="view-subject">${msg.subjek}</div>
          
          <div class="bubble bubble-user">
            <div class="bubble-meta">
              <span class="sender-name">Anda</span>
              <span>${msg.msg_date}</span>
            </div>
            <div class="bubble-text">${msg.pesan}</div>
          </div>

          ${msg.balasan ? `
            <div class="bubble bubble-admin">
              <div class="bubble-meta">
                <span class="sender-name">Admin Pawerti</span>
                <span>${msg.reply_date}</span>
              </div>
              <div class="bubble-text">${msg.balasan}</div>
            </div>
          ` : `
            <div style="text-align:center; color:#999; font-size:13px; margin-top:40px;">
              <i class="fas fa-clock"></i> Menunggu balasan dari admin...
            </div>
          `}
        </div>
      </div>
    `;

    // Mark as read in DB if there is a reply
    if (msg.balasan && msg.is_read == 0) {
      fetch(`../../BACKEND/user_messages_api.php?action=mark_read&message_id=${id}`)
        .then(() => {
          msg.is_read = 1;
          document.getElementById(`msg-item-${id}`).classList.remove('unread');
        });
    }
  }

  loadMessages();
</script>

</body>
</html>
