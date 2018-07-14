<template>

    <div>

          <div class="row">

            <div class="col-md-6">

              <div class="m-portlet m-portlet--mobile">

                <div class="m-portlet__head">
                  <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                      <h3 class="m-portlet__head-text">
                        Sub Category Details
                      </h3>
                    </div>
                  </div>
                </div>

                <div class="m-portlet__body">

                  <el-form :model="addSubCatForm" :rules="addSubCatRules" ref="addSubCatForm" label-width="120px" class="demo-addSubCatForm">

                    <el-form-item label="Subject" prop="name">
                      <el-cascader
                        placeholder="Try searching: Guide"
                        :options="subjectOptions"
                        :show-all-levels="false"
                        v-model="addSubCatForm.subject"
                        filterable
                      ></el-cascader>
                    </el-form-item>

                    <el-form-item label="Name" prop="name">
                      <el-input v-model="addSubCatForm.name"></el-input>
                    </el-form-item>

                    <el-form-item label="Description" prop="description">
                      <el-input type="textarea" v-model="addSubCatForm.description"></el-input>
                    </el-form-item>


                    <el-form-item>
                      <el-button type="primary" @click="submitForm('addSubCatForm')">Create</el-button>
                      <el-button @click="resetForm('addSubCatForm')">Reset</el-button>
                    </el-form-item>
                  </el-form>

                </div>

              </div>

            </div>

            <div class="col-md-6">

              <div class="m-portlet m-portlet--mobile">

                  <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                      <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                          <i class="flaticon-list"></i>
                        </span>
                        <h3 class="m-portlet__head-text m--font-primary">
                          Topics
                        </h3>
                      </div>
                    </div>
                    <div class="m-portlet__head-tools">
                      <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                          <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" @click="openAddTopicModal()">
                            Add
                          </button>

                        </li>
                      </ul>
                    </div>
                  </div>

                  <div class="m-portlet__body" v-if="topics.length">
                    <div class="m-widget3" v-sortable="{onEnd:reorderTopics,handle:'.la-reorder'}">

                      <div class="m-widget3__item" v-for="topic in topics" track-by="$index">
                        <div class="m-widget3__header">
                          
                          <div class="m-widget3__info" style="padding-left:0">
                            <i class="la la-reorder" style="cursor:pointer"></i>
                            <span class="m-widget3__username">
                              {{ topic.name }}
                            </span>
                          </div>
                          <span class="m-widget3__status m--font-info">

                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                              <a href="#" class="m-dropdown__toggle">
                                <i class="la la-ellipsis-h"></i>
                              </a>

                              <div class="m-dropdown__wrapper">
                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                <div class="m-dropdown__inner">
                                  <div class="m-dropdown__body">
                                    <div class="m-dropdown__content">
                                      <ul class="m-nav">
                                        <li class="m-nav__item">
                                          <a @click="editTopicModal(topic.timestamp)" class="m-nav__link">
                                            <i class="m-nav__link-icon flaticon-edit"></i>
                                            <span class="m-nav__link-text">
                                              Edit
                                            </span>
                                          </a>
                                        </li>
                                        <li class="m-nav__item">
                                          <a @click="removeTopic(topic.timestamp)" class="m-nav__link">
                                            <i class="m-nav__link-icon flaticon-delete"></i>
                                            <span class="m-nav__link-text">
                                              Remove
                                            </span>
                                          </a>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                </div>
                              </div>

                            </div>

                          </span>
                        </div>
                        <div class="m-widget3__body">
                          <p class="m-widget3__text">
                            {{ topic.description }}
                          </p>
                        </div>
                      </div>

                                    
                    </div>
                  </div>
                  
                  <div class="m-portlet__body" v-else>

                    <p class="text-center">
                      No Topics 
                    </p>
                    
                  </div>

              </div>

            </div>

          </div>
      
      <!--begin::Modal-->
          <div class="modal fade" id="add_topic_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    Add Topic
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                      &times;
                    </span>
                  </button>
                </div>
                

                <el-form :model="addTopicForm" :rules="topicRules" ref="addTopicForm" label-width="120px">

                    <div class="modal-body">

                    <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="400">

                    <el-form-item label="Name" prop="name">
                      <el-input v-model="addTopicForm.name"></el-input>
                    </el-form-item>

                    <el-form-item label="Description" prop="description">
                      <el-input type="textarea" v-model="addTopicForm.description"></el-input>
                    </el-form-item>

                    <topic-units v-bind:units.sync="addTopicForm.units" purpose="add" :refresh="refresh"></topic-units>

                    </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('addTopicForm')">
                        Close
                      </button>
                      <button type="button" class="btn btn-primary"  @click="submitForm('addTopicForm')">
                        Save changes
                      </button>
                    </div>

                </el-form>


              </div>
            </div>
          </div>
      <!--end::Modal-->

      <!--begin::Modal-->
          <div class="modal fade" id="edit_topic_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              
              <div class="modal-content">

                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    Edit Topic
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                      &times;
                    </span>
                  </button>
                </div>
                
                <el-form :model="editTopicForm" :rules="topicRules" ref="editTopicForm" label-width="120px">

                    <div class="modal-body">

                        <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="400">

                            <el-form-item label="Name" prop="name">
                              <el-input v-model="editTopicForm.name"></el-input>
                            </el-form-item>

                            <el-form-item label="Description" prop="description">
                              <el-input type="textarea" v-model="editTopicForm.description"></el-input>
                            </el-form-item>

                            <topic-units v-bind:units.sync="editTopicForm.units" purpose="edit" :refresh="refresh"></topic-units>

                        </div>

                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('editTopicForm')">
                        Close
                      </button>
                      <button type="button" class="btn btn-primary"  @click="submitForm('editTopicForm')">
                        Save changes
                      </button>
                    </div>

                </el-form>

              </div>
              
            </div>
          </div>
      <!--end::Modal-->

    </div>

