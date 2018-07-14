<template>

  <div>

      <div class="row">

        <div class="col-md-8">
          <class-timings v-on:refresh="refresh" :hub="hub" :aham_class="aham_class" v-if="aham_class"></class-timings>
          <class-enrollments v-on:refresh="refresh" :hub="hub" :aham_class="aham_class" v-if="aham_class"></class-enrollments>
          
        </div>

        <div class="col-md-4" v-if="aham_class">

            <div class="m-portlet m-portlet--mobile">

                <div class="m-portlet__head">
                  <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                      <h3 class="m-portlet__head-text">
                        Details
                      </h3>
                    </div>
                  </div>

                  <div class="m-portlet__head-tools" v-if="aham_class.status != 'cancelled' && aham_class.status != 'completed'">
                    <ul class="m-portlet__nav">
                      <li class="m-portlet__nav-item">

                        <router-link :to="{ name: 'edit-class', params: {class: aham_class.id} }"  class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" v-if="aham_class.enrolled == 0">
                          Edit
                        </router-link>

                        <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" @click="changeTopicModal">
                          Change Topic
                        </button>

                      </li>
                        </ul>
                  </div>

                </div>


                <div class="m-portlet__body">
                  
                  <ul class="list-unstyled">
                    <li><strong>Topic:</strong> {{ this.aham_class.topic_name }}</li>
                    <li><strong>Code:</strong> {{ aham_class.code }}</li>
                    <li v-if="aham_class.repeat_class_id"><strong>Type:</strong> 
                        <router-link :to="{ name: 'repeat-class-details', params: {repeatClass:          aham_class.repeat_class_id}}">
                          <strong>  Repeat Class  </strong>
                        </router-link>
                    </li>
                    <li v-if="aham_class.type"><strong>Type:</strong> 
                        <router-link :to="{ name: 'class-courses-details', params: {course: aham_class.course.id}}">
                        {{ aham_class.course.name }}
                        </router-link>
                    </li>
                    <li v-if="(aham_class.status !='got_feedback' && aham_class.status != 'completed')"><strong>Status:</strong> {{ aham_class.status }}</li>
                    <li><strong>Min. Enrollment:</strong> {{ aham_class.minimum_enrollment }}</li>
                    <li><strong>Max. Enrollment:</strong> {{ aham_class.maximum_enrollment }}</li>
                    <li><strong>Enrolled:</strong> {{ aham_class.enrolled }}</li>
                    <li><strong>Charge Multiply:</strong> {{ aham_class.charge_multiply }} times number of units</li>
                    <li><strong>Free:</strong> <span v-if="aham_class.free">Yes</span><span v-else>No</span></li>
                    <li><strong>Tutor is Paid?:</strong> <span v-if="aham_class.no_tutor_comp">No</span><span v-else>Yes</span></li>
                    <li><strong>Auto Cancel?:</strong> <span v-if="aham_class.auto_cancel">Yes</span><span v-else>No</span></li>

                    <li v-if="aham_class.status =='got_feedback' || aham_class.status == 'completed'"><strong>Status:</strong> {{ aham_class.status }}</li>
                    <li>
                      <strong>Payments Finalized?:</strong> 
                      <span v-if="aham_class.payment_finalized">Yes</span>
                      <span v-else>No <span class="badge badge-success" style="cursor: pointer;" @click="finalizePayments()">Finalize</span></span>
                      <span class="badge badge-info" style="cursor: pointer;" @click="calculatePayments()">Calculate</span>
                    </li>
                  </ul>


                  <router-link v-if="aham_class.locationf.repeat_class" :to="{ name: 'repeat-class', params: {class: aham_class.id} }"  class="m-portlet__nav-link btn btn-warning btn-block m-btn m-btn--pill m-btn--air">
                    Repeat Class
                  </router-link>

                  <button type="button" class="m-portlet__nav-link btn btn-danger btn-block m-btn m-btn--pill m-btn--air" @click="cancelClass" v-if="aham_class.status != 'cancelled' && aham_class.status != 'completed'">Cancel Class</button>

                  <button type="button" class="m-portlet__nav-link btn btn-success btn-block m-btn m-btn--pill m-btn--air" @click="completeClass" v-if="aham_class.status == 'got_feedback'">Complete Class</button>

                </div>

            </div>

            <class-notes :aham_class="aham_class"></class-notes>

        </div>

      </div>

      <div id="change_topic_modal" class="modal fade animated" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Change Topic</h4>
                </div>

                <div class="modal-body">

                <el-select v-model="createClassForm.topic" filterable clearable placeholder="Select">
                  <el-option
                    v-for="item in topicTree"
                    :key="item.id"
                    :label="item.name +' ('+item.units.length+'-Units)'"
                    :value="item.id">
                  </el-option>
                </el-select>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-warning" @click="changeTopic">
                    Change Topic
                    </button>
                </div>

            </div>
        </div>
      </div>
      
  </div>

