import store from "../store";

export default {
  user: {
    authenticated: true,
    profile: null
  },
  index(payload) {
      return axios
        .get('common/tutors', {
          params: payload
        })
        .then((response) => {
          return response;
        })
        .catch((error) => {
          console.log(error);
        });
  },
  find(id) {
      return axios
        .get('common/tutors/'+id)
        .then((response) => {
          return response;
        })
        .catch((error) => {
          console.log(error);
        });
  }
};
