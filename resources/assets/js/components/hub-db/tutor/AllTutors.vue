<template>

    <Tutor-Table :hub="hub"></Tutor-Table>
    
</template>

<script>

import TutorTable from "../../common/tutor/AllTutorDataTable";

export default {

    props: ['hub'],

    components: { 
        TutorTable
    }

}
</script>

<style>

</style>
