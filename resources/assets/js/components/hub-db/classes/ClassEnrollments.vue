<template>
    <div>
        <div class="m-portlet m-portlet--mobile">
          <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
              <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                  Enrollments
                </h3>
              </div>
            </div>

          </div>

          <div class="m-portlet__body">

              <div class="row" v-if="aham_class != null && aham_class.status != 'cancelled' && aham_class.status != 'completed'" >
                  <div class="col-md-7">
                      <el-select v-model="enrolling_id" filterable placeholder="Select">
                              <el-option
                              v-for="item in eligibleStudents"
                              :key="item.id"
                              :label="item.text"
                              :value="item.id">
                              </el-option>
                      </el-select>
                  </div>
                  <div class="col-md-2">
                      <button class="btn btn-primary btn-block" @click="checkEligibility()">Enroll</button>
                  </div>
                  <div class="col-md-3">
                      <button class="btn btn-success btn-block" @click="inviteModal()">Invite Students</button>
                  </div>
              </div>

              <hr>

              <div class="m-portlet m-portlet--mobile">
                  
                      <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                          <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                              Class Enrollments
                            </h3>
                          </div>
                        </div>
                      </div>

                      <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                      <th>Name</th>
                                      <th>Email</th>
                                      <th>Status</th>
                                      <th>Feed Back</th>
                                      <th>Remarks</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-for="enrollment in enrolledStudents">
                                      <td>{{ enrollment.name }}</td>
                                      <td>{{ enrollment.email }}</td>
                                      <td>
                                        {{ enrollment.status }}
                                        <button type="button" class="btn btn-danger btn-sm" @click="cancelClassEnrollment(enrollment.class_id, enrollment.id)" v-if="enrollment.cancelled != 1">Cancel</button>
                                      </td>
                                      <td v-if="enrollment.feedbacks !=null">{{ enrollment.feedbacks }}</td>
                                      <td v-else>--</td>
                                      <td v-if="enrollment.remarks != null">{{ enrollment.remarks }}</td>
                                      <td v-else>--</td>
                                      
                                  </tr>
                              </tbody>
                          </table>
                      </div>

              </div>

              <div class="m-portlet m-portlet--mobile" v-for="(classUnit,index) in classUnits">
                  
                      <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                          <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                              Unit # {{ index+1 }} : {{ classUnit.name }}
                            </h3>
                          </div>
                        </div>
                      </div>

                      <div class="table-responsive">
                          <table class="table table-bordered table-hover">
                              <thead>
                                  <tr>
                                      <th>Name</th>
                                      <th>Email</th>
                                      <th>Status</th>
                                      <th>Attendance</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-for="enrollment in classUnit.enrollments">
                                      <td>{{ enrollment.learner.user.name }}</td>
                                      <td>{{ enrollment.learner.user.email }}</td>
                                      <td>
                                        {{ enrollment.status }}
                                        <button v-if="enrollment.status != 'cancelled' && enrollment.status != 'cancelled_by_student' && enrollment.status != 'cancelled_by_admin' " type="button" class="btn btn-danger btn-sm" @click="cancelUnitEnrollment(enrollment.id, enrollment.student_id)">Cancel</button>
                                      </td>
                                      <td>{{ enrollment.attendance }}</td>
                                  </tr>
                              </tbody>
                          </table>

                      </div>

              </div>

              <invitedStudents :hub="hub" :aham_class="aham_class"></invitedStudents>

          </div>

        </div>


          <div id="basic_modal" class="modal fade animated" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Enroller</h4>
                    </div>
                    <div class="modal-body">
                        <p>{{ reason }}</p>


                        <div class="form-group" v-if="can_force">
                          <label>
                            <input type="checkbox" v-model="force_enroll">
                            You can force enroll
                          </label>
                        </div>

                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close
                      </button>
                      <button type="button" class="btn btn-success" v-if="this.can_enroll" @click="enroll">
                      Enroll
                      </button>
                      <button type="button" class="btn btn-warning" v-if="this.force_enroll" @click="enroll">
                      Force Enroll
                      </button>
                  </div>
              </div>
          </div>
        </div>
    </div>
</template>

<script>
import store from "../../../store";
import swal from 'sweetalert2';
import invitedStudents from './InvitedStudents';

export default {
  props:['hub','aham_class'],
  components: {
    invitedStudents
  },
  data() {
    return {
        'eligibleStudents': [],
        'enrolledStudents': [],
        'classUnits': [],

        enrolling_id: null,
        can_enroll: false,
        can_force: false,
        force_enroll: false,
        reason: '',
    };
  },
  watch:{
    'aham_class': function(){
      this.getEnrolledStudents();
      // this.inviteStudent();
    }
  },
  mounted() {
    //console.log('ahamClass mounted:',this.aham_class);
    this.getEnrolledStudents();
  },
  methods: {

    inviteModal()
    {
         console.log('invite');
         $('#student_invitations_modal').modal('show');
    },

    cancelClassEnrollment(class_id,student)
    {
      axios
      .post("hub/single-class/"+class_id+'/cancel-class-enrollment/'+student)
      .then((response) => {

        $('#basic_modal').modal('hide');

        this.$emit('refresh');
        this.getEnrolledStudents();

      })
      .catch((error) => {

      });
    },

    cancelUnitEnrollment(unit,student)
    {
      axios
      .post("hub/single-class/"+unit+'/cancel-unit-enrollment/'+student)
      .then((response) => {

        $('#basic_modal').modal('hide');

        this.$emit('refresh');
        this.getEnrolledStudents();

      })
      .catch((error) => {

      });
    },

    getEnrolledStudents(){

      axios
        .get("hub/single-class/"+this.aham_class.id+'/enrollments')
        .then(response => {
            this.classUnits = response.data.classUnits;
            this.eligibleStudents = response.data.eligibleStudents;
            this.enrolledStudents = response.data.enrolledStudents;
            this.enrolling_id = '';
            
        })
        .catch(error => {
          // console.log(error);
        });

    },

    enroll(){

      axios
      .post("hub/single-class/"+this.aham_class.id+'/enroll/'+this.enrolling_id)
      .then((response) => {

        $('#basic_modal').modal('hide');

        this.$emit('refresh');
        this.getEnrolledStudents();

      })
      .catch((error) => {

      });
    },

    checkEligibility() {
     
      axios
        .get("hub/single-class/"+this.aham_class.id+'/check-enrollment-eligibility/'+this.enrolling_id)
        .then(response => {

            $('#basic_modal').modal('show');
            this.can_enroll = response.data.can_enroll;
            this.can_force = response.data.can_force;
            this.reason = response.data.reason;

        })
        .catch(error => {
          // console.log(error);
        });

    },

  }
};
</script>
