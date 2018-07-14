<template>

    <div>

      <div class="row">
        
        <div class="col-md-6">

          <div class="m-portlet m-portlet--mobile" v-if="subcategory">

            <div class="m-portlet__head">

              <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                  <h3 class="m-portlet__head-text">
                    Sub Category - {{ subcategory.name }}
                  </h3>
                </div>
              </div>

              <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                  <li class="m-portlet__nav-item">
                    <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" data-toggle="modal" data-target="#edit_subcategory_modal">
                      Edit
                    </button>

                  </li>
                </ul>
              </div>

            </div>

            <div class="m-portlet__body">
              <ul class="list-unstyled">
                <li> <strong>Subject: </strong><router-link :to="{ name: 'view-category', params: {category: subcategory.category_id} }"  class="m-portlet__nav-link">
                              {{ subcategory.category_name }}
                            </router-link> / <router-link :to="{ name: 'view-subject', params: {subject: subcategory.subject_id} }"  class="m-portlet__nav-link">
                              {{ subcategory.subject_name }}
                            </router-link> </li>
                <li> <strong>Name:</strong> {{ subcategory.name }} </li>
                <li> <strong>Description:</strong> <p v-html="subcategory.description"></p> </li>
              </ul>
            </div>

          </div>

        </div>

         <div v-if="subcategory" class="col-md-6">

            <div>
  
              <div class="m-portlet m-portlet--collapse" data-portlet="true">

                <div class="m-portlet__head">

                    <div class="m-portlet__head-caption">
                      <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                          Topics in {{ subcategory.name }}
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

                        <ul v-if="show" class="m-portlet__nav">
                            <li class="m-portlet__nav-item">

                              <button type="button" class="btn m-btn--pill m-btn--air         btn-outline-warning m-btn m-btn--custom" @click="addSubCat()">
                                save 
                              </button>

                            </li>
                        </ul>

                    </div>

                </div>

                <div class="m-portlet__body">

                    <!--begin::m-widget4-->
                    <div class="m-widget4">

                      <div v-if="catalog !== void 0" v-for="item in catalog" class="m-widget4__item">
                        
                        <div class="m-widget4__img m-widget4__img--icon">
                          <img src="/dist/media/img/icons/warning.svg" alt="">
                        </div>

                        <div class="m-widget4__info">
                          <span class="m-widget4__text">
                            
                            <router-link :to="{name: 'view-topic', params: {topic: item.id}}">
                              {{ item.name }}
                            </router-link>

                          </span>
                        </div>

                        <div v-if="hub === void 0" class="m-widget4__ext">
                          <a v-if="item.units" @click="removeTopics(item.id)" class="m-widget4__icon" style="cursor: pointer;">
                            <div class="m-demo-icon__preview">
                              <i class="flaticon-close"></i>
                            </div>
                          </a>
                        </div>

                      </div>

                      <div  v-if="catalog">
                        
                          <div v-if="catalog.length >= 9 || page >= 1" class="m-widget13__action m--align-right">
                              <button id="showtoast" type="button" class="btn m-btn--pill m-btn--air btn-outline-accent m-btn m-btn--custom m-btn--outline-2x" @click="previous()" :disabled="previous_disabled">
                                  previous
                              </button>
                              <button id="showtoast" type="button" class="btn m-btn--pill m-btn--air btn-outline-accent m-btn m-btn--custom m-btn--outline-2x" @click="next()" :disabled="next_disabled">
                                  next
                              </button>
                          </div>

                      </div>

                    </div>

                </div>

                <!--end::Widget 9-->
              </div>

            </div>

         </div>
        
      </div>

      <!--begin::Modal-->
        <div class="modal fade" id="edit_subcategory_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">

            <div class="modal-content">

              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Edit Sub Category
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">
                    &times;
                  </span>
                </button>
              </div>
              
              <el-form :model="editSubCategoryForm" :rules="editSubCategoryRules" ref="editSubCategoryForm" label-width="120px">

                  <div class="modal-body">

                  <el-form-item label="Subject" prop="subject">
                    <el-cascader
                      placeholder="Try searching: Guide"
                      :options="subjectOptions"
                      :show-all-levels="false"
                      v-model="editSubCategoryForm.subject"
                      filterable
                    ></el-cascader>
                  </el-form-item>

                  <el-form-item label="Name" prop="name">
                    <el-input v-model="editSubCategoryForm.name"></el-input>
                  </el-form-item>

                  <el-form-item label="description" prop="description">
                    <el-input type="textarea" v-model="editSubCategoryForm.description"></el-input>
                  </el-form-item>


                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('editSubCategoryForm')">
                      Close
                    </button>
                    <button type="button" class="btn btn-primary"  @click="submitForm('editSubCategoryForm')">
                      Save changes
                    </button>
                  </div>

              </el-form>

            </div>

          </div>
        </div>
      <!--end::Modal-->

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
                        <button type="button" class="btn btn-primary"  @click="saveChanges()">
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

