/* ══ PRELOADER ══ */
(function () {
  const pre = document.getElementById("preloader");
  if (!pre) return;
  const MIN_MS = 1000;
  const started = Date.now();
  document.body.classList.add("pl-lock");

  function reveal() {
    const wait = Math.max(0, MIN_MS - (Date.now() - started));
    setTimeout(() => {
      pre.classList.add("pl-exit");
      document.body.classList.remove("pl-lock");
      let done = false;
      const finish = () => {
        if (done) return;
        done = true;
        pre.remove();
      };
      pre.addEventListener("transitionend", finish, { once: true });
      setTimeout(finish, 1500);
    }, wait);
  }

  if (document.readyState === "complete") reveal();
  else window.addEventListener("load", reveal, { once: true });
})();

/* ══ CURSOR ══ */
const cur = document.getElementById("cur"),
  ring = document.getElementById("cur-ring");
let mx = 0,
  my = 0,
  rx = 0,
  ry = 0;
document.addEventListener("mousemove", (e) => {
  mx = e.clientX;
  my = e.clientY;
  cur.style.left = mx + "px";
  cur.style.top = my + "px";
  document.body.classList.toggle(
    "hovering",
    !!e.target.closest(
      "a,button,.mbtn,.gbtn,.pf-btn,.crm-filter,.crm-close,.crm-export,.scroll-top,.tc-arrow,.tc-dot",
    ),
  );
});
(function tick() {
  rx += (mx - rx) * 0.1;
  ry += (my - ry) * 0.1;
  ring.style.left = rx + "px";
  ring.style.top = ry + "px";
  requestAnimationFrame(tick);
})();

/* ══ PAGE STATE ══ */
const activePageEl = document.querySelector(".page.active");
let curPage = activePageEl ? activePageEl.id.replace("page-", "") : "home";
const pages = [
  "home",
  "about",
  "services",
  "process",
  "projects",
  "contact",
];

// Update nav link active class on load
document
  .querySelectorAll(".nav-links a")
  .forEach((a) => a.classList.toggle("act", a.dataset.p === curPage));
document
  .querySelectorAll(".mnav-links a")
  .forEach((a) => a.classList.toggle("act", a.dataset.p === curPage));

/* ══ MOBILE NAV (HAMBURGER) ══ */
const hamBtn = document.getElementById("ham");
const mnav = document.getElementById("mnav");
const mnavBackdrop = document.getElementById("mnav-backdrop");
const mnavClose = document.getElementById("mnav-close");

function setMobileNav(open) {
  if (!mnav || !mnavBackdrop || !hamBtn) return;
  mnav.classList.toggle("is-open", open);
  mnavBackdrop.classList.toggle("is-open", open);
  mnavBackdrop.hidden = !open;
  document.body.classList.toggle("mnav-open", open);
  mnav.setAttribute("aria-hidden", String(!open));
  hamBtn.setAttribute("aria-expanded", String(open));
  if (open) mnavClose?.focus({ preventScroll: true });
  else hamBtn?.focus?.({ preventScroll: true });
}

let lastTapTS = 0;
const tap = (el, fn) => {
  if (!el) return;
  el.addEventListener("pointerup", (e) => {
    lastTapTS = Date.now();
    e.preventDefault();
    fn(e);
  });
  el.addEventListener("click", (e) => {
    // On mobile, a click often fires after pointerup; ignore that duplicate.
    if (Date.now() - lastTapTS < 450) {
      e.preventDefault();
      return;
    }
    fn(e);
  });
};

tap(hamBtn, () => setMobileNav(!mnav?.classList.contains("is-open")));
tap(mnavBackdrop, () => setMobileNav(false));
tap(mnavClose, () => setMobileNav(false));
document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") setMobileNav(false);
});
mnav?.addEventListener("click", (e) => {
  const a = e.target.closest("a[data-p]");
  if (!a) return;
  e.preventDefault();
  const p = a.dataset.p;
  if (!p) return;
  setMobileNav(false);
  goTo(p);
});
window
  .matchMedia("(min-width: 1101px)")
  .addEventListener?.("change", (e) => {
    if (e.matches) setMobileNav(false);
  });

