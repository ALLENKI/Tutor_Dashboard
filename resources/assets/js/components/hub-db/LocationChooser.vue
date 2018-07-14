<template>
<div>

<div class="wrapper">

<div class="m-grid m-grid--hor m-grid--root m-page">
<header class="m-grid__item m-header"  data-minimize="minimize" data-minimize-offset="200" data-minimize-mobile-offset="200" >
<top-header></top-header>
<bottom-header></bottom-header>
</header>
</div>

<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
	<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver	m-container m-container--responsive m-container--xxl m-page__container">
		<div class="m-grid__item m-grid__item--fluid m-wrapper">

		<div class="m-subheader ">
			<div class="d-flex align-items-center">
				<div class="mr-auto">
							
				</div>
			</div>
		</div>

		<div class="m-content">

				<!--Begin::Main Portlet-->
				<div class="row justify-content-center">
					
					<div class="col-md-8 col-md-auto">
						<!--begin:: Widgets/Authors Profit-->
						<div class="m-portlet">

							<div class="m-portlet__head">

								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											Locations
										</h3>
									</div>
								</div>

							</div>

							<div class="m-portlet__body">
								<div class="m-widget4">


								<div class="m-widget4__item" v-for="location in locations">
									<div class="m-widget4__img m-widget4__img--logo">
										<img src="https://res.cloudinary.com/ahamlearning/image/upload/v1466164427/aham_icon_m6ljr5.png" alt="">
									</div>
									<div class="m-widget4__info">
										<span class="m-widget4__title">
										<a v-bind:href="formatHubLink(location.slug)" class="">
											{{ location.name }}
										</a>
										</span>
										<br>
										<span class="m-widget4__sub">
											{{location.street_address}}
										</span>
									</div>
								</div>

								</div>
							</div>

						</div>
						<!--end:: Widgets/Authors Profit-->
					</div>

				</div>
				<!--End::Main Portlet-->

			</div>
		</div>
	</div>

</div>
</div>
</div>
</template>

<script>
import TopHeader from '../../hub-db/TopHeader';
import BottomHeader from '../../hub-db/BottomHeader';
import QuickSidebar from '../../hub-db/QuickSidebar';
import axios from 'axios';
import auth from "../../services/auth.js";

export default{

components: {
	TopHeader,
	BottomHeader,
	QuickSidebar,
},

data: function() {
	return {
	    locations: [] 
	};
},
mounted(){

	auth.check();

	axios.get('/hub/available-locations')
	  .then((response) => {
	        this.locations = response.data.locations;
	  })
	  .catch((error) => {
	    console.log(error);
	  });

},
methods:{
	formatHubLink(slug)
	{
		return "/hub-db/#/hub/"+slug+"/dashboard";
	}
}

}
</script>