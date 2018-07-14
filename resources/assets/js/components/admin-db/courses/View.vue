<template>
  
  <View-Course :dashboard="adminDb"></View-Course>

</template>

<script>

import ViewCourse from "../../common/course/View";

export default {

  components: {
    ViewCourse,
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
