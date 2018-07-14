<template>
    <create-subject :subject="subject" :hub="hub"></create-subject>
</template>

<script>
import CreateSubject from "../../../common/subject/Create";

export default {

  components: {
    CreateSubject,
  },
  data() {
    return {
      hub: "",
      subject: null,
    }
  },
  mounted() {
      this.hub = this.$route.params.hub;
      this.getSubjectTree();
  },
  methods: {

    getSubjectTree() {

        axios
            .get('common/hub-db/'+this.$route.params.hub+'/category-tree')
            .then((response) => {

              this.subject = response.data;

            })
            .catch((response) => {

            });

    }

  }

}
</script>

<style>

</style>
