<template>

    <Create-Topic  :dashboard="adminDb" ></Create-Topic>

</template>

<script>
import CreateTopic from "../../common/Topic/CreateTopic";

export default {
    components: {
        CreateTopic
    },
    data() {
        return {
            adminDb: 'admin-db',
            topicTree: null,
        }
    },
    mounted() {
    },
    methods: {
    }
}

</script>

<style>

</style>