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

                        <strong v-if="editCourse">
                            Creating Course with: 

                            <span v-if="editCourse.type == 'collection_of_topics'">
                                <strong>  Topics </strong>
                                </span> 
                                <span v-if="editCourse.type == 'collection_of_courses'">
                                <strong> Courses </strong>
                            </span>

                            

                        </strong>

                        </div>

                        <div class="m-alert__actions">		
                        </div>
                        <div class="m-alert__close">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                        </div>

                </div>

            </div>
            
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

                    <div v-if="editCourse" class="m-portlet__body">

                            <el-form :model="addCourseForm" :rules="addTopicRules" ref="addCourseForm" label-width="120px" class="demo-addCourseForm">
                                <el-form-item label="Name" prop="name">
                                <el-input v-model="editCourse.name"></el-input>
                                </el-form-item>

                                <el-form-item label="Description" prop="desc">
                                <el-input type="textarea" v-model="editCourse.description"></el-input>
                                </el-form-item>
                                <el-form-item>
                                <el-button type="primary" @click="updateCourse()">Update</el-button>
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
                            <h3 v-if="editCourse" class="m-portlet__head-text m--font-primary">
                            <span v-if="editCourse.type == 'collection_of_topics'">
                                    <strong>  Topics </strong>
                                    </span> 
                                    <span v-if="editCourse.type == 'collection_of_courses'">
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
                                <div v-if="courseTree" v-for="item in courseTree" class="m-widget4__item">
                        
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
                                            <div class="m-demo-icon__preview">
                                                <i class="flaticon-close"></i>
                                            </div>
                                        </a>
                                    </div>
                                
                                </div>
                            </div>
                        <!--end::Widget 9-->

                        <!--begin::m-widget4-->
                            <div class="m-widget4">
                                <div v-if="catalogueTree" v-for="item in catalogueTree" class="m-widget4__item">
                        
                                    <div class="m-widget4__img m-widget4__img--icon">
                                        <img src="/dist/media/img/icons/question.svg" alt="">
                                    </div>

                                    <div class="m-widget4__info">
                                        <span class="m-widget4__text">
                                        {{ item.name }}
                                        </span>
                                    </div>

                                    <div class="m-widget4__ext">
                                        <a @click="removeAddTopics(item.id)" class="m-widget4__icon" style="cursor: pointer;">
                                            <div class="m-demo-icon__preview">
                                            <i class="flaticon-close"></i>
                                            </div>
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
                            Add Unit
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                            &times;
                            </span>
                        </button>
                        </div>

                        <div class="modal-body">

                        <div v-if="catalogue != null">

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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
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

  data() {
    return {
      editCourse: null,
      courseTree: null,
      catalogue: null,
      selectedItem: [],
      catalogueTree: [],
      type: [],

      url: '',
      addCourseForm: {
        name: "",
        desc: "",
        type: ""
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

    this.getCourse();
    this.getTree();

    setTimeout(() => {
        this.getCatalogue();
    }, 3000 );

  },

  watch: {

    selectedItem: function () {
        this.showItems();
    },

  },

  methods: {

    getCourse() {

        axios
            .get("course_catalog/courses/" + this.$route.params.course + "/show")
            .then(response => {
                 this.editCourse = response.data;
                 this.addCourseForm.type = response.data.type;
            })
            .catch(error => {
            console.log(error);
            });

    },

    getTree() {

        axios
            .get("course_catalog/courses/" + this.$route.params.course + "/tree")
            .then(response => {
                    this.courseTree = response.data;
            })
            .catch(error => {
            console.log(error);
            });

    },

    getCatalogue() {

                if(this.editCourse.type == 'collection_of_topics'){
                        this.type = 'topics';
                } else {
                        this.type = 'courses';
                }
          
        axios.
            get(this.url = 'course_catalog/courses/get-'+this.type)
            .then(response => {
                this.catalogue = response.data;
            })
            .catch(error => {
                console.log(error);
            })

    },

    updateCourse() {

        this.addCourseForm.name = this.editCourse.name;
        this.addCourseForm.desc = this.editCourse.description;


         if(this.editCourse.type == 'collection_of_topics'){

            var data = {
                'course': this.addCourseForm,
                'topics': this.selectedItem,
                };

        } else {
               
               var data = {
                'course': this.addCourseForm,
                'course_require': this.selectedItem,
                };
               
        }

        

         axios.
            put('course_catalog/courses/'+this.$route.params.course,data).
            then(response => {

                return this.$router.push({
                    name: "view-course",
                    params: {course: this.$route.params.course}
                });


            }).
            catch(error => {

            })
    },

    removeTopics(id) {

        let data =  {
                        'topicId' : id,
                    };

        axios.post("course_catalog/courses/" + this.$route.params.course+"/delete-topic",data).
        then(response => {
            this.getTree();
        }).catch(error => {

        });

    },	
    
    removeAddTopics(id) {
        
        this.catalogueTree.forEach(item => {

            if( id === item.id) {
                this.catalogueTree.pop(item);
            }

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
