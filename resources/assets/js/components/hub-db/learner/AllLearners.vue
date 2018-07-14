<template>
    
    <div>
        <Learner-Table :hub="hub"> </Learner-Table>
    </div>
    
</template>

<script>

    import LearnerTable from "../../common/learner/AllLearnersDataTable";

    export default {

        props: ['hub'],

        components: {
            LearnerTable
        }

    }

</script>

<style>

</style>
