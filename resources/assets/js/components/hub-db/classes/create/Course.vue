<template>

    <create :courseTree="courseTree"  :selectedCourseId="selectedCourseId"  :hub="hub" :ofClass="type"></create>
  
</template>

<script>
import create from './Create.vue'
import store from "../../../../store";

export default {
  props:['hub'],
  components:{
    create
  },

  mounted() {
      this.getCourseTree();
      store.dispatch("setHeading", "Create Class with Course");
  },

  data() {
    return {
        selectedCourseId: null,
        courseTree: null,
        type: 'course',
        id: null,
    }
  },

  methods: {

    getCourseTree() {

        setTimeout(() => {

          axios
            .get("/common/course-tree")
            .then(response => {
              this.courseTree = response.data
            })
            .catch(error => {
              console.log(error);
            });

            
        }, 1000);

    },

    createWithCourse(payload) {

        return axios
              .post("hub/classes", payload)
              .then(response => {

                console.log(' course class created ',response);

                if(response.data != null) {
                  return response.data.id;
                }

                console.log('create class',response.data);
              })
              .catch(error => {
                console.log(error);
              });

    },

  }

}
</script>

<style>

</style>
