<template>
  

		<!--begin::Portlet-->
		<div class="m-portlet m-portlet--tab">
			<div class="m-portlet__head">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-title">
						<span class="m-portlet__head-icon m--hide">
							<i class="la la-gear"></i>
						</span>
						<h3 class="m-portlet__head-text">
							Edit Payment for {{ student.name }} 
						</h3>
					</div>
				</div>
			</div>
			<!--begin::Form-->
			<form v-if="student" class="m-form m-form--fit m-form--label-align-right">
				<div class="m-portlet__body">
					<div class="form-group m-form__group">
						<label for="Amount">
							Amount
						</label>
						<input type="number" v-model="student.amount" class="form-control m-input m-input--air" id="inputAmount" aria-describedby="emailHelp" placeholder="Enter Amount">			
					</div>
					<div class="form-group m-form__group">
						<label for="exampleTextarea">
							Remarks
						</label>
						<textarea v-model="student.remarks" class="form-control m-input m-input--air" id="exampleTextarea" rows="3"></textarea>
					</div>
					<div class="form-group m-form__group">
						<label for="exampleSelect1">
							Payment Method
						</label>
						<select v-model="student.method" class="form-control m-input m-input--air" id="exampleSelect1">
							<option>
								cheque
							</option>
							<option>
								cash
							</option>
							<option>
								online_payment
							</option>
							<option>
								pending
							</option>
							<option>
								online_transfer
							</option>
						</select>
					</div>
					<div class="form-group m-form__group">
						<label for="exampleInputPassword1">
							invoiceNo
						</label>
						<input v-model="student.invoiceNo" type="text" class="form-control m-input m-input--air" id="inputRemarks" >
					</div>
					<div class="form-group m-form__group">
							<label for="exampleInputEmail1">
								File Browser
							</label>
							<div></div>
							<label class="custom-file">
								<input @change="handleFileUpload()" ref="invoice" class="file-input" type="file" accept="application/pdf">
								<span class="custom-file-control"></span>
							</label>
					</div>
				</div>
				<div class="m-portlet__foot m-portlet__foot--fit">
					<div class="m-form__actions">
						<button @click="updateUserPayment" type="submit" class="btn btn-brand">
							Submit
						</button>
						<button @click="deleteUserPayment" type="reset" class="btn btn-secondary">
							Delete
						</button>
					</div>
				</div>
			</form>
			<!--end::Form-->
		</div>
		<!--end::Portlet-->


</template>

<script>
export default {
	data() {
		return {
			student: null,
			image:null,
			file: null,
		}
	},

	mounted(){
		this.fetchStudent();
	},

	methods:{

		fetchStudent() {

			axios
              .get("users_payments/"+this.$route.params.payment+"/edit/"+this.$route.params.type)
              .then(response => {
                    this.student = response.data;
              })
              .catch(error => {
                // console.log(error);
              });


		},	

		handleFileUpload() {
			console.log(this.$refs.invoice.files[0]);
			this.file = this.$refs.invoice.files[0];

			console.log('',this.file);
		},

		updateUserPayment() {

			this.updateInvoice();
			
			let data = {
							paymentmethod: this.student.method,
							amount: this.student.amount,
							remarks: this.student.remarks,
							invoice_no: this.student.invoiceNo,
							invoice: this.file
						}

			axios.put('users_payments/'+this.$route.params.payment+'/'+this.$route.params.type,data)
			.then((response => {
				this.fetchStudent();
			}))
			.catch(error => {

			});

		},

		updateInvoice()
		{

			let formData = new FormData();
			 formData.append('invoice', this.file);
		
			axios.post('users_payments/'+this.$route.params.payment+'/invoice/'+this.$route.params.type,formData,{
                headers: {
                    'Content-Type': 'multipart/form-data'
                }})
			.then((response => {
				this.fetchStudent();
			}))
			.catch(error => {

			});
			
		},

		deleteUserPayment() {
			axios.delete('users_payments/'+this.$route.params.payment+'/'+this.$route.params.type)
			.then((response => {
				router.push({name: 'payments-view'});
			}))
			.catch((error) =>{

			});

		},

		onFileChange(e) {

			var image = new Image();
			var reader = new FileReader();
			var vm = this;

			reader.onload = (e) => {
				vm.image = e.target.result;
			};

			var files = e.target.files || e.dataTransfer.files;
			
			reader.readAsDataURL(files[0]);

		},

		filesChange(fileName,fileList){
			console.log(fileList);
			
			this.file = fileList;
		},

	},

}
</script>

<style>

</style>
