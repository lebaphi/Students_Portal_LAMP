<script src="../form-ui/components-scripts/nav.bar.script.js" type="text/javascript"></script>
<div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
	<a class="kt-header__brand-logo" href="javascript:;">
		<img alt="Logo" src="../assets/media/logos/default-sm.png" class="kt-header__brand-logo-default brand-logo"/>
		<img alt="Logo" src="../assets/media/logos/default-sm.png" class="kt-header__brand-logo-sticky"/>
	</a>
</div>

<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn">
	<i class="la la-close"></i>
</button>

<div class="kt-header-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_header_menu_wrapper">
	<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile ">
		<ul class="kt-menu__nav" id="mainNav">
<!--			<li class="kt-menu__item  kt-menu__item--submenu kt-menu__item--rel" data-ktmenu-submenu-toggle="click" aria-haspopup="true">-->
<!--				<a href="javascript:;" name="./dashboard/dashboard.php" class="kt-menu__link kt-menu__toggle">-->
<!--					<span class="kt-menu__link-text">Dashboard</span>-->
<!--					<i class="kt-menu__ver-arrow la la-angle-right"></i>-->
<!--				</a>-->
<!--			</li>-->
		</ul>
	</div>
</div>

<div class="kt-header__topbar kt-grid__item">
	
	<div class="kt-header__topbar-item dropdown">
		<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
			<div class="kt-header__topbar-item dropdown kt-margin-r-5">
				<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
						<span class="kt-header__topbar-icon kt-pulse kt-pulse--light" onclick="loadNotification(<?php echo UserService::getInstance()->getCurrentUser()->getId(); ?>)">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect id="bound" x="0" y="0" width="24" height="24"/>
									<path
											d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z"
											id="Combined-Shape" fill="#000000" opacity="0.3"/>
									<path
											d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z"
											id="Combined-Shape" fill="#000000"/>
								</g>
							</svg>
							<span class="kt-pulse__ring"></span>
						</span>
				</div>
				<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-lg">
					<form>
						<div class="kt-head kt-head--skin-dark kt-head--fit-x kt-head--fit-b" style="background-image: url(../assets/media/misc/bg-1.jpg)">
							<h3 class="kt-head__title">
								User Notifications
								<span class="btn btn-success btn-sm btn-bold btn-font-md" id="notification_count"></span>
							</h3>
							<ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success kt-notification-item-padding-x" role="tablist">
								<li class="nav-item">
									<a class="nav-link active show" data-toggle="tab" href="#topbar_notifications" role="tab" aria-selected="true">Events</a>
								</li>
							</ul>
						</div>
						
						<div class="tab-content">
							<div class="tab-pane active" id="topbar_notifications" role="tabpanel">
								<div class="kt-notification kt-margin-t-5 kt-margin-b-5 kt-scroll" style="overflow-y: auto; max-height: 300px;" data-scroll="true" id="notification_group">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div class="kt-header__topbar-item kt-header__topbar-item--user">
		<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,0px">
			<?php
				echo '<span class="kt-header__topbar-welcome">Hi,';
				$name = $currentUser->getFirstName();
				$email = $currentUser->getEmail();
				echo '
				</span>
				<span class="kt-header__topbar-username">
					' . $name . '
				</span>;
				<img style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;" src="../assets/media/users/default.jpg"/>'
			?>
		</div>
		
		<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl" id="mainProfile">
			<?php
			echo '
			<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(../assets/media/demos/demo8/bg-1.jpg)">
				<div class="kt-user-card__avatar">
					<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success">
						' . ucwords($name === '' ? '' : $name[0]) . '
					</span>
				</div>
				<div class="kt-user-card__name">
					' . $email . '
				</div>
			</div>
			
			<div class="kt-notification">';
			  echo '<a href="javascript:;" name="./new-profile/profile.php" class="kt-notification__item">
					<div class="kt-notification__item-icon">
						<i class="flaticon2-calendar-3 kt-font-success"></i>
					</div>
					<div class="kt-notification__item-details">
						<div class="kt-notification__item-title kt-font-bold">
							My Profile
						</div>
						<div class="kt-notification__item-time">
							Account settings and more
						</div>
					</div>
				</a>';
				

				echo '<div class="kt-notification__custom kt-space-between">
					<form action="../authenticate/signout/signout.inc.php" method="post">';
					echo '<button type="button" class="btn btn-label btn-label-brand btn-sm btn-bold" onclick="handleSignOut(this)">Sign Out</button>';
				echo '
					</form>
				</div>
			</div>';
			?>
		</div>
	</div>
</div>