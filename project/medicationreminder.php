
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Medication Routine & Reminder</title>
  
  <!-- Fonts: Poppins (Headline) & Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

 <style>
  :root {
    --primary-blue: #0d47a1;
    --header-text: #ffffff;
    --header-hover: #e3f2fd;
    --text-main: #1f2937;
    --bg-main: #f8fafc;
    --card-bg: #ffffff;
    --border-color: #cbd5e1;
    --focus-ring: #0284c7;
    --danger-red: #c2410c;
    --danger-hover: #9a3412;
    --font-headline: 'Poppins', sans-serif;
    --font-body: 'Inter', sans-serif;
  }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: var(--font-body);
      color: var(--text-main);
      background-color: var(--bg-main);
      line-height: 1.5;
    }

    /* Accessibility Focus Styles (Section 508 / WCAG) */
    :focus-visible {
      outline: 3px solid var(--focus-ring);
      outline-offset: 2px;
    }

    /* Skip Navigation Link for Keyboard Accessibility */
    .skip-link {
      position: absolute;
      top: -40px;
      left: 1rem;
      background: #000;
      color: #fff;
      padding: 0.5rem 1rem;
      z-index: 1000;
      text-decoration: none;
      border-radius: 4px;
    }
    .skip-link:focus {
      top: 1rem;
    }

    /* Header Styling */
    header {
      background-color: var(--primary-blue);
      color: var(--header-text);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-logo img {
      max-height: 100px;
      width: 100px;
      display: block;
    }

    nav ul {
      display: flex;
      list-style: none;
      gap: 1.5rem;
      align-items: center;
    }

    nav a {
      color: var(--header-text);
      text-decoration: none;
      font-weight: 500;
      font-size: 0.95rem;
      padding: 0.5rem 0.75rem;
      border-radius: 4px;
      transition: background-color 0.2s, color 0.2s;
    }

    nav a:hover {
      background-color: rgba(255, 255, 255, 0.15);
      color: var(--header-hover);
    }

    /* Main Container */
    main {
      max-width: 900px;
      margin: 2.5rem auto;
      padding: 0 1.5rem;
    }

    h1, h2 {
      font-family: var(--font-headline);
      color: var(--primary-blue);
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 1.5rem;
    }

    h2 {
      font-size: 1.25rem;
      margin-bottom: 1rem;
    }

    /* Card Component */
    .card {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* Form Design */
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr auto;
      gap: 1rem;
      align-items: end;
    }

    @media (max-width: 640px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    label {
      font-weight: 600;
      font-size: 0.9rem;
    }

    input {
      font-family: var(--font-body);
      padding: 0.65rem 0.85rem;
      border: 1px solid var(--border-color);
      border-radius: 6px;
      font-size: 1rem;
      color: var(--text-main);
    }

    /* Buttons */
    .btn {
      font-family: var(--font-body);
      font-weight: 600;
      padding: 0.65rem 1.25rem;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      font-size: 0.95rem;
      transition: background-color 0.2s;
    }

    .btn-primary {
      background-color: var(--primary-blue);
      color: #ffffff;
    }

    .btn-primary:hover {
      background-color: #093170;
    }

    .btn-danger {
      background-color: var(--danger-red);
      color: #ffffff;
    }

    .btn-danger:hover {
      background-color: var(--danger-hover);
    }

    .btn-sm {
      padding: 0.35rem 0.65rem;
      font-size: 0.85rem;
    }

    /* Table Component */
    .table-header-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }

    .table-wrapper {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
    }

    th, td {
      padding: 0.85rem 1rem;
      border-bottom: 1px solid var(--border-color);
    }

    th {
      background-color: #f1f5f9;
      font-family: var(--font-headline);
      font-size: 0.9rem;
      font-weight: 600;
    }

    .empty-state {
      text-align: center;
      padding: 2rem;
      color: #64748b;
      font-style: italic;
    }

    /* ARIA Live Region (Screen Reader Only visually) */
    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }
  </style>
</head>
<body>

  <!-- WCAG Requirement: Skip to Content Link -->
  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- Header -->
  <header>
<a href="#">
  <img src="logo.png" alt="Company Logo" style="vertical-align: middle; width:90px; height:90px; margin-right:12px;">
  <span style="vertical-align: middle; font-family: var(--font-headline); font-size:30px; color:#ffffff;">InnerBloom</span>
