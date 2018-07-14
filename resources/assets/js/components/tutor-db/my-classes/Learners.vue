<template>
	<div class="m-portlet">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<h3 class="m-portlet__head-text">
							Enrolled Learners
						</h3>
					</div>
			    </div>
				<div class="m-portlet__head-tools">
					<ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
						<li class="nav-item m-tabs__item">
							<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget4_tab1_content" role="tab">
								Enrollments
							</a>
						</li>
						<li class="nav-item m-tabs__item">
							<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget4_tab2_content" role="tab">
								Attendance
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="m-portlet__body">
				<div class="tab-content">
					<div class="tab-pane active" id="m_widget4_tab1_content">
						<div class="m-portlet">
							<div class="m-portlet__body">
								<div class="row">
				                  	<div class="col-md-7">
				                  		<!-- <el-form></el-form> -->
				                      	<el-select v-model="enrolling_id" name="select" filterable placeholder="Select">
				                            <el-option
				                              v-for="item in eligibleStudents"
				                              :key="item.id"	                              
				                              :label="item.name"
				                              :value="item.id">
				                             </el-option>
				                      	</el-select>
									</div>
					                <div class="col-md-2">
					                    <button class="btn btn-primary btn-block" @click="checkEligibility()">Enroll</button>
					                </div>
			                    </div>
					        </div>
				        </div>
						<div class="m-portlet" v-for="classUnit in classUnits">
			                <div class="m-portlet__head">
			                    <div class="m-portlet__head-caption">
			                        <div class="m-portlet__head-title">
			                          	<h3 class="m-portlet__head-text">
			                               {{ classUnit.name }}
			                            </h3>
			                        </div>
			                    </div>
			                </div>
			                <div class="m-portlet__body">
								<!--begin::Widget 14-->
								<div class="m-widget4" v-for="enrollment in classUnit.enrollments">
										<!--begin::Widget 14 Item-->
									<div class="m-widget4__item" v-if="enrollment.status == 'enrolled' ">
										<div class="m-widget4__info">
											<span class="m-widget4__title">
												{{enrollment.learner.user.name}}
											</span>
											<br>
											<span class="m-widget4__sub">
												{{enrollment.learner.user.email}}
											</span>
										</div>
										<div class="m-widget4__ext">
												<button v-if="enrollment.status != 'cancelled' && enrollment.status != 'cancelled_by_student' && enrollment.status != 'cancelled_by_admin' " type="button" class="btn btn-danger btn-sm" @click="cancelUnitEnrollment(enrollment.id, enrollment.student_id)">Un enroll</button>
												<button v-if="enrollment.status != 'cancelled' && enrollment.status != 'cancelled_by_student' && enrollment.status != 'cancelled_by_admin' " type="button" class="btn btn-danger btn-sm" @click="attended(enrollment.id, enrollment.student_id)">Mark as attendend</button>
										</div>
									</div>
								</div>
							</div>
						</div>
				    </div>
				    <div class="tab-pane active" id="m_widget4_tab2_content">
				    	<div class="m-portlet" v-for="classUnit in classUnits">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
									    <h3 class="m-portlet__head-text">
									                               {{ classUnit.name }}
									    </h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
														<!--begin::Widget 14-->
								<div class="m-widget4" v-for="enrollment in classUnit.enrollments">
																<!--begin::Widget 14 Item-->
									<div class="m-widget4__item" v-if="enrollment.status == 'enrolled'&& enrollment.attendance == '1'">
										<div class="m-widget4__info">
											<span class="m-widget4__title">
												{{enrollment.learner.user.name}}
											</span>
											<br>
											<span class="m-widget4__sub">
												{{enrollment.learner.user.email}}
											</span>
										</div>
										<div class="m-widget4__ext">
											<button v-if="enrollment.status != 'cancelled' && enrollment.status != 'cancelled_by_student' && enrollment.status != 'cancelled_by_admin' " type="button" id="b1" class="btn btn-metal">Attended</button>
										</div>
									</div>
								</div>
							</div>
						</div>										
					</div>
				</div>
			</div>
 
		<div class="modal fade animated" role="dialog">
			<div class="modal-dialog">
			    <div class="modal-content">
			        <div class="modal-header">
			            <button type="button" class="close" data-dismiss="modal">×</button>
			            <h4 class="modal-title">Enroller</h4>
			        </div>
			        <div class="modal-body">
			            <p>{{ reason }}</p>
			        </div>
				    <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close
				        </button>
				        <button type="button" class="btn btn-success" v-if="this.can_enroll" @click="enroll">
				                      Enroll
				        </button>
				    </div>
			    </div>
			</div>
		</div>
	</div>
</template>
        
<script>
  export default {

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

   mounted() {
    this.getEnrolledStudents();
  },

  methods: {

    cancelUnitEnrollment(unit,student)
    {
      axios
      .post("tutor/singleclass/"+unit+'/cancel-unit-enrollment/'+student)
      .then((response) => {

        $('#basic_modal').modal('hide');

        this.$emit('refresh');
        this.getEnrolledStudents();

      })
      .catch((error) => {

      });
    },

    attended(unit,student)
    {
     
      axios
      .post("tutor/singleclass/"+unit+'/attended/'+student)
      .then((response) => {

        $('#basic_modal').modal('hide');

        this.$emit('refresh');

      })
      .catch((error) => {

      });
    },

    notattended(unit,student)
    {
     
      axios
      .post("tutor/singleclass/"+unit+'/not-attended/'+student)
      .then((response) => {

        $('#basic_modal').modal('hide');

        this.$emit('refresh');

      })
      .catch((error) => {

      });
    },
    

	getEnrolledStudents(){

      axios
        .get("tutor/singleclass/"+this.$route.params.ahamclassid+'/enrollments',{
        	headers: {
        		Accept : 'application/x.aham.v2+json'
        	},
        })
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
      .post("tutor/singleclass/"+this.$route.params.ahamclassid+'/enroll/'+this.enrolling_id)
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
  .get("tutor/singleclass/"+this.$route.params.ahamclassid+'/check-enrollment-eligibility/'+this.enrolling_id)
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

  },

  };

</script>
