<template>

<div>

<div class="row justify-content-center">
<div class="col-md-6">

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
          <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
              <h3 class="m-portlet__head-text">
                Repeat Class
              </h3>
            </div>
          </div>
        </div>

        <div class="m-portlet__body">

        <el-form :model="createClassForm" ref="createClassForm" :rules="createClassRules" class="demo-createClassForm">


        <el-form-item label="Dates" prop="dates">
            <el-date-picker
              v-model="createClassForm.dates"
              type="daterange"
              range-separator="To"
              start-placeholder="Start date"
              end-placeholder="End date">
            </el-date-picker>
        </el-form-item>

        <el-form-item label="Days" prop="checkedDays">
          <el-checkbox :indeterminate="isDayIndeterminate" v-model="dayCheckAll" @change="handleDayCheckAllChange">Check all</el-checkbox>
          <div style="margin: 15px 0;"></div>
          <el-checkbox-group :min="1" v-model="createClassForm.checkedDays" @change="handleCheckedDaysChange">
            <el-checkbox v-for="day in daysList" :label="day" :key="day">{{day}}</el-checkbox>
          </el-checkbox-group>
        </el-form-item>

        <div class="form-group">
            <label>Enrollments</label>

              <div class="row" style="margin-bottom:20px;">
                  <div class="col-md-7">
                      <el-select v-model="enrolling_id" filterable placeholder="Select">
                      <el-option
                      v-for="item in allStudents"
                      :key="item.id"
                      :label="item.text"
                      :value="item.id">
                      </el-option>
                      </el-select>
                  </div>
                  <div class="col-md-2">
                      <button type="button" class="btn btn-primary btn-block" @click="addToEnrollments()">Add</button>
                  </div>
              </div>


            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="student in createClassForm.enrollStudents">
                        <td>{{ student.name }} ({{student.email}})</td>
                        <td><button type="button" class="btn-link btn-sm btn-danger" @click="removeEnrollment(student.id)">X</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="form-group">
            <label>Invitations</label>

              <div class="row" style="margin-bottom:20px;">
                  <div class="col-md-7">
                      <el-select v-model="invite_id" filterable placeholder="Select">
                      <el-option
                      v-for="item in allInvitableStudents"
                      :key="item.id"
                      :label="item.email"
                      :value="item.id">
                      </el-option>
                      </el-select>
                  </div>
                  <div class="col-md-2">
                      <button type="button" class="btn btn-primary btn-block" @click="addToInvitations()">Add</button>
                  </div>
              </div>


            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="student in createClassForm.inviteStudents">
                        <td>{{ student.name }} ({{student.email}})</td>
                        <td><button type="button" class="btn-link btn-sm btn-danger" @click="removeInvitation(student.id)">X</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

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
import store from "../../../store";
import swal from 'sweetalert2';
const dayOptions = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

export default {
    props:['hub'],

    data() {
        return {
          topicTree: [],
          topicList: [],

          dayCheckAll: false,
          isDayIndeterminate: false,
          daysList:dayOptions,

          allStudents: [],
          enrolling_id: null,


          allInvitableStudents: [],
          invite_id: null,

          class_id : null,
          aham_class : null,

          createClassForm: {
            dates: '',
            checkedDays:[],
            enrollStudents:[],
            inviteStudents:[]
          },
          createClassRules: {
            dates: [
                { type: 'array', required: true, message: 'Please select dates', trigger: 'change' }
            ],
            checkedDays: [
             { type: 'array', required: true, message: 'Please select at least one day', trigger: 'change' }
            ]
          }

        }
    },
    mounted() {
        this.class_id = this.$route.params.class;
        this.fetchClass();
        this.getEnrolledStudents();
        this.getInvitedStudents();
    },
    watch: {

    },
    methods: {

        addToInvitations(){
            let student = _.find(this.allInvitableStudents,(s) => {
                return s.id == this.invite_id;
            });

            this.invite_id = null;

            this.createClassForm.inviteStudents.push(student);

            this.createClassForm.inviteStudents = _.uniqBy(this.createClassForm.inviteStudents, 'id');

            _.remove(this.allInvitableStudents,{id:student.id});

        },

        removeInvitation(id){

            let student = _.find(this.createClassForm.inviteStudents,(s) => {
                return s.id == id;
            });

            this.allInvitableStudents.push(student);

            _.remove(this.createClassForm.inviteStudents,{id:student.id});

        },

        addToEnrollments(){
            let student = _.find(this.allStudents,(s) => {
                return s.id == this.enrolling_id;
            });

            this.enrolling_id = null;

            this.createClassForm.enrollStudents.push(student);

            this.createClassForm.enrollStudents = _.uniqBy(this.createClassForm.enrollStudents, 'id');

            _.remove(this.allStudents,{id:student.id});

        },

        removeEnrollment(id){

            let student = _.find(this.createClassForm.enrollStudents,(s) => {
                return s.id == id;
            });

            this.allStudents.push(student);

            _.remove(this.createClassForm.enrollStudents,{id:student.id});

        },

        handleDayCheckAllChange(val) {
          this.createClassForm.checkedDays = val ? dayOptions : [];
          this.isDayIndeterminate = false;
        },

        handleCheckedDaysChange(value) {
          let checkedCount = value.length;
          this.dayCheckAll = checkedCount === this.daysList.length;
          this.isDayIndeterminate = checkedCount > 0 && checkedCount < this.daysList.length;
        },

        fetchClass(){

            axios
              .get("hub/single-class/"+this.class_id)
              .then(response => {
                this.aham_class = response.data;
                store.dispatch("setHeading", "Repeat Class: "+this.aham_class.code+' - '+this.aham_class.topic_name);
              })
              .catch(error => {
                // console.log(error);
              });
        },

        getInvitedStudents() 
        {
          axios
              .get("hub/single-class/"+this.class_id+"/student-invitation")
              .then(response => {
                console.log(response.data);
                this.allInvitableStudents = response.data.allStudents;
                this.createClassForm.inviteStudents = response.data.invitedStudents;
                this.createClassForm.inviteStudents = _.uniqBy(this.createClassForm.inviteStudents, 'id');
              }).catch(response => {

              });
        },


        getEnrolledStudents(){

          axios
            .get("hub/single-class/"+this.class_id+'/enrollments')
            .then(response => {
                this.allStudents = response.data.allStudents;
                this.createClassForm.enrollStudents = response.data.enrolledStudents;
                this.createClassForm.enrollStudents = _.uniqBy(this.createClassForm.enrollStudents, 'id');
            })
            .catch(error => {
              // console.log(error);
            });

        },

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

        createClass(){
            let payload = {
                from_date: moment(this.createClassForm.dates[0]).format("YYYY-MM-DD HH:MM:SS"),
                to_date: moment(this.createClassForm.dates[1]).format("YYYY-MM-DD HH:MM:SS"),
                days: this.createClassForm.checkedDays,
                enrollments: _.map(this.createClassForm.enrollStudents,'id'),
                invitations: _.map(this.createClassForm.inviteStudents,'id')
            };

            console.log('createClass',payload);

                axios
                .post("hub/single-class/"+this.class_id+'/repeat-class',payload)
                .then((response) => {


                  return this.$router.push({
                    name: "view-class",
                    params: { class: this.class_id }
                  });

                })
                .catch((error) => {

                });

        }

    }
};
</script>

<style>
.el-date-editor--daterange.el-input, .el-date-editor--daterange.el-input__inner {
        width: 100%;
    }
</style>
