<template>

    <viewsubcategory :hub="hub"></viewsubcategory>
  
</template>

<script>
import viewsubcategory from "../../../common/sub-category/View";

export default {

  data() { 
    return {
      hub: null,
    }
  },

  components: {
    viewsubcategory,
  },

  mounted() {
    hub = this.$route.params.hub;
  }

}
</script>

<style>

</style>
