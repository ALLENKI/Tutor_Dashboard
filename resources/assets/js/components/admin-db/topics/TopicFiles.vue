<template>

	<div>

			<div class="m-portlet m-portlet--collapse" data-portlet="true">

				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Files
							</h3>
						</div>
					</div>

					<div class="m-portlet__head-tools">

						<ul class="m-portlet__nav">

							<li class="m-portlet__nav-item">
								<a href="" data-portlet-tool="toggle" class="m-portlet__nav-link m-portlet__nav-link--icon">
									<i class="la la-angle-down"></i>
								</a>
							</li>

							<li class="m-portlet__nav-item">
								<a href="" data-toggle="modal" data-target="#uploadFile" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air">
									Upload File
								</a>
							</li>

							<div class="form-group m-form__group">

							</div>
							
						</ul>

					</div>

				</div>
				
				<!-- show docs -->
				<div class="m-portlet__body">

					<!--begin::m-widget4-->
						<div v-for="item in files" class="m-widget4">

							<div class="m-widget4__item">

								<div class="m-widget4__img m-widget4__img--icon">
								
								</div>

								<div class="m-widget4__info">
									<span class="m-widget4__text">
									 		<a :href="item.file_url"> {{item.file_name}} </a>	
									</span>
								</div>

								<div class="m-widget4__ext">
									<a @click="downloadFile(item.file_url)" class="m-widget4__icon">
										<i class="la la-download"></i>
									</a>
								</div>

								<div class="m-widget4__ext">
									<a @click="removeFile(item.id)" class="m-widget4__icon" style="cursor: pointer;">
										<div class="m-demo-icon__preview">
											<i class="flaticon-close"></i>
										</div>
									</a>
								</div>

							</div>
						
						</div>
					<!--end::Widget 9-->

				</div>

			</div>

			<!--begin::Modal-->
				<div class="modal fade" id="uploadFile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

					<div class="modal-dialog" role="document">

						<div class="modal-content">

							<div class="modal-header">

								<h5 class="modal-title" id="exampleModalLabel">
									Choose file
								</h5>

								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">
										&times;
									</span>
								</button>

							</div>

							<div class="modal-body">

								<div class="row">

									<form class="m-form m-form--fit m-form--label-align-right">

										<div class="form-group m-form__group">

											<label for="exampleInputEmail1">
												File Name
											</label>

											<input type="email" v-model="fileName" class="form-control m-input m-input--air" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter File Name">

										</div>

										<div class="form-group m-form__group">

											<label class="custom-file">

												<input @change="handleFileUpload()" ref="doc" class=" custom-file-input file-input" type="file" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
												text/plain, application/pdf, image/*">

												<span class="custom-file-control"></span>

											</label>

										</div>

										<div class="m-portlet__foot m-portlet__foot--fit">

											<div class="m-form__actions">

												<button @click="upload()" type="reset" class="btn btn-primary">
													Submit
												</button>

											</div>

										</div>
									
									</form>

								</div>

							</div>
							
						</div>

					</div>

				</div>
    <!--end::Modal-->

	</div>

</template>

<script>
export default {

	 	data() {

				return {
					file: null,
					files: null,
					fileName: null,
				}

		},
		
		mounted() {
			this.getFile();
		},
 
    methods: {
 
		handleFileUpload() {

					console.log(this.$refs.doc.files[0]);
					this.file = this.$refs.doc.files[0];
		
					console.log('',this.file);
		},

		upload() {

			let formData = new FormData();
			formData.append('doc', this.file);

			if(this.fileName) {
				formData.append('filename', this.fileName);
			}
			
			axios.post('course_catalog/topics/'+this.$route.params.topic+"/upload-doc", formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			}).then(function (response) {
				console.log(response);
			})
			.catch(function (error) {
				console.log(error);
			});

			setTimeout(() => {
				this.getFile();

				$(function(){
					 $('#uploadFile').modal('hide');
				});

			}, 2000);

			// this.$router.go(this.$router.currentRoute);

		},

		downloadFile(url) {
			window.open(url);
		},
		
		getFile() {

			axios.get("course_catalog/topics/" + this.$route.params.topic+"/get-doc")
				.then(response => {

					this.files = response.data;

				}).catch(error => {

				});

		},

		removeFile(id) {

			let data =  {
							'fileId' : id,
						};
						
			axios.post("course_catalog/topics/" + this.$route.params.topic+"/remove-doc",data)
				.then(response => {
					this.getFile();
				}).catch(error => {
				});


		},	
 
    }    

};
</script>