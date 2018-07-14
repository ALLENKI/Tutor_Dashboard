<template>
  <div>
      <h3 v-if="repeatClass"> Repeat Class:- {{ repeatClass.name }} </h3>

    <div class="row">
        <div class="col-md-3" v-for="ahamClass in ahamClasses">
                <simple-class-card  :aham_class="ahamClass"></simple-class-card>
        </div>
    </div>

  </div>
</template>

<script>

import SimpleClassCard from './SimpleClassCard';

export default {

    props:['hub'],
    components:{
        SimpleClassCard,
    },
    data() {
        return {
            ahamClasses: null,
            repeatClass: null,
        }
    },
    mounted() {

        this.getAllClasses();
        this.getRepeatClass();

    },
    watch: {

    },
    methods: {

        getAllClasses() {

             axios
                .get('hub/classes/'+this.$route.params.repeatClass+'/repeat-class-details')
                .then(response => {
                    this.repeatClass = response.data;
                })
                .catch(error => {
                    console.log(error.data);
                });

        },

        getRepeatClass() {

             axios
                .get('hub/classes/'+this.$route.params.repeatClass+'/repeat-classes')
                .then(response => {
                    this.ahamClasses = response.data.data;
                })
                .catch(error => {
                    console.log(error.data);
                });
            
        }

    },

}
</script>

<style>

</style>
