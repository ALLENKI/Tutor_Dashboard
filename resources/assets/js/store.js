import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    loading: false,
    user: null,
    location: null,
    error: null,
    breadcrumb: null,
    heading: null,
    shub: null
  },
  mutations: {
    start: state => (state.loading = true),
    stop: state => (state.loading = false),

    SET_HUB(state, hub) {
      // console.log("Set User");
      state.shub = hub;
    },

    CLEAR_HUB(state, hub) {
      // console.log("Set User");
      state.shub = null;
    },

    SET_USER(state, user) {
      // console.log("Set User");
      state.user = user;
    },
    CLEAR_USER(state) {
      state.user = null;
    },
    SET_ERROR(state, error) {
      console.log("Set error", error);
      state.error = error;
    },
    CLEAR_ERROR(state) {
      state.error = null;
    },

    SET_BREADCRUMB(state, breadcrumb) {
      console.log("Set breadcrumb");
      state.breadcrumb = breadcrumb;
    },
    CLEAR_BREADCRUMB(state) {
      console.log("Clear breadcrumb");
      state.breadcrumb = null;
    },

    SET_HEADING(state, heading) {
      state.heading = heading;
    },
    CLEAR_HEADING(state) {
      state.heading = null;
    }
  },
  actions: {
    setHub({ commit }, hub) {
      commit("SET_HUB", hub);
      eventBus.$emit("set-hub");
    },
    clearHub({ commit }) {
      commit("CLEAR_HUB");
      eventBus.$emit("clear-hub");
      console.log("Clear hub");
    },
    setUser({ commit }, user) {
      commit("SET_USER", user);
    },
    clearUser({ commit }) {
      commit("CLEAR_USER");
    },
    setError({ commit }, error) {
      commit("SET_ERROR", error);
    },
    clearError({ commit }) {
      commit("CLEAR_ERROR");
    },

    setBreadcrumb({ commit }, breadcrumb) {
      commit("SET_BREADCRUMB", breadcrumb);
    },

    clearBreadcrumb({ commit }) {
      commit("CLEAR_BREADCRUMB");
    },

    setHeading({ commit }, heading) {
      commit("SET_HEADING", heading);
    },

    clearHeading({ commit }) {
      commit("CLEAR_HEADING");
    }
  }
});
