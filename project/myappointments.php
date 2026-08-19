        <?php 

include 'db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Appointments - InnerBloom</title>
  
 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      /* WCAG 2.1 AA Compliant Blue Palette */
      --primary-blue: #1d4ed8;       
      --dark-blue: #1e3a8a;          
      --light-blue: #eff6ff;         
      --border-color: #cbd5e1;       
      --text-dark: #0f172a;          
      --text-muted: #475569;         
      --white: #ffffff;
      --success-bg: #dcfce7;
      --success-text: #15803d;
      --focus-ring: #2563eb;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--light-blue);
      color: var(--text-dark);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Headlines set to Poppins (Bold) */
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
    }

    
    a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible {
      outline: 3px solid var(--focus-ring);
      outline-offset: 2px;
    }

    /* HEADER SECTION (Base Color: Blue) */
    header {
      background-color: var(--primary-blue);
      color: var(--white);
      padding: 12px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }

    /* Standardized Logo Size */
    .header-logo {
      width: 80px;
      height: 80px;
      object-fit: contain;
      border-radius: 6px;
    }

    .brand-name {
      color: var(--white);
      font-size: 22px;
      font-weight: 700;
      letter-spacing: -0.5px;
    }

    .header-nav {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .nav-link {
      color: var(--white);
      text-decoration: none;
      font-weight: 500;
      font-size: 14.5px;
      padding: 8px 12px;
      border-radius: 6px;
      transition: background-color 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.15);
    }

    .btn-book {
      background-color: var(--white);
      color: var(--primary-blue);
      font-weight: 600;
      border-radius: 6px;
      padding: 9px 16px;
      text-decoration: none;
      transition: background-color 0.2s ease;
    }

    .btn-book:hover {
      background-color: #f1f5f9;
    }

    /* MAIN CONTENT WORKSPACE */
    .container {
      max-width: 1280px;
      width: 100%;
      margin: 30px auto;
      padding: 0 20px;
      flex: 1;
    }

    .page-title {
      margin-bottom: 25px;
    }

    .page-title h1 {
      font-size: 26px;
      color: var(--dark-blue);
    }

    .page-title p {
      color: var(--text-muted);
      font-size: 14px;
      margin-top: 4px;
    }

    .card {
      background: var(--white);
      border-radius: 12px;
      padding: 24px;
      border: 1px solid var(--border-color);
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      margin-bottom: 25px;
    }

    /* INSERT & FILTER GRIDS */
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      align-items: end;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    label {
      font-size: 13.5px;
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 6px;
    }

    input, select {
      padding: 10px 12px;
      border: 1.5px solid var(--border-color);
      border-radius: 6px;
      font-size: 14px;
      color: var(--text-dark);
      background-color: var(--white);
      font-family: 'Inter', sans-serif;
    }

    .btn-submit {
      padding: 10px 16px;
      background-color: var(--primary-blue);
      color: var(--white);
      border: none;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      height: 42px;
      transition: background-color 0.2s ease;
    }

    .btn-submit:hover {
      background-color: var(--dark-blue);
    }

    /* APPOINTMENTS TABLE */
    .table-responsive {
      overflow-x: auto;
      margin-top: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 14px;
    }

    th {
      background-color: var(--light-blue);
      color: var(--dark-blue);
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      padding: 14px 16px;
      border-bottom: 2px solid var(--border-color);
    }

    td {
      padding: 14px 16px;
      border-bottom: 1px solid var(--border-color);
      vertical-align: middle;
    }

    tr:hover {
      background-color: #f8fafc;
    }

    .badge-type {
      background-color: #dbeafe;
      color: var(--primary-blue);
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
    }

    .status-confirmed {
      background-color: var(--success-bg);
      color: var(--success-text);
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
    }

    .empty-state {
      text-align: center;
      padding: 30px;
      color: var(--text-muted);
      font-size: 14px;
    }

    /* FOOTER */
    footer {
      background-color: var(--white);
      border-top: 1px solid var(--border-color);
      padding: 20px 50px;
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: var(--text-muted);
      margin-top: auto;
    }

    footer a {
      color: var(--text-muted);
      text-decoration: none;
      margin-right: 15px;
    }

    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- HEADER SECTION -->
  <header role="banner">
    <a href="dashboard.php" class="header-left" aria-label="InnerBloom Home">
      <img src="logo.png" alt="InnerBloom Logo" class="header-logo">
      <span class="brand-name">INNERBLOOM</span>
    </a>

    <nav class="header-nav" aria-label="Main Navigation">
      <a href="questionnaire.php" class="nav-link"><i class="fa-solid fa-clipboard-question" aria-hidden="true"></i> Questionnaire</a>
      <a href="dashboard.php" class="nav-link"><i class="fa-solid fa-chart-line" aria-hidden="true"></i> Dashboard</a>
      <a href="bookappointment.php" class="btn-book"><i class="fa-solid fa-calendar-plus" aria-hidden="true"></i> Book a Session</a>
    </nav>
  </header>

  <!-- MAIN CONTAINER -->
  <main class="container" id="main-content">
    
    <div class="page-title">
      <h1>My Scheduled Appointments</h1>
      <p>Add new sessions, search, filter, and track all your booked appointments day wise.</p>
    </div>

    <!-- NEW APPOINTMENT INSERT BAR / CARD -->
    <section class="card" aria-labelledby="add-heading">
      <h2 id="add-heading" style="font-size: 17px; color: var(--dark-blue); margin-bottom: 15px;">
        <i class="fa-solid fa-calendar-plus" aria-hidden="true"></i> Insert New Session / Appointment
      </h2>
      <form id="add-session-form">
        <div class="form-grid">
          <div class="form-group">
            <label for="newDocName">Doctor's Name</label>
            <input type="text" id="newDocName" placeholder="e.g. Dr. Ahsan Rahman" required>
          </div>
          <div class="form-group">
            <label for="newPatientName">Patient Name</label>
            <input type="text" id="newPatientName" placeholder="e.g. Muntaka Mayesha" required>
          </div>
          <div class="form-group">
            <label for="newDate">Date</label>
            <input type="date" id="newDate" value="2026-08-05" required>
          </div>
          <div class="form-group">
            <label for="newTime">Time</label>
            <input type="time" id="newTime" value="10:00" required>
          </div>
          <div class="form-group">
            <label for="newType">Session Type</label>
            <select id="newType" required>
              <option value="Psychiatrist">Psychiatrist</option>
              <option value="Psychologist">Psychologist</option>
              <option value="Counseling">Counseling</option>
              <option value="Therapy Session">Therapy Session</option>
              <option value="Follow-up">Follow-up</option>
            </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn-submit">
              <i class="fa-solid fa-plus" aria-hidden="true"></i> Insert Session
            </button>
          </div>
        </div>
      </form>
    </section>

    <!-- SEARCH & FILTER CONTROLS CARD -->
    <section class="card" aria-labelledby="search-heading">
      <h2 id="search-heading" style="font-size: 17px; color: var(--dark-blue); margin-bottom: 15px;">
        <i class="fa-solid fa-filter" aria-hidden="true"></i> Search & Day-wise Filter
      </h2>

      <div class="form-grid">
        <!-- Search Doctor or Patient -->
        <div class="form-group">
          <label for="searchInput">Search  Doctor By Name</label>
          <input type="text" id="searchInput" placeholder="Doctor or Patient Name..." onkeyup="filterAppointments()">
        </div>

        <!-- Search Booked Time -->
        <div class="form-group">
          <label for="timeInput">Search Booked Time</label>
          <input type="text" id="timeInput" placeholder="e.g. 10:00 AM" onkeyup="filterAppointments()">
        </div>

        <!-- Filter Specific Date -->
        <div class="form-group">
          <label for="dateFilter">Filter Specific Date</label>
          <input type="date" id="dateFilter" onchange="filterAppointments()">
        </div>

        <!-- Day Wise Sorting Option -->
        <div class="form-group">
          <label for="daySort">Day-Wise Sort</label>
          <select id="daySort" onchange="filterAppointments()">
            <option value="asc">Date: Earliest First</option>
            <option value="desc">Date: Latest First</option>
          </select>
        </div>
      </div>
    </section>

    <!-- APPOINTMENTS LIST CARD -->
    <section class="card" aria-labelledby="list-heading">
      <h2 id="list-heading" style="font-size: 17px; color: var(--dark-blue); margin-bottom: 15px;">
        <i class="fa-solid fa-calendar-check" aria-hidden="true"></i> Appointments Records (<span id="count-badge">10</span>)
      </h2>

      <div class="table-responsive">
        <table>
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Doctor Name</th>
              <th scope="col">Patient Name</th>
              <th scope="col">Date</th>
              <th scope="col">Booked Time</th>
              <th scope="col">Session Type</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody id="appointments-tbody">
            <!-- Dynamic JavaScript Rows -->
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <!-- FOOTER -->
  <footer role="contentinfo">
    <div>
      <a href="#">Terms of Service</a>
      <a href="#">Privacy Policy</a>
    </div>
    <div>&copy; 2026 InnerBloom Inc. All rights reserved.</div>
  </footer>

  <!-- DYNAMIC INSERT, SEARCH & FILTER JAVASCRIPT -->
  <script>
    // Initial Stored Appointments
    let appointmentsData = [
      { id: 1, doctor: "Dr. Ahsan Rahman", patient: "Muntaka Mayesha", date: "2026-08-05", time: "09:00 AM", type: "Psychiatrist", status: "Confirmed" },
      { id: 2, doctor: "Mr. John Smith", patient: "Muntaka Mayesha", date: "2026-08-05", time: "10:30 AM", type: "Psychologist", status: "Confirmed" },
      { id: 3, doctor: "Dr. Sarah Jenkins", patient: "Muntaka Mayesha", date: "2026-08-06", time: "01:15 PM", type: "Counseling", status: "Confirmed" },
      { id: 4, doctor: "Dr. Michael Chang", patient: "Muntaka Mayesha", date: "2026-08-06", time: "03:00 PM", type: "Therapy Session", status: "Confirmed" },
      { id: 5, doctor: "Dr. Emily Watson", patient: "Muntaka Mayesha", date: "2026-08-07", time: "11:00 AM", type: "Follow-up", status: "Confirmed" },
      { id: 6, doctor: "Mr. David Miller", patient: "Muntaka Mayesha", date: "2026-08-08", time: "02:30 PM", type: "Psychologist", status: "Confirmed" },
      { id: 7, doctor: "Dr. Jessica Taylor", patient: "Muntaka Mayesha", date: "2026-08-10", time: "09:15 AM", type: "Psychiatrist", status: "Confirmed" },
      { id: 8, doctor: "Dr. Robert Wilson", patient: "Muntaka Mayesha", date: "2026-08-12", time: "10:00 AM", type: "Counseling", status: "Confirmed" },
      { id: 9, doctor: "Dr. Amanda White", patient: "Muntaka Mayesha", date: "2026-08-15", time: "04:00 PM", type: "Therapy Session", status: "Confirmed" },
      { id: 10, doctor: "Dr. James Anderson", patient: "Muntaka Mayesha", date: "2026-08-20", time: "11:30 AM", type: "Follow-up", status: "Confirmed" }
    ];

    function renderTable(dataset) {
      const tbody = document.getElementById('appointments-tbody');
      document.getElementById('count-badge').textContent = dataset.length;

      if (dataset.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="empty-state">No matching appointments found.</td></tr>`;
        return;
      }

      tbody.innerHTML = dataset.map((item, idx) => `
        <tr>
          <td><strong>${idx + 1}</strong></td>
          <td><strong>${item.doctor}</strong></td>
          <td>${item.patient}</td>
          <td>${item.date}</td>
          <td>${item.time}</td>
          <td><span class="badge-type">${item.type}</span></td>
          <td><span class="status-confirmed">${item.status}</span></td>
        </tr>
      `).join('');
    }

    // Dynamic Multi-field Search and Day-wise Sorting Logic
    function filterAppointments() {
      const searchVal = document.getElementById('searchInput').value.toLowerCase();
      const timeVal = document.getElementById('timeInput').value.toLowerCase();
      const dateVal = document.getElementById('dateFilter').value;
      const sortVal = document.getElementById('daySort').value;

      let filtered = appointmentsData.filter(item => {
        const matchesName = item.doctor.toLowerCase().includes(searchVal) || item.patient.toLowerCase().includes(searchVal);
        const matchesTime = item.time.toLowerCase().includes(timeVal);
        const matchesDate = dateVal === "" || item.date === dateVal;

        return matchesName && matchesTime && matchesDate;
      });

      // Day-wise Sort Logic
      filtered.sort((a, b) => {
        const dateA = new Date(a.date);
        const dateB = new Date(b.date);
        return sortVal === 'asc' ? dateA - dateB : dateB - dateA;
      });

      renderTable(filtered);
    }

    // Insert New Session Event Handler
    document.getElementById('add-session-form').addEventListener('submit', function(e) {
      e.preventDefault();

      const doctor = document.getElementById('newDocName').value.trim();
      const patient = document.getElementById('newPatientName').value.trim();
      const date = document.getElementById('newDate').value;
      let rawTime = document.getElementById('newTime').value;
      const type = document.getElementById('newType').value;

      // Convert 24h HTML5 time input format to 12h AM/PM
      if (rawTime) {
        const [hours, minutes] = rawTime.split(':');
        const hourNum = parseInt(hours, 10);
        const ampm = hourNum >= 12 ? 'PM' : 'AM';
        const formattedHour = hourNum % 12 || 12;
        rawTime = `${String(formattedHour).padStart(2, '0')}:${minutes} ${ampm}`;
      }

      if (doctor && patient && date && rawTime) {
        const newSession = {
          id: appointmentsData.length + 1,
          doctor: doctor,
          patient: patient,
          date: date,
          time: rawTime,
          type: type,
          status: "Confirmed"
        };

        appointmentsData.unshift(newSession); // Add new entry to the front
        filterAppointments(); // Refresh view
        
        // Reset Doctor and Patient Name fields
        document.getElementById('newDocName').value = '';
        document.getElementById('newPatientName').value = '';
      }
    });

    // Initial Table Load
    document.addEventListener('DOMContentLoaded', () => {
      filterAppointments();
    });
  </script>
</body>
</html>