</a>


    </div>
    <nav aria-label="Main Navigation">
      <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="bookappointment.php">Book a Session</a></li>
        <li><a href="questionnaire.php">Fact Finder</a></li>
      </ul>
    </nav>
  </header>

  <!-- Main Content Area -->
  <main id="main-content">
    <h1>Medication Routine & Reminder Queue</h1>

    <!-- Input Form Section -->
    <section class="card" aria-labelledby="form-heading">
      <h2 id="form-heading">Add New Medication</h2>
      <form id="medication-form" class="form-grid">
        <div class="form-group">
          <label for="med-name">Medication Name <span aria-hidden="true">*</span></label>
          <input type="text" id="med-name" required placeholder="e.g., Paracetamol 500mg" aria-required="true">
        </div>
        
        <div class="form-group">
          <label for="med-time">Taking Time <span aria-hidden="true">*</span></label>
          <input type="time" id="med-time" required aria-required="true">
        </div>

        <button type="submit" class="btn btn-primary">Add to Queue</button>
      </form>
    </section>

    <!-- Queue & Table Section -->
    <section class="card" aria-labelledby="queue-heading">
      <div class="table-header-container">
        <h2 id="queue-heading">Scheduled Routine Queue</h2>
        <button id="delete-last-btn" class="btn btn-danger btn-sm" style="display: none;">
          Delete Last Entry
        </button>
      </div>

      <div class="table-wrapper">
        <table id="med-table">
          <caption class="sr-only">List of scheduled medications and their intake times</caption>
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Medication Name</th>
              <th scope="col">Taking Time</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody id="queue-body">
            <!-- Dynamic rows inserted here -->
          </tbody>
        </table>
        <div id="empty-msg" class="empty-state">No medications added to the queue yet.</div>
      </div>
    </section>

    <!-- ARIA Live Region for Announcing Screen Reader Updates -->
    <div id="aria-announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>
  </main>

  <script>
    // State management array (Queue FIFO/LIFO structure)
    let medQueue = [];

    // DOM Elements
    const form = document.getElementById('medication-form');
    const medNameInput = document.getElementById('med-name');
    const medTimeInput = document.getElementById('med-time');
    const queueBody = document.getElementById('queue-body');
    const emptyMsg = document.getElementById('empty-msg');
    const deleteLastBtn = document.getElementById('delete-last-btn');
    const announcer = document.getElementById('aria-announcer');

    // Helper: Announce text to screen readers
    function announce(message) {
      announcer.textContent = message;
    }

    // Format 24-hour time to readable 12-hour AM/PM format
    function formatTime(timeStr) {
      if (!timeStr) return '';
      const [hours, minutes] = timeStr.split(':');
      const date = new Date();
      date.setHours(hours, minutes);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
    }

    // Render Table UI based on array state
    function renderQueue() {
      queueBody.innerHTML = '';

      if (medQueue.length === 0) {
        emptyMsg.style.display = 'block';
        deleteLastBtn.style.display = 'none';
        return;
      }

      emptyMsg.style.display = 'none';
      deleteLastBtn.style.display = 'inline-block';

      medQueue.forEach((item, index) => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
          <td>${index + 1}</td>
          <td><strong>${escapeHTML(item.name)}</strong></td>
          <td>${formatTime(item.time)}</td>
          <td>
            <button 
              class="btn btn-danger btn-sm" 
              onclick="deleteRow(${index})" 
              aria-label="Delete ${escapeHTML(item.name)} scheduled for ${formatTime(item.time)}">
              Delete
            </button>
          </td>
        `;
        queueBody.appendChild(tr);
      });
    }

    // Escape string helper to prevent XSS
    function escapeHTML(str) {
      return str.replace(/[&<>'"]/g, 
        tag => ({
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          "'": '&#39;',
          '"': '&quot;'
        }[tag] || tag)
      );
    }

    // Add Form Submit Listener
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const name = medNameInput.value.trim();
      const time = medTimeInput.value;

      if (name && time) {
        medQueue.push({ name, time });
        renderQueue();
        
        announce(`${name} scheduled for ${formatTime(time)} has been added.`);

        // Reset form
        medNameInput.value = '';
        medTimeInput.value = '';
        medNameInput.focus();
      }
    });

    // Delete Row by Specific Index
    window.deleteRow = function(index) {
      const removedItem = medQueue.splice(index, 1)[0];
      renderQueue();
      announce(`Removed ${removedItem.name} from schedule.`);
    };

    // Delete Last Entry (Pop from Queue)
    deleteLastBtn.addEventListener('click', () => {
      if (medQueue.length > 0) {
        const removedItem = medQueue.pop();
        renderQueue();
        announce(`Removed last entry: ${removedItem.name}.`);
      }
    });
  </script>
</body>
</html>