<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>HRIS - LGU Polangui | Human Resource Information System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary: #3172b7;
      --primary-dark: #245c9c;
      --primary-light: #4a8fd4;
      --secondary: #003f5c;
      --accent: #00C9A7;
      --light: #eaf6fc;
      --white: #ffffff;
      --gray: #6C757D;
      --dark: #343A40;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      --shadow-lg: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body,
    html {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow-x: hidden;
      color: var(--dark);
    }

    /* HEADER STYLING */
    header {
      position: fixed;
      top: 0;
      width: 100%;
      background: linear-gradient(135deg, var(--white) 0%, var(--light) 100%);
      padding: 12px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 1000;
      border-bottom: 1px solid rgba(193, 199, 204, 0.5);
      box-shadow: var(--shadow);
      backdrop-filter: blur(10px);
    }

    .header-left {
      display: flex;
      align-items: center;
    }

    .header-left img {
      height: 65px;
      margin-right: 15px;
      transition: transform 0.3s ease;
    }

    .header-left img:hover {
      transform: scale(1.05);
    }

    .header-text {
      font-size: 14px;
      line-height: 1.4;
      color: var(--secondary);
    }

    .header-text strong {
      display: block;
      font-size: 15px;
      font-weight: 700;
    }

    .header-right {
      display: flex;
      gap: 12px;
    }

    .header-btn {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: var(--white);
      border: none;
      padding: 10px 20px;
      border-radius: 30px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
    }

    .header-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .header-btn:hover::before {
      left: 100%;
    }

    .header-btn:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    /* HERO SECTION */
    .hero {
      height: 100vh;
      width: 100%;
      position: relative;
      overflow: hidden;
    }

    .slideshow {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 300%;
      display: flex;
      animation: slideLeft 45s linear infinite;
      z-index: 0;
    }

    .slideshow img {
      width: 100vw;
      height: 100vh;
      object-fit: cover;
      filter: brightness(0.7);
    }

    .hero-content {
      position: relative;
      z-index: 1;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: var(--white);
      text-align: center;
      padding: 100px 20px 40px;
      background: linear-gradient(135deg, rgba(0, 63, 92, 0.7) 0%, rgba(49, 114, 183, 0.5) 100%);
    }

    .logo-container {
      position: relative;
      margin-bottom: 30px;
    }

    .logo-container img {
      width: 180px;
      height: 180px;
      object-fit: cover;
      border-radius: 50%;
      border: 5px solid var(--white);
      box-shadow: var(--shadow-lg);
      transition: all 0.5s ease;
    }

    .logo-container::after {
      content: '';
      position: absolute;
      top: -10px;
      left: -10px;
      right: -10px;
      bottom: -10px;
      border-radius: 50%;
      border: 2px solid rgba(255, 255, 255, 0.3);
      animation: pulse 2s infinite;
    }

    .hero-content h1 {
      font-size: 42px;
      margin-bottom: 20px;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
      font-weight: 700;
      line-height: 1.2;
      max-width: 800px;
    }

    .hero-subtitle {
      font-size: 20px;
      margin-bottom: 40px;
      text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
      font-weight: 300;
      max-width: 600px;
    }

    .buttons {
      display: flex;
      gap: 25px;
      margin-top: 20px;
    }

    .btn {
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: var(--white);
      border: none;
      padding: 16px 32px;
      font-weight: 600;
      border-radius: 8px;
      box-shadow: var(--shadow);
      cursor: pointer;
      font-size: 16px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }

    .btn:hover::before {
      left: 100%;
    }

    .btn:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-lg);
    }

    .btn-admin {
      background: linear-gradient(135deg, var(--accent) 0%, #00b894 100%);
    }

    /* FEATURES SECTION */
    .features {
      background: var(--white);
      padding: 80px 40px;
      text-align: center;
    }

    .section-title {
      font-size: 32px;
      color: var(--secondary);
      margin-bottom: 15px;
      font-weight: 700;
    }

    .section-subtitle {
      font-size: 18px;
      color: var(--gray);
      margin-bottom: 50px;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .feature-card {
      background: var(--white);
      border-radius: 12px;
      padding: 30px;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      border-top: 4px solid var(--primary);
    }

    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: var(--shadow-lg);
    }

    .feature-icon {
      font-size: 40px;
      color: var(--primary);
      margin-bottom: 20px;
    }

    .feature-title {
      font-size: 20px;
      font-weight: 600;
      color: var(--secondary);
      margin-bottom: 15px;
    }

    .feature-description {
      color: var(--gray);
      line-height: 1.6;
    }

    /* FOOTER */
    footer {
      background: linear-gradient(135deg, var(--secondary) 0%, var(--primary-dark) 100%);
      color: var(--white);
      padding: 40px 20px;
      text-align: center;
    }

    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
    }

    .footer-logo {
      width: 80px;
      margin-bottom: 20px;
    }

    .footer-text {
      font-size: 14px;
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .footer-links {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
    }

    .footer-link {
      color: var(--white);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-link:hover {
      color: var(--accent);
    }

    /* ANIMATIONS */
    @keyframes slideLeft {
      0% {
        transform: translateX(0);
      }

      33% {
        transform: translateX(-100vw);
      }

      66% {
        transform: translateX(-200vw);
      }

      100% {
        transform: translateX(0);
      }
    }

    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 1;
      }

      50% {
        transform: scale(1.05);
        opacity: 0.7;
      }

      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 1024px) {
      .hero-content h1 {
        font-size: 36px;
      }

      .hero-subtitle {
        font-size: 18px;
      }
    }

    @media (max-width: 768px) {
      header {
        padding: 10px 20px;
        flex-direction: column;
        gap: 15px;
      }

      .header-right {
        width: 100%;
        justify-content: center;
      }

      .hero-content {
        padding: 120px 20px 40px;
      }

      .hero-content h1 {
        font-size: 28px;
      }

      .hero-subtitle {
        font-size: 16px;
      }

      .logo-container img {
        width: 140px;
        height: 140px;
      }

      .buttons {
        flex-direction: column;
        gap: 15px;
      }

      .btn {
        width: 220px;
        justify-content: center;
      }

      .features {
        padding: 60px 20px;
      }

      .features-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .header-text {
        font-size: 12px;
      }

      .header-text strong {
        font-size: 13px;
      }

      .header-btn {
        padding: 8px 16px;
        font-size: 12px;
      }

      .hero-content h1 {
        font-size: 24px;
      }

      .section-title {
        font-size: 26px;
      }

      .footer-links {
        flex-direction: column;
        gap: 10px;
      }
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header>
    <div class="header-left">
      <img src="assets/images/logo.png" alt="LGU Polangui Logo">
      <div class="header-text">
        <strong>REPUBLIC OF THE PHILIPPINES<br>
          MUNICIPALITY OF POLANGUI</strong>
        PROVINCE OF ALBAY
      </div>
    </div>
    <div class="header-right">
      <button class="header-btn"><i class="fas fa-info-circle"></i> About Us</button>
      <button class="header-btn"><i class="fas fa-phone-alt"></i> Contact</button>
    </div>
  </header>

  <!-- HERO SECTION -->
  <div class="hero">
    <div class="slideshow">
      <img src="assets/images/slide1.jpg" alt="Polangui Landscape">
      <img src="assets/images/slide2.jpg" alt="Municipal Building">
      <img src="assets/images/slide3.jpg" alt="Community Activities">
    </div>
    <div class="hero-content">
      <div class="logo-container">
        <img src="assets/images/logo.png" alt="Municipality Logo">
      </div>
      <h1>Human Resource Employee Information System</h1>
      <p class="hero-subtitle">Streamlining HR processes for efficient government service delivery</p>
      <div class="buttons">
        <button class="btn" onclick="location.href='employee/login.php'">
          <i class="fas fa-user-tie"></i> EMPLOYEE PORTAL
        </button>
        <button class="btn btn-admin" onclick="location.href='admin/login.php'">
          <i class="fas fa-users-cog"></i> ADMIN PORTAL
        </button>
      </div>
    </div>
  </div>

  <!-- FEATURES SECTION -->
  <section class="features">
    <h2 class="section-title">System Features</h2>
    <p class="section-subtitle">Our HRIS provides comprehensive tools for efficient human resource management</p>

    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-user-clock"></i>
        </div>
        <h3 class="feature-title">Leave Management</h3>
        <p class="feature-description">Streamlined leave application and approval process with real-time tracking and automated notifications.</p>
      </div>

      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-id-card-alt"></i>
        </div>
        <h3 class="feature-title">Employee Profiles</h3>
        <p class="feature-description">Comprehensive employee records with secure access to personal and professional information.</p>
      </div>

      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-file-contract"></i>
        </div>
        <h3 class="feature-title">Document Management</h3>
        <p class="feature-description">Centralized storage and management of employee documents, contracts, and certifications.</p>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-content">
      <img src="assets/images/logo.png" alt="LGU Polangui Logo" class="footer-logo">
      <p class="footer-text">
        <strong>LGU Polangui Human Resource Information System</strong><br>
        Municipality of Polangui, Province of Albay<br>
        Republic of the Philippines
      </p>
      <div class="footer-links">
        <a href="#" class="footer-link">Privacy Policy</a>
        <a href="#" class="footer-link">Terms of Service</a>
        <a href="#" class="footer-link">Contact Support</a>
      </div>
      <p class="footer-text" style="margin-top: 20px; font-size: 12px;">
        © 2025 LGU Polangui. All rights reserved.
      </p>
    </div>
  </footer>

  <script>
    // Add scroll effect to header
    window.addEventListener('scroll', function() {
      const header = document.querySelector('header');
      if (window.scrollY > 50) {
        header.style.background = 'rgba(255, 255, 255, 0.95)';
        header.style.backdropFilter = 'blur(10px)';
      } else {
        header.style.background = 'linear-gradient(135deg, var(--white) 0%, var(--light) 100%)';
        header.style.backdropFilter = 'blur(10px)';
      }
    });

    // Add loading animation for images
    document.addEventListener('DOMContentLoaded', function() {
      const images = document.querySelectorAll('img');
      images.forEach(img => {
        img.addEventListener('load', function() {
          this.style.opacity = 1;
        });
        if (img.complete) {
          img.style.opacity = 1;
        } else {
          img.style.opacity = 0;
          img.style.transition = 'opacity 0.5s ease';
        }
      });
    });
  </script>
</body>

</html>