           <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors Directory - INNERBLOOM</title>
    
    <!-- MANDATORY FONTS: Poppins (Headlines) & Inter (Body Text) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #1c3c89;
            --primary-hover: #1d4ed8;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --border-radius: 14px;
            --font-headline: 'Poppins', sans-serif;
            --font-body: 'Inter', sans-serif;
            --focus-ring: 3px solid #60a5fa;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #c8ecf8;
            color: var(--text-dark);
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ACCESSIBILITY FOCUS STYLES (WCAG AA) */
        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, [tabindex="0"]:focus-visible {
            outline: var(--focus-ring);
            outline-offset: 2px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-headline);
            color: var(--text-dark);
        }

        /* --- HEADER --- */
        .top-header {
            background: linear-gradient(90deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            padding: 14px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo-img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .header-brand-name {
            color: #ffffff;
            font-family: var(--font-headline);
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .header-right-nav {
            display: flex;
            align-items: center;
            gap: 16px;
            list-style: none;
        }

        .nav-link-item {
            color: #9dd1f7;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link-item:hover, .nav-link-item.active {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 600;
        }

        /* --- MAIN LAYOUT --- */
        .container {
            max-width: 1240px;
            margin: 30px auto;
            padding: 0 20px;
            flex: 1;
            width: 100%;
        }

        .page-title-section {
            margin-bottom: 25px;
        }

        .page-title-section h2 {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary-blue);
        }

        .page-title-section p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .main-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 30px;
            align-items: start;
        }

        /* --- SIDEBAR FILTERS --- */
        .sidebar {
            background: var(--card-bg);
            padding: 24px;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-group h3 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--text-dark);
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper input {
            width: 100%;
            padding: 10px 35px 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            font-family: var(--font-body);
            outline: none;
            transition: border-color 0.2s;
        }

        .search-wrapper input:focus {
            border-color: var(--primary-blue);
        }

        .search-wrapper i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #334155;
            margin-bottom: 10px;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            accent-color: var(--secondary-blue);
            cursor: pointer;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #cbebfc;
            font-size: 14px;
            font-family: var(--font-body);
            color: #334155;
            cursor: pointer;
            outline: none;
        }

        .btn-apply-filters {
            width: 100%;
            padding: 12px;
            background: var(--secondary-blue);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            transition: background 0.2s;
        }

        .btn-apply-filters:hover {
            background: var(--primary-blue);
        }

        /* --- CONTENT AREA & TOOLBAR --- */
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: var(--card-bg);
            padding: 16px 20px;
            border-radius: var(--border-radius);
            border: 1px solid var(--border-color);
        }

        .results-count h3 {
            font-size: 16px;
            font-weight: 600;
        }

        .results-count p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .sort-by-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
        }

        .sort-by-wrapper select {
            width: auto;
            padding: 8px 16px;
        }

        /* --- DOCTORS GRID --- */
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .doctor-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            padding: 20px;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .doctor-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .img-container {
            width: 100%;
            height: 180px;
            border-radius: 10px;
            background-color: #f1f5f9;
            overflow: hidden;
            margin-bottom: 15px;
            position: relative;
        }

        .img-placeholder {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .status-dot {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 12px;
            height: 12px;
            background-color: var(--success-color);
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .doc-name {
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .verified-icon {
            color: var(--secondary-blue);
            font-size: 14px;
        }

        .doc-specialty {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 12px;
            font-weight: 500;
        }

        .doc-meta {
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 13px;
            color: #475569;
            margin-bottom: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .meta-item i {
            color: var(--text-muted);
            width: 16px;
            text-align: center;
        }

        .meta-item .fa-star {
            color: var(--warning-color);
        }

        .fee-section {
            border-top: 1px dashed var(--border-color);
            padding-top: 12px;
            margin-bottom: 15px;
        }

        .fee-label {
            font-size: 12px;
            color: var(--text-muted);
        }

        .fee-amount {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-top: 2px;
        }

        .card-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* VIEW PROFILE LINK BUTTON */
        .btn-view-profile {
            width: 100%;
            padding: 10px;
            background: #ffffff;
            border: 1px solid var(--secondary-blue);
            color: var(--secondary-blue);
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s ease;
        }

        .btn-view-profile:hover {
            background: #eff6ff;
            border-color: var(--primary-blue);
            color: var(--primary-blue);
        }

        /* BOOK APPOINTMENT LINK BUTTON */
        .btn-book {
            width: 100%;
            padding: 10px;
            background: var(--secondary-blue);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s ease;
        }

        .btn-book:hover {
            background: var(--primary-blue);
        }

        /* --- FOOTER --- */
        .site-footer {
            background: #0f172a;
            color: #ffffff;
            padding: 40px 20px;
            margin-top: 60px;
        }

        .footer-columns {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-col h4, .footer-col h3 {
            font-family: var(--font-headline);
            margin-bottom: 12px;
            color: #f8fafc;
        }

        .footer-col p {
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.8;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #334155;
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 900px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- TOP HEADER -->
    <header class="top-header">
        <div class="header-left">
            <img src="logo.png" alt="INNERBLOOM Logo" class="header-logo-img" onerror="this.src='https://cdn-icons-png.flaticon.com/512/3062/3062634.png'">
            <span class="header-brand-name">INNERBLOOM</span>
        </div>

        <nav aria-label="Header Navigation">
            <ul class="header-right-nav">
                <li>
                    <a href="dashboard.php" class="nav-link-item">
                        <i class="fa-solid fa-chart-line" aria-hidden="true"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="questionnaire.php" class="nav-link-item">
                        <i class="fa-solid fa-clipboard-question" aria-hidden="true"></i>
                        <span>Questionnaire</span>
                    </a>
                </li>
                <li>
                    <a href="myappointments.php" class="nav-link-item">
                        <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                        <span>My Appointments</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <!-- Page Title -->
        <div class="page-title-section">
            <h2>Doctors Directory</h2>
            <p>Find and connect with certified mental health specialists.</p>
        </div>

        <div class="main-layout">
            <!-- SIDEBAR FILTERS -->
            <aside class="sidebar" aria-label="Filters Workspace">
                <div class="filter-group">
                    <h3>Search Doctor</h3>
                    <div class="search-wrapper">
                        <input type="text" id="searchInput" placeholder="Search by name, specialty..." oninput="applyFilters()">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="filter-group">
                    <h3>Specialization</h3>
                    <label class="checkbox-label"><input type="checkbox" class="spec-filter" value="Psychiatrist" onchange="applyFilters()"> Psychiatrist</label>
                    <label class="checkbox-label"><input type="checkbox" class="spec-filter" value="Clinical Psychologist" onchange="applyFilters()"> Clinical Psychologist</label>
                    <label class="checkbox-label"><input type="checkbox" class="spec-filter" value="Psychotherapist" onchange="applyFilters()"> Psychotherapist</label>
                    <label class="checkbox-label"><input type="checkbox" class="spec-filter" value="Counselor" onchange="applyFilters()"> Counselor</label>
                    <label class="checkbox-label"><input type="checkbox" class="spec-filter" value="Therapist" onchange="applyFilters()"> Therapist</label>
                    <label class="checkbox-label"><input type="checkbox" class="spec-filter" value="Psychologist" onchange="applyFilters()"> Psychologist</label>
                </div>

                <div class="filter-group">
                    <h3>Min Rating</h3>
                    <select id="ratingFilter" onchange="applyFilters()">
                        <option value="0">All Ratings</option>
                        <option value="4.8">4.8 & above</option>
                        <option value="4.5">4.5 & above</option>
                    </select>
                </div>

                <div class="filter-group">
                    <h3>Experience</h3>
                    <select id="expFilter" onchange="applyFilters()">
                        <option value="0">All Experience</option>
                        <option value="5">5+ Years</option>
                        <option value="8">8+ Years</option>
                    </select>
                </div>

                <button class="btn-apply-filters" onclick="applyFilters()">
                    <i class="fa-solid fa-filter" aria-hidden="true"></i> Apply Filters
                </button>
            </aside>

            <!-- CATALOG CONTENT AREA -->
            <main class="content-area">
                <div class="content-header">
                    <div class="results-count">
                        <h3>Available Specialists</h3>
                        <p id="doctorCount">Showing all doctors</p>
                    </div>

                    <!-- SORT CONTROL -->
                    <div class="sort-by-wrapper">
                        <label for="sortBySelect"><i class="fa-solid fa-sort" aria-hidden="true"></i> Sort by:</label>
                        <select id="sortBySelect" onchange="applyFilters()">
                            <option value="popular">Popularity</option>
                            <option value="category">Category / Specialty</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="rating">Rating</option>
                        </select>
                    </div>
                </div>

                <!-- DOCTORS GRID -->
                <div class="doctors-grid" id="doctorsGrid">
                    <!-- Dynamic doctor cards injected via JavaScript -->
                </div>
            </main>
        </div>
    </div>

    <!-- SITE FOOTER -->
    <footer class="site-footer">
        <div class="footer-columns">
            <div class="footer-col">
                <h3>InnerBloom Mental Wellness</h3>
                <p>Compassionate care for a healthier, happier you.</p>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <p>Home</p>
                <p>About Us</p>
                <p>Services</p>
            </div>
            <div class="footer-col">
                <h4>Support</h4>
                <p>FAQ</p>
                <p>Privacy Policy</p>
                <p>Terms of Service</p>
            </div>
            <div class="footer-col">
                <h4>Contact Us</h4>
                <p>Email: support@innerbloom.com</p>
                <p>Location: Dhaka, Bangladesh</p>
            </div>
        </div>
        <div class="footer-bottom">
            © 2026 InnerBloom Mental Wellness. All rights reserved.
        </div>
    </footer>

    <!-- SCRIPT: DATASET + REAL-TIME SEARCH, FILTER, SORT & DIRECT PROFILE NAVIGATION -->
    <script>
        // Exact dataset mapping each doctor to their respective HTML profile page
        const doctorsData = [
            { id: 1,  name: "Dr. Farzana Islam",    specialty: "Clinical Psychologist", rating: 4.8, reviews: 98,  exp: 6,  location: "Dhaka",      fee: 1000, img: "female1.png", profile: "female1.html" },
            { id: 2,  name: "Dr. Nadia Sultana",    specialty: "Psychotherapist",       rating: 4.9, reviews: 110, exp: 7,  location: "Dhaka",      fee: 1100, img: "female2.png", profile: "female2.html" },
            { id: 3,  name: "Dr. Jannatul Ferdous", specialty: "Clinical Psychologist", rating: 4.7, reviews: 88,  exp: 5,  location: "Sylhet",     fee: 1000, img: "female3.png", profile: "female3.html" },
            { id: 4,  name: "Dr. Sadia Afrin",      specialty: "Counselor",             rating: 4.6, reviews: 64,  exp: 4,  location: "Chattogram", fee: 900,  img: "female4.png", profile: "female4.html" },
            { id: 5,  name: "Dr. Ahsan Rahman",     specialty: "Psychiatrist",          rating: 4.9, reviews: 120, exp: 8,  location: "Dhaka",      fee: 1200, img: "male1.png",   profile: "male1.html" },
            { id: 6,  name: "Dr. Imtiaz Ahmed",     specialty: "Counselor",             rating: 4.7, reviews: 76,  exp: 6,  location: "Dhaka",      fee: 800,  img: "male2.png",   profile: "male2.html" },
            { id: 7,  name: "Dr. Mahmudul Hasan",   specialty: "Psychiatrist",          rating: 4.6, reviews: 65,  exp: 6,  location: "Chattogram", fee: 1200, img: "male3.png",   profile: "male3.html" },
            { id: 8,  name: "Dr. Asif Zubayer",     specialty: "Therapist",             rating: 4.8, reviews: 84,  exp: 5,  location: "Khulna",     fee: 900,  img: "male4.png",   profile: "male4.html" },
            { id: 9,  name: "Dr. Tanvir Rahman",    specialty: "Psychiatrist",          rating: 4.9, reviews: 140, exp: 10, location: "Dhaka",      fee: 1500, img: "male5.png",   profile: "male5.html" },
            { id: 10, name: "Dr. Saimon Chowdhury", specialty: "Psychologist",          rating: 4.5, reviews: 48,  exp: 3,  location: "Rajshahi",   fee: 700,  img: "male6.png",   profile: "male6.html" },
            { id: 11, name: "Dr. Kazi Arif",        specialty: "Therapist",             rating: 4.7, reviews: 92,  exp: 7,  location: "Rangpur",    fee: 1000, img: "male7.png",   profile: "male7.html" },
            { id: 12, name: "Dr. Abir Hossain",     specialty: "Therapist",             rating: 4.7, reviews: 92,  exp: 7,  location: "Rangpur",    fee: 900,  img: "male8.png",   profile: "male8.html" }
        ];

        /**
         * Main Filtering & Sorting Pipeline
         */
        function applyFilters() {
            const searchVal = document.getElementById("searchInput").value.trim().toLowerCase();
            const minRating = parseFloat(document.getElementById("ratingFilter").value) || 0;
            const minExp = parseInt(document.getElementById("expFilter").value, 10) || 0;
            const sortVal = document.getElementById("sortBySelect").value;

            // Checked Specialization Filters
            const checkedSpecs = Array.from(document.querySelectorAll('.spec-filter:checked')).map(cb => cb.value);

            // 1. FILTERING 
            let filtered = doctorsData.filter(doc => {
                const matchesSearch = doc.name.toLowerCase().includes(searchVal) || 
                                      doc.specialty.toLowerCase().includes(searchVal) || 
                                      doc.location.toLowerCase().includes(searchVal);
                
                const matchesSpec = checkedSpecs.length === 0 || checkedSpecs.includes(doc.specialty);
                const matchesRating = doc.rating >= minRating;
                const matchesExp = doc.exp >= minExp;

                return matchesSearch && matchesSpec && matchesRating && matchesExp;
            });

            // 2. SORTING
            switch (sortVal) {
                case "category":
                    filtered.sort((a, b) => a.specialty.localeCompare(b.specialty));
                    break;
                case "price-low":
                    filtered.sort((a, b) => a.fee - b.fee);
                    break;
                case "price-high":
                    filtered.sort((a, b) => b.fee - a.fee);
                    break;
                case "rating":
                    filtered.sort((a, b) => b.rating - a.rating);
                    break;
                case "popular":
                default:
                    filtered.sort((a, b) => b.reviews - a.reviews);
                    break;
            }

            // UPDATE COUNTER
            document.getElementById("doctorCount").textContent = `${filtered.length} doctors available`;

            // 3. RENDER CARDS
            const grid = document.getElementById("doctorsGrid");
            if (filtered.length === 0) {
                grid.innerHTML = `
                    <div style="grid-column: 1/-1; text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 14px; color: var(--text-muted);">
                        <i class="fa-solid fa-user-doctor" style="font-size: 36px; margin-bottom: 12px; display: block; color: var(--secondary-blue);"></i>
                        No doctors found matching your filters.
                    </div>`;
                return;
            }

            grid.innerHTML = filtered.map(doc => `
                <div class="doctor-card">
                    <div>
                        <div class="img-container">
                            <img class="img-placeholder" 
                                 src="${doc.img}" 
                                 alt="${doc.name}" 
                                 onerror="this.src='https://images.unsplash.com/photo-1559839734-2b71ea197ec2?w=300&auto=format&fit=crop&q=80'">
                            <div class="status-dot" title="Available Online"></div>
                        </div>
                        <div class="doc-name">
                            ${doc.name} 
                            <i class="fa-solid fa-circle-check verified-icon" aria-hidden="true" title="Verified Specialist"></i>
                        </div>
                        <div class="doc-specialty">${doc.specialty}</div>
                        <div class="doc-meta">
                            <div class="meta-item">
                                <i class="fa-solid fa-star" aria-hidden="true"></i> 
                                ${doc.rating} (${doc.reviews} reviews)
                            </div>
                            <div class="meta-item">
                                <i class="fa-solid fa-briefcase" aria-hidden="true"></i> 
                                ${doc.exp} Years Experience
                            </div>
                            <div class="meta-item">
                                <i class="fa-solid fa-location-dot" aria-hidden="true"></i> 
                                ${doc.location}
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="fee-section">
                            <div class="fee-label">Consultation Fee</div>
                            <div class="fee-amount">৳ ${doc.fee}</div>
                        </div>
                        <div class="card-actions">
                            <a href="${doc.profile}" class="btn-view-profile">View Profile</a>
                            <a href="AppointmentForm.php?doc_id=${doc.id}&doc_name=${encodeURIComponent(doc.name)}" class="btn-book">Book Appointment</a>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Initial rendering on page load
        applyFilters();
    </script>
</body>
</html>