function goTo(p) {
  const targetPage = document.getElementById("page-" + p);
  if (targetPage) {
    document
      .querySelectorAll(".page")
      .forEach((el) => el.classList.remove("active"));
    targetPage.classList.add("active");
    curPage = p;
    window.scrollTo({ top: 0, behavior: "instant" });
    const nav = document.getElementById("nav");
    nav.classList.remove("stuck");
    document
      .querySelectorAll(".nav-links a")
      .forEach((a) => a.classList.toggle("act", a.dataset.p === p));
    document
      .querySelectorAll(".mnav-links a")
      .forEach((a) => a.classList.toggle("act", a.dataset.p === p));
    setTimeout(() => {
      initReveal();
      initMagnetic();
      if (p === "home") initCounters();
    }, 50);
  } else {
    // Redirect to the respective PHP page if not in DOM
    if (p === "home") {
      window.location.href = "index.php";
    } else if (p === "projects") {
      window.location.href = "project.php";
    } else {
      window.location.href = p + ".php";
    }
  }
}

function goToSection(page, sectionId) {
  var targetPage = document.getElementById("page-" + page);
  if (targetPage) {
    // Page exists in current DOM — switch to it and scroll
    goTo(page);
    setTimeout(function () {
      var el = document.getElementById(sectionId);
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 150);
  } else {
    // On a different PHP page — redirect with hash so services.php can scroll on load
    var href = (page === "home" ? "index" : page) + ".php#" + sectionId;
    window.location.href = href;
  }
}

// On page load, if a hash is present scroll to that section
(function () {
  function scrollToHash() {
    var hash = window.location.hash;
    if (!hash) return;
    var el = document.getElementById(hash.slice(1));
    if (el) {
      setTimeout(function () {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 400);
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', scrollToHash);
  } else {
    scrollToHash();
  }
}());

/* ══ SMOOTH PARALLAX ENGINE ══ */
let scrollY = 0,
  targetScroll = 0;
const lerp = (a, b, t) => a + (b - a) * t;

// All parallax targets: {el, speed, type}
const pxTargets = [];

function collectPX() {
  pxTargets.length = 0;
  const pg = document.getElementById("page-" + curPage);
  if (!pg) return;
  // Full-section background layers
  pg.querySelectorAll("[data-speed]").forEach((el) => {
    pxTargets.push({ el, speed: +el.dataset.speed, type: "bg" });
  });
  // Images that parallax within their container
  pg.querySelectorAll("[data-parallax-img]").forEach((el) => {
    pxTargets.push({
      el,
      speed: +el.dataset.parallaImg || +el.dataset.parallaxImg,
      type: "img",
    });
  });
  // Individual elements
  pg.querySelectorAll("[data-parallax-el]").forEach((el) => {
    pxTargets.push({ el, speed: +el.dataset.parallaxEl, type: "el" });
  });
  // Page hero backgrounds
  pg.querySelectorAll(".ph-pbg").forEach((el) => {
    const spd = +el.dataset.speed || 0.35;
    pxTargets.push({ el, speed: spd, type: "bg" });
  });
}

function applyPX(sy) {
  pxTargets.forEach(({ el, speed, type }) => {
    if (!el.isConnected) return;
    if (type === "bg" || type === "el") {
      el.style.transform = `translateY(${sy * speed}px)`;
    } else if (type === "img") {
      // Relative to its container's scroll position
      const rect = el
        .closest(".ss-img,.si-img,.pgcard")
        ?.getBoundingClientRect();
      if (!rect) return;
      const midDiff = window.innerHeight / 2 - rect.top - rect.height / 2;
      el.style.transform = `translateY(${midDiff * speed}px)`;
    }
  });
}

// RAF loop for smooth parallax
let lastSY = 0,
  smoothSY = 0;
function pxLoop() {
  const raw = window.scrollY;
  smoothSY = lerp(smoothSY, raw, 0.09);
  if (Math.abs(smoothSY - lastSY) > 0.1) {
    applyPX(smoothSY);
    lastSY = smoothSY;
  }
  requestAnimationFrame(pxLoop);
}
pxLoop();
window.addEventListener(
  "scroll",
  () => {
    collectPX();
  },
  { passive: true },
);

/* ══ NAV ON SCROLL ══ */
const navEl = document.getElementById("nav");
const scrollTopBtn = document.getElementById("scroll-top");

function updateScrollTopButton() {
  if (!scrollTopBtn) return;
  const maxScroll =
    document.documentElement.scrollHeight - window.innerHeight;
  const progress = maxScroll > 0 ? Math.min(window.scrollY / maxScroll, 1) : 0;
  scrollTopBtn.classList.toggle("is-visible", window.scrollY > 420);
  scrollTopBtn.style.setProperty("--scroll-progress", `${progress * 360}deg`);
}

window.addEventListener(
  "scroll",
  () => {
    navEl?.classList.toggle("stuck", window.scrollY > 80);
    updateScrollTopButton();
  },
  { passive: true },
);

scrollTopBtn?.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

/* ══ SCROLL REVEAL ══ */
function initReveal() {
  const obs = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          e.target.classList.add("on");
        }
      });
    },
    { threshold: 0.1, rootMargin: "0px 0px -40px 0px" },
  );
  const pg = document.getElementById("page-" + curPage);
  if (!pg) return;
  pg.querySelectorAll(".rv,.rvl,.rvr,.rvs").forEach((el) =>
    obs.observe(el),
  );
  collectPX();
}

