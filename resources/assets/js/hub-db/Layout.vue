<template>

		<router-view></router-view>

</template>

<script>
import TopHeader from './TopHeader';
import BottomHeader from './BottomHeader';
import QuickSidebar from './QuickSidebar';
import axios from 'axios';
import auth from "../services/auth.js";

export default {
	components: {
		TopHeader,
		BottomHeader,
		QuickSidebar,
	},
    mounted() {
		console.log('Admin Layout Mounted');
		auth.check();
    },
	computed: {
		heading() {
		  return this.$store.state.heading;
		}
	}
}
</script>
