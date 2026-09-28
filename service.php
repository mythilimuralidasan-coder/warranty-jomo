<?php include 'header.php'; ?>
<div class="page active" id="page-service">
  <div class="ph">
    <div class="ph-pbg hero-bg-img" data-speed="0.45" id="hero-bg"></div>
    <div class="ph-veil"></div>
    <div class="ph-body">
      <div class="ph-tag">
        <span class="ph-tag-line"></span><span>After-Sales Support</span>
      </div>
      <h1 class="ph-title">
        Claim Your<br /><span class="gold">Warranty</span>
      </h1>
      <p class="ph-sub">
        Already a JOMO customer? Tell us what's wrong and share a few photos
        or a short video — our warranty team will get back to you shortly.
      </p>
    </div>
  </div>
  <section class="ct-body" id="request-service" style="scroll-margin-top: 100px;">
    <div class="ct-grid">
      <div class="wc-left">
        <div class="rv">
          <div class="eb">
            <div class="eb-line"></div>
            <span class="eb-t">Warranty Claims</span>
          </div>
          <h2 class="stitle">
            We're Here To<br /><span class="th">Make It Right</span>
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
                <a href="mailto:care@jomointeriors.in">care@jomointeriors.in</a>
                Reply within 24h
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ct-form-wrap">
        <div class="ct-form-title rv">Raise a Warranty Claim</div>
        <p class="ct-form-sub rv" data-d="1">
          Fill in your details below and our warranty team will reach out to
          schedule a visit or resolve it remotely.
        </p>
        <form class="jf rv" data-d="2" id="service-form" onsubmit="submitService(event)" enctype="multipart/form-data">
          <div class="jf-field">
            <label class="jf-lbl">Name</label>
            <input class="jf-inp" name="fullname" type="text" placeholder="Your full name"
              oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')" required />
          </div>
          <div class="jf-field">
            <label class="jf-lbl">Phone Number</label>
            <div class="jf-inp" style="padding: 0; display: block;">
              <input id="service-phone" name="phone" type="tel" placeholder="Phone Number"
                style="border: none; outline: none; background: transparent; width: 100%; padding: 14px 18px; color: inherit; font-size: inherit; font-weight: inherit;"
                required />
            </div>
          </div>
          <div class="jf-field">
            <label class="jf-lbl">Upload Pics / Videos</label>
            <label class="jf-file" for="service-files">
              <div class="jf-file-icon">
                <svg viewBox="0 0 24 24" stroke-width="1.5" fill="none">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 8.25L12 3.75m0 0L7.5 8.25M12 3.75v13.5" />
                </svg>
              </div>
              <div>
                <div class="jf-file-h">Click to upload photos or a video</div>
                <div class="jf-file-p">Any photo format or short video — up to 6 files, 35 MB in total</div>
              </div>
            </label>
            <input id="service-files" name="media[]" type="file" accept="image/*,video/*" multiple hidden
              onchange="renderServiceFiles(this)" />
            <div id="service-file-list" class="jf-file-list"></div>
          </div>
          <div class="jf-field">
            <label class="jf-lbl">Describe the Issue</label>
            <textarea class="jf-ta" name="issue" placeholder="Tell us what's wrong — the more detail, the faster we can help."
              required></textarea>
          </div>
          <div id="jf-notice" class="jf-notice"></div>
          <div class="jf-actions">
            <button type="submit" class="mbtn" id="service-submit-btn">
              <div class="mbtn-bg"></div>
              <span class="mbtn-lbl" id="service-submit-lbl">Submit Claim</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
  <?php include 'footer.php'; ?>

  <style>
    @media (min-width: 1101px) {
      .wc-left .ct-ways {
        margin-top: 100px;
      }
    }

    .jf-file {
      display: flex;
      align-items: center;
      gap: 14px;
      width: 100%;
      background: var(--gr);
      border: 1.5px dashed rgba(var(--n-rgb), 0.25);
      padding: 16px 18px;
      cursor: pointer;
      transition: border-color 0.3s, background 0.3s;
    }

    .jf-file:hover {
      border-color: var(--g);
      background: rgba(var(--g-rgb), 0.05);
    }

    .jf-file-icon {
      flex: none;
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: rgba(var(--g-rgb), 0.12);
      color: var(--g);
    }

    .jf-file-icon svg {
      width: 20px;
      height: 20px;
      stroke: currentColor;
    }

    .jf-file-h {
      font-family: "Quicksand", sans-serif;
      font-size: 14px;
      font-weight: 700;
      color: var(--n);
    }

    .jf-file-p {
      font-family: "Quicksand", sans-serif;
      font-size: 12px;
      font-weight: 500;
      color: rgba(var(--n-rgb), 0.5);
      margin-top: 2px;
    }

    .jf-file-list {
      display: flex;
      flex-direction: column;
      gap: 6px;
      margin-top: 4px;
    }

    .jf-file-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      font-family: "Quicksand", sans-serif;
      font-size: 12px;
      font-weight: 600;
      color: var(--n);
      background: rgba(var(--n-rgb), 0.05);
      padding: 8px 12px;
    }

    .jf-file-row span.sz {
      color: rgba(var(--n-rgb), 0.5);
      font-weight: 500;
      flex: none;
    }
  </style>
</div>
