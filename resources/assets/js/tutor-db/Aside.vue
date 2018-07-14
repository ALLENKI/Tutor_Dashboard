<template>
 <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

	<!-- BEGIN: Aside Menu -->
	<div 
		id="m_ver_menu" 
		class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " 
		data-menu-vertical="true"

		 data-menu-scrollable="false" data-menu-dropdown-timeout="500">
		
						<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">

							<router-link tag="li" :to="{path: '/' }" exact  class="m-menu__item" aria-haspopup="true">
								<a class="m-menu__link ">
									<i class="m-menu__link-icon la la-home"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												Dashboard
											</span>
										</span>
									</span>
								</a>
							</router-link>

							<router-link tag="li" :to="{path: '/browse-topics' }" exact  class="m-menu__item" aria-haspopup="true">
								<a class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-edit"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												Invitations
											</span>
										</span>
									</span>
								</a>
							</router-link>

							<router-link tag="li" :to="{path: '/browse-courses' }" exact  class="m-menu__item" aria-haspopup="true">
								<a class="m-menu__link ">
									<i class="m-menu__link-icon la la-graduation-cap"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text">
												Aham Catalog
											</span>
										</span>
									</span>
								</a>
							</router-link>

							<li class="m-menu__section">
								<h4 class="m-menu__section-text">
									Tutor...
								</h4>
								<i class="m-menu__section-icon flaticon-more-v3"></i>

							</li>
							
					
                           <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-signs-2"></i>
									<span class="m-menu__link-text">
										<router-link tag="li" :to="{path: '/my-classes' }">My Classes
										</router-link>
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

                          <li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-coins"></i>
									<span class="m-menu__link-text">
										My Certification
									</span>
								</a>
							</li>

		 
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-coins"></i>
									<span class="m-menu__link-text">
										Payment History
									</span>
								</a>
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon la la-book"></i>
									<span class="m-menu__link-text">
										Aham Catalog
									</span>
								</a>
							</li>

							
							
							
							<router-link tag="li" :to="{path: '/my-classes/profile' }" exact  class="m-menu__item" aria-haspopup="true">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon la la-cog"></i>
									<span class="m-menu__link-text">
										Account Settings
									</span>
								</a>
							
							</router-link>
						

							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true"  data-menu-submenu-toggle="hover">
								<a  href="#" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon la la-power-off"></i>
									<span class="m-menu__link-text">
										Logout

									</span>
								</a>
							</li>
						</li>


						</ul>
					</div>
					<!-- END: Aside Menu -->

	</div>
</template>
<script>
    export default {
  mounted() {
  },
  computed: {
    user() {
      return this.$store.state.user;
    },
    loading() {
      return this.$store.state.loading;

    }
  }
};
</script>
<style>
.sidebar-user-material-content > a > img {
    height: 100px;
}
.sidebar-user-material .category-content {
    background: url(https://res.cloudinary.com/ahamlearning/image/upload/c_thumb,h_1119,w_1493/c_thumb,h_1119,l_black_opacity_90_kvowki,w_1493/v1466164427/aham_icon_m6ljr5.png) center center no-repeat;
    }
 .img-circle{
 border-radius: 50%;
 }
 .img-responsive{
 display: block;
 max-width: 50%;
 margin: 0 auto;
 }
.div{
	display:block;
}
.category-content {
    position: relative;
    padding: 20px;
    }
*{
	box-sizing: border-box;
}
</style>

