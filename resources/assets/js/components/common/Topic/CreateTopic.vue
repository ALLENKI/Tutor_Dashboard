<template>

    <div>

      <div class="row">
        
        <div class="col-md-6">

          <div class="m-portlet m-portlet--mobile">

              <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                  <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                      Topic Details
                    </h3>
                  </div>
                </div>
              </div>

              <div class="m-portlet__body">

                <el-form :model="addTopicForm" :rules="addTopicRules" ref="addTopicForm" label-width="120px" class="demo-addTopicForm">

                  <el-form-item label="Sub Category" prop="subcategory">
                    <el-cascader
                      placeholder="Try searching: Guide"
                      :options="subCategoryTree"
                      :show-all-levels="true"
                      v-model="addTopicForm.subcategory"
                      filterable
                    ></el-cascader>
                  </el-form-item>

                  <el-form-item label="Name" prop="name">
                    <el-input v-model="addTopicForm.name"></el-input>
                  </el-form-item>

                  <el-form-item label="Description" prop="description">
                    <el-input type="textarea" v-model="addTopicForm.description"></el-input>
                  </el-form-item>

                  <el-form-item label="Topic Status">

                    <el-select v-model="addTopicForm.status" placeholder="please select Topic Status">
                      <el-option label="Active" value="Active"></el-option>
                      <el-option label="In Progress" value="in_progress"></el-option>
                      <el-option label="In Future" value="in_future"></el-option>
                      <el-option label="Obsolete" value="obsolete"></el-option>
                    </el-select>

                  </el-form-item>

                  <el-form-item>
                    <el-button type="primary" @click="submitForm('addTopicForm')">Create</el-button>
                    <el-button @click="resetForm('addTopicForm')">Reset</el-button>
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
                    Units
                  </h3>
                </div>
              </div>
              <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                  <li class="m-portlet__nav-item">
                    <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" data-toggle="modal" data-target="#add_unit_modal">
                      Add
                    </button>

                  </li>
                </ul>
              </div>
            </div>

            <div class="m-portlet__body" v-if="units.length">
              <div class="m-widget3" v-sortable="{onEnd:reorderUnits,handle:'.la-reorder'}">

                <div class="m-widget3__item" v-for="unit in units" track-by="$index">
                  <div class="m-widget3__header">
                    
                    <div class="m-widget3__info" style="padding-left:0">
                      <i class="la la-reorder" style="cursor:pointer"></i>
                      <span class="m-widget3__username">
                        {{ unit.name }}
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
                                    <a @click="editUnitModal(unit.timestamp)" class="m-nav__link">
                                      <i class="m-nav__link-icon flaticon-edit"></i>
                                      <span class="m-nav__link-text">
                                        Edit
                                      </span>
                                    </a>
                                  </li>
                                  <li class="m-nav__item">
                                    <a @click="removeUnit(unit.timestamp)" class="m-nav__link">
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
                    <p class="m-widget3__text" v-html="unit.description">
                    </p>
                  </div>
                </div>

                              
              </div>
            </div>
            
            <div class="m-portlet__body" v-else>

              <p class="text-center">
                No Units 
              </p>
              
            </div>


          </div>

        </div>


      </div>
      
      <!--begin::Modal-->
        <div class="modal fade" id="add_unit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
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
              

              <el-form :model="addUnitForm" :rules="unitRules" ref="addUnitForm" label-width="120px">

                  <div class="modal-body">

                  <el-form-item label="Name" prop="name">
                    <el-input v-model="addUnitForm.name"></el-input>
                  </el-form-item>

                  <el-form-item label="Description" prop="description">

                    <vue-editor id="c_editor" class="body_editor" v-model="addUnitForm.description" :editorToolbar="customToolbar"></vue-editor>

                  </el-form-item>


                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('addUnitForm')">
                      Close
                    </button>
                    <button type="button" class="btn btn-primary"  @click="submitForm('addUnitForm')">
                      Save changes
                    </button>
                  </div>

              </el-form>


            </div>
          </div>
        </div>
      <!--end::Modal-->

      <!--begin::Modal-->
        <div class="modal fade" id="edit_unit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Edit Unit
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">
                    &times;
                  </span>
                </button>
              </div>
              

              <el-form :model="editUnitForm" :rules="unitRules" ref="editUnitForm" label-width="120px">

                  <div class="modal-body">

                  <el-form-item label="Name" prop="name">
                    <el-input v-model="editUnitForm.name"></el-input>
                  </el-form-item>

                  <el-form-item label="Description" prop="description">
                    <vue-editor id="e_editor" class="body_editor" v-model="editUnitForm.description" :editorToolbar="customToolbar"></vue-editor>
                  </el-form-item>


                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('editUnitForm')">
                      Close
                    </button>
                    <button type="button" class="btn btn-primary"  @click="submitForm('editUnitForm')">
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
import { VueEditor } from "vue2-editor";
Vue.directive("sortable", {
  inserted: function(el, binding) {
    new Sortable(el, binding.value || {});
  }
});

