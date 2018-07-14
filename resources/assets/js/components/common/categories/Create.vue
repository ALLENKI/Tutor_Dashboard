<template>

    <div>

    <div class="row">
    <div class="col-md-6">

    <div class="m-portlet m-portlet--mobile">

    <div class="m-portlet__head">
      <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
          <h3 class="m-portlet__head-text">
            Category Details
          </h3>
        </div>
      </div>
    </div>

    <div class="m-portlet__body">

    <el-form :model="addCategoryForm" :rules="addCategoryRules" ref="addCategoryForm" label-width="120px" class="demo-addCategoryForm">
      <el-form-item label="Name" prop="name">
        <el-input v-model="addCategoryForm.name"></el-input>
      </el-form-item>

      <el-form-item label="Description" prop="desc">
        <el-input type="textarea" v-model="addCategoryForm.desc"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="submitForm('addCategoryForm')">Create</el-button>
        <el-button @click="resetForm('addCategoryForm')">Reset</el-button>
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

  props:['dashboard','hub'],
  data() {
    return {
      addCategoryForm: {
        name: "",
        desc: ""
      },
      addCategoryRules: {
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
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "addCategoryForm") {
            this.addCategory();
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
    addCategory() {

        if(this.hub) {
            var hub = this.hub;
        } else {
            var hub = '';
        }
        
        let payload = {
            name: this.addCategoryForm.name,
            description: this.addCategoryForm.desc,
            hub_id: hub,
            approve: false,
        };

          axios
            .post("course_catalog/categories", payload)
            .then(response => {
              console.log(response.data.category);

              return this.$router.push({
                name: "view-category",
                params: { category: response.data.category.id }
              });
            })
            .catch(error => {
              console.log(error);
            });

          console.log(payload);

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
