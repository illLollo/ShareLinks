<style>
    * {
      box-sizing: border-box;
    }

    .sidebar {
      margin-top: 8vh;
      position: fixed;
      top: 20px;
      bottom: 20px;
      width: 30vw;
      min-width: 25vw;
      max-width: 55vw;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.15);
      padding: 20px;
      overflow-y: auto;
      resize: horizontal;
      overflow: auto;
      z-index: 1000;
      transition: transform 0.4s ease, opacity 0.4s ease;
      height: 85vh;
      overflow-y: scroll;
    }

    .sidebar.hidden {
      transform: translateX(-110%);
      opacity: 0;
    }

    .sidebar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .sidebar-header h2 {
      margin: 0;
      font-size: 18px;
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 22px;
      cursor: pointer;
    }

    .card {
      background: #f9f9f9;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 16px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .card h3 {
      margin-top: 0;
    }

    .content {
      margin-left: 20px;
      padding: 40px;
    }


    .toggle-btn.show {
      display: flex;
    }
  </style>
  <div class="sidebar-container">
  <div class="sidebar" style="<?php if ($direction == 'left') echo 'left: 20px;'; else echo 'right: 20px;'; ?>">
    <button class="close-btn" onclick="this.closest('.sidebar').classList.add('hidden'); this.parentElement.parentElement.querySelector('.sidebarToggleBtn').style.display = 'flex';">&times;</button>

