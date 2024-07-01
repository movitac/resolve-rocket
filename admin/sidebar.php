<style>
  /* Change text color to white for the sidebar */
  .sb-sidenav a {
    color: white !important;
    transition: color 0.3s ease;
    /* Smooth transition for color change */
  }

  /* Hover effect for sidebar links */
  .sb-sidenav a:hover {
    color: #ffc107 !important;
    /* Change color on hover */
    text-decoration: none;
    /* Remove underline */
  }

  .sb-sidenav a.active {
    color: #ffc107 !important;
  }
</style>
<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">Menu</div>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-dashboard.php') ? 'active' : ''; ?>" href="admin-dashboard.php">
          <div class="sb-nav-link-icon"></div>
          Dashboard
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-new-tickets.php') ? 'active' : ''; ?>" href="admin-new-tickets.php">
          <div class="sb-nav-link-icon"></div>
          New Tickets
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-manage-knowledgebase.php') ? 'active' : ''; ?>" href="admin-manage-knowledgebase.php">
          <div class="sb-nav-link-icon"></div>
          Manage Knowledge base
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-feedback.php') ? 'active' : ''; ?>" href="admin-feedback.php">
          <div class="sb-nav-link-icon"></div>
          Feedback
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'admin-users.php') ? 'active' : ''; ?>" href="admin-users.php">
          <div class="sb-nav-link-icon"></div>
          Manage Users
        </a>
        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'analytics.php') ? 'active' : ''; ?>" href="analytics.php">
          <div class="sb-nav-link-icon"></div>
          Analytics
        </a>
        <!-- <a class="nav-link" href="../logout.php">
                <div class="sb-nav-link-icon"></div>
                Logout
            </a> -->
      </div>
    </div>
  </nav>

</div>