    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     BUILT-IN CRM DASHBOARD
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    <div id="crm-page">
      <div class="crm-nav">
        <div class="crm-logo">JOMO<span class="dot">.</span> CRM</div>
        <button class="crm-close" onclick="closeCRM()">âœ• Exit CRM</button>
      </div>
      <div class="crm-body">
        <div class="crm-stats" id="crm-stats">
          <div class="crm-stat">
            <span class="crm-stat-n" id="stat-total">0</span
            ><span class="crm-stat-l">Total Leads</span>
          </div>
          <div class="crm-stat gold-card">
            <span class="crm-stat-n" id="stat-new">0</span
            ><span class="crm-stat-l">New Leads</span>
          </div>
          <div class="crm-stat">
            <span class="crm-stat-n" id="stat-qualified">0</span
            ><span class="crm-stat-l">Qualified</span>
          </div>
          <div class="crm-stat">
            <span class="crm-stat-n" id="stat-today">0</span
            ><span class="crm-stat-l">Today</span>
          </div>
        </div>
        <div class="crm-actions">
          <button
            class="crm-filter act"
            data-cf="all"
            onclick="filterCRM(this, 'all')"
          >
            All
          </button>
          <button
            class="crm-filter"
            data-cf="new"
            onclick="filterCRM(this, 'new')"
          >
            New
          </button>
          <button
            class="crm-filter"
            data-cf="contacted"
            onclick="filterCRM(this, 'contacted')"
          >
            Contacted
          </button>
          <button
            class="crm-filter"
            data-cf="qualified"
            onclick="filterCRM(this, 'qualified')"
          >
            Qualified
          </button>
          <button
            class="crm-filter"
            data-cf="lost"
            onclick="filterCRM(this, 'lost')"
          >
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

    <!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     JAVASCRIPT ENGINE
â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
    <script src="Jomo_Updated.js"></script>
  </body>
</html> 
