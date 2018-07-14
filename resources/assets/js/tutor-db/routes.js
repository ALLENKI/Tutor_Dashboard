import Vue from 'vue';
import VueRouter from "vue-router";
// import Paginate from 'vuejs-paginate';
// Vue.use(Paginate);
// Vue.component('paginate', Paginate);
// //Vue.component('paginate', VuejsPaginate)
Vue.use(VueRouter);
//Vue.use(Vue.Range);
// /*import VueResource from 'vue-resource'
// import VuePaginator from 'vuejs-paginator'
// Vue.use(VueResource);*/
// //Vue.use(VuePaginate);
// =======

let routes = [
	  {
	  	path: "/my-classes",
	  	name: "my-classes",
	  	component: require("../components/tutor-db/my-classes/Index")
	  },
	

	  {
	  	path:"/my-classes/classpage/:ahamclassid",
	  	name:"classdetail",
	  	component: require("../components/tutor-db/my-classes/Classpage")
	  },

	  {
	  	path:"/my-classes/changetopic",
	  	name:"changetopic",
	  	component: require("../components/tutor-db/my-classes/changetopic")
	  },
	  {
	  	path:"/my-classes/uploadfile",
	  	name:"uploadfile",
	  	component: require("../components/tutor-db/my-classes/Uploadfile")
	  },
	  {
	  	path:"/my-classes/profile",
	  	name:"accountSettings",
	  	component: require("../components/tutor-db/my-classes/profile")
	  },
	  {
	  	path:"/my-classes/password",
	  	name:"password",
	  	component: require("../components/tutor-db/my-classes/password")
	  },
	   {
	  	path:"/my-classes/update_no",
	  	name:"update_no",
	  	component: require("../components/tutor-db/my-classes/mobileno")
	  },
 ];

export default new VueRouter({
  routes,
  linkActiveClass: "m-menu__item--active"
});