import TopicPrerequisites from '../../admin-db/topics/TopicPrerequisites';
import TopicUnits from "../../admin-db/sub-categories/TopicUnits";

export default {
  props:['hub'],
  components: {
    TopicPrerequisites,
    TopicUnits
  },
  data() {
    return {
      next_disabled:false,
      previous_disabled:true,
      show: false,
      addTopicForm: {
        name: "",
        description: "",
        units: []
      },
      topics: [],
      refresh: false,
      subjectOptions: [],
      subcategory: null,
      catalog: null,
      editSubCategoryForm: {
        name: "",
        description: "",
        subject: null
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
      editSubCategoryRules: {
        name: [
          {
            required: true,
            message: "Please input Topic name",
            trigger: "blur"
          }
        ]
      },
      page: 1,
    };
  },
  mounted() {
    console.log("subcat mounted", this.$route.params.subcategory);
    this.getSubjectTree();
    this.getSubCategory();
    this.getCatalogTree();
  },

  methods: {

    removeTopics(id) {

      this.catalog.forEach(item => {

        if (id === item.id) {
            this.catalog.pop(item);
            this.topics.pop(item);
        }

      })

    },

    saveChanges() {

        this.show = true;

        this.catalog.push(this.addTopicForm);
        this.topics.push(this.addTopicForm);

        $('#add_topic_modal').modal('hide');

        this.addTopicForm = {
                              name: "",
                              description: "",
                              units: []
                            };
    },
    
    openAddTopicModal() {
      this.refresh = true;
      $("#add_topic_modal").modal("show");
    },

    resetForm(formName) {
      this.$refs[formName].resetFields();
    },

    getSubjectTree() {
      axios
        .get("/common/subject-tree")
        .then(response => {
          this.subjectOptions = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },

    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "editSubCategoryForm") {
            this.updateSubCategory();
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
        name: this.editSubCategoryForm.name,
        description: this.editSubCategoryForm.description,
        parent_id: this.editSubCategoryForm.subject[1],
        topics: this.topics,
        hub: hub,
      };

      console.log(payload);

      axios
          .put("course_catalog/sub-categories/"+this.$route.params.subcategory, payload)
          .then(response => {
              console.log(response);

              setTimeout(() => {
                 this.$router.go();
              }, 500);

          })
          .catch(error => {
            // console.log(error);
          });
          
      console.log("Add sub cat", _.clone(payload));
    },

    getSubCategory() {
      axios
        .get("course_catalog/sub-categories/" + this.$route.params.subcategory)
        .then(response => {
          this.subcategory = response.data;
          this.editSubCategoryForm.subject = [
            this.subcategory.category_id,
            this.subcategory.subject_id
          ];
          this.editSubCategoryForm.name = this.subcategory.name;
          this.editSubCategoryForm.description = this.subcategory.description;
        })
        .catch(error => {
          console.log(error);
        });
    },

    updateSubCategory() {
      let payload = {
        name: this.editSubCategoryForm.name,
        description: this.editSubCategoryForm.description,
        parent_id: this.editSubCategoryForm.subject[1]
      };

      axios
        .put("course_catalog/sub-categories/" + this.subcategory.id, payload)
        .then(response => {
          this.getSubCategory();
          $("#edit_subcategory_modal").modal("hide");
        })
        .catch(error => {
          console.log(error);
        });
    },

    getCatalogTree() {

      axios
        .get('/course_catalog/'+this.$route.params.subcategory+'/get-a-catalog-path?page='+this.page)
        .then(response => {
            this.catalog = response.data.data;
            if (response.data.next_page_url == null) {
              this.next_disabled = true;
            } else {
              this.next_disabled = false;
            }
            if (response.data.prev_page_url == null) {
              this.previous_disabled = true;
            } else {
              this.previous_disabled = false;
            }
        })
        .catch(error => {
          console.log(error);
        });
    },

    previous() {
      if(this.page > 1){
          this.page--;
          this.getCatalogTree();
      }
    },

    next() {
      this.page++;
      this.getCatalogTree();
    },

  }
};
</script>