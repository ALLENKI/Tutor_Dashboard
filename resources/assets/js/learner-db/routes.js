import VueRouter from "vue-router";

let routes = [
  {
    path: "/",
    component: require("../components/learner-db/Dashboard")
  }
];

export default new VueRouter({
  routes,
  linkActiveClass: "m-menu__item--active"
});
