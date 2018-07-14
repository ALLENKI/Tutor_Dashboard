<template>

    <Create-Category :hub="hub"> </Create-Category>
  
</template>

<script>

import CreateCategory from "../../../common/categories/Create";

export default {

  components: {
    CreateCategory,
  },
  data() {
    return {
      hub: "",
    }
  },
  mounted() {
      this.hub = this.$route.params.hub;
  }

}

</script>

<style>

</style>