/* ══ MAGNETIC BUTTONS ══ */
function initMagnetic() {
  document
    .querySelectorAll("#page-" + curPage + " .mbtn")
    .forEach((el) => {
      el.onmousemove = (e) => {
        const r = el.getBoundingClientRect();
        const dx = e.clientX - (r.left + r.width / 2);
        const dy = e.clientY - (r.top + r.height / 2);
        el.style.transform = `translate(${dx * 0.22}px,${dy * 0.22}px)`;
      };
      el.onmouseleave = () => (el.style.transform = "");
    });
}

/* ══ COUNTERS ══ */
function initCounters() {
  function cnt(el, t, s) {
    let v = 0;
    const st = t / 55;
    const tm = setInterval(() => {
      v = Math.min(v + st, t);
      el.textContent = Math.round(v) + s;
      if (v >= t) clearInterval(tm);
    }, 16);
  }
  const strip = document.querySelector("#page-home .hero-strip");
  if (!strip) return;
  const o = new IntersectionObserver(
    (es) => {
      if (es[0].isIntersecting) {
        document
          .querySelectorAll("#page-home .hsi-n")
          .forEach((el) =>
            cnt(el, +el.dataset.target, el.dataset.suffix),
          );
        o.disconnect();
      }
    },
    { threshold: 0.5 },
  );
  o.observe(strip);
}

/* ══ PROJECT FILTERS ══ */
document.querySelectorAll(".pf-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    document
      .querySelectorAll(".pf-btn")
      .forEach((b) => b.classList.remove("act"));
    btn.classList.add("act");
    const f = btn.dataset.filter;

    const pgrid = document.querySelector(".pgrid");
    if (pgrid) {
      if (f === "all") {
        pgrid.classList.remove("filtered-view");
      } else {
        pgrid.classList.add("filtered-view");
      }
    }

    document.querySelectorAll(".pgcard").forEach((c) => {
      const cats = (c.dataset.cats || c.dataset.cat || "").split(",");
      const isVisible = f === "all" || cats.includes(f);
      c.classList.toggle("is-filter-hidden", !isVisible);
      if (c.classList.contains("wide")) {
        c.style.gridColumn = f === "all" ? "span 2" : "span 1";
      }
    });
  });
});

