<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modern Tech & SaaS Dashboard</title>

  <!-- Mandatory Fonts: Poppins (Bold Headings) & Inter (Body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

  <style>
    /* CSS Reset & Variables */
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      
      --header-bg: #0b3c85;        
      --header-text: #d1e1f8;       
      --header-hover: #1557b0;     
      --focus-outline: #ffd600;     
      
      --bg-body: #f1f5f9;           
      --card-bg: #ffffff;          
      --text-main: #031b4a;        
      --text-muted: #010c1bd9;        
      --accent-blue: #2563eb;      
      --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--bg-body);
      color: var(--text-main);
      line-height: 1.6;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    
    .skip-link {
      position: absolute;
      top: -60px;
      left: 15px;
      background: var(--focus-outline);
      color: #9cc6f376;
      padding: 10px 20px;
      z-index: 1000;
      font-weight: 700;
      text-decoration: none;
      border-radius: 6px;
      transition: top 0.2s ease-in-out;
    }
    .skip-link:focus {
      top: 15px;
    }

    /* Header Container & Layout */
    header {
      background-color: var(--header-bg);
      color: var(--header-text);
      padding: 1rem 2rem;
      box-shadow: 0 4px 12px rgba(187, 204, 238, 0.15);
    }

    .nav-container {
      max-width: 1400px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* Left Header Element: Logo exactly 90x90px */
    .logo-container {
      display: flex;
      align-items: center;
    }

    .logo-container img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      display: block;
      border-radius: 8px;
    }

    /* Right Header Elements */
    .nav-links {
      display: flex;
      list-style: none;
      gap: 1rem;
      align-items: center;
    }

    .nav-links a {
      color: var(--header-text);
      text-decoration: none;
      font-weight: 600;
      font-size: 1.05rem;
      padding: 0.75rem 1.25rem;
      border-radius: 8px;
      transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .nav-links a:hover {
      background-color: var(--header-hover);
    }

   
    a:focus-visible, button:focus-visible {
      outline: 3px solid var(--focus-outline);
      outline-offset: 3px;
    }

    /* Main Dashboard Layout */
    main {
      max-width: 1400px;
      margin: 3rem auto;
      padding: 0 1.5rem;
    }

    .page-header {
      margin-bottom: 2.5rem;
      text-align: center;
    }

    .page-title {
      font-size: 2.5rem;
      color: var(--text-main);
      margin-bottom: 0.5rem;
    }

    .page-subtitle {
      color: var(--text-muted);
      font-size: 1.15rem;
      max-width: 700px;
      margin: 0 auto;
    }

    /* Big Video Box Grid Layout */
    .video-grid {
      display: grid;
      grid-template-columns: 1fr; /* Single column layout for BIG prominent video boxes */
      gap: 3rem;
      max-width: 1000px;
      margin: 0 auto;
    }

    /* Vivid, Clean Video Card */
    .video-card {
      background: var(--card-bg);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
      border: 1px solid rgba(209, 222, 241, 0.87);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .video-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 30px -10px rgba(218, 241, 241, 0.98);
    }

    /* Large 16:9 Aspect Ratio Container */
    .iframe-wrapper {
      position: relative;
      width: 100%;
      padding-top: 56.25%; /* 16:9 aspect ratio */
      background-color: #c5d4dfb1;
    }

    .iframe-wrapper iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: 0;
    }

    .video-card-content {
      padding: 2rem;
    }

    .video-card-content h2 {
      font-size: 1.5rem;
      color: var(--text-main);
      margin-bottom: 0.5rem;
    }

    .video-card-content p {
      color: var(--text-muted);
      font-size: 1rem;
      font-weight: 500;
    }

    /* Responsive Breakdown for Smaller Screens */
    @media (max-width: 768px) {
      .nav-container {
        flex-direction: column;
        gap: 1.5rem;
      }
      
      .nav-links {
        flex-wrap: wrap;
        justify-content: center;
      }

      .page-title {
        font-size: 2rem;
      }

      .video-card-content {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

  <a href="#main-content" class="skip-link">Skip to main content</a>

  <!-- Header Section -->
  <header role="banner">
    <div class="nav-container">
      
      
      <div class="logo-container">
        <a href="/" aria-label="SaaS Home Page">
          <img src="logo.png" alt="Company Logo" width="100" height="100">
        </a>
      </div>

      <!-- Most Right: Navigation Links -->
      <nav role="navigation" aria-label="Main Navigation">
        <ul class="nav-links">
          <li><a href="dashboard.php">Dashboard</a></li>
          <li><a href="bookappointment.php">Book a Session</a></li>
          <li><a href="questionnaire.php">Fact Finder</a></li>
        </ul>
      </nav>

    </div>
  </header>

  <!-- Main Content Area -->
  <main id="main-content" tabindex="-1">
    
    <header class="page-header">
      <h1 class="page-title">Master Your Mind & Bodies</h1>
      <p class="page-subtitle">Science-Backed Breathing Exercises & Meditations for Total Calms</p>
    </header>

    <!-- Big Video Box Grid -->
    <section class="video-grid" aria-label="Featured Video Sessions">

      <!-- Big Video Box 1 -->
      <article class="video-card">
        <div class="iframe-wrapper">
          <iframe 
            src="https://www.youtube-nocookie.com/embed/Rt08wzTYKHg" 
            title="4-4-4-4 Breathing Exercise - Box Breathing for Calm and Focus by Pocket Breath Coach - Luke Horton" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
          </iframe>
        </div>
        <div class="video-card-content">
          <h2>4-4-4-4 Breathing Exercise | Box Breathing for Calm and Focus</h2>
          <p>By Pocket Breath Coach - Luke Horton</p>
        </div>
      </article>

      <!-- Big Video Box 2 -->
      <article class="video-card">
        <div class="iframe-wrapper">
          <iframe 
            src="https://www.youtube-nocookie.com/embed/O-6f5wQXSu8" 
            title="10-Minute Meditation For Anxiety by Goodful" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
          </iframe>
        </div>
        <div class="video-card-content">
          <h2>10-Minute Meditation For Anxiety</h2>
          <p>By Goodful [10:00]</p>
        </div>
      </article>

      <!-- Big Video Box 3 -->
      <article class="video-card">
        <div class="iframe-wrapper">
          <iframe 
            src="https://www.youtube-nocookie.com/embed/CpBYGrpsaIA" 
            title="মেডিটেশন : শিথিলায়ন শিথিল প্রক্রিয়া by Quantum Meditation for All" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
          </iframe>
        </div>
        <div class="video-card-content">
          <h2>মেডিটেশন : শিথিলায়ন শিথিল প্রক্রিয়া</h2>
          <p>By Quantum Meditation for All [00:31:09]</p>
        </div>
      </article>

    </section>

  </main>

</body>
</html>

