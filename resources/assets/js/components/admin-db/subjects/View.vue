<template>
    <div>

      <div class="row">

          <div class="col-md-6">

            <div class="m-portlet m-portlet--mobile" v-if="subject">

                  <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                      <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                          Subject - {{ subject.name }}
                        </h3>
                      </div>
                    </div>

                    <div class="m-portlet__head-tools">
                      <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                          <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" data-toggle="modal" data-target="#edit_subject_modal">
                            Edit
                          </button>
                        </li>
                      </ul>
                    </div>

                  </div>

                  <div class="m-portlet__body">
                    <ul class="list-unstyled">
                      <li> <strong>Category:</strong><router-link :to="{ name: 'view-category', params: {category: subject.category_id} }"  class="m-portlet__nav-link">
                              {{ subject.category_name }}
                            </router-link></li>
                      <li> <strong>Name:</strong> {{ subject.name }} </li>
                      <li> <strong>Description:</strong> <p v-html="subject.description"></p> </li>
                        <li>
                          <mark> Visibility: </mark>
                            <label class="m-checkbox m-checkbox--state-success">
                                <input disabled type="checkbox" v-model="subject.visibility" :value="subject.visibility"></intput>
                                <span></span>
                            </label>
                        </li>

                    </ul>
                  </div>

            </div>

          </div>

          <div class="col-md-6">
            
            <div v-if="subject">
                <div>
          
                  <div class="m-portlet m-portlet--collapse" data-portlet="true">

                    <div class="m-portlet__head">
                      <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                          Sub-Categories in {{ subject.name }}
                        </h3>
                        </div>
                      </div>
                    </div>

                    <div class="m-portlet__body">
                      <!--begin::m-widget4-->
                      <div class="m-widget4">

                        <div v-if="catalog" v-for="item in catalog" class="m-widget4__item">
                          
                          <div class="m-widget4__img m-widget4__img--icon">
                          <img src="/dist/media/img/icons/warning.svg" alt="">
                          </div>

                          <div class="m-widget4__info">
                            <span class="m-widget4__text">
                              
                              <router-link :to="{name: 'view-sub-category', params: {subcategory: item.id}}">
                              {{ item.name }}
                              </router-link>

                            </span>
                          </div>

                        </div>


                        <div v-if="catalog">
                          <div v-if="catalog.length > 10" class="m-widget13__action m--align-right">
                              <button id="showtoast" type="button" class="btn m-btn--pill m-btn--air         btn-outline-accent m-btn m-btn--custom m-btn--outline-2x" @click="previous()">
                                    previous
                              </button>
                              <button id="showtoast" type="button" class="btn m-btn--pill m-btn--air         btn-outline-accent m-btn m-btn--custom m-btn--outline-2x" @click="next()">
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

      </div>

      <!--begin::Modal-->
          <div class="modal fade" id="edit_subject_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">

                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">
                    Edit Subject
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                      &times;
                    </span>
                  </button>
                </div>
                
                <el-form :model="editSubjectForm" :rules="editSubjectRules" ref="editSubjectForm" label-width="120px">

                    <div class="modal-body">

                        <el-form-item label="Category" prop="category">
                          <el-cascader
                            placeholder="Try searching: Guide"
                            :options="categoryOptions"
                            :show-all-levels="false"
                            v-model="editSubjectForm.category"
                            filterable
                          ></el-cascader>
                        </el-form-item>

                        <el-form-item label="Name" prop="name">
                          <el-input v-model="editSubjectForm.name"></el-input>
                        </el-form-item>

                        <el-form-item label="description" prop="description">
                          <el-input type="textarea" v-model="editSubjectForm.description"></el-input>
                        </el-form-item>

                         <el-form-item label="Topic Visibility">

                          <el-checkbox v-model="visibilityChecked"> Visibility </el-checkbox>
                        
                        </el-form-item>


                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('editSubjectForm')">
                        Close
                      </button>
                      <button type="button" class="btn btn-primary"  @click="submitForm('editSubjectForm')">
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
export default {
  data() {
    return {
      visibilityChecked: true,
      categoryOptions: [],

      subject: null,
      editSubjectForm: {
        name: "",
        description: "",
        category: null
      },
      editSubjectRules: {
        name: [
          {
            required: true,
            message: "Please input Topic name",
            trigger: "blur"
          }
        ]
      },
      catalog: null,
    };
  },
  mounted() {
    console.log("View mounted", this.$route.params.category);
    this.getSubject();
    this.getCategoryTree();
    this.getCatalogTree();
  },
  methods: {
    getCategoryTree() {
      axios
        .get("/common/category-tree")
        .then(response => {
          this.categoryOptions = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "editSubjectForm") {
            this.updateSubject();
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

    getSubject() {
      axios
        .get("course_catalog/subjects/" + this.$route.params.subject)
        .then(response => {
          console.log(response.data);
          this.subject = response.data;
          this.editSubjectForm.category = [this.subject.category_id];
          this.editSubjectForm.name = this.subject.name;
          this.editSubjectForm.description = this.subject.description;
          this.visibilityChecked = this.subject.visibility;
        })
        .catch(error => {
          console.log(error);
        });
    },

    updateSubject() {
      let payload = {
        name: this.editSubjectForm.name,
        description: this.editSubjectForm.description,
        parent_id: this.editSubjectForm.category[0],
        visibility: this.visibilityChecked,
      };

      axios
        .put("course_catalog/subjects/" + this.subject.id, payload)
        .then(response => {
          this.getSubject();
          $("#edit_subject_modal").modal("hide");
          this.getSubject();
        })
        .catch(error => {
          console.log(error);
        });
    },

    getCatalogTree() {

      axios
        .get('/course_catalog/'+this.$route.params.subject+'/get-a-catalog-path')
        .then(response => {
            this.catalog = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });

    },

  }
};
</script>