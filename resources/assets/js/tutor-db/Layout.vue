<template>
	<div class="wrapper">

		<div class="m-grid m-grid--hor m-grid--root m-page">
			<topbar></topbar>

			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">

				<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
					<i class="la la-close"></i>
				</button>
				<left-side></left-side>

				<div class="m-grid__item m-grid__item--fluid m-wrapper">

					<div class="m-subheader">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title ">
									{{ heading }}
								</h3>
							</div>
						</div>
					</div>

					<div class="m-content">
						<router-view></router-view>
					</div>	

				</div>

			</div>

			<theme-footer></theme-footer>
			
		</div>

		<quick-sidebar></quick-sidebar>

	</div><!-- wrapper -->

</template>

<script>
	import Topbar from './Topbar';
	import LeftSide from './Aside';
	import QuickSidebar from './QuickSidebar';
	import ThemeFooter from './ThemeFooter';
	import auth from "../services/auth.js";

	


    export default {
		components: {
			Topbar,
			LeftSide,
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
		},
    }


   
</script>


