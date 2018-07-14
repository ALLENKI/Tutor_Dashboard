<template>
    <div>

        <div class="row">
            <div class="col-md-3" v-for="hub in hubs">
                <single-hub-card :hub="hub"></single-hub-card>
            </div>
         </div>


    </div>
</template>
<script>
import store from "../../../store";
import SingleHubCard from "./SingleHubCard";


export default {

  components: {
    SingleHubCard
  },

  data() {
    return {
      hubs: []
    };
  },

  mounted() {
    store.dispatch("setHeading", "Hubs");

    axios.get('/hub/available-locations')
      .then((response) => {
            this.hubs = response.data.locations;
      })
      .catch((error) => {
        console.log(error);
      });

  }

}

</script>