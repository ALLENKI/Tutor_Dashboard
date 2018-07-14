import VueRouter from "vue-router";

let routes = [
	{
		path: "/",
		component: require("../components/hub-db/LocationChooser")
	},
	{
		path: "/hub/:hub",
		component: require("../components/hub-db/LocationLayout"),
		children: [
			{
				path: "dashboard",
				name: "hub-dashboard",
				component: require("../components/hub-db/Dashboard")
			},
			{
				path: 'location-home',
				name: 'location-home',
				component: require('../components/hub-db/classes/LocationHome'),
			},
			{
				path: "all-classes",
				name: "all-classes",
				component: require("../components/hub-db/classes/Index")
			},
			{
				path: "create-topic-class",
				name: "create-topic-class",
				component: require("../components/hub-db/classes/CreateTopic")
			},
			{
				path: "create-course-class",
				name: "create-course-class",
				component: require("../components/hub-db/classes/create/Course")
			},
			{
				path: "schedule-class/:class",
				name: "schedule-class",
				component: require("../components/hub-db/classes/Schedule")
			},
			{
				path: "view-class/:class",
				name: "view-class",
				component: require("../components/hub-db/classes/View")
			},
			{
				path: "repeat-class/:class",
				name: "repeat-class",
				component: require("../components/hub-db/classes/Repeat")
			},
			{
				path: "edit-class/:class",
				name: "edit-class",
				component: require("../components/hub-db/classes/Edit")
			},
			{
				path: "calendar",
				name: "calendar",
				component: require("../components/hub-db/Calendar")
			},
			{
				path: "teacher-not-invited",
				name: "TeacherNotInvited",
				component: require("../components/hub-db/classes/Index")
			},
			{
				path: "invited-but-not-Awarded",
				name: "InvitedButNotAwarded",
				component: require("../components/hub-db/classes/Index")
			},
			{
				path: "min-enrollment-not-found",
				name: "MinEnrollmentNotFound",
				component: require("../components/hub-db/classes/Index")
			},
			{
				path: "waiting-for-feedback",
				name: "WaitingForFeedback",
				component: require("../components/hub-db/classes/Index")
			},
			{
				path: "all-learners",
				name: "all-learners",
				component: require("../components/hub-db/learner/AllLearners")
			},
			{
				path: "all-tutors",
				name: "all-tutors",
				component: require("../components/hub-db/tutor/AllTutors")
			},
			{
				path: "learner-details/:learner",
				name: "learner-details",
				component: require("../components/hub-db/learner/LearnerDetail")
			},
			{
				path: "learner-details1/:learner",
				name: "learner-details1",
				component: require("../components/hub-db/learner/LearnerDetail1")
			},
			{
				path: "tutor-details/:tutor",
				name: "tutor-details",
				component: require("../components/hub-db/tutor/TutorDetail")
			},
			{
			    path: "course-catalog/browse-topics",
			    name: "course-catalog-topics",
			    component: require("../components/hub-db/course-catalog/BrowseTopics")
		  	},
			{
			    path: "course-catalog/browse-courses",
			    name: "course-catalog-browse-courses",
			    component: require("../components/hub-db/course-catalog/BrowseCourses")
		  	},
			{
			    path: "course-catalog/view-courses",
			    name: "course-catalog-view-courses",
			    component: require("../components/hub-db/course-catalog/ViewCatalog")
		  	},
			{
			    path: "course-catalog/view-courses:course",
			    name: "course-catalog-edit-courses",
			    component: require("../components/hub-db/course-catalog/EditCatalog")
		  	},
			{
			    path: "class/repeat-class/:repeatClass",
			    name: "repeat-class-details",
			    component: require("../components/hub-db/classes/RepeatClass")
			},
			{
			    path: "course-catalog/view-class-courses/:course",
			    name: "class-courses-details",
			    component: require("../components/hub-db/classes/ClassCourseDetail")
		  	},
			{
			    path: "course-catalog/view-topic/:topic",
			    name: "view-topic",
			    component: require("../components/hub-db/course-catalog/topic/view")
			},
			{
			    path: "course-catalog/create-topic",
			    name: "create-topic",
			    component: require("../components/hub-db/course-catalog/topic/create")
		  	},
			{
			    path: "course-catalog/view-course/:course",
			    name: "view-course",
			    component: require("../components/hub-db/course-catalog/course/View")
			},
			{
			    path: "course-catalog/create-course",
			    name: "create-course",
			    component: require("../components/hub-db/course-catalog/course/Create")
			},
			{
				path: "course-catalog/categories/create",
			    name: "create-categories",
				component: require("../components/hub-db/course-catalog/categories/Create")
			},
			{
				path: "course-catalog/categories/view/:category",
				name: "view-category",
				component: require("../components/hub-db/course-catalog/categories/View")
			},
			{
				path: "course-catalog/sub-category/create",
			    name: "create-subCategory",
				component: require("../components/hub-db/course-catalog/sub-category/Create")
			},
			{
				path: "course-catalog/sub-category/view/:subcategory",
				name: "view-sub-category",
				component: require("../components/hub-db/course-catalog/sub-category/View")
			},
			{
				path: "course-catalog/subject/create",
			    name: "create-subject",
				component: require("../components/hub-db/course-catalog/subject/Create")
			},
			{
				path: "course-catalog/subject/view/:subject",
				name: "view-subject",
				component: require("../components/hub-db/course-catalog/subject/View")
			},
			{
				path: "wishlist/:learner/view/",
				name: "wishlist",
				component: require("../components/hub-db/wishlist/WishList")
			},
		]
	}
];

export default new VueRouter({
	routes,
    linkActiveClass: "m-menu__item--active"
});
