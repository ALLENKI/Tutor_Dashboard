<template>
    <div>

        <div class="row">

            <div class="col-md-6">

              <div class="m-portlet m-portlet--mobile" v-if="topic">

                    <div class="m-portlet__head">
                      <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                          <h3 class="m-portlet__head-text">
                            Topic - {{ topic.name }}
                          </h3>
                        </div>
                      </div>

                      <div v-if="dashboard" class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                          <li class="m-portlet__nav-item">
                            
                            <router-link :to="{ name: 'edit-topic', params: {topic: topic.id} }"  class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
                              Edit
                            </router-link>

                          </li>
                        </ul>
                      </div>

                    </div>

                    <div class="m-portlet__body">

                      <ul class="list-unstyled">
                        <li> <strong>Sub Category:</strong> <router-link :to="{ name: 'view-category', params: {category: topic.category_id} }"  class="m-portlet__nav-link">
                              {{ topic.category_name }}
                            </router-link> / <router-link :to="{ name: 'view-subject', params: {subject: topic.subject_id} }"  class="m-portlet__nav-link">
                              {{ topic.subject_name }}
                            </router-link> / <router-link :to="{ name: 'view-sub-category', params: {subcategory: topic.sub_category_id} }"  class="m-portlet__nav-link">
                              {{ topic.sub_category_name }}
                            </router-link></li>
                        <li> <strong>Name:</strong> {{ topic.name }}</li>
                        <li> <strong>Description:</strong>
                        
                        <read-more more-str="View" :text="topic.description" link="#" less-str="Hide" :max-chars="0" v-if="topic.description"></read-more>

                        </li>
                        <li> <strong>Status:   </strong> <b> {{ topic.status }} </b> </li>
                         <mark> approve :   </mark>
                        <label class="m-checkbox m-checkbox--state-success">
                         
                          <input  disabled type="checkbox" v-model="topic.approve" :value="topic.approve">
                                  
                                    <span></span>
                        </label>

                        <li>
                          <mark> Visibility: </mark>
                            <label class="m-checkbox m-checkbox--state-success">
                                <input disabled type="checkbox" v-model="topic.visibility" :value="topic.visibility"></intput>
                                <span></span>
                            </label>
                        </li>

                      </ul>

                    </div>

              </div>

              <div class="m-portlet m-portlet--mobile" v-if="topic">

                <div class="m-portlet__head">
                  <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                      <span class="m-portlet__head-icon">
                        <i class="flaticon-list"></i>
                      </span>
                      <h3 class="m-portlet__head-text m--font-primary">
                        Units
                      </h3>
                    </div>
                  </div>

                </div>

                <div class="m-portlet__body" v-if="topic.units.length">
                  <div class="m-widget3__item" v-for="unit in topic.units" track-by="$index">
                    <div class="m-widget3__header">

                      <div class="m-widget3__info" style="padding-left:0">

                        
                        <span class="m-widget3__username">
                          <h3>{{ unit.name }}</h3>
                        </span>
                        <br>
                        <strong>
                          Description:
                        </strong>
                        <read-more more-str="View" :text="unit.description" link="#" less-str="Hide" :max-chars="0" v-if="unit.description"></read-more>
                      </div>

                    </div>
                  </div>
                </div>

              </div>

            </div>

            <div class="col-md-6">
              <topic-files v-if="dashboard" topic-files></topic-files>
              <topic-prerequisites v-if="dashboard" topic-prerequisites></topic-prerequisites>
            </div>

        </div>

    </div>
</template>

<script>
import TopicFiles from '../../admin-db/topics/TopicFiles';
import TopicPrerequisites from '../../admin-db/topics/TopicPrerequisites';

export default {
  props: ['dashboard'],
  components: {
    TopicFiles,
    TopicPrerequisites
  },
  data() {
    return {
      topic: null
    };
  },
  mounted() {
    console.log("View mounted", this.$route.params.topic);
    this.getTopic();
  },
  methods: {
    
    getTopic() {
      axios
        .get("course_catalog/topics/" + this.$route.params.topic)
        .then(response => {
          this.topic = response.data;
          console.log(this.topic);
        })
        .catch(error => {
          console.log(error);
        });
    }
    
  }
};
</script>
<style>
#readmore {
  margin-bottom: 10px;
  display: block;
}
</style>