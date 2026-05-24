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
            "a,button,.mbtn,.gbtn,.pf-btn,.crm-filter,.crm-close,.crm-export",
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
      window.addEventListener(
        "scroll",
        () => {
          navEl.classList.toggle("stuck", window.scrollY > 80);
        },
        { passive: true },
      );

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
          document.querySelectorAll(".pgcard").forEach((c) => {
            c.style.display = f === "all" || c.dataset.cat === f ? "" : "none";
            if (c.classList.contains("wide"))
              c.style.gridColumn =
                f === "all" || c.dataset.cat === f ? "span 2" : "none";
          });
        });
      });

      /* ══ CRM (LocalStorage) ══ */
      let leads = JSON.parse(localStorage.getItem("jomo_leads") || "[]");
      let crmFilter = "all";

      function saveLead(lead) {
        leads.unshift(lead);
        localStorage.setItem("jomo_leads", JSON.stringify(leads));
      }

      function submitLead(e) {
        e.preventDefault();
        const f = e.target;
        const notice = document.getElementById("jf-notice");
        const btn = document.getElementById("submit-btn");
        const lbl = document.getElementById("submit-lbl");
        const firstName = f.firstName.value.trim();
        const lastName = f.lastName.value.trim();
        if (!firstName || !f.phone.value.trim()) {
          notice.className = "jf-notice err";
          notice.style.display = "block";
          notice.textContent = "Please fill in your name and phone number.";
          return;
        }
        // Save lead
        const lead = {
          id: Date.now(),
          name: `${firstName} ${lastName}`,
          phone: f.phone.value.trim(),
          email: f.email.value.trim() || "—",
          property: f.property.value || "Not specified",
          service: f.service.value || "Not specified",
          message: f.message.value.trim() || "—",
          source: "Contact Page",
          timestamp: new Date().toLocaleString("en-IN", {
            dateStyle: "medium",
            timeStyle: "short",
          }),
          status: "new",
        };
        saveLead(lead);
        // UI feedback
        lbl.textContent = "Sent Successfully ✓";
        btn.style.pointerEvents = "none";
        notice.className = "jf-notice ok";
        notice.style.display = "block";
        notice.textContent =
          "Thank you! A designer will call you within 24 hours. You can also WhatsApp us for faster response.";
        f.reset();
        setTimeout(() => {
          lbl.textContent = "Send Message →";
          btn.style.pointerEvents = "";
        }, 4000);
      }

      /* ══ CRM DASHBOARD ══ */
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

      /* ══ HOME QUICK CONTACT ══ */
      function submitHQC() {
        const name = document.getElementById("hqc-name").value.trim();
        const phone = document.getElementById("hqc-phone").value.trim();
        const notice = document.getElementById("hqc-notice");
        const lbl = document.getElementById("hqc-lbl");
        if (!name || !phone) {
          notice.className = "hqc-notice err";
          notice.style.display = "block";
          notice.textContent =
            "Please enter both your name and contact number.";
          return;
        }
        const lead = {
          id: Date.now(),
          name,
          phone,
          email: "—",
          property: "Not specified",
          service: "Not specified",
          message: "—",
          source: "Homepage Form",
          timestamp: new Date().toLocaleString("en-IN", {
            dateStyle: "medium",
            timeStyle: "short",
          }),
          status: "new",
        };
        saveLead(lead);
        lbl.textContent = "Request Sent ✓";
        notice.className = "hqc-notice ok";
        notice.style.display = "block";
        notice.textContent =
          "Thank you! We will call you back shortly during business hours.";
        document.getElementById("hqc-name").value = "";
        document.getElementById("hqc-phone").value = "";
        setTimeout(() => {
          lbl.textContent = "Request Callback →";
        }, 4000);
      }

      /* ══ INIT ══ */
      initReveal();
      initMagnetic();
      initCounters();
      collectPX();
