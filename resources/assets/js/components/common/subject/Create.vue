<template>

    <div>

        <div class="row">

          <div class="col-md-6">

            <div class="m-portlet m-portlet--mobile">

              <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                  <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                      Subject Details
                    </h3>
                  </div>
                </div>
              </div>

              <div class="m-portlet__body">

                <el-form :model="addSubjectForm" :rules="addSubjectRules" ref="addSubjectForm" label-width="120px" class="demo-addSubjectForm">

                  <el-form-item label="Category" prop="category">
                    <el-cascader
                      placeholder="Try searching: Guide"
                      :options="categoryOptions"
                      :show-all-levels="false"
                      v-model="addSubjectForm.category"
                      filterable
                    ></el-cascader>
                  </el-form-item>

                  <el-form-item label="Name" prop="name">
                    <el-input v-model="addSubjectForm.name"></el-input>
                  </el-form-item>

                  <el-form-item label="Description" prop="description">
                    <el-input type="textarea" v-model="addSubjectForm.description"></el-input>
                  </el-form-item>
                  <el-form-item>
                    <el-button type="primary" @click="submitForm('addSubjectForm')">Create</el-button>
                    <el-button @click="resetForm('addSubjectForm')">Reset</el-button>
                  </el-form-item>

                </el-form>

              </div>

            </div>

          </div>

        </div>

    </div>

</template>

<script>
export default {
  props: ['dashboard','hub','subject'],
  data() {
    return {
      categoryOptions: [],

      addSubjectForm: {
        name: "",
        description: "",
        category: null
      },
      addSubjectRules: {
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

    setTimeout(() => {
          this.getCategoryTree();
    }, 1000);

  },
  methods: {
    getCategoryTree() {

      if (this.subject === void 0) {
        
          axios
              .get("/common/category-tree")
              .then(response => {
                this.categoryOptions = response.data;
              })
              .catch(error => {
                console.log(error);
              });

      } else {

        this.categoryOptions = this.subject;

      }

    },
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "addSubjectForm") {
            this.addSubject();
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
    addSubject() {

      var hub = '';

      if (this.subject !== void 0) {
        var hub = this.hub;
      }

      let payload = {
        name: this.addSubjectForm.name,
        description: this.addSubjectForm.description,
        parent_id: this.addSubjectForm.category[0],
        hub_id: hub,
      };

      // console.log(payload);

      axios
        .post("course_catalog/subjects", payload)
        .then(response => {
          return this.$router.push({
            name: "view-subject",
            params: { subject: response.data.subject.id }
          });
        })
        .catch(error => {
          // console.log(error);
        });

      // console.log(payload);
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
