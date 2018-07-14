<template>

    <div>

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
                    <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" @click="openAddUnitModal()">
                    Add
                    </button>

                </li>
                </ul>
            </div>
            </div>

            <div class="m-portlet__body" v-if="mutableUnits.length">
            <div class="m-widget3" v-sortable="{onEnd:reorderUnits,handle:'.la-reorder'}">

                <div class="m-widget3__item" v-for="unit in mutableUnits" track-by="$index">
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
                    <p class="m-widget3__text">
                    {{ unit.description }}
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


        <!--begin::Modal-->
        <div class="modal fade" v-bind:id="add_unit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
            

            <el-form :model="addUnitForm" :rules="unitRules" ref="addUnitForm" label-width="120px">

                <div class="modal-body">

                <el-form-item label="Name" prop="name">
                    <el-input v-model="addUnitForm.name"></el-input>
                </el-form-item>

                <el-form-item label="description" prop="description">
                    <el-input type="textarea" v-model="addUnitForm.description"></el-input>
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
        <div class="modal fade" v-bind:id="edit_unit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
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

                <el-form-item label="description" prop="description">
                    <el-input type="textarea" v-model="editUnitForm.description"></el-input>
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
Vue.directive("sortable", {
  inserted: function(el, binding) {
    new Sortable(el, binding.value || {});
  }
});

export default {
  props: ["units", "purpose", "refresh"],
  data() {
    return {
      mutableUnits: [],
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
      }
    };
  },
  computed: {
    add_unit_modal: function() {
      return "add_unit_modal_" + this.purpose;
    },
    edit_unit_modal: function() {
      return "edit_unit_modal_" + this.purpose;
    }
  },
  watch: {
    mutableUnits: function() {
      this.$emit("update:units", this.mutableUnits);
    },
    refresh: function() {
      this.mutableUnits = _.clone(this.units);
    }
  },
  mounted() {
    this.mutableUnits = _.clone(this.units);
  },
  methods: {
    openAddUnitModal() {
      $("#" + this.add_unit_modal).modal("show");
    },

    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          if (formName == "addSubCatForm") {
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
    addUnit() {
      this.mutableUnits.push({
        name: this.addUnitForm.name,
        description: this.addUnitForm.description,
        timestamp: (Date.now() / 1000) | 0
      });
      $("#" + this.add_unit_modal).modal("hide");
      this.resetForm("addUnitForm");
    },
    removeUnit(timestamp) {
      _.remove(this.mutableUnits, { timestamp: timestamp });
      this.$forceUpdate();
    },
    editUnitModal(timestamp) {
      let unit = _.find(this.mutableUnits, o => o.timestamp == timestamp);
      // console.log("Editing", timestamp, unit);
      this.editUnitForm.name = unit.name;
      this.editUnitForm.description = unit.description;
      this.editUnitForm.timestamp = unit.timestamp;
      $("#" + this.edit_unit_modal).modal("show");
    },
    updateUnit() {
      let unit = _.find(
        this.mutableUnits,
        o => o.timestamp == this.editUnitForm.timestamp
      );
      unit = {
        name: this.editUnitForm.name,
        description: this.editUnitForm.description,
        timestamp: this.editUnitForm.timestamp
      };
      let index = _.findIndex(
        this.mutableUnits,
        o => o.timestamp == this.editUnitForm.timestamp
      );
      this.mutableUnits[index] = unit;
      $("#" + this.edit_unit_modal).modal("hide");
      this.$forceUpdate();
    },
    reorderUnits({ oldIndex, newIndex }) {
      const movedItem = this.mutableUnits.splice(oldIndex, 1)[0];
      this.mutableUnits.splice(newIndex, 0, movedItem);
      let cloned_units = Object.assign({}, this.mutableUnits);

      this.mutableUnits = [];

      setTimeout(() => {
        _.forEach(cloned_units, cloned_unit =>
          this.mutableUnits.push(cloned_unit)
        );
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
