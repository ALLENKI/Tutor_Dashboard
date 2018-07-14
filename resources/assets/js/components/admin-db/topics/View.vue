<template>

    <View-Topic :dashboard="adminDb"></View-Topic>

</template>

<script>

import ViewTopic from "../../common/Topic/ViewTopic";

export default {
  components: {
    ViewTopic
  },
  data(){
    return {
      adminDb: 'admin-db',
    }
  }

}
</script>

<style>

</style>
