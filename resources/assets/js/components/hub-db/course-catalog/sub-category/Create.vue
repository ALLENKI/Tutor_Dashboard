<template>

  <Create-Sub-Category :subjectTree="subjectTree" :hub="hub"> </Create-Sub-Category>

</template>

<script>

import CreateSubCategory from "../../../common/sub-category/Create";

export default {

  components: {
    CreateSubCategory,
  },
  data() {
    return {
      hub: "",
      subjectTree: null,
    }
  },
  mounted() {
      this.hub = this.$route.params.hub;
      this.getSubject();
  },
  methods: {

      getSubject() {

          axios.get("/common/hub-db/"+ this.$route.params.hub +"/subject-tree")
                .then((response) => {
                    this.subjectTree = response.data;
                })
                .catch((error) => {
                   console.log(error);
                });

      },

  },

}

</script>

<style>

</style>
