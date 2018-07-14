<template>

<div>

<div class="row justify-content-center">
<div class="col-md-6">

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
          <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
              <h3 class="m-portlet__head-text">
                Edit Class
              </h3>
            </div>
          </div>
        </div>

        <div class="m-portlet__body">

        <el-form :model="createClassForm" ref="createClassForm" :rules="createClassRules" class="demo-createClassForm">

        <el-form-item label="Topic" prop="topic">
          <el-select v-model="createClassForm.topic" filterable clearable placeholder="Select">
            <el-option
              v-for="item in topicTree"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>

          <el-form-item label="Name" prop="name">
            <el-input v-model="createClassForm.name"></el-input>
          </el-form-item>

          <el-form-item label="Unit Duration in Hours" prop="unit_duration">
              <el-input-number v-model="createClassForm.unit_duration" :min="1" :max="10"></el-input-number>
          </el-form-item>

          <el-row :gutter="20">
            <el-col :span="8">
              <el-form-item label="Charge Multiply" prop="charge_multiply">
                <el-input v-model="createClassForm.charge_multiply"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="8">
              <el-form-item label="Min. Enrollment" prop="min_enrollment">
                <el-input v-model="createClassForm.min_enrollment"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="8">
              <el-form-item label="Max. Enrollment" prop="max_enrollment">
                <el-input v-model="createClassForm.max_enrollment"></el-input>
              </el-form-item>
            </el-col>
          </el-row>

          <el-row :gutter="20">
            <el-col :span="8">
                <el-checkbox v-model="createClassForm.free_class">
                  Free Class
                </el-checkbox>
            </el-col>
            <el-col :span="8">
                <el-checkbox v-model="createClassForm.pay_tutor">
                  Pay Tutor
                </el-checkbox>
            </el-col>
            <el-col :span="8">
                <el-checkbox v-model="createClassForm.auto_cancel">
                  Auto Cancel
                </el-checkbox>
            </el-col>
          </el-row>


          <hr>


        <el-form-item>
          <el-button type="primary" @click="submitForm('createClassForm')">Update</el-button>
          <el-button @click="resetForm('createClassForm')">Reset</el-button>
        </el-form-item>

        </el-form>


        </div>

    </div>

</div>
</div>
</div>
</template>

<script>
import store from "../../../store";
import swal from 'sweetalert2';

export default {
    props:['hub'],

    data() {
        return {
          topicTree: [],
          topicList: [],

          class_id : null,
          aham_class : null,

          createClassForm: {
            topic_id: null,
            topic_model: null,
            topic: null,
            type: 'single_class',
            course: false,
            name: '',
            unit_duration: 2,
            charge_multiply:1,
            min_enrollment:1,
            max_enrollment:6,
            free_class: false,
            pay_tutor: false,
            auto_cancel: false,
          },
          createClassRules: {
            topic: [
              {
                required: true,
                message: "Please select a topic",
                trigger: "blur"
              }
            ],
            name: [
              {
                required: true,
                message: "Please input name",
                trigger: "blur"
              }
            ]
          }

        }
    },
    mounted() {
        this.getTopicTree();
        this.class_id = this.$route.params.class;

        store.dispatch("setHeading", "Edit Class");
    },
    watch: {
      'createClassForm.topic': function(){
        if(this.createClassForm.topic && this.createClassForm.topic != '')
        {

          let topic_model = _.find(this.topicList,(t) => { 
            return t.id == this.createClassForm.topic; 
          });

          if(this.aham_class.classUnits.data.length != topic_model.units.length)
          {
              this.createClassForm.topic = this.aham_class.topic.id;
              swal('Invalid Topic', 'The number of units do not match', 'error');
              return false;
          }

          this.createClassForm.topic_id = this.createClassForm.topic;
          this.createClassForm.topic_model = _.find(this.topicList,(t) => { 
            return t.id == this.createClassForm.topic_id; 
          });
          this.createClassForm.name = this.createClassForm.topic_model.name;

          if(this.createClassForm.topic_model.status != 'active' || this.createClassForm.topic_model.units.length == 0)
          {
            console.log("Invalid Topic");
            this.createClassForm.topic = null;
            this.createClassForm.topic_id = null;
            this.createClassForm.topic_model = null;
            this.createClassForm.name = '';

            swal('Invalid Topic', 'The selected topic is either inactive or has no units', 'error');
            return false;
          }

          
        }

      }
    },
    methods: {


        fetchClass()
        {
              axios
                .get("hub/single-class/"+this.class_id)
                .then(response => {
                  this.aham_class = response.data;

                  this.createClassForm.topic_id = this.aham_class.topic.id;
                  this.createClassForm.topic_model = this.aham_class.topic;
                  this.createClassForm.topic = this.aham_class.topic.id;
                  this.createClassForm.type = this.aham_class.type;
                  this.createClassForm.name = this.aham_class.name;
                  this.createClassForm.unit_duration = this.aham_class.unit_duration;
                  this.createClassForm.charge_multiply = this.aham_class.charge_multiply;
                  this.createClassForm.min_enrollment = this.aham_class.minimum_enrollment;
                  this.createClassForm.max_enrollment = this.aham_class.maximum_enrollment;
                  this.createClassForm.free_class = this.aham_class.free ? true : false;
                  this.createClassForm.pay_tutor = this.aham_class.no_tutor_comp ? false : true;
                  this.createClassForm.auto_cancel = this.aham_class.auto_cancel ? true : false;

                  store.dispatch("setHeading", "Class Details: "+this.aham_class.code+' - '+this.aham_class.topic_name);
                })
                .catch(error => {
                  // console.log(error);
                });
        },

        submitForm(formName) {
          this.$refs[formName].validate(valid => {
            if (valid) {
              if (formName == "createClassForm") {
                this.updateClass();
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

        updateClass() {

          let payload = {
                name: this.createClassForm.name,
                topic_id: this.createClassForm.topic_id,
                unit_duration: this.createClassForm.unit_duration,
                minimum_enrollment: this.createClassForm.min_enrollment,
                maximum_enrollment: this.createClassForm.max_enrollment,
                pay_tutor: this.createClassForm.pay_tutor,
                free_class: this.createClassForm.free_class,
                auto_cancel: this.createClassForm.auto_cancel,
                charge_multiply: this.createClassForm.charge_multiply,
                type: this.createClassForm.type,
                location_id: this.hub.id,
          };

          console.log("updateClass",payload);
          // return false;


          axios
            .put("hub/classes/"+this.aham_class.id, payload)
            .then(response => {

              return this.$router.push({
                name: "view-class",
                params: { class: response.data.id }
              });

              console.log(response.data);
            })
            .catch(error => {
              // console.log(error);
            });

        },

        getTopicTree() {

          axios
            .get("/hub/topics/"+this.hub.slug)
            .then(response => {
              this.topicTree = response.data;
              this.topicList = response.data;
              this.fetchClass();
            })
            .catch(error => {
              console.log(error);
            });

        },
    }
};
</script>
