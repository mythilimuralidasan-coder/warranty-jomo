<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>JOMO. Home Interiors Crafting Homes Where You Belong</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@200;300;400;600;700;800;900&family=Quicksand:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="JOMO Home Interiors | Custom Interior Design & Modular Home Solutions" />
  <meta property="og:description"
    content="Transform your home with JOMO Home Interiors. We specialize in premium interior design, modular kitchens, wardrobes, living rooms, bedrooms, and complete home interior solutions. Book your free consultation today." />
  <meta property="og:image" content="https://jomointeriors.in/assets/Images/jomo_logo1.png" />
  <meta property="og:url" content="https://jomointeriors.in/" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="JOMO Home Interiors" />
  <meta property="og:locale" content="en_IN" />
  <link rel="stylesheet" href="Jomo_Updated.css" />
  <link rel="icon" type="image/png" href="assets/Images/fav-icon1.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.0/build/css/intlTelInput.css"/>
  <style>
    .iti {
      width: 100%;
      display: block;
    }
    .iti__flag-container {
      z-index: 10;
    }
    .iti__country-list {
      color: #333;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border: 1px solid #eee;
      width: 100% !important; /* Make it same size as form input */
      max-width: 100% !important;
      white-space: normal !important;
    }
    .iti__country-name, .iti__dial-code {
      color: #333 !important;
    }
    .iti__country.iti__highlight {
      background-color: #f5f5f5;
    }
    .iti__selected-flag {
      background: transparent !important;
      padding-left: 10px;
    }
    /* Ensure the input text has enough space from the flag */
    .iti input[type="tel"] {
      padding-left: 50px !important;
    }
    /* Search box styling tweaks */
    .iti__search-input {
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 6px;
      margin: 8px;
      width: calc(100% - 16px);
      box-sizing: border-box;
      outline: none;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <div id="preloader" class="preloader" role="status" aria-label="Loading">
    <div class="pl-panel pl-panel-l"></div>
    <div class="pl-panel pl-panel-r"></div>
    <div class="preloader-inner">
      <svg class="pl-sketch" viewBox="0 0 200 170" xmlns="http://www.w3.org/2000/svg">
        <circle class="pl-ring" cx="100" cy="82" r="72" />
        <path class="pl-line pl-roof" d="M18 82 L100 22 L182 82" />
        <path class="pl-line pl-wall" d="M38 82 L38 148 L162 148 L162 82" />
        <path class="pl-line pl-window" d="M56 100 H80 V124 H56 Z M120 100 H144 V124 H120 Z" />
        <path class="pl-line pl-door" d="M90 148 L90 106 L110 106 L110 148" />
      </svg>
      <div class="pl-word"><span class="jomo-brand">JOMO<span class="jomo-dot">.</span></span></div>
      <div class="pl-tag">Crafting Homes Where You Belong</div>
      <div class="pl-bar"><span class="pl-bar-fill"></span></div>
    </div>
  </div>
  <div id="cur"></div>
  <div id="cur-ring"></div>


  <nav id="nav">
    <a href="index.php" class="wm" onclick="
      goTo('home');
      return false;
    ">
      <img src="assets/Images/jomo_logo1.png" style="width:70%; height: auto;" alt="Jomo Logo" class="logo">
    </a>
    <ul class="nav-links">
      <li>
        <a href="#" data-p="home" onclick="
              goTo('home');
              return false;
            ">Home</a>
      </li>
      <li>
        <a href="#" data-p="about" onclick="
              goTo('about');
              return false;
            ">About</a>
      </li>
      <li>
        <a href="#" data-p="services" onclick="
              goTo('services');
              return false;
            ">Services</a>
      </li>
      <li>
        <a href="#" data-p="process" onclick="
              goTo('process');
              return false;
            ">Process</a>
      </li>
      <li>
        <a href="#" data-p="projects" onclick="
              goTo('projects');
              return false;
            ">Projects</a>
      </li>
      <li>
        <a href="#" data-p="contact" onclick="
              goTo('contact');
              return false;
            ">Contact</a>
      </li>
    </ul>
    <div class="ncta-group">
      <a href="service.php" class="ncta ncta-ghost"><span>Claim Warranty</span></a>
      <a href="contact.php#book-consultation" class="ncta"><span>Book Consultation</span></a>
    </div>
    <button class="ham" id="ham" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mnav">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <div class="mnav-backdrop" id="mnav-backdrop" hidden></div>
  <aside class="mnav" id="mnav" aria-hidden="true">
    <div class="mnav-head">
      <div class="mnav-brand">
        <div class="mnav-logo"><span class="jomo-brand">JOMO<span class="jomo-dot">.</span></span></div>
        <div class="mnav-sub">Home Interiors</div>
      </div>
      <button class="mnav-close" id="mnav-close" type="button" aria-label="Close menu">×</button>
    </div>

    <nav class="mnav-links" aria-label="Mobile">
      <a href="#" data-p="home" onclick="goTo('home'); return false;">Home</a>
      <a href="#" data-p="about" onclick="goTo('about'); return false;">About</a>
      <a href="#" data-p="services" onclick="goTo('services'); return false;">Services</a>
      <a href="#" data-p="process" onclick="goTo('process'); return false;">Process</a>
      <a href="#" data-p="projects" onclick="goTo('projects'); return false;">Projects</a>
      <a href="#" data-p="contact" onclick="goTo('contact'); return false;">Contact</a>
      <a href="service.php">Claim Warranty</a>
    </nav>

  </aside>