<template>

  <create-sub-category :dashboard="adminDb"> </create-sub-category>

</template>

<script>

import CreateSubCategory from "../../common/sub-category/Create";

export default {

  components: {
    CreateSubCategory,
  },
  data() {
    return {
      adminDb: 'admin-db'
    }
  }

}

</script>

<style>

</style>
