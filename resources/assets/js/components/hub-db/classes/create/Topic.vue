<template>
    <create :topicTree="topicTree" :topicList="topicList" :hub="hub" :ofClass="type"></create>
</template>

<script>
import create from './Create.vue'
import store from "../../../../store";

export default {
  props:['hub'],
  components:{
    create
  },
  data(){

    return {
      type: 'topic',
      topicTree: [],
      topicList: [],
    }

  },
  mounted() {

    this.getTopicTree();
    store.dispatch("setHeading", "Create Class with Topic");

  },
  methods:{

    getTopicTree() {

          axios
            .get("/hub/topics/"+this.hub.slug)
            .then(response => {
              this.topicTree = response.data;
              this.topicList = response.data;
            })
            .catch(error => {
              console.log(error);
            });
            
          // axios
          //   .get("/common/topic-tree")
          //   .then(response => {
          //     this.topicTree = response.data.tree;
          //     this.topicList = response.data.list;
          //   })
          //   .catch(error => {
          //     console.log(error);
          //   });

    },

    createWithTopic(payload) {

        axios
            .post("hub/classes", payload)
            .then(response => {

                return this.$router.push({
                    name: "schedule-class",
                    params: { class: response.data.id }
                });

                console.log(response.data);
            })
            .catch(error => {
            // console.log(error);
            });

    },

  }
}
</script>

<style>

</style>
