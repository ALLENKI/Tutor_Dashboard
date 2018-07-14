import VueRouter from "vue-router";

let routes = [
  {
    path: "/",
    component: require("../components/ExampleComponent")
  },
  {
    path: "/hubs",
    component: require("../components/admin-db/hubs/Index")
  },
  {
    path: "/hubs/:hub/topics",
    name:'hub-topics',
    component: require("../components/admin-db/hubs/Topics")
  },
  {
    path: "/hubs/:hub/earnings",
    name:'hub-earnings',
    component: require("../components/admin-db/hubs/Earnings")
  },
  // {
  //   path: "/hubs/:id",
  //   component: require("../components/admin-db/hubs/View")
  // },
  {
    path: "/learners",
    component: require("../components/admin-db/learners/Index")
  },
  {
    path: "/learners/:learner/credits",
    name:'learner-credits',
    component: require("../components/admin-db/learners/Credits")
  },
  {
    path: "/learners/:learner/edit",
    name:'learner-edit',
    component: require("../components/admin-db/learners/Edit")
  },
  {
    path: "/course-catalog/browse-topics",
    name: "course-catalog",
    component: require("../components/admin-db/course-catalog/BrowseTopics")
  },
  {
    path: "/course-catalog/browse-courses",
    name: "course-catalog-courses",
    component: require("../components/admin-db/course-catalog/BrowseCourses")
  },
  {
    path: "/course-catalog/topics/create",
    component: require("../components/admin-db/topics/Create")
  },
  {
    path: "/course-catalog/topics/view/:topic",
    name: "view-topic",
    component: require("../components/admin-db/topics/View")
  },
  {
    path: "/course-catalog/topics/edit/:topic",
    name: "edit-topic",
    component: require("../components/admin-db/topics/Edit")
  },
  {
    path: "/course-catalog/courses/create",
    name: "create-course",
    component: require("../components/admin-db/courses/Create")
  },
  {
    path: "/course-catalog/courses/view/:course",
    name: "view-course",
    component: require("../components/admin-db/courses/View")
  },
  {
    path: "/course-catalog/courses/edit/:course",
    name: "edit-course",
    component: require("../components/admin-db/courses/Edit")
  },
  {
    path: "/course-catalog/categories/create",
    component: require("../components/admin-db/categories/Create")
  },
  {
    path: "/course-catalog/categories/view/:category",
    name: "view-category",
    component: require("../components/admin-db/categories/View")
  },
  {
    path: "/course-catalog/subjects/create",
    component: require("../components/admin-db/subjects/Create")
  },
  {
    path: "/course-catalog/subjects/view/:subject",
    name: "view-subject",
    component: require("../components/admin-db/subjects/View")
  },
  {
    path: "/course-catalog/sub-categories/create",
    component: require("../components/admin-db/sub-categories/Create")
  },
  {
    path: "/course-catalog/sub-categories/view/:subcategory",
    name: "view-sub-category",
    component: require("../components/admin-db/sub-categories/View")
  },
  {
    path: "/payments-view",
    name: "payments-view",
    component: require("../components/admin-db/payments/View")
  },
  {
    path: "/payment-edit/:payment/:type",
    name: "payment-edit",
    component: require("../components/admin-db/payments/Edit")
  },
  {
    path: "/payment-invoice/:payment/:type",
    name: "payment-invoice",
    component: require("../components/admin-db/payments/Invoice")
  },
  {
    path: "/tutor-payment-settings/:tutor",
    name: "tutor-payment-settings",
    component: require("../components/admin-db/tutor-payments/View")
  },
  {
    path: "/wishlist/:learner/",
    name: "wishlist",
    component: require("../components/admin-db/wishlist/WishList")
  },
  {
    path: "/tutors",
    name: "tutors",
    component: require("../components/admin-db/tutors/Index")
  },
  {
    path: "/user-permission/:user/",
    name: "user-permission",
    component: require("../components/admin-db/user/TutorPermission")
  },
];

export default new VueRouter({
  routes,
  linkActiveClass: "m-menu__item--active"
});
