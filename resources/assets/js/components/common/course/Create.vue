<template>

    <div>

      <div class="row">
         <div class="col-8">

              <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="m-alert__icon">
                      <i class="flaticon-exclamation-1"></i>
                      <span></span>
                    </div>
                    <div class="m-alert__text">

                      <strong>
                        Creating Course with:

                          <span v-if="Topic">
                          <strong>  Topics </strong>
                          </span> 
                          <span v-if="course">
                            <strong> Courses </strong>
                          </span>

                      </strong>

                    </div>
                    <div class="m-alert__actions">
                          <button @click="choose" type="button" class="btn btn-brand btn-sm m-btn m-btn--pill m-btn--wide">
                              change
                          </button>
                    </div>
                    <div class="m-alert__close">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                    </div>
              </div>

            </div>
      </div>

          <div class="row">

            <div class="col-md-6">

                <div class="m-portlet m-portlet--mobile">

                        <div class="m-portlet__head">
                          <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                              <h3 class="m-portlet__head-text">
                                Course Details
                              </h3>
                            </div>
                          </div>
                        </div>

                      <div class="m-portlet__body">

                              <el-form :model="addCourseForm" :rules="addTopicRules" ref="addCourseForm" label-width="120px" class="demo-addCourseForm">
                                <el-form-item label="Name" prop="name">
                                  <el-input v-model="addCourseForm.name"></el-input>
                                </el-form-item>

                                <el-form-item label="Description" prop="desc">
                                  <el-input type="textarea" v-model="addCourseForm.desc"></el-input>
                                </el-form-item>
                                <el-form-item>
                                  <el-button type="primary" @click="createCourse()">Create</el-button>
                                  <el-button @click="resetForm('addCourseForm')">Reset</el-button>
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
                              <span v-if="Topic">
                              <strong>  Topics </strong>
                              </span> 
                              <span v-if="course">
                              <strong> Courses </strong>
                              </span>
                          </h3>
                        </div>
                      </div>
                      <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                          <li class="m-portlet__nav-item">
                            <button id="m_notify_btn"  type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" data-toggle="modal" data-target="#add_unit_modal">
                              Add
                            </button>

                          </li>
                        </ul>
                      </div>
                    </div>

                    <div class="m-portlet__body">
                        <!--begin::m-widget4-->
                          <div class="m-widget4">
                            <div v-for="item in catalogueTree" class="m-widget4__item">
                      
                              <div class="m-widget4__img m-widget4__img--icon">
                                <img src="/dist/media/img/icons/warning.svg" alt="">
                              </div>

                              <div class="m-widget4__info">
                                <span class="m-widget4__text">
                                  {{ item.name }}
                                </span>
                              </div>

                              <div class="m-widget4__ext">
                                <a @click="removeTopics(item.id)" class="m-widget4__icon" style="cursor: pointer;">
                                  <a v-on:click.prevent href="#" class="btn btn-outline-danger m-btn m-btn--icon m-btn--icon-only m-btn--outline-2x m-btn--pill m-btn--air">
                                                                <i class="flaticon-cancel"></i>
                                                    </a>
                                </a>
                              </div>
                              
                            </div>
                          </div>
                        <!--end::Widget 9-->
                    </div>
                    
                </div>

            </div>
              
          </div>
        
        <!--begin::Modal-->
          <div class="modal fade" id="add_unit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              
              <div class="modal-content">

                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                      <span v-if="Topic">
                              <strong> Add Topics </strong>
                              </span> 
                              <span v-if="course">
                              <strong> Add Courses </strong>
                      </span>
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                      &times;
                    </span>
                  </button>
                </div>

                <div class="modal-body">

                  <div class="col-md-12" v-if="catalogue != null">

                    <el-select v-model="selectedItem" multiple filterable placeholder="Select">
                        <el-option
                          v-for="item in catalogue"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id">
                        </el-option>
                    </el-select>

                  </div>
                  
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('addUnitForm')">
                    Close
                  </button>
                </div>

              </div>
              
            </div>
          </div>
        <!--end::Modal-->

          <!--begin::Modal-->
          <div class="modal fade" id="queryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                    Choose Course Type
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">
                        &times;
                      </span>
                    </button>
                  </div>

                  <div class="modal-body">

                    <div class="col">

                      <div class="">

                        <div class="m-checkbox-list">

                          <label class="m-checkbox m-checkbox--air">
                            <input type="checkbox" v-model="Topic">
                              Topics
                            <span></span>
                          </label>

                          <label class="m-checkbox m-checkbox--air">
                            <input type="checkbox" v-model="course">
                              Courses
                            <span></span>
                          </label>

                        </div>
                        
                      </div>

                    </div>

                  </div>

                  <div class="modal-footer">
                      <button @click="getCatalogue" type="button" class="btn btn-secondary" data-dismiss="modal">
                          Close
                        </button>
                  </div>

              </div>
            </div>
          </div>
        <!--end::Modal-->

    </div>

