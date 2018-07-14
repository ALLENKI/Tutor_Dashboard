
<template>

    <div>

        <div class="m-portlet_body">

            <div class="m-portlet m-portlet--mobile">
                
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Invited Students
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in students">
                                <td>{{item.name}}</td>
                                <td>{{item.email}}</td>
                                <td>
                                    
                                    <span class="m-badge m-badge--success m-badge--wide">{{item.status}}</span>

                                    <button v-if="item.status == 'pending'" class="btn m-btn m-btn--gradient-from-accent m-btn--gradient-to-success" @click="enroll(item.id)">
                                    Enroll</button>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            
        </div>

        <!-- begin::Modal-->
        <div class="modal fade" id="student_invitations_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                  Invite Students
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">
                    &times;
                  </span>
                </button>
              </div>
              

              <el-form label-width="120px">

                  <div class="modal-body">
                    
                    <el-form-item label="Student" prop="student_id">

                      <el-select v-if="studentInvitations != null" multiple v-model="invite_student_ids" clearable filterable placeholder="Select" required>
                          <el-option
                          v-for="item in studentInvitations"
                          :key="item.id"
                          :label="item.name+' ( '+ item.email+' )'"
                          :value="item.id">
                          </el-option>
                      </el-select>

                    </el-form-item>

                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" @click="inviteStudents"  :disabled="!invite_student_ids">
                      Invite Student
                    </button>
                  </div>

              </el-form>


            </div>
          </div>
        </div>
        <!--end::Modal -->

    </div>
       
</template>

<script>
import axios from 'axios';

export default {
    props: ['hub','aham_class'],

    data() {

        return {
            students: null,
            invite_student_ids: [],
            studentInvitations: null,
            enrolling_id: null,
            reason: null,
            can_force: false,
            force_enroll: false,
            can_enroll: false,
        }

    },

    mounted() {
        console.log('aham_class:',this.aham_class);
        this.allStudents();
    },

    methods: {

        allStudents() 
        {
          axios
              .get("hub/single-class/"+this.aham_class.id+"/student-invitation")
              .then(response => {
                    console.log("studentInvitation",response.data.eligibleStudents);
                    this.studentInvitations = response.data.allStudents;
                    this.students = response.data.invitedStudents;
                }).catch(response => {

                });
        },

        enroll(enrollingId) 
        {

            axios
            .post("hub/single-class/"+this.aham_class.id+'/enroll/'+enrollingId)
            .then((response) => {

                this.$parent.$emit('refresh');
                this.allStudents();

            })
            .catch((error) => {

            });

        },

        inviteStudents() 
        {
            let payload = {
                'invitations': this.invite_student_ids,
            };

            axios
            .post("hub/single-class/"+this.aham_class.id+"/student-invitation",payload)
            .then(response => {
                
                $('#student_invitations_modal').modal('hide');
                this.$emit('refresh');
                this.allStudents();

            }).catch(response => {

            });

        },

    }
}

</script>
