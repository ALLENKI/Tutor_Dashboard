<template>

  <div>

        <h4>Payment Profile for {{ tutor.name }} - {{ tutor.email }}</h4>
        <div class="form-group">
          
        <label>Hub</label>
        <el-select v-model="hubId" placeholder="select hub">
          <el-option
            v-for="item in hubs"
            :key="item.value"
            :label="item.label"
            :value="item.value">
          </el-option>
        </el-select>

        </div>

              
      <!--begin::Section-->
        <div class="m-section">

          <div class="m-section__content">

            <div v-if="formData">
                <div v-for="item in formData.timings">
                  <day-card :data="item"></day-card>
                </div>
            </div>
          
          </div>

        </div>
      <!--end::Section-->

      <!--begin::Modal-->
        <div class="modal fade" id="m_modal_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">

									<div class="modal-header">

										<h5 class="modal-title" id="exampleModalLabel">
											Add Settlement
										</h5>

										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">
												&times;
											</span>
										</button>

									</div>

									<div class="modal-body">

                     <el-form>

                        <el-form-item label="Commisssion Type">
                        
                          <el-select v-model="commissionType" placeholder="select Commisson type">
                            <el-option
                              v-for="item in commissionTypeOptions"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value">
                            </el-option>
                          </el-select>
                          
                        </el-form-item>

                        <el-form-item label="Settlement Type" v-if="this.commissionType == 'amount'">

                          <el-select v-model="settlementType" placeholder="select Commisson type">
                            <el-option
                              v-for="item in settlementTypeOptions"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value">
                            </el-option>
                          </el-select>

                        </el-form-item>

                        <el-form-item label="Commission Value">
                            <el-input v-model="commissionValue"></el-input> 
                        </el-form-item>

                        <el-form-item label="Min Enrollment" v-if="this.settlementType == 'per_enrollment'">
                            <el-input v-model="minEnrollment"></el-input>
                        </el-form-item>

                        <el-form-item label="Max Enrollment" v-if="this.settlementType == 'per_enrollment'">
                            <el-input v-model="maxEnrollment"></el-input>
                        </el-form-item>

                        <el-form-item label="From and To Times">

                            <el-time-picker
                              is-range
                              v-model="fromTo"
                              range-separator="To"
                              start-placeholder="Start time"
                              end-placeholder="End time">
                            </el-time-picker>

                        </el-form-item>

                     </el-form>  
										
									</div>

									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">
											Close
										</button>
										<button v-if="editable == false" @click="update" type="button" class="btn btn-primary">
											Save changes
										</button>
                                        <button v-if="editable == true " @click="update" type="button" class="btn btn-primary">
											edit
										</button>
									</div>

								</div>
							</div>
				</div>
      <!--end::Modal-->
    
  </div>

</template>

<script>

import DayCard from './card';
import moment from "moment";

