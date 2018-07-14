<template>
  <div>

	<div class="m-header__bottom">
		<div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
			
			<div class="m-stack m-stack--ver m-stack--desktop">
				
				<!-- begin::Horizontal Menu -->
					<div class="m-stack__item m-stack__item--middle m-stack__item--fluid">

						<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
							<i class="la la-close"></i>
						</button>
						<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-dark m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light">

							<ul class="m-menu__nav  m-menu__nav--submenu-arrow ">

								<router-link tag="li" :to="{path: '/' }" exact class="m-menu__item">
									<a class="m-menu__link ">
										<span class="m-menu__item-here"></span>
										<span class="m-menu__link-text">
											Dashboard
										</span>
									</a>
								</router-link>

								<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel"  data-menu-submenu-toggle="click" aria-haspopup="true" :class="{'m-menu__item--active': subIsActive('course-catalog')}">
									<a  href="#" class="m-menu__link m-menu__toggle">
										<span class="m-menu__item-here"></span>
										<span class="m-menu__link-text">
											Course Catalog
										</span>
										<i class="m-menu__hor-arrow la la-angle-down"></i>
										<i class="m-menu__ver-arrow la la-angle-right"></i>
									</a>
									<div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left">
										<span class="m-menu__arrow m-menu__arrow--adjust"></span>
										<ul class="m-menu__subnav">

											<router-link tag="li" :to="{path: '/course-catalog/browse-topics' }" exact  class="m-menu__item">
												<a  class="m-menu__link ">
													<i class="m-menu__link-icon flaticon-diagram"></i>
													<span class="m-menu__link-title">
														<span class="m-menu__link-wrap">
															<span class="m-menu__link-text">
																Browse Topics
															</span>
														</span>
													</span>
												</a>
											</router-link>

											<router-link tag="li" :to="{path: '/course-catalog/browse-courses' }" exact  class="m-menu__item">
												<a  class="m-menu__link ">
													<i class="m-menu__link-icon flaticon-diagram"></i>
													<span class="m-menu__link-title">
														<span class="m-menu__link-wrap">
															<span class="m-menu__link-text">
																Browse Courses
															</span>
														</span>
													</span>
												</a>
											</router-link>

											<li class="m-menu__item  m-menu__item--submenu"  data-menu-submenu-toggle="hover" data-redirect="true" aria-haspopup="true">
												<a  href="#" class="m-menu__link m-menu__toggle">
													<i class="m-menu__link-icon flaticon-business"></i>
													<span class="m-menu__link-text">
														Create New
													</span>
													<i class="m-menu__hor-arrow la la-angle-right"></i>
													<i class="m-menu__ver-arrow la la-angle-right"></i>
												</a>
												<div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--right">
													<span class="m-menu__arrow "></span>
													<ul class="m-menu__subnav">
														

															<router-link tag="li" :to="{path: '/course-catalog/topics/create' }" exact  class="m-menu__item" data-redirect="true" aria-haspopup="true">
																<a class="m-menu__link ">
																	<span class="m-menu__link-text">
																		Topic
																	</span>
																</a>
															</router-link>


															<router-link tag="li" :to="{path: '/course-catalog/courses/create' }" exact  class="m-menu__item" data-redirect="true" aria-haspopup="true" style="display:none;">
																<a class="m-menu__link ">
																	<span class="m-menu__link-text">
																		Course
																	</span>
																</a>
															</router-link>

															<router-link tag="li" :to="{path: '/course-catalog/sub-categories/create' }" exact  class="m-menu__item" data-redirect="true" aria-haspopup="true">
																<a class="m-menu__link ">
																	<span class="m-menu__link-text">
																		Sub Category
																	</span>
																</a>
															</router-link>

															<router-link tag="li" :to="{path: '/course-catalog/subjects/create' }" exact  class="m-menu__item" data-redirect="true" aria-haspopup="true">
																<a class="m-menu__link ">
																	<span class="m-menu__link-text">
																		Subject
																	</span>
																</a>
															</router-link>


															<router-link tag="li" :to="{path: '/course-catalog/categories/create' }" exact  class="m-menu__item" data-redirect="true" aria-haspopup="true">
																<a class="m-menu__link ">
																	<span class="m-menu__link-text">
																		Categories
																	</span>
																</a>
															</router-link>

															<router-link tag="li" :to="{name: 'create-course' }" exact  class="m-menu__item" data-redirect="true" aria-haspopup="true">
																<a class="m-menu__link ">
																	<span class="m-menu__link-text">
																		Course
																	</span>
																</a>
															</router-link>

													</ul>
												</div>
											</li>

										</ul>
									</div>
								</li>

								<router-link tag="li" :to="{path: '/hubs' }" exact class="m-menu__item">
									<a class="m-menu__link ">
										<span class="m-menu__item-here"></span>
										<span class="m-menu__link-text">
											Hubs
										</span>
									</a>
								</router-link>


								<router-link tag="li" :to="{path: '/learners' }" exact class="m-menu__item">
									<a class="m-menu__link ">
										<span class="m-menu__item-here"></span>
										<span class="m-menu__link-text">
											Learners
										</span>
									</a>
								</router-link>

								<router-link tag="li" :to="{path: '/tutors' }" exact class="m-menu__item">
									<a class="m-menu__link ">
										<span class="m-menu__item-here"></span>
										<span class="m-menu__link-text">
											Tutors
										</span>
									</a>
								</router-link>

								<router-link tag="li" :to="{path: '/payments-view' }" exact class="m-menu__item">
									<a class="m-menu__link ">
										<span class="m-menu__item-here"></span>
										<span class="m-menu__link-text">
											Payments
										</span>
									</a>
								</router-link>
								
							</ul>
							
						</div>
						
					</div>
				<!-- end::Horizontal Menu -->	

			</div>
			
		</div>
	</div>
                     
  </div>
</template>

<script>
export default {
  mounted() {},
  methods: {
	  
    subIsActive(input) {
      const paths = Array.isArray(input) ? input : [input];
      return paths.some(path => {
        return this.$route.path.indexOf(path) === 1; // current path starts with this path string
      });
	}
	
  }
};
</script>

<style type="text/css">
.m-header--fixed .m-body {
    padding-top: 80px !important;
}
</style>
