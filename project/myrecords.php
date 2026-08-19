<?php 
// Section 1: Database Setup — Establishes persistent MySQL connection ($conn) for session & profile operations.
include 'db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Fact Finder - Document Vault - InnerBloom</title>
  
  <!-- 1. MANDATORY SAAS FONTS: Poppins (Headline Bold) & Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    /* 2. WCAG 2.1 AA & SECTION 508 COLOR PALETTE & RESET */
    :root {
      --primary-blue: #1d4ed8;       
      --dark-blue: #1e3a8a;          
      --light-blue: #eff6ff;         
      --border-color: #cbd5e1;       
      --text-dark: #0f172a;          
      --text-muted: #475569;         
      --white: #ffffff;
      --focus-ring: #60a5fa;
      --error-red: #b91c1c;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif; /* 1. Body Font: Inter */
      background-color: var(--light-blue);
      color: var(--text-dark);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* 1. Headline Font: Poppins Bold */
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: var(--text-dark);
    }

    /* 2. Keyboard Navigation Focus Indicator */
    a:focus-visible, button:focus-visible, input:focus-visible, label:focus-visible {
      outline: 3px solid var(--focus-ring);
      outline-offset: 3px;
    }

    /* 3. HEADER SECTION (Base Color: Blue) */
    .header-bar {
      background-color: var(--primary-blue);
      color: var(--white);
      padding: 12px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    /* 4.1 Most Left Element: logo.png */
    .brand-container {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }

    .brand-logo {
      width: 40px;
      height: 40px;
      object-fit: contain;
      border-radius: 6px;
    }

    .brand-title {
      font-family: 'Poppins', sans-serif;
      color: var(--white);
      font-size: 20px;
      font-weight: 700;
      letter-spacing: -0.3px;
    }

    /* 4.2 Most Right Elements Navigation */
    .header-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .btn-nav {
      background-color: var(--white);
      color: var(--primary-blue);
      font-family: 'Inter', sans-serif;
      font-weight: 600;
      font-size: 14px;
      border-radius: 6px;
      padding: 8px 14px;
      text-decoration: none;
      transition: background-color 0.2s ease, transform 0.1s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-nav:hover {
      background-color: #f1f5f9;
      transform: translateY(-1px);
    }

    /* MAIN CONTENT CONTAINER */
    .workspace-container {
      max-width: 1000px;
      width: 100%;
      margin: 40px auto;
      padding: 0 20px;
      flex: 1;
    }

    /* CARD CONTAINER */
    .card {
      background: var(--white);
      border-radius: 16px;
      padding: 32px;
      border: 1px solid var(--border-color);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      width: 100%;
    }

    .vault-header h2 {
      font-size: 20px;
      color: var(--dark-blue);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .vault-header p {
      font-size: 14px;
      color: var(--text-muted);
      margin-top: 4px;
      margin-bottom: 24px;
    }

    /* STORAGE GRID */
    .storage-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 24px;
    }

    @media (min-width: 768px) {
      .storage-grid {
        grid-template-columns: 1fr 1fr;
      }
    }

    /* LEFT DROPZONE AREA */
    .upload-dropzone {
      border: 2px dashed #2563eb;
      background-color: #ffffff;
      border-radius: 12px;
      padding: 32px 20px;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .cloud-icon {
      width: 48px;
      height: 48px;
      background-color: var(--primary-blue);
      color: var(--white);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      margin-bottom: 12px;
    }

    .upload-title {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 16px;
      color: var(--text-dark);
      margin-bottom: 4px;
    }

    .upload-subtitle {
      font-size: 13px;
      color: var(--text-muted);
      margin-bottom: 16px;
    }

    .file-upload-btn {
      background-color: var(--primary-blue);
      color: var(--white);
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 14px;
      padding: 10px 24px;
      border-radius: 8px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background-color 0.2s ease;
    }

    .file-upload-btn:hover {
      background-color: var(--dark-blue);
    }

    .hidden-input {
      display: none;
    }

    /* RIGHT STORED DOCUMENTS VAULT */
    .stored-files-card {
      background-color: #f8fafc;
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }

    .vault-top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid var(--border-color);
      padding-bottom: 12px;
      margin-bottom: 16px;
    }

    .vault-top-bar span.title {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 15px;
      color: var(--dark-blue);
    }

    .vault-top-bar span.badge {
      font-size: 12px;
      font-weight: 600;
      background-color: var(--light-blue);
      color: var(--primary-blue);
      padding: 2px 10px;
      border-radius: 12px;
    }

    .file-list-container {
      max-height: 250px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 12px;
      padding-right: 6px;
    }

    /* Scrollbar styling */
    .file-list-container::-webkit-scrollbar {
      width: 8px;
    }
    .file-list-container::-webkit-scrollbar-thumb {
      background-color: #94a3b8;
      border-radius: 4px;
    }

    .file-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 16px;
      background: var(--white);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      font-size: 14px;
    }

    .file-item-top {
      border-left: 4px solid var(--primary-blue);
    }

    .file-info {
      display: flex;
      align-items: center;
      gap: 12px;
      overflow: hidden;
    }

    .file-info i {
      font-size: 22px;
      color: var(--primary-blue);
    }

    .file-details {
      overflow: hidden;
    }

    .last-uploaded-tag {
      display: block;
      font-size: 10px;
      font-weight: 700;
      color: var(--primary-blue);
      letter-spacing: 0.5px;
    }

    .file-name {
      font-weight: 600;
      color: var(--text-dark);
      white-space: nowrap;
      text-overflow: ellipsis;
      overflow: hidden;
      display: block;
    }

    .btn-delete-file {
      background-color: var(--error-red);
      color: var(--white);
      border: none;
      padding: 6px 14px;
      border-radius: 6px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: background-color 0.2s;
    }

    .btn-delete-file:hover {
      background-color: #991b1b;
    }

    .btn-remove-last {
      background-color: var(--dark-blue);
      color: var(--white);
      font-family: 'Poppins', sans-serif;
      border: none;
      padding: 12px 20px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      margin-top: 16px;
      width: 100%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: background-color 0.2s ease;
    }

    .btn-remove-last:hover {
      background-color: var(--primary-blue);
    }

    /* FOOTER */
    footer {
      background-color: var(--white);
      border-top: 1px solid var(--border-color);
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13.5px;
      color: var(--text-muted);
      margin-top: auto;
    }

    footer a {
      color: var(--text-muted);
      text-decoration: none;
      margin-right: 16px;
    }

    footer a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .header-bar {
        padding: 12px 20px;
        flex-direction: column;
        gap: 12px;
      }
      footer {
        flex-direction: column;
        gap: 10px;
        text-align: center;
      }
    }
  </style>
