<template>
  <div>

        <!-- DataTables -->
        <div class="row">
            <div class="col-lg-12">

                <div class="m-portlet m-portlet--mobile">

                    <div class="m-portlet__head">

                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                   User Payments	
                                </h3>
                            </div>
                        </div>

                        <div class="m-portlet__head-tools">
                            <button @click.prevent.stop="exportCsv" type="button" class="btn m-btn--pill m-btn--air         btn-outline-warning m-btn m-btn--outline-2x ">
                                    <!-- <a :href=exportUrl>Export</a>	 -->
                                    Export
                            </button>
                        </div>

    
                    </div>

                    <div class="m-portlet__body">

                        <!--begin: Search Form -->
                        <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">

                            <div class="row align-items-center">
                                
                                <div class="col-xl-8 order-2 order-xl-1">

                                    <div class="form-group m-form__group row align-items-center">

                                        <div class="col-md-4">

                                            <div class="m-input-icon m-input-icon--left">
                                                <input type="text" class="form-control m-input" placeholder="Search..." id="paymentsSearch">
                                                <span class="m-input-icon__icon m-input-icon__icon--left">
                                                    <span>
                                                        <i class="la la-search"></i>
                                                    </span>
                                                </span>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                        <!--end: Search Form -->

                        <!--begin: Datatable -->
                        <table class="m-datatable" id="html_table" width="100%">

                            <thead>
                                <tr>
                                    <th title="Field #1">
                                        #
                                    </th>
                                    <th title="Field #2">
                                        Action
                                    </th>
                                    <th title="Field #3">
                                        Date
                                    </th>
                                    <th title="Field #4">
                                        Amount
                                    </th>
                                    <th title="Field #5">
                                        Invoice_no
                                    </th>
                                    <th title="Field #6">
                                        Email
                                    </th>
                                    <th title="Field #7">
                                        Student Name
                                    </th>
                                    <th title="Field #8">
                                        Payment Method
                                    </th>
                                    <th title="Field #9">
                                        Remarks
                                    </th>
                                    <th title="Field #10">
                                        type
                                    </th>
                                </tr>
                            </thead>
                            <tbody v-if="payments != null">
                                <tr v-for="item in payments">
                                    <td>
                                        {{item.id}}
                                    </td>
                                    <td>

                                        <router-link tag="a" :to="{ name: 'payment-edit', params: {payment: item.id,type: item.type} }">
                                            <i class="flaticon-edit-1"></i>
                                        </router-link>

                                    </td>
                                    <td>
                                        {{item.date}}
                                    </td>
                                    <td>
                                        {{item.amount}}
                                    </td>
                                    <td>
                                        <span v-if="item.invoice.url">
                                            <a :href="item.invoice.url">{{item.invoice.invoiceNo}}</a>
                                        </span>
                                        <span v-if="item.invoice.url == null">
                                            <router-link tag="a" :to="{ name: 'payment-invoice', params: {payment: item.id,type: item.type} }">
                                                {{item.invoice.invoiceNo}}
                                            </router-link>
                                        </span>
                                    </td>
                                    <td>

                                        <router-link tag="a" :to="{ name: 'learner-credits', params: {learner: item.studentId}}">
                                            {{ item.email }}
                                        </router-link>

                                    </td>
                                    <td>
                                        {{item.name}}
                                    </td>
                                    <td>
                                        {{item.method}}
                                    </td>
                                    <td>
                                        {{item.remarks}}
                                    </td>
                                    <td>
                                        {{item.type}}
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <!--end: Datatable -->
                        

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
            payments: null,
            exportUrl: null,
        }
    },

    mounted() {
       let vm = this;
        this.fetchPayments();

        setTimeout(() => {

            $(document).ready(function(){
               
                vm.dataTable();
                
            });

        },3000);

        this.exportUrl = location.origin+"/users_payments/export";
    },

    methods:{
        
        dataTable() {

            this.table =  $('.m-datatable').mDatatable({
                
                    search: {
                        input: $('#paymentsSearch')
                    },
                
                    columns: [
                                {
                                    field: "id",
                                },  
                                {
                                    field: "actions",
                                },              
                    ],

            });

        },

        fetchPayments() {
            axios
              .get('/users_payments/table1')
              .then(response => {
                    this.payments = response.data.data;
              })
              .catch(error => {
                // console.log(error);
              });
        },

        exportCsv() {
            // window.open(this.exportUrl, '_blank');

            axios
              .get('/users_payments/export')
              .then(response => {
                    // this.payments = response.data.data;
                    let blob = new Blob([response.data], { type:   'application/csv' } );
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'invoice.csv'
                    link.click()
              })
              .catch(error => {
                // console.log(error);
              });
        },

    },

}
</script>

<style>

</style>
