<template>

    <div>

        <h2> Course Name:- {{course.name}} </h2>

        <div class="row">

            <div class="col-md-3" v-if="ahamClasses" v-for="ahamClass in ahamClasses">
               <simple-class-card  :aham_class="ahamClass"></simple-class-card>
            </div>

        </div>

    </div>

</template>

<script>

import SimpleClassCard from "./SimpleClassCard";

export default {

    props: ['hub'],
    data() {
        return {
            ahamClasses: null,
            course: null,
        }
    },
    components:{
        SimpleClassCard,
    },
    mounted() {

        this.getAllClasses();
        this.getCourseClasses();

    },
    methods: {

        getAllClasses() {

            axios
            .get('hub/classes/course/'+this.$route.params.course+'/classes')
            .then(response => {
                this.ahamClasses = response.data.data;
            })
            .catch(error => {
                console.log(error.data);
            });

        },

        getCourseClasses() {

            axios
            .get('hub/classes/course/'+this.$route.params.course+'/class-course')
            .then(response => {
                this.course = response.data;
            })
            .catch(error => {
                console.log(error.data);
            });

        },

    },


}
</script>

<style>

</style>
