<!-- Main sidebar -->
	<div class="sidebar sidebar-main">
		<div class="sidebar-content">

			<!-- User menu -->
			<div class="sidebar-user">
				<div class="category-content">
					<div class="media">
						<a href="#" class="media-left"><img src="{{ asset('public/backend/images/placeholder.jpg') }}" class="img-circle img-sm" alt=""></a>
						@if(Auth::guard('admin')->check())
						<div class="media-body">
							<span class="media-heading text-semibold">{{ Auth::guard('admin')->user()->name.' '.Auth::guard('admin')->user()->lname }}</span>
							<div class="text-size-mini text-muted">
								<i class="icon-pin text-size-small"></i> &nbsp;{{ Auth::guard('admin')->user()->address }}
							</div>
						</div>
						@endif
					</div>
				</div>
			</div>
			<!-- /user menu -->


			<!-- Main navigation -->
			<div class="sidebar-category sidebar-category-visible">
				<div class="category-content no-padding">
					<ul class="navigation navigation-main navigation-accordion">

						<!-- Main -->
						<li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
						<li class="{{(request()->segment(2) == 'dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
						<!-- /Main -->
					</ul>
					<ul class="navigation navigation-main navigation-accordion">
						<li class="{{(request()->segment(2) == 'users') ? 'active' : '' }}"><a href="{{ route('admin.manage-users') }}"><i class="icon-users4"></i> <span>Users</span></a></li>
					</ul>
					<ul class="navigation navigation-main navigation-accordion">
						<li class="{{(request()->segment(2) == 'category') ? 'active' : '' }}"><a href="{{ route('admin.manage-category') }}"><i class="icon-menu3"></i> <span>Menu Categories</span></a></li>
					</ul>
				</div>
			</div>
			<!-- /main navigation -->

		</div>
	</div>
<!-- /main sidebar -->