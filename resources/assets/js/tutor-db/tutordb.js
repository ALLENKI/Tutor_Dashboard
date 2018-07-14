import common from "../common";
require("../bootstrap");
window.Vue = require("vue");
import Layout from "./Layout.vue";

import router from "./routes";
import store from "../store";


import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";
import locale from "element-ui/lib/locale/lang/en";


Vue.use(ElementUI, {locale});

router.beforeEach((to, from, next) => {
  next();
});

router.afterEach((to, from) => {
});

window.axios.defaults.headers.common = {
	"X-Requested-with": "XMLHttpRequest",
	Accept: "application/x.aham.v1+json",
	Authorization: "Bearer " + authToken,
	"ContentType": "application/json"
};

const app = new Vue({
  el: '#app',
  store,
  router,

	components: {
		Layout
	},
	mounted() {
		$(function() {
			console.log("Jquery loaded in vue");
		});
	}
});
