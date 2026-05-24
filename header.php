<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>JOMO. Home Interiors â€” Crafting Homes Where You Belong</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@200;300;400;600;700;800;900&family=Quicksand:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="Jomo_Updated.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  <div id="cur"></div>
  <div id="cur-ring"></div>

 
  <nav id="nav">
    <a
      href="#"
      class="wm"
      onclick="
          goTo('home');
          return false;
        ">
      <span class="wm-main">JOMO<span class="dot">.</span></span>
      <span class="wm-sub">Home Interiors</span>
    </a>
    <ul class="nav-links">
      <li>
        <a
          href="#"
          data-p="home"
          onclick="
              goTo('home');
              return false;
            ">Home</a>
      </li>
      <li>
        <a
          href="#"
          data-p="about"
          onclick="
              goTo('about');
              return false;
            ">About</a>
      </li>
      <li>
        <a
          href="#"
          data-p="services"
          onclick="
              goTo('services');
              return false;
            ">Services</a>
      </li>
      <li>
        <a
          href="#"
          data-p="process"
          onclick="
              goTo('process');
              return false;
            ">Process</a>
      </li>
      <li>
        <a
          href="#"
          data-p="projects"
          onclick="
              goTo('projects');
              return false;
            ">Projects</a>
      </li>
      <li>
        <a
          href="#"
          data-p="contact"
          onclick="
              goTo('contact');
              return false;
            ">Contact</a>
      </li>
    </ul>
    <a
      href="#"
      class="ncta"
      onclick="
          goTo('contact');
          return false;
        "><span>Book Consultation</span></a>
    <button class="ham" id="ham" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mnav">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <div class="mnav-backdrop" id="mnav-backdrop" hidden></div>
  <aside class="mnav" id="mnav" aria-hidden="true">
    <div class="mnav-head">
      <div class="mnav-brand">
        <div class="mnav-logo">JOMO<span class="dot">.</span></div>
        <div class="mnav-sub">Home Interiors</div>
      </div>
      <button class="mnav-close" id="mnav-close" type="button" aria-label="Close menu">×</button>
    </div>

    <div class="mnav-search">
      <span class="mnav-search-ico">⌕</span>
      <input class="mnav-search-inp" type="search" placeholder="Search..." aria-label="Search" />
    </div>

    <nav class="mnav-links" aria-label="Mobile">
      <a href="#" data-p="home">Home</a>
      <a href="#" data-p="about">About</a>
      <a href="#" data-p="services">Services</a>
      <a href="#" data-p="process">Process</a>
      <a href="#" data-p="projects">Projects</a>
      <a href="#" data-p="contact">Contact</a>
    </nav>

    <a
      href="#"
      class="mnav-cta"
      onclick="
        goTo('contact');
        return false;
      "
    >Book Consultation</a>
  </aside>