export default {

  props: ['tutor','hubs'],
  components: {
    DayCard,
  },
  data() {
    return {
      editable : false,
      editing: null,
      day: null,
      hubId: null,
      tutorId: null,
      formData: null,
      from: null,
      to: null,
      fromTo: null,
      commissionTypeOptions:  [
                                  {
                                      'value': 'percentage',
                                      'label': 'Percentage',
                                  },
                                  {
                                      'value': 'amount',
                                      'label': 'Amount',
                                  }
                              ],
      settlementTypeOptions:  [
                                  {
                                      'value': 'per_enrollment',
                                      'label': 'PerEnrollment',
                                  },
                                  {
                                      'value': 'fixed',
                                      'label': 'Fixed',
                                  }
                              ],
      settlementType: null,
      commissionType: null,
      commissionValue: 0,
      minEnrollment: 0,
      maxEnrollment: 0,
    }
  },
  watch: {
     commissionType: function() {

      if(this.commissionType == 'percentage') {
          this.settlementType = 'per_enrollment';
      }

     },

     hubId: function() {
       this.getTutorPaymentProfile();
     },
  },
  mounted() {
      this.tutorId = this.tutor.id;
      this.hubId = this.hubs[0].value;
      this.getTutorPaymentProfile();
  },
  methods: {

    setDay(day) {
      this.editable = false;
      this.day = day;
    },

    fillDays() {
         
    },
    
    getTutorPaymentProfile() {

          if (this.tutorId && this.hubId) {

            this.formData = {
                "tutorId": this.tutorId,
                "hubId": this.hubId,
                "timings" : [
                          {
                              "label": "Sunday",
                              "day": "sunday",
                              "timings": [
                              ]
                          },
                          {
                              "label": "Monday",
                              "day": "monday",
                              "timings": [
                              ]
                          },
                          {
                              "label": "Tuesday",
                              "day": "tuesday",
                              "timings": [
                              ]
                          },
                          {
                              "label": "Wednesday",
                              "day": "wednesday",
                              "timings": [
                              ]
                          },
                          {
                              "label": "Thursday",
                              "day": "thursday",
                              "timings": [
                              ]
                          },
                          {
                              "label": "Friday",
                              "day": "friday",
                              "timings": [  
                              ]
                          },
                          {
                              "label": "Saturday",
                              "day": "saturday",
                              "timings": [
                              ]
                          },
                        ]
            }

          }

          axios.get('tutor_payments/'+this.tutorId+'/hub/'+this.hubId+'/show')
                 .then(response => {
                    //  console.log(response.data);
                    if(response.data.timings)
                    {
                        response.data.timings.forEach(item => {
                          this.formData.timings.forEach(formItem => {
                            
                            if (item.day == formItem.day) {
                                formItem.timings = item.timings;
                            }
                            
                          })
                        })
                    }

                 })
                 .catch(error => {
                     console.log(error);
                 });  

    },

    update() {

        this.from =  moment(this.fromTo[0]).format('HH:mm'); 
        this.to = moment(this.fromTo[1]).format('HH:mm');

        var payload =   { 
                          "id": this.day+'_'+this.from+'_'+this.to,
                          "from": this.from,
                          "to": this.to,
                          "commission_type": this.commissionType,
                          "settlement_type": this.settlementType,
                          "commission_value": parseInt(this.commissionValue),
                          "min_enrollment": parseInt(this.minEnrollment),
                          "max_enrollment": parseInt(this.maxEnrollment),
                        };
                                  
        console.log('updateing : ',payload);
        this.postTutorPayments(payload);
        $('#m_modal_1').modal('hide');
    },

    postTutorPayments(payload) {

            if (this.editable) {

                // remove that row in timings
                this.formData.timings.forEach((item,key) => {
                    if (item.day == this.day) {

                        console.log('item',item.timings,'editing',this.editing);

                        var editing = this.editing;

                        item.timings =   _.filter(item.timings, function(o) { 
                                                if (o.id != editing.id) {
                                                    return item;
                                                } 
                                        });

                        console.log('removed item', item.timings);

                        item.timings.push(payload);
                        console.log('formData: ',item);
                    }
                });

                // console.log('new timings',this.formData.timings);

            } else {

                this.formData.timings.forEach(item => {
                    if (item.day == this.day) {
                        item.timings.push(payload);
                        console.log('formData: ',item);
                    }
                });

            }

            axios.post('tutor_payments/createOrUpdate/payment',this.formData)
            .then(response => {
                console.log(response);
                this.getTutorPaymentProfile();
            })
            .catch(error => {
                console.log(error);
            });

    },

    destroy(day,deletableItem) {

          console.log('parent delete: ',deletableItem,deletableItem.id,day);

          // {tutorId}/delete/{paymentId}/timing
          axios.delete('tutor_payments/tutor/'+this.tutorId+'/delete/'+this.hubId+'/day/'+day+'/timings/'+deletableItem.id)
                .then(response => {
                    console.log(response);
                    this.getTutorPaymentProfile();
                })
                .catch(error => {
                    console.log(error);
                });
       
    },

    clear() {
        this.from = null;
        this.to = null;
        this.commissionType = null;
        this.settlementType = null;
        this.minEnrollment = null;
        this.maxEnrollment = null;
    },

    edit(item,day) {

     this.editable  = true;
     this.editing = item;
     this.day = day;

      $('#m_modal_1').modal('show');
      console.log('editing: ',item);

      this.fromTo = [moment().format('Y-M-D')+" "+item.from,moment().format('Y-M-D')+" "+item.to];

      this.from = moment().format('Y-M-D')+" "+item.from;
      this.to = moment().format('Y-M-D')+" "+item.to;
      this.commissionType = item.commission_type;
      this.settlementType = item.settlement_type;
      this.commissionValue = item.commission_value;
      this.minEnrollment = item.min_enrollment;
      this.maxEnrollment = item.max_enrollment;

    },

  },
  
}
</script>

<style scoped>

  .el-select,.el-date-editor{
    width: 100%;
  }

  #nostyles {
    padding: 0px;
    boarder: 0px;
    margin: 0px;
  }  

</style>
