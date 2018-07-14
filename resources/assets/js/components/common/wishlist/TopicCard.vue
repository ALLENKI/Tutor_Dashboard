<template>

    <div v-if="topic">
				
        <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">

                        <span class="m-portlet__head-icon m--hide">
                            <i class="flaticon-statistics"></i>
                        </span>

                        <h3 class="m-portlet__head-text">
                            <router-link :to="{ name: 'view-category', params: {category: topic.id} }" v-if="topic.type == 'category'">
                            {{ topic.name }}
                            </router-link>

                            <router-link :to="{ name: 'view-sub-category', params: {subcategory: topic.id} }" v-if="topic.type == 'sub-category'">
                            {{ topic.name }}
                            </router-link>

                            <router-link :to="{ name: 'view-subject', params: {subject: topic.id} }" v-if="topic.type == 'subject'">
                            {{ topic.name }}
                            </router-link>

                            <router-link :to="{ name: 'view-topic', params: {topic: topic.id} }" v-if="topic.type == 'topic'">
                            {{ topic.name }}
                            </router-link>

                            <router-link :to="{ name: 'view-course', params: {course: topic.id} }" v-if="topic.type == 'course'">
                            {{ topic.name }}
                            </router-link>

                        </h3>

                        <h2 class="m-portlet__head-label m-portlet__head-label--success">
                            <span>
                                {{ topic.type }}
                            </span>
                        </h2>

                    </div>
                </div>
                <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">

                            <li class="m-portlet__nav-item">
                                <a href="#"  class="m-portlet__nav-link m-portlet__nav-link--icon remove-link">
                                    <i @click="remove(topic.id,$event)" class="la la-close"></i>
                                </a>
                            </li>
                                                    
                        </ul>
                </div>
            </div>
            <div class="m-portlet__body">          
            </div>
        </div>

    </div>
    
</template>

<script>

export default {

    props: ['topic','remove','subject', 'subcategory', 'category', 'course'],
    data() {
        return {
        }
    },
    methods: {
        
    },
    mounted() {
        this.calculateTopicUrl();
        $(function() {
            $('.remove-link').click(function(event){
                event.preventDefault();
                console.log('prevent');
            });
        });
    }

}

</script>

<style>

</style>
