<!doctype html>
<html lang="fa" dir="rtl">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="msapplication-tap-highlight" content="no"/>
		<link rel="icon" type="image/png" href="{{ URL::asset('assets/img/favicon-16x16.png') }}" sizes="16x16">
		<link rel="icon" type="image/png" href="{{ URL::asset('assets/img/favicon-32x32.png') }}" sizes="32x32">
		<title>سامانه آزمون الکترونیک</title>
		<link rel="stylesheet" href="{{ URL::asset('assets/css/uikit.rtl.css') }}" media="all">
		<link rel="stylesheet" href="{{ URL::asset('assets/icons/flags/flags.min.css') }}" media="all">
		<link rel="stylesheet" href="{{ URL::asset('assets/css/style_switcher.min.css') }}" media="all">
		<link rel="stylesheet" href="{{ URL::asset('assets/css/main.min.css') }}" media="all">
		<link rel="stylesheet" href="{{ URL::asset('assets/css/themes/themes_combined.min.css') }}" media="all">
		<link rel="stylesheet" href="{{ URL::asset('assets/css/custom.css') }}" media="all">

		<!-- calendar -->
		<link rel="stylesheet" href="{{ URL::asset('assets/calendar/bootstrap.css') }}" media="all">
		<link rel="stylesheet" href="{{ URL::asset('assets/calendar/bootstrap-theme.css') }}" media="all">
		<link rel="stylesheet" href="{{ URL::asset('assets/calendar/jquery.Bootstrap-PersianDateTimePicker.css') }}" media="all">
		
		<link rel="stylesheet" href="{{ URL::asset('assets/fontawesome/css/font-awesome.min.css') }}" media="all">
	</head>
	<body class=" sidebar_main_open sidebar_main_swipe">
		<!-- main header -->
		<header id="header_main">
			<div class="header_main_content">
				<nav class="uk-navbar">
					<!-- main sidebar switch -->
					<a href="list.html#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
					<span class="sSwitchIcon"></span>
					</a>
					<div class="uk-navbar-flip">
						<ul class="uk-navbar-nav user_actions">
							<!-- <li><a href="" id="full_screen_toggle" class="user_action_icon uk-visible-large"><i class="material-icons md-24 md-light">&#xE5D0;</i></a></li> -->
							<li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
								<a href="javascript::void();" class="user_action_image">{{ Auth::user()->name }} {{ Auth::user()->lname }} <img class="md-user-image" src="{{ URL::asset('assets/img/avatars/avatar_09.png') }}" alt=""/></a>
								<div class="uk-dropdown uk-dropdown-small">
									<ul class="uk-nav js-uk-prevent">
										<li><a href="{{ URL::to('users/edit') }}/{{ Auth::user()->id }}">پروفایل من</a></li>
										<li><a href="{{ URL::to('auth/logout') }}">خروج</a></li>
									</ul>
								</div>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!-- main header end -->
		<!-- main sidebar -->
		<aside id="sidebar_main">
			<div class="sidebar_main_header">
				<div class="sidebar_logo">
					<a href="{{ URL::to('/') }}" class="sSidebar_hide sidebar_logo_large" style="color: #ffffff; background-color: rgba(0 , 0 , 0 , 0.6); padding: 0 6px 3px 7px; font-size: 16px; line-height: 32px; margin-right: 0;">
						سامانه آزمون الکترونیک
					</a>
				</div>
			</div>
			<div class="menu_section">
				<ul>
					<li title="نتایج آزمون‌ها">
						<a href="{{ URL::to('results') }}">
						<span class="menu_icon"><i class="fa fa-graduation-cap"></i></span>
						<span class="menu_title">نتایج آزمون‌ها</span>
						</a>
					</li>
					<li title="برگذاری آزمون‌ها">
						<a href="{{ URL::to('takeexams') }}">
						<span class="menu_icon"><i class="fa fa-calendar-check-o"></i></span>
						<span class="menu_title">آزمون‌های درحال برگذاری</span>
						</a>
					</li>
					<?php if((\Auth::user()->group) == 'instructor' || \Auth::user()->id == 1): ?>
					<li title="آزمون‌ها">
						<a href="{{ URL::to('exams') }}">
						<span class="menu_icon"><i class="material-icons">create</i></span>
						<span class="menu_title">آزمون‌ها</span>
						</a>
					</li>
					<li title="بانک سوال‌ها">
						<a href="{{ URL::to('bankquestions') }}">
						<span class="menu_icon"><i class="material-icons">archive</i></span>
						<span class="menu_title">بانک سوال‌ها</span>
						</a>
					</li>
					<li title="دسته‌بندی آزمون‌ها">
						<a href="{{ URL::to('categories') }}">
						<span class="menu_icon"><i class="fa fa-folder-open"></i></span>
						<span class="menu_title">دسته‌بندی آزمون‌ها</span>
						</a>
					</li>
					<li title="درجه‌های سختی سوال‌ها">
						<a href="{{ URL::to('difficultylevels') }}">
						<span class="menu_icon"><i class="fa fa-certificate"></i></span>
						<span class="menu_title">درجه‌های سختی سوال‌ها</span>
						</a>
					</li>
					<?php endif ?>
					<?php if((\Auth::user()->id) == 1): ?>
						<li title="کاربران">
							<a href="{{ URL::to('users') }}">
							<span class="menu_icon"><i class="material-icons">people</i></span>
							<span class="menu_title">کاربران</span>
							</a>
						</li>
					<?php endif ?>
					<li title="پروفایل من">
						<a href="{{ URL::to('users/edit') }}/{{ Auth::user()->id }}">
						<span class="menu_icon"><i class="material-icons">person</i></span>
						<span class="menu_title">پروفایل من</span>
						</a>
					</li>
					<li title="خروج">
						<a href="{{ URL::to('auth/logout') }}">
						<span class="menu_icon"><i class="material-icons">lock</i></span>
						<span class="menu_title">خروج</span>
						</a>
					</li>
				</ul>
			</div>
			<div style="text-align: center; width: 100%; position: absolute; bottom: 0; color: #424242; font-size: 11px; margin-bottom: 10px;">Designed by <a href="javascript:void(0)" target="blank" data-uk-tooltip title="استاد راهنما: دکتر حمیدزاده<br>تهیه کننده: مهرزاد احمدیان">Mehrzad Ahmadian</a></div>
		</aside>
		<!-- main sidebar end -->
		<div id="page_content">
			<div id="page_content_inner">
				<div id="top_bar">
					<ul id="breadcrumbs">
						@yield('breadcrumbs')
					</ul>
				</div>
				<div class="md-card uk-margin-medium-bottom">
					<div class="md-card-content">
						@include('common.errors')
						@yield('content')
					</div>
				</div>
			</div>
		</div>
		<div>
		</div>
		<script src="{{ URL::asset('assets/js/common.min.js') }}"></script>			
		<script src="{{ URL::asset('assets/js/uikit_custom.min.js') }}"></script>			
		<script src="{{ URL::asset('assets/js/altair_admin_common.min.js') }}"></script>

		<script src="{{ URL::asset('assets/countdown-timer/jquery.countdownTimer.js') }}"></script>

		<!-- calendar -->
		<script src="{{ URL::asset('assets/calendar/bootstrap.js') }}"></script>
		<script src="{{ URL::asset('assets/calendar/calendar.js') }}"></script>
		<script src="{{ URL::asset('assets/calendar/jquery.Bootstrap-PersianDateTimePicker.js') }}"></script>
		<script>
			$(function() {
				if(isHighDensity) {
					// enable hires images
					altair_helpers.retina_images();
				}
				if(Modernizr.touch) {
					// fastClick (touch devices)
					FastClick.attach(document.body);
				}
			});
			$window.load(function() {
				// ie fixes
				altair_helpers.ie_fix();
			});
		</script>
	</body>
</html>
