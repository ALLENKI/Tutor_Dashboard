<template>
    <div>

      <div class="row">

        <div class="col-md-6">

          <div class="m-portlet m-portlet--mobile" v-if="category">

              <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                  <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                      Categories - {{ category.name }}
                    </h3>
                  </div>
                </div>

                <div class="m-portlet__head-tools">
                  <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                      <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" data-toggle="modal" data-target="#edit_category_modal">
                        Edit
                      </button>

                    </li>
                  </ul>
                </div>

              </div>

              <div class="m-portlet__body">
                <ul class="list-unstyled">
                  <li> <strong>Name:</strong> {{ category.name }}</li>
                  <li> <strong>Description:</strong> <p v-html="category.description"></p></li>
                </ul>
              </div>

          </div>

        </div>


         <div v-if="category" class="col-md-6">
            <div>
  
              <div class="m-portlet m-portlet--collapse" data-portlet="true">

                <div class="m-portlet__head">
                  <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                      <h3 class="m-portlet__head-text">
                        Subjects in {{ category.name }}
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
                            
                            <router-link :to="{name: 'view-subject', params: {subject: item.id}}">
                              {{ item.name }}
                            </router-link>

                          </span>
                        </div>

                      </div>

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

                <!--end::Widget 9-->
              </div>

            </div>
        </div>


      </div>


    <!--begin::Modal-->
      <div class="modal fade" id="edit_category_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                Edit Category
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">
                  &times;
                </span>
              </button>
            </div>
            

            <el-form :model="editCategoryForm" :rules="editCategoryRules" ref="editCategoryForm" label-width="120px">

                <div class="modal-body">

                <el-form-item label="Name" prop="name">
                  <el-input v-model="editCategoryForm.name"></el-input>
                </el-form-item>

                <el-form-item label="description" prop="description">
                  <el-input type="textarea" v-model="editCategoryForm.description"></el-input>
                </el-form-item>


                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('editCategoryForm')">
                    Close
                  </button>
                  <button type="button" class="btn btn-primary"  @click="submitForm('editCategoryForm')">
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
      category: null,
      catalog: null,
      editCategoryForm: {
        name: "",
        description: ""
      },
      editCategoryRules: {
        name: [
          {
            required: true,
            message: "Please input Topic name",
            trigger: "blur"
          }
        ]
      }
    };
  },
  mounted() {
    console.log("View mounted", this.$route.params.category);
    this.getCategory();
    this.getCatalogTree();
  },
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "editCategoryForm") {
            this.updateCategory();
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

    getCategory() {
      axios
        .get("course_catalog/categories/" + this.$route.params.category)
        .then(response => {
          this.category = response.data;
          this.editCategoryForm.name = this.category.name;
          this.editCategoryForm.description = this.category.description;
        })
        .catch(error => {
          console.log(error);
        });
    },

    updateCategory() {
      let payload = {
        name: this.editCategoryForm.name,
        description: this.editCategoryForm.description
      };

      axios
        .put("course_catalog/categories/" + this.category.id, payload)
        .then(response => {
          console.log(response.data.category);
          this.category = response.data.category;
          this.editCategoryForm.name = this.category.name;
          this.editCategoryForm.description = this.category.description;

          $("#edit_category_modal").modal("hide");
        })
        .catch(error => {
          console.log(error);
        });
    },

    getCatalogTree() {

      axios
        .get('/course_catalog/'+this.$route.params.category+'/get-a-catalog-path?page='+this.page)
        .then(response => {
            this.catalog = response.data.data;
        })
        .catch(error => {
          console.log(error);
        });

    },

    previous(){

          if(this.page > 1){
              this.page--;
              this.getCatalogTree();
          }

      },

      next(){
          this.page++;
          this.getCatalogTree();
      },
  }
};
</script>