</template>

<script>
import store from "../../../store";
import ClassTimings from "./ClassTimings";
import ClassEnrollments from "./ClassEnrollments";
import ClassNotes from "./Notes";
import swal from 'sweetalert2';


export default {
  props:['hub'],
  components: {
    ClassTimings,
		ClassEnrollments,
		ClassNotes
  },
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
        }

    };
  },

  mounted() {
    store.dispatch("setHeading", "All Classes");
		this.refresh();
    this.getTopicTree();
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

      getTopicTree() {
          axios
            .get("/hub/topics/"+this.hub.slug)
            .then(response => {
              this.topicTree = response.data;
              this.topicList = response.data;
            })
            .catch(error => {
              console.log(error);
            });
      },

      changeTopicModal() {
         $('#change_topic_modal').modal('show');
      },

      changeTopic(){
        
          axios
          .post("hub/single-class/"+this.class_id+'/change-topic/'+this.createClassForm.topic_id)            
          .then(response => {
                this.refresh();
                this.getTopicTree();
                $('#change_topic_modal').modal('hide');
          })
          .catch(error => {
              // console.log(error);
          });

      },

  		refresh()
  		{
  			console.log("Refresh");

		    this.class_id = this.$route.params.class;
        
		    this.fetchClass();
  		},

	    fetchClass()
	    {
	          axios
	            .get("hub/single-class/"+this.class_id)
	            .then(response => {
                //console.log(response.data);
	              this.aham_class = response.data;
                store.dispatch("setHeading", "Class Details: "+this.aham_class.code+' - '+
                this.aham_class.name);
	            })
	            .catch(error => {
	              // console.log(error);
	            });
	    },

      cancelClass()
      {
            var vm = this;

            let result = swal({
                title: "Are you sure?",
                text: "You are about to cancel a class.",
                type: "error",
                input: 'textarea',
                inputPlaceholder: 'Please tell us why do you want to cancel!',
                confirmButtonClass: 'btn btn-danger',
                cancelButtonClass: 'btn btn-warning',
                confirmButtonText: 'Sure! Cancel It.',
                cancelButtonText:"Don't Cancel",
                showCancelButton: true,
            }).then(function(result) {

              if(result.value == '') {
                 // cancel
                // console.log(' cancel ',result )
                  axios
                  .post("hub/single-class/"+vm.class_id+'/cancel-class',{remarks:result.value})
                  .then((response) => {

                    vm.refresh();

                  })
                  .catch((error) => {

                  }); 
                  
                  //  console.log();
              } else if(result.dismiss == 'cancel') {
                // don't cancel
                console.log("donn't cancel ",result.dismiss);
              }

    
            });
      },

     finalizePayments()
     {
         var vm = this;

         let result = swal({
           title: "Finalize Payments?",
           type: "question",
          cancelButtonText:"No",
          showCancelButton: true,
         }).then(function(response){

         if(response.value)
         {
             axios
             .post('hub/single-class/'+vm.aham_class.id+'/finalize-payments')
             .then((response) => {
               vm.refresh();
             })
             .catch((error) => {

             });
         }


         });
     },

     calculatePayments()
     {
         axios
         .post('hub/single-class/'+this.aham_class.id+'/calculate-payments')
         .then((response) => {
           this.refresh();
         })
         .catch((error) => {

         });
     },

     completeClass()
     {
         var vm = this;

         let result = swal({
           title: "completing a class",
           type: "success",
           cancelButtonText:"Cancel",
           showCancelButton: true,
         }).then(function(response){
           
            if (response.value) {

                console.log('completing');

                  axios
                      .post('hub/single-class/'+vm.aham_class.id+'/complete-class')
                      .then((response) => {
                        vm.refresh();
                      })
                      .catch((error) => {

                      });

            }

         });
     },


  }
};
</script>