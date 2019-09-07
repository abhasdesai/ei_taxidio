<header class="main-header">
<!-- Logo -->
<a href="<?php echo site_url('admins/dashboard') ?>" class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><b>TAXIDIO</b></span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><b>TAXIDIO</b></span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
	<span class="sr-only">Toggle navigation</span>
  </a>
  <div class="navbar-custom-menu">
	<ul class="nav navbar-nav">
	 <li class="dropdown user user-menu">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		 <span class="hidden-xs">My Account</span>
		</a>
		<ul class="dropdown-menu">
		  <!-- User image -->
		 
		 
		  <!-- Menu Footer-->
		  <li class="user-footer">
			<div class="pull-left">
			  <a href="javascript:void(0);" class="btn btn-default btn-flat" onClick="openChangePasswordModal()">Change Password</a>
			</div>
			<div class="pull-right">
			  <a href="<?php echo site_url('admins/dashboard/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
			</div>
		  </li>
		</ul>
	  </li>
	  <!-- Control Sidebar Toggle Button -->

	</ul>
  </div>
</nav>
</header>
