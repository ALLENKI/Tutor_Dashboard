<template>

    <All-Tutors :dashboard="dashboard"> </All-Tutors>
       
</template>

<script>

import AllTutors from '../../common/tutor/AllTutorDataTable';

export default {
    components: {
        AllTutors
    },
    data() {
        return {
            dashboard: 'hub-db',
        }
    }
}
</script>

<style>

</style>
