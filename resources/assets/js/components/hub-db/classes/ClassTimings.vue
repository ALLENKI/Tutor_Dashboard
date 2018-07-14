<template>

    <div>

        <div class="m-portlet m-portlet--mobile">

          <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
              <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                  Timings
                </h3>
              </div>
            </div>  

            <div class="m-portlet__head-tools">
              <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                  
                <router-link v-if="aham_class != null && aham_class.status != 'cancelled' && aham_class.status != 'completed'" tag="div" :to="{ name: 'schedule-class', params: {class: aham_class.id} }">
                  <button class="pull-right btn btn-primary">Manage Schedule</button>
                </router-link>

                </li>
              </ul>
            </div>

          </div>

          <div class="m-portlet__body">

            <div class="table-responsive" v-if="aham_class && aham_class.timings.length">

              <table class="table table-bordered table-hover" id="table1">

                <thead>
                    <tr>
                      <th>Date</th>
                      <th>Unit</th>
                      <th>Timing</th>
                      <th>Classroom</th>
                      <th>Tutor(s)</th>
                      <th>Status</th>
                      <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                      <tr v-for="my_class in aham_class.timings">
                        <td>{{ my_class.udate | moment }}</td>
                        <td>{{ my_class.class_unit.name }}</td>
                        <td>{{ my_class.start_time }} to {{ my_class.end_time }}</td>
                        <td>
                          <span v-if="my_class.oclassroom">
                            {{ my_class.oclassroom.name }}
                          </span>
                          <span v-else>NA</span>
                        </td>

                        <td>
                          <span v-if="my_class.teacher != 'NA'">
                          {{ my_class.teacher }}
                          <button class='btn btn-primary btn-sm' @click="changeTutorModal(my_class.id)" v-if="aham_class != null && aham_class.status != 'cancelled' && aham_class.status != 'completed'" >Change</button>
                          </span>
                          <span v-else>
                              <button class='btn btn-primary btn-sm' @click="assignTutorModal(my_class.id)" v-if="aham_class != null && aham_class.status != 'cancelled' && aham_class.status != 'completed'" >Assign</button>
                          </span>
                        </td>

                        <td>
                          <span v-if="!my_class.done">
                            <button class='btn btn-success btn-sm' @click="markAsDoneModal(my_class.id,my_class.teacher)" v-if="aham_class != null && aham_class.status != 'cancelled' && aham_class.status != 'completed'" >Mark as Done</button>
                          </span>
                          <span v-else>
                              Done
                          </span>
                        </td>

                        <td>
                              {{ my_class.tutor_payment }}

                              <small v-on:click.prevent="showPaymentsModel(my_class.id,my_class.tutor_payment)" style="cursor: pointer;">
                              Edit
                              </small>
                         </td>

                      </tr>
                </tbody>

              </table>

            </div>

          </div>

        </div>


        <!--begin::Modal-->
            <div class="modal fade" id="assign_tutor_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      Assign Tutor
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">
                        &times;
                      </span>
                    </button>
                  </div>
                  

                  <el-form label-width="120px">

                      <div class="modal-body">

                        <el-form-item label="Teacher" prop="tutor_id">

                          <el-select v-model="tutor_id" clearable filterable placeholder="Select" required>
                              <el-option
                              v-for="item in invitations.eligibleTutors"
                              :key="item.id"
                              :label="item.name"
                              :value="item.id">
                              </el-option>
                          </el-select>

                        </el-form-item>

                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" @click="assignTutor"  :disabled="!tutor_id">
                          Assign Tutor
                        </button>
                      </div>

                  </el-form>


                </div>
              </div>
            </div>
        <!--end::Modal-->

         <!--begin::Modal-->
            <div class="modal fade" id="add_payments" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      Edit Tutor Payment
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">
                        &times;
                      </span>
                    </button>
                  </div>
                  

                  <el-form label-width="120px">
              
                      <div class="modal-body">

                          <el-form-item label="Amount" prop="tutor_id">
                            <el-input  v-model="tutor_payment"></el-input>
                          </el-form-item>

                      </div>
                      <div class="modal-footer">
                          <button type="submit" class="btn btn-primary" v-on:click="editPayments">
                            Add
                          </button>
                      </div>

                  </el-form>


                </div>
              </div>
            </div>
        <!--end::Modal-->

        <!--begin::Modal-->
            <div class="modal fade" id="change_tutor_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      Change Tutor
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">
                        &times;
                      </span>
                    </button>
                  </div>
                  

                  <el-form label-width="120px">

                      <div class="modal-body">

                        <el-form-item label="Teacher" prop="tutor_id">

                          <el-select v-model="tutor_id" clearable filterable placeholder="Select" required>
                              <el-option
                              v-for="item in invitations.eligibleTutors"
                              :key="item.id"
                              :label="item.name"
                              :value="item.id">
                              </el-option>
                          </el-select>

                        </el-form-item>

                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" @click="changeTutor"  :disabled="!tutor_id">
                          Change Tutor
                        </button>
                      </div>

                  </el-form>


                </div>
              </div>
            </div>
        <!--end::Modal-->

        <!-- beginModal -->
            <div class="modal fade" id="mark_as_done_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">

                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                      Mark Unit as done
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">
                        &times;
                      </span>
                    </button>
                  </div>
        
                  <el-form label-width="120px">
                      <div class="modal-body">
                        <el-form-item label="Remarks">
                          <el-input type="textarea" class="form-control" v-model="remark"></el-input>
                        </el-form-item>
                      </div>

                      <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" @click="markAsDone">
                          Confirm
                        </button>
                      </div>
                      
                  </el-form>


                </div>
              </div>
            </div>
       <!-- endModal -->

    </div>

