import store from "../store";

export default {
  user: {
    authenticated: true,
    profile: null
  },
  check() {
    axios
      .get("account",{
          headers: {
            Accept : 'application/x.aham.v2+json'
          },
        })

      .then(response => {
        store.dispatch("setUser", response.data);
        this.user.profile = response.data;
      })
      .catch(error => {
        console.log(error);
      });
  }
}