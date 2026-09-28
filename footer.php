<div id="crm-page">
  <div class="crm-nav">
    <div class="crm-logo"><span class="jomo-brand">JOMO<span class="jomo-dot">.</span></span> CRM</div>
    <button class="crm-close" onclick="closeCRM()">Exit CRM</button>
  </div>
  <div class="crm-body">
    <div class="crm-stats" id="crm-stats">
      <div class="crm-stat">
        <span class="crm-stat-n" id="stat-total">0</span><span class="crm-stat-l">Total Leads</span>
      </div>
      <div class="crm-stat gold-card">
        <span class="crm-stat-n" id="stat-new">0</span><span class="crm-stat-l">New Leads</span>
      </div>
      <div class="crm-stat">
        <span class="crm-stat-n" id="stat-qualified">0</span><span class="crm-stat-l">Qualified</span>
      </div>
      <div class="crm-stat">
        <span class="crm-stat-n" id="stat-today">0</span><span class="crm-stat-l">Today</span>
      </div>
    </div>
    <div class="crm-actions">
      <button class="crm-filter act" data-cf="all" onclick="filterCRM(this, 'all')">
        All
      </button>
      <button class="crm-filter" data-cf="new" onclick="filterCRM(this, 'new')">
        New
      </button>
      <button class="crm-filter" data-cf="contacted" onclick="filterCRM(this, 'contacted')">
        Contacted
      </button>
      <button class="crm-filter" data-cf="qualified" onclick="filterCRM(this, 'qualified')">
        Qualified
      </button>
      <button class="crm-filter" data-cf="lost" onclick="filterCRM(this, 'lost')">
        Lost
      </button>
      <button class="crm-export" onclick="exportCSV()">Export CSV</button>
    </div>
    <div class="crm-table-wrap">
      <table class="crm-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Property</th>
            <th>Service</th>
            <th>Source</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody id="crm-tbody">
          <tr>
            <td colspan="10" class="crm-empty">
              No leads yet. Submit the contact form to see leads appear
              here.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<footer>
  <div class="fg">
    <div>
      <a href="#" onclick="
            goTo('home');
            return false;
          ">
        <img src="assets/Images/jomo_logo2.png" style="width:30%" alt="Jomo Logo" class="logo mb-3">
      </a>
      <p class="fga">
        Crafting homes where you belong. Premium interior design from
        Mogappair, Chennai.
      </p>
      <div class="fgc">
        <a href="tel:+919940187677">+91 99401 87677</a><a
          href="mailto:info@jomointeriors.in">info@jomointeriors.in</a><address><a href="#">Mogappair, Chennai, TN</a></address>
      </div>
    </div>
    <div class="fg-col">
      <h3>Services</h3>
      <ul>
        <li>
          <a href="#" onclick="goToSection('services','living-spaces');return false;">Living Spaces</a>
        </li>
        <li>
          <a href="#" onclick="goToSection('services','modular-kitchen');return false;">Modular Kitchen</a>
        </li>
        <li>
          <a href="#" onclick="goToSection('services','bedroom-wardrobe');return false;">Bedroom & Wardrobe</a>
        </li>
        <li>
          <a href="#" onclick="goToSection('services','false-ceiling');return false;">False Ceiling</a>
        </li>
      </ul>
    </div>
    <div class="fg-col">
      <h3>Company</h3>
      <ul>
        <li>
          <a href="about.php">About <span class="jomo-brand">JOMO<span class="jomo-dot">.</span></span></a>
        </li>
        <li>
          <a href="#" onclick="
                    goTo('process');
                    return false;
                  ">Our Process</a>
        </li>
        <li>
          <a href="#" onclick="
                    goTo('projects');
                    return false;
                  ">Portfolio</a>
        </li>
        <li>
          <a href="#" onclick="
                    goTo('contact');
                    return false;
                  ">Experience Centre</a>
        </li>
      </ul>
    </div>
    <div class="fg-col">
      <h3>Connect</h3>
      <ul>
        <li><a href="https://www.instagram.com/jomo_homeinteriors/" target="_blank">Instagram</a></li>
        <li><a href="https://www.facebook.com/profile.php?id=61565428650850&mibextid=ZbWKwL" target="_blank">Facebook</a></li>
        <li><a href="https://wa.me/919940187677" target="_blank">WhatsApp</a></li>
        <li><a href="https://www.linkedin.com/company/jomohomeinteriors/?originalSubdomain=in" target="_blank">Linkedin</a></li>
      </ul>
    </div>
  </div>
  <div class="fb">
    <p class="fb-copy text-center">
      © <?= date("Y"); ?> <span class="jomo-brand">JOMO<span class="jomo-dot">.</span></span> Home Interiors - Mogappair, Chennai
    </p>
    <div class="fb-copy">
      <p class="ft-link">
        <a href="terms.php" class="text-white" target="_blank">Terms and Conditions |</a>
        <a href="privacy.php" class="text-white" target="_blank">Privacy Policy</a>
      </p>
    </div>
  </div>
</footer>
</div>
<button class="scroll-top" id="scroll-top" type="button" aria-label="Scroll to top">
  <span class="scroll-top__ring" aria-hidden="true"></span>
  <span class="scroll-top__arrow" aria-hidden="true">&uarr;</span>
</button>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.0/build/js/intlTelInput.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    var heroPhone = document.querySelector("#hero-phone");
    if (heroPhone) {
      window.itiHero = window.intlTelInput(heroPhone, {
        initialCountry: "in",
        countrySearch: true,
        fixDropdownWidth: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.0/build/js/utils.js"
      });
    }

    var contactPhone = document.querySelector("#contact-phone");
    if (contactPhone) {
      window.itiContact = window.intlTelInput(contactPhone, {
        initialCountry: "in",
        countrySearch: true,
        fixDropdownWidth: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.0/build/js/utils.js"
      });
    }

    var servicePhone = document.querySelector("#service-phone");
    if (servicePhone) {
      window.itiService = window.intlTelInput(servicePhone, {
        initialCountry: "in",
        countrySearch: true,
        fixDropdownWidth: true,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.0/build/js/utils.js"
      });
    }
  });
</script>
<script src="Jomo_Updated.js?v=2"></script>

</body>

</html>
