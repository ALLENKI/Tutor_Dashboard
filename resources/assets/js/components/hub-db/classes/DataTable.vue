<template>

<!-- DataTables -->

    <div class="row">
        <div class="col-lg-12">

            <div class="m-portlet m-portlet--mobile">

                <div class="m-portlet__head">

                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                {{ this.title }}	
                            </h3>
                        </div>
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
                                            <input type="text" class="form-control m-input" placeholder="Search..." id="search">
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
                        <div class="m_datatable" id="table">
                        </div>
                    <!--end: Datatable -->
                    

                </div>

            </div>
            
        </div>
    </div>
  
</template>

<script>
export default {

    props:['hub','title','url','columns'],

    data(){
        return {
            date: null,
        }
    },

    mounted() {

        setTimeout(() => {
             this.fetch();
        }, 3000);
       
    },

    methods: {

        fetch(){

            // Learner Table
            var datatable =  $('#table').mDatatable({

                    data: {
                        type: 'local',
                        source: {
                            read: {
                                method: 'GET',
                                url: this.url,
                                },
                                    params: {
                                    // custom query params
                                    query: {
                                        search: '',
                                    }
                                },
                        },   
                        pageSize: 10,                
                    },
                    
                    // layout definition
                    layout: {
                        theme: 'default', // datatable theme
                        class: '', // custom wrapper class
                        scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                        height: 450, // datatable's body's fixed height
                        footer: false // display/hide footer
                    },

                    search: {
                        input: $('#search')
                    },

                    // column sorting
                    sortable: true,

                    pagination: true,

                    toolbar: {
                        // toolbar items
                        items: {
                            // pagination
                            pagination: {
                                // page size select
                                pageSizeSelect: [1, 10, 20, 30, 50, 100]
                            }
                        }
                    },
                    
                    columns: this.columns,
                            

            });

        },

    },

}
</script>
