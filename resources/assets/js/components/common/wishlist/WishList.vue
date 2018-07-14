
<template>

    <div> 

        <div class="col">

            <learner-detail :hub="hub" :learnerId="learnerId"></learner-detail>

        </div>

        <div class="col">
             <Add :add="add" :topics="topics"></Add>
        </div>

        <div class="row"  v-if="selectedTopics.length">

            <div class="col-md-4" v-for="item in selectedTopics">
                
                <topic-card :topic="item" :remove="remove"></topic-card>

            </div>
            
        </div>

    </div>

</template>

<script>

import TopicCard from './TopicCard';
import Add from './Add';
import LearnerDetail from '../LearnerDetail';

    export default {

        props:['hub','topics','selectedTopics','course','type','add','remove'],

        components: {
            TopicCard,
            Add,
            LearnerDetail
        },

        data() {
            return {
                learnerId: null,
                
            }
        },
        mounted() {
            this.learnerId = this.$route.params.learner;
        },
        methods: {
            

            buildSelectedTopics() {

                this.selectedTopics = [];
                
                this.topics.forEach( item => {

                    this.selectedTopicIds.forEach(item1 => {

                        if (item1 === item.id) {
                            this.selectedTopics.push(item);
                        }

                    });                   

                })

            },

            onClickChild (payload) {
                console.log(payload);
                this.selectedTopics = payload;
            },

        },

        

    }

</script>

<style>

</style>
