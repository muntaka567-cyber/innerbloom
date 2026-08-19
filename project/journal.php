<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blue SaaS Journal & Dream Logger</title>
  
  <!-- Fonts: Poppins (Headline) & Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-blue: #2563eb;
      --primary-hover: #1d4ed8;
      --header-blue: #5683ec;
      --text-navy: #0f172a;
      --text-sub-blue: #64748b;
      --bg-light-blue: #f8fafc;
      --card-white: #ffffff;
      --border-blue: #cbd5e1;
      --tint-blue: #f1f5f9;
      --focus-ring: #0284c7;
      --danger-red: #dc2626;
      --danger-hover: #b91c1c;
      --font-headline: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
      --font-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-body);
      color: var(--text-navy);
      background-color: var(--bg-light-blue);
      line-height: 1.5;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* WCAG Focus Styles */
    :focus-visible {
      outline: 3px solid var(--focus-ring);
      outline-offset: 2px;
    }

    /* WCAG Skip Link */
    .skip-link {
      position: absolute;
      top: -60px;
      left: 1rem;
      background: var(--primary-blue);
      color: #ffffff;
      padding: 0.5rem 1rem;
      z-index: 1000;
      text-decoration: none;
      border-radius: 4px;
      font-weight: 600;
      transition: top 0.2s ease-in-out;
    }
    .skip-link:focus { 
      top: 1rem; 
    }

    /* Header Styling */
    header {
      background-color: var(--header-blue);
      color: #ffffff;
      padding: 0.85rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
    }

    .header-logo a {
      color: #ffffff;
      text-decoration: none;
      font-family: var(--font-headline);
      font-weight: 700;
      font-size: 1.25rem;
      letter-spacing: -0.5px;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    .header-logo img {
      height: 90px;
      width: 90px;
      object-fit: contain;
      display: block;
    }

    nav ul {
      display: flex;
      list-style: none;
      gap: 0.75rem;
      align-items: center;
    }

    nav a {
      color: #e2e8f0;
      text-decoration: none;
      font-weight: 500;
      font-size: 0.9rem;
      padding: 0.5rem 0.85rem;
      border-radius: 6px;
      transition: background-color 0.2s, color 0.2s;
    }

    nav a:hover {
      background-color: rgba(255, 255, 255, 0.12);
      color: #ffffff;
    }

    /* App Container */
    .app-layout {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 1.5rem;
      max-width: 1350px;
      width: 100%;
      margin: 2rem auto;
      padding: 0 1.5rem;
      flex: 1;
    }

    @media (max-width: 980px) {
      .app-layout { 
        grid-template-columns: 1fr; 
      }
    }

    /* Cards */
    .card {
      background: var(--card-white);
      border: 1.5px solid var(--border-blue);
      border-radius: 12px;
      padding: 1.75rem;
      box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
      margin-bottom: 1.25rem;
    }

    h1, h2, h3 {
      font-family: var(--font-headline);
      color: var(--text-navy);
      line-height: 1.3;
    }

    h1 { font-size: 1.6rem; margin-bottom: 1.25rem; }
    h2 { font-size: 1.2rem; }
    h3 { font-size: 1rem; margin-top: 1rem; margin-bottom: 0.5rem; }

    /* Category Tabs */
    .editor-top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .category-tabs {
      display: flex;
      background: var(--tint-blue);
      padding: 4px;
      border-radius: 8px;
      border: 1px solid var(--border-blue);
      gap: 4px;
    }

    .tab-btn {
      background: transparent;
      border: none;
      color: var(--text-sub-blue);
      padding: 0.45rem 1rem;
      border-radius: 6px;
      font-family: var(--font-body);
      font-weight: 500;
      cursor: pointer;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }

    .tab-btn[aria-selected="true"] {
      background-color: var(--primary-blue);
      color: #ffffff;
      font-weight: 600;
    }

    .date-input-wrapper input[type="date"] {
      background: #ffffff;
      border: 1.5px solid var(--border-blue);
      border-radius: 8px;
      padding: 0.45rem 0.75rem;
      color: var(--text-navy);
      font-family: var(--font-body);
      font-weight: 500;
      font-size: 0.875rem;
    }

    /* Form Inputs */
    .title-input {
      width: 100%;
      background: #ffffff;
      border: 1.5px solid var(--border-blue);
      border-radius: 8px;
      color: var(--text-navy);
      font-family: var(--font-headline);
      font-size: 1.05rem;
      padding: 0.65rem 0.85rem;
      margin-bottom: 1rem;
      transition: border-color 0.2s ease;
    }

    .content-textarea {
      width: 100%;
      min-height: 180px;
      background: #ffffff;
      border: 1.5px solid var(--border-blue);
      border-radius: 8px;
      color: var(--text-navy);
      font-family: var(--font-body);
      font-size: 0.95rem;
      padding: 0.85rem;
      resize: vertical;
      margin-bottom: 1rem;
      transition: border-color 0.2s ease;
    }

    .title-input:focus,
    .content-textarea:focus {
      border-color: var(--primary-blue);
    }

    /* Image Dropzone & Preview */
    .dropzone {
      border: 2px dashed var(--border-blue);
      border-radius: 8px;
      padding: 1.5rem;
      text-align: center;
      background: var(--tint-blue);
      cursor: pointer;
      margin-bottom: 1.25rem;
      transition: background-color 0.2s, border-color 0.2s;
    }

    .dropzone.dragover {
      background-color: #e0f2fe;
      border-color: var(--primary-blue);
    }

    .dropzone p {
      color: var(--text-sub-blue);
      font-size: 0.875rem;
      margin-top: 0.5rem;
    }

    .image-preview-grid {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
      margin-top: 0.75rem;
      justify-content: center;
    }

    .image-preview-grid img {
      width: 65px;
      height: 65px;
      object-fit: cover;
      border-radius: 6px;
      border: 1px solid var(--border-blue);
    }

    /* Buttons */
    .btn {
      font-family: var(--font-headline);
      font-weight: 600;
      padding: 0.65rem 1.25rem;
      border-radius: 8px;
      border: 1px solid transparent;
      cursor: pointer;
      font-size: 0.95rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.2s, border-color 0.2s, color 0.2s;
    }

    .btn-primary {
      background-color: var(--primary-blue);
      color: #ffffff;
      width: 100%;
    }

    .btn-primary:hover { 
      background-color: var(--primary-hover); 
    }

    .btn-secondary {
      background-color: var(--tint-blue);
      color: var(--text-navy);
      border-color: var(--border-blue);
    }

    .btn-secondary:hover { 
      background-color: var(--border-blue); 
    }

    .btn-danger {
      background-color: transparent;
      color: var(--danger-red);
      border-color: #fecaca;
    }

    .btn-danger:hover {
      background-color: #fef2f2;
      border-color: var(--danger-red);
    }

    .btn-sm {
      padding: 0.35rem 0.65rem;
      font-size: 0.8rem;
    }

    /* Sidebar Journal List */
    .sidebar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      border-bottom: 1px solid var(--border-blue);
      padding-bottom: 0.75rem;
    }

    .journal-list {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      max-height: 550px;
      overflow-y: auto;
      padding-right: 4px;
    }

    .journal-card {
      background: var(--tint-blue);
      border: 1px solid var(--border-blue);
      border-radius: 8px;
      padding: 0.85rem;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      transition: transform 0.1s ease;
    }

    .journal-card:hover {
      border-color: var(--primary-blue);
    }

    .journal-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }

    .journal-card-title {
      font-weight: 600;
      font-size: 0.95rem;
      color: var(--text-navy);
      word-break: break-word;
    }

    .journal-card-meta {
      font-size: 0.75rem;
      color: var(--text-sub-blue);
      display: flex;
      gap: 0.5rem;
      align-items: center;
    }

    .badge {
      background: var(--card-white);
      border: 1px solid var(--border-blue);
      color: var(--text-navy);
      padding: 1px 6px;
      border-radius: 4px;
      font-weight: 600;
      font-size: 0.75rem;
    }

    .card-actions {
      display: flex;
      gap: 0.5rem;
      margin-top: 0.25rem;
    }

    /* Full View Journal Panel */
    .full-view-panel {
      display: none;
    }

    .full-view-header {
      display: flex;
      align-items: center;
      margin-bottom: 1.25rem;
    }

    .scrollable-body {
      max-height: 400px;
      overflow-y: auto;
      margin: 1rem 0;
      border: 1px solid var(--border-blue);
      border-radius: 8px;
      padding: 1.25rem;
      background: var(--tint-blue);
      white-space: pre-wrap;
      word-wrap: break-word;
      line-height: 1.6;
    }

    /* Scrollbars */
    .scrollable-body::-webkit-scrollbar,
    .journal-list::-webkit-scrollbar {
      width: 6px;
    }
    .scrollable-body::-webkit-scrollbar-thumb,
    .journal-list::-webkit-scrollbar-thumb {
      background: var(--border-blue);
      border-radius: 4px;
    }

    .full-gallery {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
      gap: 0.75rem;
      margin-top: 0.75rem;
    }

    .full-gallery img {
      width: 100%;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid var(--border-blue);
    }

    .empty-sidebar {
      text-align: center;
      color: var(--text-sub-blue);
      font-size: 0.85rem;
      padding: 2rem 0;
    }

    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0,0,0,0);
      white-space: nowrap;
      border: 0;
    }
  </style>
