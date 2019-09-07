<aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
         <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>

             <li class="treeview <?php  if(isset($webpagename) && ($webpagename=='Ratings' || $webpagename=='Tags' || $webpagename=='Months' || $webpagename=='Hoteltypes' || $webpagename=='Weathers' || $webpagename=='Accomodations' || $webpagename=='Travelers' || $webpagename=='Traveltimeslots' || $webpagename=='Budgets' || $webpagename=='Dois' || $webpagename=='Days' || $webpagename=='Mandatorytagmaster' || $webpagename=='defaulttags' || $webpagename=='Packages')){ echo 'active'; } ?>">
              <a href="#"><i class="fa fa-fw fa-suitcase"></i> <span>Masters</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
				  <li class="<?php if ($webpagename == 'Packages') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Packages') ?>')"><i class="fa fa-circle-o"></i><span>Travel Packages</span></a></li>
                 <li class="<?php if ($webpagename == 'Ratings') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Ratings') ?>')"><i class="fa fa-circle-o"></i><span>Ratings</span></a></li>
                 <li class="<?php if ($webpagename == 'Tags') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Tags') ?>')"><i class="fa fa-circle-o"></i><span>Tags</span></a></li>
                 <?php /* ?>
                 <li class="<?php if ($webpagename == 'Months') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Months') ?>')"><i class="fa fa-circle-o"></i><span>Months</span></a></li>
                 <?php */ ?>
                 <li class="<?php if ($webpagename == 'Hoteltypes') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Hoteltypes') ?>')"><i class="fa fa-circle-o"></i><span>Hotel Types</span></a></li>
                 <li class="<?php if ($webpagename == 'Weathers') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Weathers') ?>')"><i class="fa fa-circle-o"></i><span>Weather</span></a></li>
                 <li class="<?php if ($webpagename == 'Accomodations') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Accomodations') ?>')"><i class="fa fa-circle-o"></i><span>Accomodations</span></a></li>
                 <li class="<?php if ($webpagename == 'Travelers') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Travelers') ?>')"><i class="fa fa-circle-o"></i><span>Travelers</span></a></li>
                <li class="<?php if ($webpagename == 'Dois') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Dois') ?>')"><i class="fa fa-circle-o"></i><span>DOI</span></a></li>
               <li class="<?php if ($webpagename == 'Traveltimeslots') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Traveltimeslots') ?>')"><i class="fa fa-circle-o"></i><span>Travel Time Slot</span></a></li>
               <li class="<?php if ($webpagename == 'Budgets') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Budgets') ?>')"><i class="fa fa-circle-o"></i><span>Hotel Budget Per Night</span></a></li>
             <li class="<?php if ($webpagename == 'Days') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Days') ?>')"><i class="fa fa-circle-o"></i><span>Days Range</span></a></li>
                <li class="<?php if ($webpagename == 'Mandatorytagmaster') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Mandatorytagmaster') ?>')"><i class="fa fa-circle-o"></i><span>Mandatory Tag</span></a></li>
                 <li class="<?php if ($webpagename == 'defaulttags') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Defaulttags') ?>')"><i class="fa fa-circle-o"></i><span>Default Tag</span></a></li>
              </ul>
            </li>

            <li class="<?php if ($webpagename == 'Continents') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Continents') ?>')"><i class="fa fa-fw fa-map"></i><span>Continents</span></a></li>

            <li class="<?php if ($webpagename == 'Countries') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Countries') ?>')"><i class="fa fa-fw fa-map-signs"></i><span>Countries</span></a></li>

            <li class="<?php if ($webpagename == 'Cities') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/city/Cities') ?>')"><i class="fa fa-fw fa-building"></i><span>Cities</span></a></li>


            <li class="treeview <?php  if(isset($webpagename) && ($webpagename=='career' || $webpagename=='Faq' || $webpagename=='terms' || $webpagename=='faqcategory' || $webpagename=='Team' || $webpagename=='privacy' || $webpagename=='discover' || $webpagename=='press' || $webpagename=='cookie' || $webpagename=='userpolicy')){ echo 'active'; } ?>">
              <a href="#"><i class="fa fa-fw fa-suitcase"></i> <span>CMS</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">

                   <li class="treeview <?php if(isset($webpagename) && ($webpagename=='Faq' || $webpagename=='faqcategory')){ echo 'active'; } ?>">

                        <a href="#"><i class="fa fa-fw fa-suitcase"></i> <span>FAQ</span> <i class="fa fa-angle-left pull-right"></i></a>

                          <ul class="treeview-menu <?php if(isset($webpagename) && ($webpagename=='faqcategory' || $webpagename=='Faq')){ echo 'active'; } ?>">

                             <li class="<?php if ($webpagename == 'faqcategory') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Faq/faqcategory') ?>')"><i class="fa fa-circle-o"></i><span>FAQ Category</span></a></li>

                             <li class="<?php if ($webpagename == 'Faq') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Faq') ?>')"><i class="fa fa-circle-o"></i><span>FAQ's</span></a></li>
                      </ul>
                </li>

                 <li class="<?php if ($webpagename == 'Team') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Team')?>')"><i class="fa fa-circle-o"></i><span>Team</span></a></li>

                 <li class="<?php if ($webpagename == 'career') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Cms/index').'/'.md5(1)?>')"><i class="fa fa-circle-o"></i><span>Career</span></a></li>

                 <?php /* ?>

                 <li class="<?php if ($webpagename == 'terms') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Cms/index').'/'.md5(2)?>')"><i class="fa fa-circle-o"></i><span>Terms & Condition</span></a></li>

                 <?php */ ?>

                  <li class="<?php if ($webpagename == 'privacy') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Cms/index').'/'.md5(3)?>')"><i class="fa fa-circle-o"></i><span>Policies</span></a></li>

                   <li class="<?php if ($webpagename == 'discover') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Cms/index').'/'.md5(4)?>')"><i class="fa fa-circle-o"></i><span>Discover Taxidio</span></a></li>
                     <?php /* ?>
                     <li class="<?php if ($webpagename == 'press') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Cms/index').'/'.md5(5)?>')"><i class="fa fa-circle-o"></i><span>Press/Media</span></a></li>
                     <?php */ ?>

                      <li class="<?php if ($webpagename == 'cookie') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Cms/index').'/'.md5(7)?>')"><i class="fa fa-circle-o"></i><span>Cookie</span></a></li>
                         <?php /* ?>
                       <li class="<?php if ($webpagename == 'userpolicy') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Cms/index').'/'.md5(8)?>')"><i class="fa fa-circle-o"></i><span>User Content & Conduct Policy</span></a></li>
                       <?php */ ?>

              </ul>
            </li>

            <?php /* ?> <li class="<?php if ($webpagename == 'Hotels') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Hotels') ?>')"><i class="fa fa-fw fa-building"></i><span>Hotels</span></a></li>
            <?php */ ?>

           <?php /* ?>
            <li class="<?php if ($webpagename == 'Attractions') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Attractions') ?>')"><i class="fa fa-fw fa-building"></i><span>Attractions</span></a></li>



           <li class="treeview <?php  if(isset($webpagename) && ($webpagename=='cityReport')){ echo 'active'; } ?>">
              <a href="#"><i class="fa fa-fw fa-suitcase"></i> <span>Reports</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                 <li class="<?php if ($webpagename == 'cityReport') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Reports/cityReport') ?>')"><i class="fa fa-circle-o"></i><span>City</span></a></li>
             </ul>
          </li>
            <?php */ ?>


            <li class="treeview <?php  if(isset($webpagename) && ($webpagename=='Seo')){ echo 'active'; } ?>">
             <a href="#"><i class="fa fa-fw fa-sitemap"></i> <span>SEO</span> <i class="fa fa-angle-left pull-right"></i></a>
             <ul class="treeview-menu">

                <li class="<?php if (isset($page) && $page == 'Top Countries') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Seo/Topcountries') ?>')"><i class="fa fa-circle-o"></i><span>Top Countries</span></a></li>

                <li class="<?php if (isset($page) && $page == 'Top Cities') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Seo/Topcities') ?>')"><i class="fa fa-circle-o"></i><span>Top Cities</span></a></li>
            </ul>
           </li>

           <li class="<?php if ($webpagename == 'Planned itineraries') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/planneditineraries/index');?>')"><i class="fa fa-fw fa-map-o"></i><span>Planned Itinerary</span></a></li>

            <li class="<?php if ($webpagename == 'Users') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/users') ?>')"><i class="fa fa-fw fa-user"></i><span>Users</span></a></li>

            <li class="<?php if ($webpagename == 'Subscribers') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Subscribers') ?>')"><i class="fa fa-fw fa-newspaper-o"></i><span>Subscribers</span></a></li>

           <li class="<?php if ($webpagename == 'Travelblueprint') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Travelblueprint') ?>')"><i class="fa fa-fw fa-camera-retro"></i><span>Travel Blue Print</span></a></li>  

           <li class="<?php if ($webpagename == 'Orders') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/orders');?>')"><i class="fa fa-fw fa-money"></i><span>Order History</span></a></li>
            
            <li class="<?php if ($webpagename == 'Settings') {echo 'active';}?>"><a href="javascript:void(0);" onclick="funRedirectPage('<?php echo site_url('admins/Settings/index');?>')"><i class="fa fa-fw fa-cog"></i><span>Settings</span></a></li>
        </ul>
        </section>
        <!-- /.sidebar -->
      </aside>

<script type="text/javascript">
function funRedirectPage(SiteURL)
{
    localStorage.removeItem('DataTables_'+window.location.pathname);
   localStorage.removeItem('DataTables_'+window.location.pathname+'_variable');
   window.location=SiteURL;
}

</script>