</head>
<body>

  <!-- 3. BLUE HEADER BAR -->
  <header class="header-bar" role="banner">
    <!-- 4.1 Most Left: logo.png -->
    <a href="INDEX.PHP" class="brand-container">
      <img src="logo.png" alt="InnerBloom Logo" class="brand-logo">
      <span class="brand-title">InnerBloom</span>
    </a>

    <!-- 4.2 Most Right Navigation Links -->
    <nav class="header-actions" aria-label="Main Navigation">
      <!-- 4.4 Fact Finder Link -->
      <a href="questionnaire.php" class="btn-nav">
        <i class="fa-solid fa-lightbulb" aria-hidden="true"></i> Fact Finder
      </a>

      <!-- 4.2.a Dashboard Link -->
      <a href="dashboard.phpca" class="btn-nav">
        <i class="fa-solid fa-chart-line" aria-hidden="true"></i> Dashboard
      </a>

      <!-- 4.3 Book a Session Link -->
      <a href="bookappointment.php" class="btn-nav">
        <i class="fa-solid fa-calendar-plus" aria-hidden="true"></i> Book a Session
      </a>
    </nav>
  </header>

  <!-- MAIN STORAGE VAULT SECTION -->
  <main class="workspace-container">
    <div class="card">
      <div class="vault-header">
        <h2><i class="fa-solid fa-box-archive" aria-hidden="true"></i> Medical Document Storage Vault</h2>
        <p>Upload, store, and manage your health files securely in your active session storage.</p>
      </div>

      <div class="storage-grid">
        <!-- Upload Dropzone -->
        <div class="upload-dropzone">
          <div class="cloud-icon">
            <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
          </div>
          <div class="upload-title">Upload Health Files</div>
          <div class="upload-subtitle">Supported: PDF, PNG, JPG (Max 15MB)</div>
          
          <label class="file-upload-btn">
            <i class="fa-solid fa-folder-open" aria-hidden="true"></i> Choose File
            <input type="file" id="fileInput" class="hidden-input" onchange="uploadFile(this)">
          </label>
        </div>

        <!-- Stored Documents Vault -->
        <div class="stored-files-card">
          <div class="vault-top-bar">
            <span class="title">Stored Documents</span>
            <span id="fileCountBadge" class="badge">4 Files</span>
          </div>

          <div class="file-list-container" id="fileListContainer">
            <!-- Dynamic Files Rendered via JavaScript -->
          </div>

          <button type="button" class="btn-remove-last" onclick="removeLastFile()">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Remove Last Uploaded File
          </button>
        </div>
      </div>
    </div>
  </main>

  <!-- ACCESSIBLE FOOTER -->
  <footer role="contentinfo">
    <div>
      <a href="#">Terms of Service</a>
      <a href="#">Privacy Policy</a>
    </div>
    <div>&copy; 2026 InnerBloom Support. All rights reserved.</div>
  </footer>

  <script>
    // LocalStorage LIFO Stack implementation
    let fileStorage = JSON.parse(localStorage.getItem("innerbloom_vault_files")) || [
      { id: 1, name: "Initial_Intake.pdf" },
      { id: 2, name: "Prescription_Aug.pdf" },
      { id: 3, name: "Checkup_Report_June.pdf" },
      { id: 4, name: "MRI_Results_Brain.pdf" }
    ];

    function saveStorage() {
      localStorage.setItem("innerbloom_vault_files", JSON.stringify(fileStorage));
      renderFiles();
    }

    function renderFiles() {
      const container = document.getElementById("fileListContainer");
      const badge = document.getElementById("fileCountBadge");
      container.innerHTML = "";

      badge.textContent = `${fileStorage.length} File${fileStorage.length !== 1 ? 's' : ''}`;

      if (fileStorage.length === 0) {
        container.innerHTML = "<p style='font-size: 13px; color: var(--text-muted); text-align: center; padding: 20px;'>No documents stored in vault.</p>";
        return;
      }

      // Render LIFO order (Last Uploaded on top)
      [...fileStorage].reverse().forEach((file, index) => {
        const isTop = index === 0;
        const div = document.createElement("div");
        div.className = `file-item ${isTop ? 'file-item-top' : ''}`;
        
        div.innerHTML = `
          <div class="file-info">
            <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
            <div class="file-details">
              ${isTop ? '<span class="last-uploaded-tag">LAST UPLOADED</span>' : ''}
              <span class="file-name" style="font-weight:${isTop ? '700' : '600'};">${file.name}</span>
            </div>
          </div>
          <button type="button" class="btn-delete-file" onclick="deleteSingleFile(${file.id})">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Delete
          </button>
        `;
        container.appendChild(div);
      });
    }

    function uploadFile(input) {
      if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const newFile = {
          id: Date.now(),
          name: fileName
        };
        fileStorage.push(newFile);
        saveStorage();
        input.value = "";
      }
    }

    function deleteSingleFile(id) {
      fileStorage = fileStorage.filter(f => f.id !== id);
      saveStorage();
    }

    function removeLastFile() {
      if (fileStorage.length > 0) {
        fileStorage.pop();
        saveStorage();
      } else {
        alert("Vault is empty!");
      }
    }

    // Initial render
    renderFiles();
  </script>
</body>
</html>
