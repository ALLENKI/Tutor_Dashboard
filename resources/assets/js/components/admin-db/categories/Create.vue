<template>

    <Create-Category :dashboard="adminDb"> </Create-Category>
  
</template>

<script>

import CreateCategory from "../../common/categories/Create";

export default {

  components: {
    CreateCategory,
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
