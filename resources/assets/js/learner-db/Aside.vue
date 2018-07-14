<template>
	<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

	<!-- BEGIN: Aside Menu -->
	<div 
		id="m_ver_menu" 
		class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " 
		data-menu-vertical="true"
		 data-menu-scrollable="false" data-menu-dropdown-timeout="500"  
		>
						<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">

							<router-link tag="li" :to="{path: '/' }" exact  class="m-menu__item" aria-haspopup="true">
								<a class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												Dashboard
											</span>
										</span>
									</span>
								</a>
							</router-link>

							<li class="m-menu__section">
								<h4 class="m-menu__section-text">
									Learning..
								</h4>
								<i class="m-menu__section-icon flaticon-more-v3"></i>
							</li>

							<li class="m-menu__item" aria-haspopup="true" >
								<a  href="#" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												Recommended
											</span>
										</span>
									</span>
								</a>
							</li>

							<li class="m-menu__item" aria-haspopup="true" >
								<a  href="#" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												Classes At Aham
											</span>
										</span>
									</span>
								</a>
							</li>

							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-signs-2"></i>
									<span class="m-menu__link-text">
										My Classes
									</span>
									<i class="m-menu__ver-arrow la la-angle-right"></i>
								</a>
								<div class="m-menu__submenu">
									<span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
										<li class="m-menu__item  m-menu__item--parent" aria-haspopup="true" >
											<a  href="#" class="m-menu__link ">
												<span class="m-menu__link-text">
													My Classes
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/utils/session-timeout.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Today
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/utils/idle-timer.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Upcoming
												</span>
											</a>
										</li>
										<li class="m-menu__item " aria-haspopup="true" >
											<a  href="components/utils/idle-timer.html" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot">
													<span></span>
												</i>
												<span class="m-menu__link-text">
													Completed
												</span>
											</a>
										</li>
									</ul>
								</div>
							</li>

							<li class="m-menu__item" aria-haspopup="true" >
								<a  href="#" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												Aham Catalog
											</span>
										</span>
									</span>
								</a>
							</li>

							<li class="m-menu__item" aria-haspopup="true" >
								<a  href="#" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												My Goals
											</span>
										</span>
									</span>
								</a>
							</li>

							<li class="m-menu__item" aria-haspopup="true" >
								<a  href="#" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												Q&A
											</span>
										</span>
									</span>
								</a>
							</li>


							<li class="m-menu__section">
								<h4 class="m-menu__section-text">
									You..
								</h4>
								<i class="m-menu__section-icon flaticon-more-v3"></i>
							</li>

							<li class="m-menu__item" aria-haspopup="true" >
								<a  href="#" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												My Credits
											</span>
										</span>
									</span>
								</a>
							</li>
							
							<li class="m-menu__item" aria-haspopup="true" >
								<a  href="#" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-line-graph"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												My Account Settings
											</span>
										</span>
									</span>
								</a>
							</li>


						</ul>
					</div>
					<!-- END: Aside Menu -->

	</div>
</template>

<script>

    export default {
        mounted() {
        }
    }

</script>