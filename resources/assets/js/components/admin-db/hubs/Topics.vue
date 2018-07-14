<template>
    <div>

    <div class="row">
    	<div class="col-md-6">
    		
    		<div class="m-portlet">
    			<div class="m-portlet__head">

  					<div class="m-portlet__head-caption">
  						<div class="m-portlet__head-title">
  							<h3 class="m-portlet__head-text">
  								Topics
  							</h3>
  						</div>
  					</div>

  					<div class="m-portlet__head-tools">
  						<ul class="m-portlet__nav">
  						<li class="m-portlet__nav-item">
  							<button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" @click="openModal('topics')">
  								Add
  							</button>
  						</li>
  						</ul>
  					</div>

    			</div>

          <div class="m-portlet__body">
            <div class="m-widget4" v-if="topics.length">
              <div v-for="topic in topics" class="m-widget4__item">

                <div class="m-widget4__img m-widget4__img--icon">
                  <img src="/dist/media/img/icons/warning.svg" alt="">
                </div>

                <div class="m-widget4__info">
                  <span class="m-widget4__text">
                    {{ topic.name }}

                    <span class="m-badge m-badge--brand m-badge--wide">
                      {{ topic.type }}
                    </span>

                  </span>
                </div>

                <div class="m-widget4__ext">
                  <a @click="removeHubTopic(topic.id,'topic')" class="m-widget4__icon" style="cursor: pointer;">
                    <div class="m-demo-icon__preview">
                      <i class="flaticon-close"></i>
                    </div>
                  </a>
                </div>

              </div>
            </div>
          </div>

    		</div>

    	</div>
    	<div class="col-md-6">
    		<div class="m-portlet">
    			<div class="m-portlet__head">

					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Courses
							</h3>
						</div>
					</div>

					<div class="m-portlet__head-tools">
						<ul class="m-portlet__nav">
						<li class="m-portlet__nav-item">
							<button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" @click="openModal('courses')">
								Add
							</button>
						</li>
						</ul>
					</div>

    			</div>


          <div class="m-portlet__body">
            <div class="m-widget4" v-if="courses.length">
              <div v-for="course in courses" class="m-widget4__item">

                <div class="m-widget4__img m-widget4__img--icon">
                  <img src="/dist/media/img/icons/warning.svg" alt="">
                </div>

                <div class="m-widget4__info">
                  <span class="m-widget4__text">
                    {{ course.name }}

                    <span class="m-badge m-badge--brand m-badge--wide">
                      {{ course.type }}
                    </span>

                  </span>

                </div>

                <div class="m-widget4__ext">
                  <a @click="removeHubTopic(course.id,'course')" class="m-widget4__icon" style="cursor: pointer;">
                    <div class="m-demo-icon__preview">
                      <i class="flaticon-close"></i>
                    </div>
                  </a>
                </div>

              </div>
            </div>
          </div>

    		</div>
    	</div>
    </div>

    <div class="modal fade" id="add_unit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<h5 class="modal-title" id="exampleModalLabel">
    					<span v-if="selecting == 'topics'">Select Topics</span>
    					<span v-if="selecting == 'courses'">Select Courses</span>
    				</h5>
    			</div>

    			<div class="modal-body">

                   <div v-if="catalogue.length">

                      <el-select v-model="selectedItems" multiple filterable placeholder="Select">
                          <el-option
                            v-for="item in catalogue"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                          </el-option>
                      </el-select>

                    </div>

    			</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" v-if="selectedItems.length" @click="addTopics">
					  Add
					</button>
				</div>

    		</div>
    	</div>
    </div>

    </div>
</template>
<script>
import store from "../../../store";


export default {

  components: {

  },

  data() {
    return {
    	selectedItems:[],
    	catalogue: [],
    	selecting: 'topics',
    	hub_slug: null,
    	hub: null,
      topics:[],
      courses:[],
    };
  },

  mounted() {
    store.dispatch("setHeading", "Hub - Topics");

		this.hub_slug = this.$route.params.hub;

        axios.get('/hub/location/'+this.hub_slug).then((response) => {            	
        	this.hub = response.data.location;
          store.dispatch("setHeading", "Hub - Topics - "+this.hub.name);
        })
        .catch((error) => {

        	console.log(error);

        });

        this.getHubSelectedTopics();

  },

  methods:{


    getHubSelectedTopics(){

      axios
        .get("hubs/hub-selected-topics/"+this.hub_slug)
        .then(response => {
          this.topics = response.data.topics;
          this.courses = response.data.courses;
        })
        .catch(error => {
          console.log(error);
        });

    },

  	getTopics(){

      axios
        .get("hubs/get-topics/"+this.hub_slug+'/topics')
        .then(response => {
          this.catalogue = response.data.data;
          this.selectedItems = [];
        })
        .catch(error => {
          console.log(error);
        });

  	},

  	getCourses(){

      axios
        .get("hubs/get-topics/"+this.hub_slug+'/courses')
        .then(response => {
          this.catalogue = response.data.data;
          this.selectedItems = [];
        })
        .catch(error => {
          console.log(error);
        });

  	},

  	openModal(type){

  		this.selecting = type;
  		this.selectedItems = [];
  		if(this.selecting === 'topics')
  		{
  			this.getTopics();
  		}

  		if(this.selecting === 'courses')
  		{
  			this.getCourses();
  		}

  		$('#add_unit_modal').modal('show');
  	},

  	addTopics(){

  		let payload = {
  			selection: this.selecting,
  			items: this.selectedItems
  		};

      	axios
        .post("hubs/assign-topics/"+this.hub_slug, payload)
        .then(response => {

            $('#add_unit_modal').modal('hide');
            this.getHubSelectedTopics();
          
        })
        .catch(error => {
          console.log(error);
        });

  	},

    removeHubTopic(id, type){

        axios
        .post("hubs/remove-topics/"+this.hub_slug, {
          'id': id, 
          'type': type
        })
        .then(response => {
          this.getHubSelectedTopics();
        })
        .catch(error => {
          console.log(error);
        });

    }

  }

}

</script>

<style type="text/css" scoped>
	.el-select{
		width: 100%;
	}
</style>