/* ══ CRM (LocalStorage) ══ */
let leads = JSON.parse(localStorage.getItem("jomo_leads") || "[]");
let crmFilter = "all";
const leadForm = document.getElementById("lead-form");
const leadStep1 = document.getElementById("lead-step-1");
const leadStep2 = document.getElementById("lead-step-2");
const leadStepText = document.getElementById("lead-step-text");
const leadBackBtn = document.getElementById("lead-back");
const submitLbl = document.getElementById("submit-lbl");
const submitBtn = document.getElementById("submit-btn");

function saveLead(lead) {
  leads.unshift(lead);
  localStorage.setItem("jomo_leads", JSON.stringify(leads));
}

function setLeadStep(step) {
  if (!leadForm || !leadStep1 || !leadStep2 || !leadStepText || !leadBackBtn || !submitLbl) {
    return;
  }

  const isStep1 = step === 1;
  leadStep1.hidden = !isStep1;
  leadStep1.classList.toggle("is-active", isStep1);
  leadStep2.hidden = isStep1;
  leadStep2.classList.toggle("is-active", !isStep1);
  leadStepText.textContent = isStep1 ? "Step 1 of 2" : "Step 2 of 2";
  leadBackBtn.hidden = isStep1;
  submitLbl.textContent = isStep1 ? "Next Step" : "Send Message";
  leadForm.dataset.step = String(step);
}

function validateLeadFields(container) {
  const fields = container.querySelectorAll("input, select, textarea");
  for (const field of fields) {
    if (field.disabled) continue;
    if (!field.checkValidity()) {
      field.reportValidity?.();
      field.focus?.();
      return false;
    }
  }
  return true;
}

leadBackBtn?.addEventListener("click", () => {
  if (!leadForm) return;
  setLeadStep(1);
});

setLeadStep(1);