</head>
<body>

  <!-- WCAG Skip Navigation -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- Header -->
  <header>
    <div class="header-logo">
      <a href="#">
        <img src="logo.png" alt="SaaS Journal Logo">
        <span>InnerBloom</span>
      </a>
    </div>
    <nav aria-label="Main Navigation">
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="bookappointment.php">Sessions</a></li>
      
      </ul>
    </nav>
  </header>

  <!-- Main Application Container -->
  <div class="app-layout">
    
    <!-- Left Main Content (Editor + Full View Mode) -->
    <main id="main-content">
      
      <!-- 1. Editor Mode -->
      <section id="editor-section">
        <h1>Journal & Dream Entry</h1>
        <form id="journal-form" class="card">
          
          <!-- Category Tabs & Date -->
          <div class="editor-top-bar">
            <div class="category-tabs" role="tablist" aria-label="Entry Category">
              <button type="button" class="tab-btn" role="tab" aria-selected="true" data-cat="Dream">Dream</button>
              <button type="button" class="tab-btn" role="tab" aria-selected="false" data-cat="Vision">Vision</button>
              <button type="button" class="tab-btn" role="tab" aria-selected="false" data-cat="Experience">Experience</button>
            </div>

            <div class="date-input-wrapper">
              <label for="entry-date" class="sr-only">Date</label>
              <input type="date" id="entry-date" required>
            </div>
          </div>

          <!-- Title Input -->
          <label for="entry-title" class="sr-only">Title</label>
          <input type="text" id="entry-title" class="title-input" placeholder="Title..." required>

          <!-- Text Area -->
          <label for="entry-content" class="sr-only">Journal Content</label>
          <textarea id="entry-content" class="content-textarea" placeholder="What did you dream or experience..." required></textarea>

          <!-- Image Insertion Dropzone -->
          <div class="dropzone" id="dropzone" tabindex="0" role="button" aria-label="Upload Pictures">
            <svg width="24" height="24" fill="none" stroke="var(--primary-blue)" stroke-width="2" viewBox="0 0 24 24" style="margin:0 auto; display:block;">
              <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
            </svg>
            <p>Click or drop pictures here to attach to journal</p>
            <input type="file" id="file-input" accept="image/*" multiple style="display:none;">
            <div id="image-previews" class="image-preview-grid"></div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="btn btn-primary">Save & View Journal</button>
        </form>
      </section>

      <!-- 2. Full Journal View Mode (Hidden by default) -->
      <section id="full-view-section" class="full-view-panel">
        <div class="card">
          <div class="full-view-header">
            <button id="back-btn" class="btn btn-secondary btn-sm" aria-label="Go back to editor">
              ← Back to Editor
            </button>
          </div>

          <h2 id="view-title" style="font-size: 1.5rem; margin-bottom:0.25rem;"></h2>
          <div class="journal-card-meta" style="margin-bottom: 1rem;">
            <span id="view-badge" class="badge"></span>
            <span id="view-date"></span>
          </div>

          <!-- Scrollable Body for Previous Entry Text -->
          <div class="scrollable-body" id="view-content"></div>

          <!-- Attached Pictures Display -->
          <h3>Attached Pictures</h3>
          <div id="view-gallery" class="full-gallery"></div>
        </div>
      </section>

    </main>

    <!-- Right Sidebar Storage (Date & Title Wise) -->
    <aside class="card" aria-labelledby="sidebar-heading">
      <div class="sidebar-header">
        <h2 id="sidebar-heading">Stored Journals</h2>
      </div>
      <div id="journal-list" class="journal-list">
        <!-- Dynamically Rendered Storage Cards -->
      </div>
    </aside>

  </div>

  <!-- Accessibility Screen Reader Live Region -->
  <div id="aria-announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>

  <script>
    // State Store (Persisted via LocalStorage)
    let journals = JSON.parse(localStorage.getItem('blue_journals') || '[]');
    let currentCategory = 'Dream';
    let uploadedImages = [];
    let activeViewId = null;

    // DOM Elements
    const journalForm = document.getElementById('journal-form');
    const entryTitle = document.getElementById('entry-title');
    const entryContent = document.getElementById('entry-content');
    const entryDate = document.getElementById('entry-date');
    const journalList = document.getElementById('journal-list');
    const announcer = document.getElementById('aria-announcer');
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file-input');
    const imagePreviews = document.getElementById('image-previews');

    // View Mode Elements
    const editorSection = document.getElementById('editor-section');
    const fullViewSection = document.getElementById('full-view-section');
    const backBtn = document.getElementById('back-btn');
    const viewTitle = document.getElementById('view-title');
    const viewBadge = document.getElementById('view-badge');
    const viewDate = document.getElementById('view-date');
    const viewContent = document.getElementById('view-content');
    const viewGallery = document.getElementById('view-gallery');

    // Set Default Date to Today
    entryDate.value = new Date().toISOString().split('T')[0];

    function announce(msg) { 
      announcer.textContent = msg; 
    }

    // Category Tabs Switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        document.querySelectorAll('.tab-btn').forEach(b => b.setAttribute('aria-selected', 'false'));
        e.target.setAttribute('aria-selected', 'true');
        currentCategory = e.target.getAttribute('data-cat');
      });
    });

    // Image Upload Handling
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        fileInput.click();
      }
    });

    // Drag and Drop Events
    ['dragenter', 'dragover'].forEach(eventName => {
      dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
      });
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
      });
    });

    dropzone.addEventListener('drop', (e) => {
      if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
        handleFiles(Array.from(e.dataTransfer.files));
      }
    });

    fileInput.addEventListener('change', (e) => {
      handleFiles(Array.from(e.target.files));
    });

    function handleFiles(files) {
      const validImageFiles = files.filter(file => file.type.startsWith('image/'));
      validImageFiles.forEach(file => {
        const reader = new FileReader();
        reader.onload = (event) => {
          uploadedImages.push(event.target.result);
          renderImagePreviews();
        };
        reader.readAsDataURL(file);
      });
    }

    function renderImagePreviews() {
      imagePreviews.innerHTML = uploadedImages.map(imgSrc => `<img src="${imgSrc}" alt="Attached Preview">`).join('');
    }

    // Render Right Sidebar List
    function renderSidebar() {
      journalList.innerHTML = '';

      if (journals.length === 0) {
        journalList.innerHTML = '<div class="empty-sidebar">No stored journals yet.</div>';
        return;
      }

      // Sort Date-Wise (Most recent first)
      const sorted = [...journals].sort((a, b) => new Date(b.date) - new Date(a.date));

      sorted.forEach((item) => {
        const card = document.createElement('div');
        card.className = 'journal-card';

        card.innerHTML = `
          <div class="journal-card-header">
            <div class="journal-card-title">${escapeHTML(item.title)}</div>
          </div>
          <div class="journal-card-meta">
            <span class="badge">${escapeHTML(item.category)}</span>
            <span>${escapeHTML(item.date)}</span>
          </div>
          <div class="card-actions">
            <button class="btn btn-secondary btn-sm" onclick="openFullView('${item.id}')" aria-label="View journal ${escapeHTML(item.title)}">
              View
            </button>
            <button class="btn btn-danger btn-sm" onclick="deleteJournal('${item.id}')" aria-label="Delete journal ${escapeHTML(item.title)}">
              Delete
            </button>
          </div>
        `;
        journalList.appendChild(card);
      });
    }

    // Submit Form
    journalForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const title = entryTitle.value.trim();
      const content = entryContent.value.trim();
      const date = entryDate.value;

      if (!title || !content || !date) return;

      const newJournal = {
        id: Date.now().toString(),
        title,
        content,
        date,
        category: currentCategory,
        images: [...uploadedImages]
      };

      try {
        journals.unshift(newJournal);
        localStorage.setItem('blue_journals', JSON.stringify(journals));
      } catch (err) {
        alert('Storage limit exceeded. Try adding fewer or smaller images.');
        journals.shift();
        return;
      }

      renderSidebar();
      announce(`Saved journal titled ${title}`);

      // Open in full view immediately
      openFullView(newJournal.id);

      // Reset Editor Form
      entryTitle.value = '';
      entryContent.value = '';
      uploadedImages = [];
      imagePreviews.innerHTML = '';
      fileInput.value = '';
    });

    // Open Full View Mode
    window.openFullView = function(id) {
      const item = journals.find(j => j.id === id);
      if (!item) return;

      activeViewId = id;
      viewTitle.textContent = item.title;
      viewBadge.textContent = item.category;
      viewDate.textContent = item.date;
      viewContent.textContent = item.content;

      // Gallery Display
      if (item.images && item.images.length > 0) {
        viewGallery.innerHTML = item.images.map(src => `<img src="${src}" alt="Journal Image Attachment">`).join('');
      } else {
        viewGallery.innerHTML = '<p style="font-size:0.85rem; color:var(--text-sub-blue);">No pictures attached to this entry.</p>';
      }

      editorSection.style.display = 'none';
      fullViewSection.style.display = 'block';
      announce(`Viewing full journal titled ${item.title}`);
    };

    // Back Button Event
    backBtn.addEventListener('click', () => {
      fullViewSection.style.display = 'none';
      editorSection.style.display = 'block';
      activeViewId = null;
      announce("Returned to editor");
    });

    // Delete Row
    window.deleteJournal = function(id) {
      const item = journals.find(j => j.id === id);
      journals = journals.filter(j => j.id !== id);
      
      try {
        localStorage.setItem('blue_journals', JSON.stringify(journals));
      } catch (err) {
        console.error("Storage error updating list", err);
      }

      renderSidebar();

      if (item) announce(`Deleted journal titled ${item.title}`);
      
      // Only switch back to editor if the currently displayed journal was the one deleted
      if (activeViewId === id) {
        backBtn.click();
      }
    };

    // Escape HTML Helper
    function escapeHTML(str) {
      return str.replace(/[&<>'"]/g, tag => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;'
      }[tag] || tag));
    }

    // Initial render
    renderSidebar();
  </script>
</body>
</html>