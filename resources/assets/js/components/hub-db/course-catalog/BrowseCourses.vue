<template>
    <div>
        <browse-catalog-courses v-bind:fetchCourses="fetchCourses"></browse-catalog-courses>
    </div>
</template>

<script>
import store from "../../../store";
import BrowseCatalogCourses from "../../common/BrowseCatalogCourses";

export default {
  props:['hub'],
    
  components: {
    BrowseCatalogCourses
  },
  data() {
    return {

    };
  },
  mounted() {
    store.dispatch("setHeading", "Browse Catalog - Courses");
  },
  methods: {

    fetchCourses(payload) {

      return axios
        .get("hub/"+this.hub.slug+"/course-catalog/browse-courses", {
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