</template>

<script>
import store from "../../../store";
import moment from "moment";
import swal from 'sweetalert2';


export default {
  props:['hub','aham_class'],
  data() {
    return {
      tutor_id:null,
      invitations:{
        eligibleTutors: [],
        certifiedTutors: []
      },
      tutor_payment: null,

      unit_id: null,
      remark: '',
    };
  },
  watch:{
    'aham_class':function(){
      this.getEligibleTutors();
    }
  },
  mounted() {
    console.log(this.aham_class);
    this.getEligibleTutors();
  },
  filters: {
    moment: function (date) {
      return moment(date).format('Do MMMM YY - dddd');
    }
  },
  methods: {

    editPayments() {

      var data = {
        tutor_payment: this.tutor_payment
      }

      axios
        .post("hub/single-class/"+this.aham_class.id+'/tutor/'+this.unit_id+'/addPayment',data)
        .then((response) => {

          $('#add_payments').modal('hide');
          this.$emit('refresh');
          //this.$router.go();
        })
        .catch((error) => {
            new Error(error);
        });

        this.unit_id = null;

    },

    showPaymentsModel(id,oldpay=null){
      console.log(id);
      this.unit_id = id;
      this.tutor_payment=oldpay;
      $("#add_payments").modal("show");
    },

    isPast(date){
      return moment().isSameOrAfter(date,'d');
    },

    changeTutor(){
       axios.post("hub/single-class/"+this.aham_class.id+'/assign-tutor/'+this.tutor_id+'/'+this.unit_id)
          .then((response) => {

            $('#change_tutor_modal').modal('hide');
            this.$emit('refresh');

          })
          .catch((error) => {

          });
    },

    markAsDoneModal(unit_id,teacher){ 
      if(teacher!="NA")
      {
        //console.log('mark as done',unit_id);
        $("#mark_as_done_modal").modal("show");
        this.unit_id = unit_id;
      }
      else
      {
        swal('Assine Tutor', 'Please Assign The Tutor', 'error');
      }
    },

    markAsDone() {

      let payload = {
        'remark': this.remark
      }

      axios.post('hub/single-class/'+this.aham_class.id+'/mark-as-done/'+this.unit_id,payload)
      .then(response => {
          $('#mark_as_done_modal').modal('hide');
          this.$emit('refresh');
      })
      .catch(error => {

      });

    },

    changeTutorModal(unit_id,tutor_id) {

        this.invitations = {};

        this.unit_id =  unit_id;

        this.getTutorPerUnit();

        $("#change_tutor_modal").modal("show");
        
    },

    getTutorPerUnit() {
      axios
             .get("hub/single-class/"+this.aham_class.id+"/tutors/"+this.unit_id)
             .then((response) => {
                this.invitations = response.data;
             })
             .catch((error) => {
               
             })

    },

    assignTutorModal()
    {
        $("#assign_tutor_modal").modal("show");
    },

    getEligibleTutors() {

      axios
          .get("hub/single-class/"+this.aham_class.id+'/tutors')
          .then(response => {
            
              this.invitations.eligibleTutors = response.data.eligibleTutors;
              this.invitations.certifiedTutors = response.data.certifiedTutors;
          })
          .catch(error => {
            // console.log(error);
          });

    },

    assignTutor() {

      axios.
        post("hub/single-class/"+this.aham_class.id+'/assign-tutor/'+this.tutor_id)
        .then((response) => {

          $('#assign_tutor_modal').modal('hide');
          this.$emit('refresh');

        })
        .catch((error) => {

        });

    }

  }
};
</script>
