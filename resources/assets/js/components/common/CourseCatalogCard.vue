<template>
    <div>
				
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
                    </h3>

                    <h2 class="m-portlet__head-label" v-bind:class="{ 
                        'm-portlet__head-label--danger': topic.type == 'category',
                        'm-portlet__head-label--info': topic.type == 'topic',
                        'm-portlet__head-label--success': topic.type == 'sub-category',
                        ' m-portlet__head-label--warning': topic.type == 'subject',
                         }">
                        <span>
                            {{ topic.type }}
                        </span>
                    </h2>

                </div>
            </div>
        </div>
        <div class="m-portlet__body">

            <span v-if="topic.approve && topic.type == 'topic'" class="m-badge m-badge--danger m-badge--wide">
                approved
            </span>

            <span v-if="topic.type == 'topic'" class="m-badge m-badge--warning m-badge--wide">
				{{ topic.status }}
            </span>

            
                        <span v-if="topic.type == 'category'" class="m-badge m-badge--metal m-badge--wide" style="float: right;">
                        Subjects- {{ topic.count }}
                        </span>

                        <span v-if="topic.type == 'subject'" class="m-badge m-badge--metal m-badge--wide" style="float: right;">
                        Sub-Category- {{ topic.count }}
                        </span>

                        <span v-if="topic.type == 'sub-category'" class="m-badge m-badge--metal m-badge--wide" style="float: right;">
                        Topic- {{ topic.count }}
                        </span>

                        
                        <span v-if="topic.type == 'topic'" class="m-badge m-badge--metal m-badge--wide" style="float: right;">
                        <b>Units- {{ topic.count }}</b>
                        </span>
              

                    
        </div>
    </div>

    </div>
</template>

<script>
import store from "../../store";
export default {
  props: ["topic"],
  data() {
    return {};
  },
  mounted() {},
  methods: {}
};
</script>

<style scoped>
.m-portlet__head-label {
  text-transform: uppercase;
}
.m-portlet.m-portlet--creative
  .m-portlet__head
  .m-portlet__head-caption
  .m-portlet__head-label {
  top: -4.5rem;
  font-size: 0.9rem;
  padding: 0 0.5rem;
  height: 1.75rem;
}
</style>