</template>

<script>

import Sortable from "sortablejs";
import TopicUnits from "../../admin-db/sub-categories/TopicUnits";

Vue.directive("sortable", {
  inserted: function(el, binding) {
    new Sortable(el, binding.value || {});
  }
});

export default {
  props:['subjectTree','hub'],
  data() {
    return {
      subjectOptions: [],
      refresh: false,
      addSubCatForm: {
        name: "",
        description: "",
        subject: null
      },
      addSubCatRules: {
        name: [
          {
            required: true,
            message: "Please input name",
            trigger: "blur"
          }
        ],
        subject: [
          {
            required: true,
            message: "Please select subject",
            trigger: "blur"
          }
        ]
      },

      addTopicForm: {
        name: "",
        description: "",
        units: []
      },
      topicRules: {
        name: [
          {
            required: true,
            message: "Please input Topic name",
            trigger: "blur"
          }
        ]
      },
      editTopicForm: {
        name: "",
        description: "",
        units: [],
        timestamp: null
      },

      topics: []
    };
  },
  components: {
    TopicUnits
  },
  mounted() {

    setTimeout(() => {
      this.getSubjectTree();
    }, 500);
        
  },
  methods: {
    getSubjectTree() {

      if (this.subjectTree == null) {

         axios
            .get("/common/subject-tree")
            .then(response => {
              this.subjectOptions = response.data;
            })
            .catch(error => {
              console.log(error);
            });

      } else {

         this.subjectOptions = this.subjectTree;
      }
      
    },
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "addSubCatForm") {
            this.addSubCat();
          }
          if (formName == "addTopicForm") {
            this.addTopic();
          }
          if (formName == "editTopicForm") {
            this.updateTopic();
          }
        } else {
          console.log("error submit!!");
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    addSubCat() {

      var hub;

      if (this.hub === void 0) {
          var hub = '';
      }

      let payload = {
        name: this.addSubCatForm.name,
        description: this.addSubCatForm.description,
        parent_id: this.addSubCatForm.subject[1],
        topics: this.topics,
        hub: hub,
      };

      console.log(payload);

      axios
        .post("course_catalog/sub-categories", payload)
        .then(response => {
            console.log(response);

            return this.$router.push({
              name: "view-sub-category",
              params: { subcategory: response.data.subCategory.id }
            });

        })
        .catch(error => {
          // console.log(error);
        });

      console.log("Add sub cat", _.clone(payload));
    },
    openAddTopicModal() {
      this.refresh = true;
      $("#add_topic_modal").modal("show");
    },
    addTopic() {
      this.topics.push({
        name: this.addTopicForm.name,
        description: this.addTopicForm.description,
        units: this.addTopicForm.units,
        timestamp: (Date.now() / 1000) | 0
      });
      this.refresh = false;
      $("#add_topic_modal").modal("hide");
      this.addTopicForm.units = [];
      this.resetForm("addTopicForm");
    },
    removeTopic(timestamp) {
      _.remove(this.topics, { timestamp: timestamp });
      this.$forceUpdate();
    },
    editTopicModal(timestamp) {
      let topic = _.find(this.topics, o => o.timestamp == timestamp);
      this.editTopicForm.name = topic.name;
      this.editTopicForm.description = topic.description;
      this.editTopicForm.units = topic.units;
      this.editTopicForm.timestamp = topic.timestamp;
      $("#edit_topic_modal").modal("show");
      this.refresh = true;
    },
    updateTopic() {
      let topic = _.find(
        this.topics,
        o => o.timestamp == this.editTopicForm.timestamp
      );
      topic = {
        name: this.editTopicForm.name,
        description: this.editTopicForm.description,
        units: this.editTopicForm.units,
        timestamp: this.editTopicForm.timestamp
      };
      let index = _.findIndex(
        this.topics,
        o => o.timestamp == this.editTopicForm.timestamp
      );
      this.topics[index] = topic;
      $("#edit_topic_modal").modal("hide");
      this.refresh = false;
      this.$forceUpdate();
      this.editTopicForm.units = [];
    },
    reorderTopics({ oldIndex, newIndex }) {
      const movedItem = this.topics.splice(oldIndex, 1)[0];
      this.topics.splice(newIndex, 0, movedItem);
      let cloned_topics = Object.assign({}, this.topics);

      this.topics = [];

      setTimeout(() => {
        _.forEach(cloned_topics, cloned_unit => this.topics.push(cloned_unit));
      }, 10);
    }
  }
};
</script>

<style scoped>
.line {
  text-align: center;
}

.el-checkbox-group .el-checkbox {
  float: left;
  width: 160px;
  padding-right: 20px;
  margin: 0;
  padding: 0;
}
</style>
