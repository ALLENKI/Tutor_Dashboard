<template>

    <div v-if="learner" class="col-xl-12">

        <!--begin:: Widgets/Support Tickets -->
            <div class="m-portlet m-portlet--full-height ">
                <div class="m-portlet__body">
                    <div class="m-widget3">
                        <div class="m-widget3__item">
                            <div class="m-widget3__header">
                                <div class="m-widget3__user-img">
                                    <img class="m-widget3__img" :src="learner.avatar" alt="">
                                </div>
                                <div class="m-widget3__info">
                                    <span v-if="hub" class="m-widget3__username">

                                        <router-link  :to="{name: 'learner-details', params: {learner: learnerId}}">
                                            {{learner.name}}
                                        </router-link>
                                        
                                        
                                    </span>
                                    <span v-else class="m-widget3__username">
                                            {{learner.name}}                                            
                                    </span>
                                    <br>
                                    <span class="m-widget3__time">
                                        <mark>Email: {{learner.email}}</mark> 
                                    </span>
                                    <span class="m-widget3__time">
                                        
                                            <span class="m-badge m-badge--danger m-badge--dot"></span>
                                        
                                    <mark>  Mobile: {{learner.mobile}} </mark>
                                    </span>
                                    
                                </div>
                            </div>
                            <div class="m-widget3__body">
                                <p class="m-widget3__text">
                                    
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!--end:: Widgets/Support Tickets -->

    </div>
  
</template>

<script>

export default {
    props:['learnerId','hub'],
    data() {
        return {
           learner: null,
        }
    },
    mounted() {

        setTimeout(() => {

                this.getLearner();
            
        }, 1000);

    },
    methods: {

        getLearner() {
            axios
                .get('/hub/user-profile/'+this.learnerId)
                .then(response => {
                    this.learner = response.data;
                })
                .catch(error => {

                });
        }

    }

}
</script>

<style>

</style>
