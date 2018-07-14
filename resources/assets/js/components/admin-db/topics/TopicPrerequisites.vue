<template>

	<div>
		
			<div class="m-portlet m-portlet--collapse" data-portlet="true">

				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Prerequisites
							</h3>
						</div>
					</div>
					<div class="m-portlet__head-tools">
						<ul class="m-portlet__nav">
							<li class="m-portlet__nav-item">
								<a href="" data-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
									<i class="la la-angle-down"></i>
								</a>
								<button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" data-toggle="modal" data-target="#topicModal">
									Add
								</button>
							</li>
						</ul>
					</div>
				</div>

				<div class="m-portlet__body">
						<!--begin::m-widget4-->
						<div class="m-widget4">

							<div v-if="prerequisites" v-for="item in prerequisites" class="m-widget4__item">
								
								<div class="m-widget4__img m-widget4__img--icon">
									<img src="/dist/media/img/icons/warning.svg" alt="">
								</div>

								<div class="m-widget4__info">
									<span class="m-widget4__text">
										{{ item.name }}
									</span>
								</div>

								<div class="m-widget4__ext">
									<a @click="removeTopics(item.id)" class="m-widget4__icon" style="cursor: pointer;">
										<div class="m-demo-icon__preview">
											<i class="flaticon-close"></i>
										</div>
									</a>
								</div>

							</div>

						</div>
				</div>

				<!--end::Widget 9-->
			</div>

		<!--begin::Modal-->
			<div class="modal fade" id="topicModal" tabindex="-1" role="dialog" aria-labelledby="topicModal" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">
								Add Topics
							</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">
									&times;
								</span>
							</button>
						</div>
						<div v-if="activeTopics" class="modal-body">

							<el-select v-model="selectedTopic" multiple clearable filterable placeholder="Select" required>
								<el-option
									v-for="item in activeTopics"
									:key="item.id"
									:label="item.name"
									:value="item.id">
								</el-option>
							</el-select>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">
								Close
							</button>
							<button type="button" @click="addTopics" class="btn btn-primary">
								add Topics
							</button>
						</div>
					</div>
				</div>
			</div>
		<!--end::Modal-->

	</div>

</template>

<script>
import swal from 'sweetalert2';
export default {

		data(){
			return {
				activeTopics: [],
				selectedTopic: null,
				prerequisites: null,
			}
		},

		mounted() {

			this.getActiveTopics();
			this.getPrerequisite();

			$(function () {

				$('.preventReload').click(function(e){
					e.preventDefault()
				});

			});


		},

		methods: {

			getActiveTopics() {

				axios
					.get("course_catalog/topics/" + this.$route.params.topic+"/get-active-topics")
					.then(response => {
						this.activeTopics = response.data;
					})
					.catch(error => {
						console.log(error);
					});
	
			},

			getPrerequisite() {

				axios
					.get("course_catalog/topics/" + this.$route.params.topic+"/get-prerequisite")
					.then(response => {
						this.prerequisites = response.data;
					})
					.catch(error => {
						console.log(error);
					});
	
			},

			addTopics() {

				let data =  {
								'topicId': this.selectedTopic,
							}

				axios
					.post("course_catalog/topics/" + this.$route.params.topic+"/add-prerequisite",data)
					.then(response => {

							$(function() {
								$('#topicModal').modal('hide');
							});

							let message = response.data;

							if(message)
							{

								swal({
										type: 'error',
										title: 'try again',
										text: message,
										footer: '',
								})

							}

								this.getPrerequisite();
					})
					.catch(error => {

						let message = error.data;

						
							
					});

			},
			
			removeTopics(id) {

				let data =  {
								'topicId' : id,
							};

				axios.post("course_catalog/topics/" + this.$route.params.topic+"/remove-prerequisite",data).
				then(response => {
					this.getPrerequisite();
				}).catch(error => {

				});

			},	

		},
};
</script>