<template>

  <create-course :dashboard="adminDb"></create-course>
  
</template>

<script>

import CreateCourse from "../../common/course/Create";

export default {

  components: {
    CreateCourse,
  },
  data() {
    return {
      adminDb: 'admin-db',
    }
  },

}

</script>

<style>

</style>