</template>

<script>
import Sortable from "sortablejs";
import swal from 'sweetalert2';

Vue.directive("sortable", {
  inserted: function(el, binding) {
    new Sortable(el, binding.value || {});
  }
});

export default {
  props: ['topicTree','courseTree','hub'],
  data() {
    return {
      Topic: false,
      payload: null,
      course: false,
      catalogue: null,
      selectedItem: [],
      catalogueTree: [],
      url: '',
      addCourseForm: {
        name: "",
        desc: "",
        type: "",
      },
      addTopicRules: {
        name: [
          {
            required: true,
            message: "Please input Topic name",
            trigger: "blur"
          }
        ]
      },
      addUnitForm: {
        name: "",
        desc: ""
      },
      unitRules: {
        name: [
          {
            required: true,
            message: "Please input Topic name",
            trigger: "blur"
          }
        ]
      },
      editUnitForm: {
        name: "",
        desc: "",
        timestamp: null
      }
    };
  },
  mounted() {

    this.choose();

    if(this.Topic === true || this.course === true) {
        this.getCatalogue();
    }

  },
  watch: {

    Topic: function () {

        if(true == this.course) {
            this.course = false; 
        }

        this.url = '';
        this.url = 'course_catalog/courses/get-topics';

        this.catalogue = this.topicTree;
        this.addCourseForm.type = "collection_of_topics";
    },

    course: function () {

        this.url = '';
        this.url = 'course_catalog/courses/get-courses';

        this.catalogue = this.courseTree;
        this.addCourseForm.type = "collection_of_courses";
    },

    selectedItem: function () {
        this.showItems();
    },
  },
  methods: {

    choose(){
        $(function(){
          $('#queryModal').modal('show');
        });
    },

    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "addCourseForm") {
            this.addTopic();
          }
          if (formName == "addUnitForm") {
            this.addUnit();
          }
          if (formName == "editUnitForm") {
            this.updateUnit();
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

    getCatalogue() 
    {

      if (this.Topic == true && this.course == true) {

        swal({
                type: 'error',
                title: 'try again',
                text: 'Select Course or Topic',
                footer: '',
        });

        this.$router.go(this.$router.currentRoute)
      }

      if (this.topicTree == null && this.courseTree == null) {

          axios
                .get(this.url)
                .then(response => {
                  this.catalogue = response.data;
                }).
                catch(error => {
                  console.log(error);
                })

      } 

    },

    buildPayload() 
    { 

          if (this.Topic) {

              this.payload = {
                          'course': this.addCourseForm,
                          'topics': this.selectedItem,
                          'hub_id': this.hub,
                         };

          } else {
            
              this.payload = {
                          'course': this.addCourseForm,
                          'course_require': this.selectedItem,
                          'hub_id': this.hub,
                         };

          }

    },

    createCourse() 
    {

          if (this.hub === void 0) {

              this.hub = '';

              this.buildPayload();

          } else {

              this.buildPayload();
            
          }


         axios
            .post('course_catalog/courses/',this.payload)
            .then(response => {

              $(function() {
                  $('#add_unit_modal').modal('hide');
              });

              return this.$router.push({
                    name: "view-course",
                    params: {course: response.data.id}
              });

            }).
            catch(error => {

            })

    },

    showItems() {

      let vm = this;

      vm.catalogueTree = [];

      vm.selectedItem.forEach(selectedId => {
          vm.catalogue.forEach(item => {

            if( selectedId === item.id ) {
                vm.catalogueTree.push({'name': item.name,'id': item.id});
            }

          });
      });

    },

    removeTopics(id) {

      this.catalogueTree.forEach(item => {

        if( id === item.id){
            this.catalogueTree.pop(item);
        }

      })

      this.selectedItem.forEach(item => {

        if( id === item){
            this.selectedItem.pop(item);
        }

      })

    },

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

.el-select {
    width: 400px;
    display: block;
}

</style>
