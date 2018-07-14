<template>
    
    <Create-Course :hub="hub" :topicTree="topicTree" :courseTree="courseTree"> </Create-Course>

</template>

<script>

import CreateCourse from "../../../common/course/Create"

export default {

    components: {
        CreateCourse
    },
    data() {
        return {
            hub: null,
            topicTree: null,
            courseTree: null,
        }
    },
    mounted() {

        this.hub = this.$route.params.hub; 
        this.getTopicTree();
        this.getCourseTree();

    },
    methods: {

        getTopicTree() {

            axios
                .get('/hub/topics/'+this.$route.params.hub)
                .then((response) => {
                    this.topicTree = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });

        },

        getCourseTree() {

            axios
                .get('common/hub-db/'+this.$route.params.hub+'/course-tree')
                .then((response) => {
                    this.courseTree = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });

        },

    }

}

</script>

<style>

</style>
