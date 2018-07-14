<template>

<div>

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="m-portlet m-portlet--mobile">
        
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Create Class
                    </h3>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">

                <el-form :model="createClassForm" ref="createClassForm" :rules="createClassRules" class="demo-createClassForm">

                    <el-form-item v-if="ofClass == 'topic'" label="Topic" prop="topic">
                            <el-cascader
                            placeholder="Choose Topic"
                            :options="topicTree"
                            :show-all-levels="true"
                            v-model="createClassForm.topic"
                            filterable
                            clearable
                            ></el-cascader>
                    </el-form-item>

                    <el-form-item v-if="ofClass == 'course'" label="Course" prop="corse">
                       
                        <div v-if="courseTree" class="modal-body">

                            <el-select v-model="selectedCourseId" clearable filterable placeholder="Select a Course" required>

                                <el-option
                                v-for="item in courseTree"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                                </el-option>

                            </el-select>

                        </div>

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
                        <el-button type="primary" @click="submitForm('createClassForm')">Create</el-button>
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
import store from "../../../../store";
import swal from 'sweetalert2';

export default {
    props:['hub','ofClass','courseTree','topicTree','topicList'],

    data() {

        return {
          type: null,
          selectedCourseId: null,

          createClassForm: {
            topic_id: null,
            topic_model: null,
            topic: null,
            type: '',
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
    },
    watch: {

        'selectedCourseId': function() {

            let value = _.find(this.courseTree,(t) => { 
                if(t.id == this.selectedCourseId){
                    return t;
                }
            });

            this.createClassForm.name = value.name;

        },

        '$route': function() {

            if(this.$route.name == 'create-topic-class') {
                this.type = "topic";
            } else {
                this.type = "course";
            }

        }
			
    },
    methods: {

        submitForm(formName) {
          this.$refs[formName].validate(valid => {
            if (valid) {
              if (formName == "createClassForm") {
                this.createClass();
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

        createClass() {

            if(this.ofClass == "topic") {

                let payload = {
                        topic_id: this.createClassForm.topic_id,
                        name: this.createClassForm.name,
                        unit_duration: this.createClassForm.unit_duration,
                        minimum_enrollment: this.createClassForm.min_enrollment,
                        maximum_enrollment: this.createClassForm.max_enrollment,
                        pay_tutor: this.createClassForm.pay_tutor,
                        free_class: this.createClassForm.free_class,
                        auto_cancel: this.createClassForm.auto_cancel,
                        charge_multiply: this.createClassForm.charge_multiply,
                        type: 'single_class',
                        location_id: this.hub.id,
                };

                this.$parent.$options.methods.createWithTopic(payload);
            } else {

                let payload = {
                    course_id: this.selectedCourseId,
                    name: this.createClassForm.name,
                    unit_duration: this.createClassForm.unit_duration,
                    minimum_enrollment: this.createClassForm.min_enrollment,
                    maximum_enrollment: this.createClassForm.max_enrollment,
                    pay_tutor: this.createClassForm.pay_tutor,
                    free_class: this.createClassForm.free_class,
                    auto_cancel: this.createClassForm.auto_cancel,
                    charge_multiply: this.createClassForm.charge_multiply,
                    type: 'group_class',
                    location_id: this.hub.id,
                };

                setTimeout(() => {

                    this.$parent.$options.methods.createWithCourse(payload)
                        .then(response => {

                            console.log('response:--',response);

                            this.$router.push({
                                name: "class-courses-details",
                                params: {course: response}
                            });
                            
                    });
                    
                }, 3000);

            }

        },

    }
};
</script>
