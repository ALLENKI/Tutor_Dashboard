<template>
  <div>

    <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible m--margin-bottom-30" role="alert">
        <div class="m-alert__icon">
            <i class="flaticon-exclamation m--font-brand"></i>
        </div>
        <div class="m-alert__text">
            <h1> Invoice </h1>
        </div>
	</div>

    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--bordered m-portlet--rounded  m-portlet--last">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        <img src="https://res.cloudinary.com/ahamlearning/image/upload/c_fit,h_50,q_auto:eco,f_auto/v1466848309/logo_huge_lfpjpa.png" alt="" srcset="">
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                  <li class="m-portlet__nav-item">
                        <strong><marK>To:</mark> {{ invoice.user.name }}</strong>
                        <br>
                        <strong><mark>Email:</mark> {{ invoice.user.email }}</strong>
                  </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">

            <!--begin: Datatable -->
                <table class="table" id="html_table" width="100%">
                    <thead>
                        <tr>
                            <th title="Field #1">
                                Date
                            </th>
                            <th title="Field #1">
                                Invoice No
                            </th>
                            <th title="Field #2">
                                Credits
                            </th>
                            <th title="Field #3">
                                Rate
                            </th>
                            <th title="Field #4">
                                Amount
                            </th>
                        </tr>
                    </thead>
                    <tbody v-if="invoice != null">
                        <tr>
                            <td>
                               {{ invoice.added_on}}
                            </td>
                            <td>
                               {{ invoice.invoice_no}}
                            </td>
                            <td>
                                {{ invoice.credits }}
                            </td>
                            <td>
                                <!-- {{ invoice.price }} -->
                            </td>
                            <td>
                                {{ invoice.price }}
                            </td>
                            
                        </tr>
                    </tbody>

                </table>
            <!--end: Datatable -->



        </div>
        <div class="m-portlet__foot">
            <div class="row align-items-center">
                <div class="col-lg-6 m--valign-middle">
                    Total : <span class="m-badge m-badge--warning m-badge--wide"> {{ invoice.price }} </span>
                    
                    Including GST.
                </div>

                <div class="col-lg-6 m--align-right">
                    Invoice amount: <span class="m-badge m-badge--warning m-badge--wide"> {{ invoice.price }}</span> 
                </div>
            </div>
        </div>
    </div>
    <!--end::Portlet-->



  </div>
</template>

<script>
export default {
    data(){
        return{
            invoice: null,
            table: null,
        }
    },
    mounted() {
        let vm = this;
        $(function() {
            vm.getInvoice();
        });

    },
     methods: {

        getInvoice() {

            axios
            .get("users_payments/"+this.$route.params.payment+"/invoice/"+this.$route.params.type)
            .then(response => {
                    this.invoice = response.data;
                    console.log(response.data);
                    this.dataTable();
            })
            .catch(error => {
                // console.log(error);
            });

        },	

         dataTable() {

            this.table =  $('.m-datatable').mDatatable({
                
                    search: {
                        input: $('#paymentsSearch')
                    },
                
                    columns: [
                            {
                                field: "added_on",
                            },  
                            {
                                field: "credits",
                            }, 
                    ],

            });

        },

    },
    

}
</script>

<style>

</style>
