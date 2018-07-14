<template>
    <div>
        <browse-catalog-topics v-bind:fetchTopics="fetchTopics"></browse-catalog-topics>
    </div>
</template>

<script>
import store from "../../../store";
import BrowseCatalogTopics from "../../common/BrowseCatalogTopics";

export default {
  components: {
    BrowseCatalogTopics
  },
  data() {
    return {

    };
  },
  mounted() {
    store.dispatch("setHeading", "Browse Catalog");
  },
  methods: {

    fetchTopics(payload){

      return axios
        .get("course_catalog/browse-topics", {
          params: payload
        })
        .then(response => {
          return response;
        })
        .catch(error => {
          console.log(error);
        });

    }
  }
};
</script>

<style scoped>
.el-select,
.el-button {
  width: 100%;
}
</style>
