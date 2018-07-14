<template>
  <div>

	<div class="m-header__top">
		<div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
			<div class="m-stack m-stack--ver m-stack--desktop">
				<!-- begin::Brand -->
						<div class="m-stack__item m-brand">
							<div class="m-stack m-stack--ver m-stack--general m-stack--inline">
								<div class="m-stack__item m-stack__item--middle m-brand__logo">
									<a href="/admin-db" class="m-brand__logo-wrapper" v-if="!loading">
										<img alt="" src="https://res.cloudinary.com/ahamlearning/image/upload/c_fit,h_50,q_auto:eco,f_auto/v1466848309/logo_huge_lfpjpa.png"/>
									</a>
									<div v-else>
										<div class="m-spinner m-spinner--brand m-spinner--sm"></div>
										<div class="m-spinner m-spinner--primary m-spinner--sm"></div>
										<div class="m-spinner m-spinner--success m-spinner--sm"></div>
										<div class="m-spinner m-spinner--info m-spinner--sm"></div>
										<div class="m-spinner m-spinner--warning m-spinner--sm"></div>
										<div class="m-spinner m-spinner--danger m-spinner--sm"></div>
									</div>
								</div>
							</div>
						</div>
				<!-- end::Brand -->		
				<!-- begin::Topbar -->
						<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
							<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-topbar__nav-wrapper">
									<ul class="m-topbar__nav m-nav m-nav--inline">
										<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click" v-if="user">
											<a href="#" class="m-nav__link m-dropdown__toggle">
												<span class="m-topbar__welcome">
													Hello,&nbsp;
												</span>
												<span class="m-topbar__username">
													{{ user.name }}
												</span>
												<span class="m-topbar__userpic">
													<img :src="user.avatar" class="m--img-rounded m--marginless m--img-centered" alt=""/>
												</span>
											</a>
											<div class="m-dropdown__wrapper">
												<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
												<div class="m-dropdown__inner">
													<div class="m-dropdown__header m--align-center" style="background: url(/dist/media/img/misc/user_profile_bg.jpg); background-size: cover;">
														<div class="m-card-user m-card-user--skin-dark">
															<div class="m-card-user__pic">
																<img :src="user.avatar" class="m--img-rounded m--marginless" alt=""/>
															</div>
															<div class="m-card-user__details">
																<span class="m-card-user__name m--font-weight-500">
																	{{ user.name }}
																</span>
																<a href="" class="m-card-user__email m--font-weight-300 m-link">
																	{{ user.email }}
																</a>
															</div>
														</div>
													</div>
													<div class="m-dropdown__body">
														<div class="m-dropdown__content">
															<ul class="m-nav m-nav--skin-light">
																<li class="m-nav__section m--hide">
																	<span class="m-nav__section-text">
																		Section
																	</span>
																</li>

																<li class="m-nav__separator m-nav__separator--fit"></li>
																<li class="m-nav__item">
																	<a href="/auth/logout" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
																		Logout
																	</a>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</li>
										<!-- <li id="m_quick_sidebar_toggle" class="m-nav__item">
											<a href="#" class="m-nav__link m-dropdown__toggle">
												<span class="m-nav__link-icon m-nav__link-icon--aside-toggle">
													<span class="m-nav__link-icon-wrapper">
														<i class="flaticon-menu-button"></i>
													</span>
												</span>
											</a>
										</li> -->
									</ul>
								</div>
							</div>
						</div>
				<!-- end::Topbar -->
			</div>
		</div>
	</div>

  </div>
</template>
import store from "../../../store";

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
