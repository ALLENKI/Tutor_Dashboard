require("./bootstrap");
import store from "./store";

window.axios.interceptors.request.use(
  function(config) {
    store.commit("start");
    store.dispatch("clearError");
    return config;
  },
  function(error) {
    store.commit("stop");
    return Promise.reject(error);
  }
);

// Add a response interceptor
window.axios.interceptors.response.use(
  function(response) {
    store.commit("stop");
    return response;
  },
  function(error) {
    store.commit("stop");

    if (error.response.status == 401) {
      window.location.reload(true);
    } else if (error.response.status == 422) {
      if (error.response.status == 422) {
        let errors = [];

        _.each(error.response.data.messages, (element, index) => {
          errors.push(element.join(","));
        });

        var error_message = errors.join("<br>");
      }
    } else {
      if (_.size(error.response.data.messages)) {
        var error_message = error.response.data.messages.join(",");
      }

      if (_.size(error.response.data.errors)) {
        let errors = [];

        _.each(error.response.data.errors, (element, index) => {
          errors.push(element.join(","));
        });

        var error_message = errors.join("<br>");
      }
    }

    console.log("Ola! Error!", error.response, error_message);

    console.log("Error", error_message);
    store.dispatch("setError", error_message);

    return Promise.reject(error);
  }
);