async function submitLead(e) {
  e.preventDefault();
  const f = e.target;
  const notice = document.getElementById("jf-notice");
  const step = Number(f.dataset.step || "1");

  if (step === 1) {
    if (!leadStep1 || !validateLeadFields(leadStep1)) {
      notice.className = "jf-notice err";
      notice.style.display = "block";
      notice.textContent = "Please complete your name, phone number, email address, and property type.";
      return;
    }
    
    const phoneRaw = f.phone.value.trim();
    if (window.itiContact && !window.itiContact.isValidNumber()) {
      notice.className = "jf-notice err";
      notice.style.display = "block";
      notice.textContent = "Please enter a valid mobile number for the selected country.";
      return;
    }
    const phone = window.itiContact ? window.itiContact.getNumber() : phoneRaw;
    
    const email = f.email.value.trim();
    if (!email.includes('@')) {
      notice.className = "jf-notice err";
      notice.style.display = "block";
      notice.textContent = "Please include an '@' in the email address.";
      return;
    }

    notice.className = "jf-notice";
    notice.style.display = "block";
    notice.textContent = "Great, now share your budget and pincode.";
    setLeadStep(2);
    return;
  }

  if (!leadStep2 || !validateLeadFields(leadStep2)) {
    notice.className = "jf-notice err";
    notice.style.display = "block";
    notice.textContent = "Please complete your budget and pincode.";
    return;
  }

  const pincode = f.pincode.value.trim();
  if (pincode && pincode.length !== 6) {
    notice.className = "jf-notice err";
    notice.style.display = "block";
    notice.textContent = "Pincode must be exactly 6 digits.";
    return;
  }

  const btn = submitBtn;
  const lbl = submitLbl;
  const name = f.fullname.value.trim();
  const phone = f.phone.value.trim();
  const email = f.email.value.trim();
  const property = f.property.value || "Not specified";
  const budget = f.budget.value || "Not specified";

  lbl.textContent = "Submitting...";
  btn.style.pointerEvents = "none";
  leadBackBtn && (leadBackBtn.style.pointerEvents = "none");
  notice.className = "jf-notice";
  notice.style.display = "block";
  notice.textContent = "Sending your enquiry...";

  const formData = new FormData();
  formData.append("fullname", name);
  formData.append("phone", phone);
  formData.append("email", email);
  formData.append("property", property);
  formData.append("budget", budget);
  formData.append("pincode", pincode);

  try {
    const res = await fetch("mailhandler2.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();
    if (data.status !== "success") {
      throw new Error(data.message || "Unable to send enquiry.");
    }

    lbl.textContent = "Submitted";
    notice.className = "jf-notice ok";
    notice.textContent =
      data.message ||
      "Thank you! Your consultation request has been sent successfully.";
    f.reset();
    setLeadStep(1);
    setTimeout(() => {
      lbl.textContent = "Send Message";
      btn.style.pointerEvents = "";
      leadBackBtn && (leadBackBtn.style.pointerEvents = "");
    }, 3000);
  } catch (err) {
    lbl.textContent = "Submit";
    btn.style.pointerEvents = "";
    leadBackBtn && (leadBackBtn.style.pointerEvents = "");
    notice.className = "jf-notice err";
    notice.textContent =
      err.message || "Sorry, something went wrong. Please try again.";
  }
}

/* ══ WARRANTY CLAIM FORM ══ */
const SERVICE_MAX_FILES = 6;
const SERVICE_MAX_SIZE = 35 * 1024 * 1024; // 35MB total across all files

function formatFileSize(bytes) {
  if (bytes >= 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + " MB";
  if (bytes >= 1024) return (bytes / 1024).toFixed(0) + " KB";
  return bytes + " B";
}

function renderServiceFiles(input) {
  const list = document.getElementById("service-file-list");
  const notice = document.getElementById("jf-notice");
  if (!list) return;
  list.innerHTML = "";

  const files = Array.from(input.files || []);
  if (files.length > SERVICE_MAX_FILES) {
    notice.className = "jf-notice err";
    notice.style.display = "block";
    notice.textContent = `Please select up to ${SERVICE_MAX_FILES} files only.`;
    input.value = "";
    return;
  }
  const totalSize = files.reduce((sum, f) => sum + f.size, 0);
  if (totalSize > SERVICE_MAX_SIZE) {
    notice.className = "jf-notice err";
    notice.style.display = "block";
    notice.textContent = `Your files total ${formatFileSize(totalSize)}, which is over the 35 MB limit. Please choose fewer or smaller files.`;
    input.value = "";
    return;
  }

  notice.style.display = "none";
  files.forEach((f) => {
    const row = document.createElement("div");
    row.className = "jf-file-row";
    row.innerHTML = `<span>${f.name}</span><span class="sz">${formatFileSize(f.size)}</span>`;
    list.appendChild(row);
  });
}

async function submitService(e) {
  e.preventDefault();
  const f = e.target;
  const notice = document.getElementById("jf-notice");
  const btn = document.getElementById("service-submit-btn");
  const lbl = document.getElementById("service-submit-lbl");

  if (!validateLeadFields(f)) {
    notice.className = "jf-notice err";
    notice.style.display = "block";
    notice.textContent = "Please complete your name, phone number, and describe the issue.";
    return;
  }

  const phoneRaw = f.phone.value.trim();
  if (window.itiService && !window.itiService.isValidNumber()) {
    notice.className = "jf-notice err";
    notice.style.display = "block";
    notice.textContent = "Please enter a valid mobile number for the selected country.";
    return;
  }
  const phone = phoneRaw;

  const fileInput = document.getElementById("service-files");
  if (fileInput && fileInput.files.length > SERVICE_MAX_FILES) {
    notice.className = "jf-notice err";
    notice.style.display = "block";
    notice.textContent = `Please select up to ${SERVICE_MAX_FILES} files only.`;
    return;
  }
  if (fileInput && Array.from(fileInput.files).reduce((s, x) => s + x.size, 0) > SERVICE_MAX_SIZE) {
    notice.className = "jf-notice err";
    notice.style.display = "block";
    notice.textContent = "Your files are over the 35 MB limit. Please choose fewer or smaller files.";
    return;
  }

  lbl.textContent = "Submitting...";
  btn.style.pointerEvents = "none";
  notice.className = "jf-notice";
  notice.style.display = "block";
  notice.textContent = "Sending your warranty claim...";

  const formData = new FormData();
  formData.append("fullname", f.fullname.value.trim());
  formData.append("phone", phone);
  formData.append("issue", f.issue.value.trim());
  if (fileInput) {
    Array.from(fileInput.files).forEach((file) => formData.append("media[]", file));
  }

  try {
    const res = await fetch("service_submit.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();
    if (data.status !== "success") {
      throw new Error(data.message || "Unable to send request.");
    }

    lbl.textContent = "Submitted";
    notice.className = "jf-notice ok";
    notice.textContent =
      data.message ||
      "Thank you! Your warranty claim has been received.";
    f.reset();
    const list = document.getElementById("service-file-list");
    if (list) list.innerHTML = "";
    setTimeout(() => {
      lbl.textContent = "Submit Claim";
      btn.style.pointerEvents = "";
    }, 3000);
  } catch (err) {
    lbl.textContent = "Submit Claim";
    btn.style.pointerEvents = "";
    notice.className = "jf-notice err";
    notice.textContent =
      err.message || "Sorry, something went wrong. Please try again.";
  }
}

/* -- CRM DASHBOARD ══ */
function openCRM() {
  document.getElementById("crm-page").style.display = "block";
  renderCRM();
}
function closeCRM() {
  document.getElementById("crm-page").style.display = "none";
}
function renderCRM() {
  const today = new Date().toLocaleDateString("en-IN");
  const filtered =
    crmFilter === "all"
      ? leads
      : leads.filter((l) => l.status === crmFilter);
  document.getElementById("stat-total").textContent = leads.length;
  document.getElementById("stat-new").textContent = leads.filter(
    (l) => l.status === "new",
  ).length;
  document.getElementById("stat-qualified").textContent = leads.filter(
    (l) => l.status === "qualified",
  ).length;
  document.getElementById("stat-today").textContent = leads.filter(
    (l) =>
      l.timestamp.includes(
        today.split("/").reverse().join("-").slice(-5),
      ) ||
      l.timestamp.includes(
        new Date().toLocaleDateString("en-IN", {
          day: "numeric",
          month: "short",
        }),
      ),
  ).length;
  const tbody = document.getElementById("crm-tbody");
  if (!filtered.length) {
    tbody.innerHTML =
      '<tr><td colspan="10" class="crm-empty">No leads found.</td></tr>';
    return;
  }
  tbody.innerHTML = filtered
    .map(
      (l, i) => `
    <tr>
      <td>${leads.length - leads.indexOf(l)}</td>
      <td><strong>${l.name}</strong></td>
      <td><a href="tel:${l.phone}" style="color:var(--g);font-weight:700;">${l.phone}</a></td>
      <td>${l.email}</td>
      <td>${l.property}</td>
      <td>${l.service}</td>
      <td>${l.source}</td>
      <td style="white-space:nowrap;">${l.timestamp}</td>
      <td>
        <select class="crm-sel" onchange="updateStatus(${l.id},this.value)">
          <option value="new" ${l.status === "new" ? "selected" : ""}>🟡 New</option>
          <option value="contacted" ${l.status === "contacted" ? "selected" : ""}>🔵 Contacted</option>
          <option value="qualified" ${l.status === "qualified" ? "selected" : ""}>🟢 Qualified</option>
          <option value="lost" ${l.status === "lost" ? "selected" : ""}>🔴 Lost</option>
        </select>
      </td>
      <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${l.message}">${l.message}</td>
    </tr>
  `,
    )
    .join("");
}
function updateStatus(id, status) {
  leads = leads.map((l) => (l.id === id ? { ...l, status } : l));
  localStorage.setItem("jomo_leads", JSON.stringify(leads));
  renderCRM();
}
function filterCRM(btn, f) {
  crmFilter = f;
  document
    .querySelectorAll(".crm-filter")
    .forEach((b) => b.classList.remove("act"));
  btn.classList.add("act");
  renderCRM();
}
function exportCSV() {
  const headers = [
    "#",
    "Name",
    "Phone",
    "Email",
    "Property",
    "Service",
    "Message",
    "Source",
    "Date & Time",
    "Status",
  ];
  const rows = leads.map((l, i) => [
    leads.length - i,
    l.name,
    l.phone,
    l.email,
    l.property,
    l.service,
    `"${l.message}"`,
    l.source,
    l.timestamp,
    l.status,
  ]);
  const csv = [headers, ...rows].map((r) => r.join(",")).join("\n");
  const a = document.createElement("a");
  a.href = "data:text/csv;charset=utf-8," + encodeURIComponent(csv);
  a.download =
    "jomo-leads-" + new Date().toISOString().slice(0, 10) + ".csv";
  a.click();
}
// Secret CRM access: type "admin" anywhere
let adminTyped = "";
document.addEventListener("keydown", (e) => {
  adminTyped = (adminTyped + e.key).slice(-5);
  if (adminTyped === "admin") openCRM();
});

/* ══ HOME QUICK CONTACT ═h═ */
async function submitHeroForm() {
  const nameEl = document.getElementById("hero-name");
  const phoneEl = document.getElementById("hero-phone");
  const emailEl = document.getElementById("hero-email");
  const notice = document.getElementById("hero-notice");
  const lbl = document.getElementById("hero-lbl");
  const name = nameEl.value.trim();
  const phoneRaw = phoneEl.value.trim();
  const email = (emailEl && emailEl.value) ? emailEl.value.trim() : '';

  if (!name || !phoneRaw) {
    notice.className = "hero-notice err";
    notice.style.display = "block";
    notice.textContent = "Please enter your name and contact number.";
    return;
  }
  
  if (window.itiHero && !window.itiHero.isValidNumber()) {
    notice.className = "hero-notice err";
    notice.style.display = "block";
    notice.textContent = "Please enter a valid mobile number for the selected country.";
    return;
  }
  const phone = window.itiHero ? window.itiHero.getNumber() : phoneRaw;

  // Require a valid email
  if (!email) {
    notice.className = "hero-notice err";
    notice.style.display = "block";
    notice.textContent = "Please enter your email address.";
    return;
  }
  
  if (!email.includes('@')) {
    notice.className = "hero-notice err";
    notice.style.display = "block";
    notice.textContent = "Please include an '@' in the email address.";
    return;
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    notice.className = "hero-notice err";
    notice.style.display = "block";
    notice.textContent = "Please enter a valid email address.";
    return;
  }

  const lead = {
    id: Date.now(),
    name,
    phone,
    email: email,
    property: "Not specified",
    service: "Not specified",
    message: "Homepage free quotation form submission.",
    source: "Homepage Form",
    timestamp: new Date().toLocaleString("en-IN", {
      dateStyle: "medium",
      timeStyle: "short",
    }),
    status: "new",
  };

  lbl.textContent = "Sending...";
  notice.className = "hero-notice";
  notice.style.display = "block";
  notice.textContent = "Sending your request...";

  const formData = new FormData();
  formData.append("fullname", name);
  formData.append("phone", phone);
  formData.append("email", email);
  formData.append("requirement", "Free quotation request");
  formData.append("message", "Homepage free quotation form submission.");

  try {
    const res = await fetch("mailhandler1.php", {
      method: "POST",
      body: formData,
    });
    const data = await res.json();
    if (data.status !== "success") {
      throw new Error(data.message || "Unable to send request.");
    }

    saveLead(lead);
    lbl.textContent = "Request Sent";
    notice.className = "hero-notice ok";
    notice.textContent =
      data.message ||
      "Thank you! We will call you back shortly during business hours.";
    nameEl.value = "";
    phoneEl.value = "";
    if (emailEl) emailEl.value = "";
    setTimeout(() => {
      lbl.textContent = "Start Design Consultation";
    }, 4000);
  } catch (err) {
    lbl.textContent = "Start Design Consultation";
    notice.className = "hero-notice err";
    notice.textContent =
      err.message || "Sorry, something went wrong. Please try again.";
  }
}

function submitHQC() {
  submitHeroForm();
}

/* -- INIT ══ */
initReveal();
initMagnetic();
initCounters();
collectPX();
updateScrollTopButton();

/* ══ TESTIMONIAL CAROUSEL ══ */
(function () {
  var slider = document.getElementById("tc-slider");
  if (!slider) return;

  var track    = document.getElementById("tc-track");
  var dotsWrap = document.getElementById("tc-dots");
  var cards    = Array.from(track.querySelectorAll(".tc"));
  var total    = cards.length;
  var current  = 0;
  var timer    = null;
  var DELAY    = 3500;

  function getPerView() {
    var w = window.innerWidth;
    if (w < 640)  return 1;
    if (w < 1024) return 2;
    return 3;
  }

  function getMax() { return Math.max(0, total - getPerView()); }

  function layout() {
    var cw = slider.offsetWidth / getPerView();
    cards.forEach(function (c) { c.style.width = cw + "px"; });
  }

  function translate(idx) {
    var cw = slider.offsetWidth / getPerView();
    track.style.transform = "translateX(" + (-idx * cw) + "px)";
  }

  function buildDots() {
    dotsWrap.innerHTML = "";
    var count = getMax() + 1;
    for (var i = 0; i < count; i++) {
      (function (idx) {
        var btn = document.createElement("button");
        btn.className = "tc-dot" + (idx === current ? " act" : "");
        btn.setAttribute("aria-label", "Slide " + (idx + 1));
        btn.addEventListener("click", function () { stop(); goTo(idx); start(); });
        dotsWrap.appendChild(btn);
      }(i));
    }
  }

  function updateDots() {
    dotsWrap.querySelectorAll(".tc-dot").forEach(function (d, i) {
      d.classList.toggle("act", i === current);
    });
  }

  function goTo(idx) {
    var max = getMax();
    if (idx < 0)   idx = max;
    if (idx > max) idx = 0;
    current = idx;
    translate(current);
    updateDots();
  }

  function stop() {
    if (timer) { clearInterval(timer); timer = null; }
  }

  function start() {
    stop(); /* prevent duplicate timers */
    timer = setInterval(function () { goTo(current + 1); }, DELAY);
  }

  /* — controls — */
  document.getElementById("tc-prev").addEventListener("click", function () { stop(); goTo(current - 1); start(); });
  document.getElementById("tc-next").addEventListener("click", function () { stop(); goTo(current + 1); start(); });

  /* — hover pause (only on the sliding area) — */
  slider.addEventListener("mouseenter", stop);
  slider.addEventListener("mouseleave", start);

  /* — touch swipe — */
  var tx = 0;
  track.addEventListener("touchstart", function (e) { stop(); tx = e.touches[0].clientX; }, { passive: true });
  track.addEventListener("touchend",   function (e) {
    var diff = tx - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) goTo(diff > 0 ? current + 1 : current - 1);
    start();
  }, { passive: true });

  /* — pause when browser tab is hidden — */
  document.addEventListener("visibilitychange", function () {
    document.hidden ? stop() : start();
  });

  /* — resize — */
  window.addEventListener("resize", function () {
    layout();
    if (current > getMax()) current = getMax();
    translate(current);
    buildDots();
  });

  /* — init after layout is painted — */
  requestAnimationFrame(function () {
    requestAnimationFrame(function () {
      layout();
      buildDots();
      start();
    });
  });
}());
