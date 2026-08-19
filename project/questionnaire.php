<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="InnerBloom Mental Health Self-Assessment Portal">
  <title>Self-Assessment - InnerBloom</title>
  
 
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      /* WCAG 2.1 AA Compliant Palette */
      --primary-blue: #1d4ed8;       
      --dark-blue: #1e3a8a;          
      --light-blue: #eff6ff;         
      --border-color: #cbd5e1;       
      --text-dark: #0f172a;          
      --text-muted: #475569;         
      --white: #ffffff;
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
      line-height: 1.5;
    }

    /* Headline System */
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: var(--text-dark);
    }

    a:focus-visible, button:focus-visible, input:focus-visible {
      outline: 3px solid var(--focus-ring);
      outline-offset: 3px;
    }

    /* HEADER SECTION (Base Color: Blue) */
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

    .brand-container {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }

    .brand-logo {
      width: 90px;
      height: 90px;
      object-fit: contain;
      border-radius: 6px;
    }

    .brand-title {
      color: var(--white);
      font-size: 22px;
      font-weight: 700;
      letter-spacing: -0.5px;
    }

    .header-actions {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .nav-btn {
      color: var(--white);
      text-decoration: none;
      font-weight: 500;
      font-size: 14.5px;
      padding: 8px 14px;
      border-radius: 6px;
      transition: background-color 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .nav-btn:hover {
      background-color: rgba(255, 255, 255, 0.15);
    }

    .btn-book {
      background-color: var(--white);
      color: var(--primary-blue);
      font-weight: 600;
      border-radius: 6px;
      padding: 9px 16px;
      text-decoration: none;
      transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .btn-book:hover {
      background-color: #f1f5f9;
      transform: translateY(-1px);
    }

    /* MAIN CONTAINER */
    .workspace-container {
      max-width: 1280px;
      width: 100%;
      margin: 40px auto;
      padding: 0 24px;
      flex: 1;
    }

    .section-header {
      text-align: center;
      margin-bottom: 35px;
    }

    .section-header h1 {
      font-size: 28px;
      color: var(--dark-blue);
      margin-bottom: 8px;
    }

    .section-header p {
      color: var(--text-muted);
      font-size: 15px;
      max-width: 600px;
      margin: 0 auto;
    }

    /* ASSESSMENT CARDS GRID */
    .topics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
    }

    .topic-card {
      background: var(--white);
      border: 1px solid var(--border-color);
      border-radius: 12px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      text-decoration: none;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .topic-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
      border-color: var(--primary-blue);
    }

    .topic-card .card-emoji {
      font-size: 36px;
      margin-bottom: 12px;
      display: inline-block;
    }

    .topic-card h2 {
      font-size: 18px;
      color: var(--dark-blue);
      margin-bottom: 8px;
    }

    .topic-card p {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 16px;
      flex-grow: 1;
    }

    .topic-card .learn-more {
      font-size: 13.5px;
      color: var(--primary-blue);
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    /* FOOTER */
    footer {
      background-color: var(--white);
      border-top: 1px solid var(--border-color);
      padding: 24px 40px;
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
      }
      footer {
        flex-direction: column;
        gap: 12px;
        text-align: center;
      }
    }
  </style>
</head>
<body>

  <!-- HEADER NAVIGATION BAR -->
  <header class="header-bar" role="banner">
    <a href="dashboard.php" class="brand-container" aria-label="InnerBloom Homepage">
      <img src="logo.png" alt="InnerBloom Brand Logo" class="brand-logo">
      <span class="brand-title">INNERBLOOM</span>
    </a>

    <nav class="header-actions" aria-label="Main Navigation">
     <a href="dashboard.php" class="btn-book">
        <i class="fa-solid fa-chart-line" aria-hidden="true"></i>Dashboard
      </a>

      <a href="bookappointment.php" class="btn-book">
        <i class="fa-solid fa-calendar-plus" aria-hidden="true"></i> Book a Session
      </a>
    </nav>
  </header>

  <!-- MAIN WORKSPACE -->
  <main class="workspace-container" id="main-content">
    
    <div class="section-header">
      <h1>What Could Be Weighing You Down?</h1>
      <p>Select a topic below to begin your confidential assessment and find guidance tailored for you.</p>
    </div>

    <!-- CARDS GRID -->
    <section class="topics-grid" aria-label="Self Assessment Categories">
      
      <a href="achievement_pressure.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="Target">🎯</span>
          <h2>Achievement Pressure</h2>
          <p>High expectations and the constant need to perform can feel overwhelming.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="emotional.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="Broken Heart">💔</span>
          <h2>Emotional Abuse & Neglect</h2>
          <p>Emotional pain is real and valid. You deserve kindness and genuine support.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="bullying.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="Pensive Face">😔</span>
          <h2>Bullying & Social Rejection</h2>
          <p>No one should navigate life alone. Discover resources to stand strong against rejection.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="domestic.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="House">🏠</span>
          <h2>Domestic Violence</h2>
          <p>You have the absolute right to safety at home. Confidential support is here.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="personal.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="Thought Balloon">💭</span>
          <h2>Self-Related Issues</h2>
          <p>Navigating personal identity challenges is a journey shared by many.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="selfworth.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="Blue Heart">💙</span>
          <h2>Self-Worth Issues</h2>
          <p>You are inherently valuable. Your worth is never defined by external metrics.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="family.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="House with Garden">🏡</span>
          <h2>Home Environment Issues</h2>
          <p>A turbulent home life can impact mental health deeply. Find clarity and coping tools.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="bodyimage.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="Mirror">🪞</span>
          <h2>Body Image Concerns</h2>
          <p>You are far more than what you see in the mirror. Cultivate body neutrality and love.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="comparison.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="Busts in Silhouette">👥</span>
          <h2>Social Comparison</h2>
          <p>Constant comparison diminishes joy. Focus on your unique path and progress.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

      <a href="judgment.html" class="topic-card">
        <div>
          <span class="card-emoji" role="img" aria-label="Speech Balloon">💭</span>
          <h2>Fear of Judgment</h2>
          <p>Overcoming anxiety about others' opinions unlocks your true potential.</p>
        </div>
        <span class="learn-more">Learn More <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
      </a>

          </section>

  </main>

  <!-- ACCESSIBLE FOOTER -->
  <footer role="contentinfo">
    <div>
      <a href="#">Terms of Service</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Help Center</a>
    </div>
    <div>&copy; 2026 InnerBloom Support. All rights reserved.</div>
  </footer>

</body>
</html>