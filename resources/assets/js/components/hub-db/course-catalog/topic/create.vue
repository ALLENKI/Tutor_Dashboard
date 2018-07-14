<template>
    <Create-Topic  :topicTree="topicTree" :hub="hub"></Create-Topic>
</template>

<script>

    import CreateTopic from "../../../common/Topic/CreateTopic";

    export default {

        components: {
            CreateTopic
        },
        data() {
            return {
                hub: null,
                topicTree: null,
            }
        },
        mounted() {
            this.hub = this.$route.params.hub; 
            this.getTopicTree();
        },
        methods: {

            getTopicTree() {

                    axios
                        .get('common/hub-db/'+this.$route.params.hub+'/topic-tree')
                        .then((response) => {
                            this.topicTree = response.data;
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
