<template>
    <create-subject :dashboard="adminDb"></create-subject>
</template>

<script>

import CreateSubject from "../../common/subject/Create";

export default {

  components: {
    CreateSubject,
  },
  data() {
    return {
      adminDb: 'admin-db',
    }
  }

}
</script>

<style>

</style>
