require('../bootstrap');
window.Vue = require('vue');
import Layout from './Layout.vue';
import common from "../common";

import router from "./routes";
import store from "../store";

import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";
import locale from "element-ui/lib/locale/lang/en";

Vue.use(ElementUI, { locale });


$(document).ready(function() {

	// console.log("ajaxSetup");
	
    $(document)
    .ajaxStart(function(){
        store.commit('start');
        // console.log("ajaxStart");
    })
    .ajaxStop(function(){
        store.commit('stop');
        // console.log("ajaxStop");
    });

    $.ajaxSetup({
        beforeSend: function (xhr)
        {
          // console.log("ajaxSetup");
          xhr.setRequestHeader("Accept", 'application/x.aham.v2+json');
          xhr.setRequestHeader("Authorization", "Bearer " + authToken);
          xhr.setRequestHeader('X-Requested-With','XMLHttpRequest');
          xhr.setRequestHeader('Content-Type','application/json');
        }
    });

});

router.beforeEach((to, from, next) => {
  store.dispatch("clearHeading");
  store.dispatch('clearBreadcrumb');

  next();
});

router.afterEach((to, from) => {
});


const app = new Vue({
	el: '#app',
	store,
	router,
	components: {
		Layout
	},
	mounted(){
		$(function(){
			console.log("Jquery loaded in vue");
		})
	}
});