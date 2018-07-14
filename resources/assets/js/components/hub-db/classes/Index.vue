<template>
<div>

	<form v-on:submit.prevent="search">

	<el-row>

		<el-col :span="4">
			<label>
				Status
				(<input type="checkbox" v-model="all"> All)
			</label>
			<el-select v-model="searchForm.status" multiple collapse-tags placeholder="Select">
					<el-option
					v-for="item in status_options"
					:key="item.value"
					:label="item.label"
					:value="item.value">
					</el-option>
			</el-select>
		</el-col>

		<el-col :span="4">
			<label>Tutor</label>
			<el-select v-model="searchForm.tutor" filterable placeholder="Select">
					<el-option
					v-for="item in tutor_options"
					:key="item.value"
					:label="item.label"
					:value="item.value">
					</el-option>
			</el-select>
		</el-col>

		<el-col :span="4">
			<label>Topic</label>
			<el-select v-model="searchForm.topic" filterable placeholder="Select">
					<el-option
					v-for="item in topic_options"
					:key="item.id"
					:label="item.name"
					:value="item.id">
					</el-option>
			</el-select>
		</el-col>

		<el-col :span="4">
			<label>Input</label>
			<el-input placeholder="Please input" clearable v-model="searchForm.input" v-on:keyup.enter.native="search()"></el-input>
		</el-col>

		<el-col :span="4">
			<label>Sort</label>
			<el-select v-model="searchForm.sort" placeholder="Select">
					<el-option
					v-for="item in sort_options"
					:key="item.value"
					:label="item.label"
					:value="item.value">
					</el-option>
			</el-select>
		</el-col>

		<el-col :span="4">
			<label>&nbsp;&nbsp;</label>
			<br>
			<el-button type="info" plain icon="el-icon-search" @click="search()">Search</el-button>
		</el-col>

	</el-row>

	</form>

	<hr>
	
	<div class="row">

		<div class="col-md-3" v-for="aham_class in classes">
			<simple-class-card :aham_class="aham_class"></simple-class-card>
		</div>
		
		<div class="clearfix"></div>

		<el-button type="info" plain icon="el-icon-search" @click="search(pagination.current_page+1)"  v-if="pagination && pagination.current_page != pagination.total_pages">
			Load More From Page {{ pagination.current_page+1 }}
		</el-button>

	</div>


</div>
</template>

<script>
import store from "../../../store";
import SimpleClassCard from "./SimpleClassCard";

export default {
  props:['hub'],
  components: {
    SimpleClassCard
  },
  data() {
    return {
      all: false,
      status_options: [
          {label: 'Initiated', title: 'Initiated', value: 'initiated'},
          {label: 'Created', title: 'Created', value: 'created'},
          {label: 'Invited', title: 'Invited', value: 'invited'},
          {label: 'Accepted', title: 'Accepted', value: 'accepted'},
          {label: 'Open For Enrollment', title: 'Open For Enrollment', value: 'open_for_enrollment'},
          {label: 'Scheduled', title: 'Scheduled', value: 'scheduled'},
          {label: 'In Session', title: 'In Session', value: 'in_session'},
          {label: 'Get Feedback', title: 'Get Feedback', value: 'get_feedback'},
          {label: 'Got Feedback', title: 'Got Feedback', value: 'got_feedback'},
          {label: 'Completed', title: 'Completed', value: 'completed'},
          {label: 'Cancelled', title: 'Cancelled', value: 'cancelled'},
          {label: 'Closed', title: 'Closed', value: 'closed'}
      ],
      sort_options:[
        {
          value: "created_at",
          label: "Created At"
        },
        {
          value: "start_date_asc",
          label: "Start Date (ASC)"
        },
        {
          value: "start_date_desc",
          label: "Start Date (DESC)"
        },
      ],
      tutor_options:[],
      topic_options:[],
      searchForm: {
        status:  ['scheduled','in_session','open_for_enrollment'],
        sort: 'created_at',
        input: "",
        tutor:null,
        topic:null
      },
      classes: [],
      pagination: false
    };
  },

  mounted() {
		store.dispatch("setHeading", "All Classes");

		if(this.$route.name == 'all-classes') {
				this.fetch();
		} else {
			this.middleState();
		}
    
		console.log('status',this.$route.name);
		
  },

		
  watch:{

  	'all': function(){
  		
  		if(this.all)
  		{
  			this.searchForm.status = _.map(this.status_options,(o) => {
  				return o.value;
  			});

  		}else{
  			this.searchForm.status = ['scheduled','in_session','open_for_enrollment'];
  		}

		},

		'$route': function() {
			
			 if(this.$route.name == 'all-classes') {
					 if(this.searchForm.status.length <= 1) {
						 	this.searchForm.status = [];
						  this.searchForm.status.push('scheduled','in_session','open_for_enrollment');
					 }
					 this.fetch();
			 } else {
				 	console.log('middle state',this.$route.name);
					this.middleState()
			 }
			 
		}

	},
	
	

  methods: {

				fetch()
				{
						this.getTopicTree();
						this.getTutorTree();
						this.search();
				},

				middleState()
				{

					switch (this.$route.name) {
							case "TeacherNotInvited":

										this.searchForm.status = [];
										this.searchForm.status.push("no_invitations");

										this.fetch();

									break;

							case "InvitedButNotAwarded":

										this.searchForm.status = [];
										this.searchForm.status.push("has_invitations_no_teacher");

										this.fetch();

									break;

							case "MinEnrollmentNotFound":

										this.searchForm.status = [];
										this.searchForm.status.push("min-enrollment-not-met");

										this.fetch();

									break;

							case "WaitingForFeedback":

										this.searchForm.status = [];
										this.searchForm.status.push("get-feedback");

										this.fetch();

									break;
					
							default:
									break;
					}

				},

        getTopicTree() {
          axios
            .get("/common/topic-tree")
            .then(response => {
              this.topic_options = response.data.list;
            })
            .catch(error => {
              console.log(error);
            });
        },

        getTutorTree() {
          axios
            .get("/common/tutors")
            .then(response => {
              this.tutor_options = response.data;
            })
            .catch(error => {
              console.log(error);
            });
        },

        search(page) {
				page = page || 1;
				let payload = this.searchForm;
				payload["page"] = page;
				payload["hub"] = this.$route.params.hub;

				console.log(payload);

				axios
					.get("hub/classes", {
						params: payload
					})
					.then(response => {
						if (page > 1) {
							this.classes = _.union(this.classes, response.data.data);
						} else {
							this.classes = response.data.data;
						}

						this.pagination = response.data.meta.pagination;
						store.dispatch(
							"setHeading",
							"Browse Catalog: Total " + this.pagination.total
						);
					})
					.catch(error => {
						console.log(error);
					});
    }
  }
};
</script>