export default {
  props: ['dashboard','hub','topicTree'],
  components: {
    VueEditor
  },

  data() {
    return {
      customToolbar: [
        ["bold", "italic", "underline"], // toggled buttons
        [{ list: "ordered" }, { list: "bullet" }],
        [{ script: "sub" }, { script: "super" }], // superscript/subscript
        [{ indent: "-1" }, { indent: "+1" }], // outdent/indent
        [{ color: [] }], // dropdown with defaults from theme
        ["link"],
        [],
        []
      ],

      addTopicForm: {
        name: "",
        description: "",
        subcategory: null,
        status: 'Active'
      },
      addTopicRules: {
        name: [
          {
            required: true,
            message: "Please input Topic name",
            trigger: "blur"
          }
        ],
        subcategory: [
          {
            required: true,
            message: "Please select a subcategory",
            trigger: "blur"
          }
        ]
      },

      addUnitForm: {
        name: "",
        description: ""
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
        description: "",
        timestamp: null
      },

      units: [],
      cloned_units: [],
      subCategoryTree: []
    };
  },
  mounted() {

    setTimeout(() => {
         this.getSubCategoryTree();
    }, 1000);

  },
  methods: {
    getSubCategoryTree() {

      if (this.topicTree === void 0) {

          axios
              .get("/common/sub-category-tree")
              .then(response => {
                this.subCategoryTree = response.data;
              })
              .catch(error => {
                console.log(error);
              });
        
      } else {
          this.subCategoryTree = this.topicTree;
      }

      
    },
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "addTopicForm") {
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
    addTopic() {

      if (this.dashboard == 'admin-db') { 
          var approve = true;
      } else {
          var approve = false;
      }

      if (this.hub !== void 0) {
        var hub = this.hub;
      } else {
        var hub = '';
      }

      let payload = {
        name: this.addTopicForm.name,
        status: this.addTopicForm.status,
        description: this.addTopicForm.description,
        parent_id: this.addTopicForm.subcategory[2],
        units: this.units,
        approve: approve,
        hub: hub,
      };

      // console.log(payload);

      axios
        .post("course_catalog/topics", payload)
        .then(response => {
          console.log(response.data.topic);

          return this.$router.push({
            name: "view-topic",
            params: { topic: response.data.topic.id }
          });
        })
        .catch(error => {
          console.log(error);
        });

      console.log(payload);
    },
    addUnit() {
      this.units.push({
        name: this.addUnitForm.name,
        description: this.addUnitForm.description,
        timestamp: (Date.now() / 1000) | 0
      });
      $("#add_unit_modal").modal("hide");
      this.resetForm("addUnitForm");
    },
    removeUnit(timestamp) {
      _.remove(this.units, { timestamp: timestamp });
      this.$forceUpdate();
    },
    editUnitModal(timestamp) {
      let unit = _.find(this.units, o => o.timestamp == timestamp);
      this.editUnitForm.name = unit.name;
      this.editUnitForm.description = unit.description;
      this.editUnitForm.timestamp = unit.timestamp;
      $("#edit_unit_modal").modal("show");
    },
    updateUnit() {
      let unit = _.find(
        this.units,
        o => o.timestamp == this.editUnitForm.timestamp
      );
      unit = {
        name: this.editUnitForm.name,
        description: this.editUnitForm.description,
        timestamp: this.editUnitForm.timestamp
      };
      let index = _.findIndex(
        this.units,
        o => o.timestamp == this.editUnitForm.timestamp
      );
      this.units[index] = unit;
      $("#edit_unit_modal").modal("hide");
      this.$forceUpdate();
    },
    reorderUnits({ oldIndex, newIndex }) {
      const movedItem = this.units.splice(oldIndex, 1)[0];
      this.units.splice(newIndex, 0, movedItem);
      let cloned_units = Object.assign({}, this.units);

      this.units = [];

      setTimeout(() => {
        _.forEach(cloned_units, cloned_unit => this.units.push(cloned_unit));
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

.el-cascader {
  width: 100%;
}
</style>
