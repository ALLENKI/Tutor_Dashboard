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
							<h3 class="m-subheader__title m-subheader__title--separator">{{ heading }}</h3>
							<breadcrumb></breadcrumb>	
						</div>
					</div>
				</div>

				<div class="m-content">
					<router-view :hub="hub" v-if="hub"></router-view>
				</div>	

				</div>
			</div>
	</div>

	<quick-sidebar></quick-sidebar>

</div><!-- wrapper -->
</div>
</template>

<script>
import TopHeader from '../../hub-db/TopHeader';
import BottomHeader from '../../hub-db/BottomHeader';
import QuickSidebar from '../../hub-db/QuickSidebar';
import axios from 'axios';
import auth from "../../services/auth.js";
import store from "../../store";
import Breadcrumb from "../Breadcrumb";

export default {
	components: {
		TopHeader,
		BottomHeader,
		QuickSidebar,
		Breadcrumb,
	},
	data: function()
	{
		return {
			hub:null,
			hub_slug: null
		};
	},
    watch:{
        '$route':function(){
            this.refresh();
        }
    },
	mounted() {
		auth.check();
		this.refresh();
	},
	computed: {
		heading() {
		  return this.$store.state.heading;
		}
	},
    methods: {

    	refresh() {
    		this.hub_slug = this.$route.params.hub;
    		this.getHub();
    	},
        getHub() {

            axios.get('/hub/location/'+this.hub_slug).then((response) => {            	
            	this.hub = response.data.location;
            	store.dispatch('setHub',response.data.location);
            })
            .catch((error) => {

            	console.log(error);

            });

        }

    }
}
</script>


<style>

.el-cascader,.el-select,.el-input-number {
  width: 100%;
}

</style>
