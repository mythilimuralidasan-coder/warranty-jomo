<?php include 'header.php'; ?>
<div class="page active" id="page-contact">
  <div class="ph">
    <div class="ph-pbg hero-bg-img" data-speed="0.45" id="hero-bg"></div>
    <div class="ph-veil"></div>
    <div class="ph-body">
      <div class="ph-tag">
        <span class="ph-tag-line"></span><span>Get In Touch</span>
      </div>
      <h1 class="ph-title">
        Let's Build<br />Your <span class="gold">Home</span>
      </h1>
      <p class="ph-sub">
        Reach out for a free consultation, visit our Experience Centre, or
        simply ask us anything.
      </p>
    </div>
  </div>
  <section class="ct-body" id="book-consultation" style="scroll-margin-top: 100px;">
    <div class="ct-grid">
      <div>
        <div class="rv">
          <div class="eb">
            <div class="eb-line"></div>
            <span class="eb-t">Reach Us</span>
          </div>
          <h2 class="stitle">
            Every Conversation<br /><span class="th">Starts Here</span>
          </h2>
        </div>
        <div class="ct-ways rv" data-d="1">
          <div class="ct-way">
            <div class="ct-way-icon">
              <svg viewBox="0 0 24 24" stroke-width="1.5" fill="none">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
              </svg>
            </div>
            <div>
              <div class="ct-way-h">Phone</div>
              <div class="ct-way-v">
                <a href="tel:+919940187677">+91 99401 87677</a> Mon - Sat
                10am - 6pm
              </div>
            </div>
          </div>
          <div class="ct-way">
            <div class="ct-way-icon">
              <svg viewBox="0 0 24 24" stroke-width="1.5" fill="none">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
              </svg>
            </div>
            <div>
              <div class="ct-way-h">WhatsApp</div>
              <div class="ct-way-v">
                <a href="https://wa.me/919940187677" target="_blank">Chat on WhatsApp</a>
                Fastest response
              </div>
            </div>
          </div>
          <div class="ct-way">
            <div class="ct-way-icon">
              <svg viewBox="0 0 24 24" stroke-width="1.5" fill="none">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
              </svg>
            </div>
            <div>
              <div class="ct-way-h">Email</div>
              <div class="ct-way-v">
                <a href="mailto:info@jomointeriors.in">info@jomointeriors.in</a>
                Reply within 24h
              </div>
            </div>
          </div>
        </div>
        <div class="ct-ec rv" data-d="2">
          <div class="ctec-lbl">Experience Centre</div>
          <div class="ctec-h">Visit Us in Mogappair</div>
          <address class="ctec-addr">
            <span class="jomo-brand">JOMO<span class="jomo-dot">.</span></span> Experience Centre<br />Mogappair,
            Chennai 600037<br />Tamil
            Nadu, India
          </address>
          <div class="ctec-hours">
            <div class="ctec-hr">
              <span>Monday Saturday</span><span>10:00 AM - 6:00 PM</span>
            </div>
            <div class="ctec-hr">
              <span>Sunday</span><span>Closed</span>
            </div>
            <div class="ctec-hr">
              <span>Walk-ins</span><span>Always Welcome</span>
            </div>
          </div>
        </div>
      </div>
      <div class="ct-form-wrap">
        <div class="ct-form-title rv">Book a Free Consultation</div>
        <p class="ct-form-sub rv" data-d="1">
          One of our designers will get back to you within 24 hours. No
          commitment, no pressure.
        </p>
        <form class="jf rv" data-d="2" id="lead-form" onsubmit="submitLead(event)">
          <div class="jf-progress">
            <span class="jf-progress-badge" id="lead-step-text">Step 1 of 2</span>
          </div>
          <div class="jf-step is-active" data-step="1" id="lead-step-1">
            <div class="jf-field">
              <label class="jf-lbl">Name</label>
              <input class="jf-inp" name="fullname" type="text" placeholder="Your full name"
                oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" required />
            </div>
            <div class="jf-field">
              <label class="jf-lbl">Phone Number</label>
              <div class="jf-inp" style="padding: 0; display: block;">
                <input id="contact-phone" name="phone" type="tel" placeholder="Phone Number"
                  style="border: none; outline: none; background: transparent; width: 100%; padding: 14px 18px; color: inherit; font-size: inherit; font-weight: inherit;"
                  required />
              </div>
            </div>
            <div class="jf-field">
              <label class="jf-lbl">Email Address</label>
              <input class="jf-inp" name="email" type="email" placeholder="your@email.com" required />
            </div>
            <div class="jf-field">
              <label class="jf-lbl">Property Type</label>
              <select class="jf-sel" name="property" required>
                <option value="" disabled selected>
                  Select property type
                </option>
                <option>Residential</option>
                <option>5bhk + villa</option>
                <option>individual house</option>
                <option>Rennovation</option>
              </select>
            </div>
          </div>
          <div class="jf-step" data-step="2" id="lead-step-2" hidden>
            <div class="jf-field">
              <label class="jf-lbl">Budget</label>
              <select class="jf-sel" name="budget">
                <option value="" disabled selected>
                  Select your budget
                </option>
                <option>Under 5 Lakhs</option>
                <option>5 - 10 Lakhs</option>
                <option>10 - 15 Lakhs</option>
                <option>15 - 25 Lakhs</option>
                <option>25 Lakhs and above</option>
              </select>
            </div>
            <div class="jf-field">
              <label class="jf-lbl">Pincode</label>
              <input class="jf-inp" name="pincode" type="text" inputmode="numeric" maxlength="6"
                placeholder="Enter pincode" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
            </div>
          </div>
          <div id="jf-notice" class="jf-notice"></div>
          <div class="jf-actions">
            <button type="button" class="mbtn jf-back" id="lead-back" hidden>
              <div class="mbtn-bg"></div>
              <span class="mbtn-lbl">Back</span>
            </button>
            <button type="submit" class="mbtn" id="submit-btn">
              <div class="mbtn-bg"></div>
              <span class="mbtn-lbl" id="submit-lbl">Send Message</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
  <div class="map-sec">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d28135.49641869485!2d80.1417785!3d13.0775015!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a52616095b8ce11%3A0x24c24e6fc0353ca0!2sJOMO%20HOME%20INTERIORS!5e1!3m2!1sen!2sin!4v1780149405798!5m2!1sen!2sin"
      class="map-bg" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
  <?php include 'footer.php'; ?>

  <style>
    .map-sec {
      position: relative;
      width: 100%;
      height: 500px;
      overflow: hidden;
    }

    .map-bg {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      border: 0;
      pointer-events: all;
    }

    @media (max-width: 768px) {
      .map-sec {
        height: 300px;
      }
    }
  </style>