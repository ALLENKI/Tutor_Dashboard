<template>

  <div>

      <tutor-payment :tutor="tutor" :hubs="hubs" v-if="tutor && hubs.length"> </tutor-payment>

  </div>

</template>

<script>

import TutorPayment from "../../common/tutor-payments/View";

export default {

    components: {
        TutorPayment,
    },
    data() {
        return {
            tutor: null,
            hubs: [],
        }
    },
    mounted() {
        this.getTutor();
        this.getHubs();
    },
    methods: {
        getTutor() {

            axios
                .get('common/tutors/'+this.$route.params.tutor)
                .then(response => {
                    this.tutor = response.data;
                })
                .catch(error => {
                    console.log(error)
                });

        },
        getHubs() {

             axios
                .get('common/hubs')
                .then(response => {
                    this.hubs = response.data;
                })
                .catch(error => {
                    console.log(error);
                });

        },
        getPaymentPreferences() {

        },
        createNewPaymentPreferences() {

            axios
                .post('/tutor_payments/createOrUpdate/payment')
                .then(response => {
                    console.log(response);
                })
                .catch(error => {
                    console.log(error);
                })

        },

    }

}
</script>

<style>

</style>
