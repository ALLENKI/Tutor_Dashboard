<template>

	<div>

			<div class="wrapper">
				
						<div class="m-grid m-grid--hor m-grid--root m-page">

								<header class="m-grid__item m-header"  data-minimize="minimize" data-minimize-offset="200" data-minimize-mobile-offset="200" >
											<top-header></top-header>
											<bottom-header></bottom-header>
								</header>

								<theme-footer></theme-footer>

						</div>

						<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">

								<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver	m-container m-container--responsive m-container--xxl m-page__container">

									<div class="m-grid__item m-grid__item--fluid m-wrapper">

											<div class="m-subheader ">
												<div class="d-flex align-items-center">
													<div class="mr-auto">
														<h3 class="m-subheader__title">{{ heading }}</h3>			
													</div>
												</div>
											</div>

											<div class="m-content">
												<router-view></router-view>
											</div>	

									</div>

								</div>

						</div>

						<quick-sidebar></quick-sidebar>

			</div>

	</div>

</template>

<script>
import TopHeader from "./TopHeader";
import BottomHeader from "./BottomHeader";
import QuickSidebar from "./QuickSidebar";
import ThemeFooter from "./ThemeFooter";
import auth from "../services/auth.js";

export default {
  components: {
    TopHeader,
    BottomHeader,
    QuickSidebar,
    ThemeFooter
  },
  mounted() {
    auth.check();
  },
  computed: {
    heading() {
      return this.$store.state.heading;
    }
  }
};
</script>
