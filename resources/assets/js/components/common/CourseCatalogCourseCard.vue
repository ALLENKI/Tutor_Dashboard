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
                        <router-link :to="{ name: 'view-course', params: {course: course.id} }">
                        {{ course.name }}
                        </router-link>
                    </h3>
                    <h2 class="m-portlet__head-label" v-bind:class="{ 
                        'm-portlet__head-label--danger': course.type == 'collection_of_topics',
                        'm-portlet__head-label--info': course.type == 'collection_of_courses',
                         }">
                        <span>
                            {{ course.type }}
                        </span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">

        </div>
    </div>

    </div>
</template>

<script>
import store from "../../store";
export default {
  props: ["course"],
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
