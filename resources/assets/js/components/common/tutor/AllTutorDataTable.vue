<template>

    <div v-if="hub != null || dashboard">

        <!-- DataTables -->
        <div class="row">
            <div class="col-lg-12">

                <div class="m-portlet m-portlet--mobile">

                    <div class="m-portlet__head">

                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Tutors	
                                </h3>
                            </div>
                        </div>

                        <div class="m-portlet__head-tools">
                            <div class="m-portlet__nav">

                                <div class="m-portlet__nav-item">
                                    Active
                                    <div class="m-list-settings__item">
                                        <span class="m-list-settings__item-label">
                                            
                                        </span>
                                        
                                            <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                                                <label>
                                                    <input type="checkbox" v-model="activeTutor" name="active">
                                                    <span></span>
                                                </label>
                                            </span>
                                    
                                    </div>
                                </div>

                                <div class="m-portlet__nav-item">
                                    In-Active
                                    <div class="m-list-settings__item">
                                        <span class="m-list-settings__item-label">
                                            
                                        </span>
                                        
                                            <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                                                <label>
                                                    <input type="checkbox" v-model="inActiveTutor" name="inactive">
                                                    <span></span>
                                                </label>
                                            </span>
                                    
                                    </div>
                                </div>

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
                                                <input type="text" class="form-control m-input" placeholder="Search..." id="tutorSearch">
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
                        <div class="m_datatable" id="tutorTable">
                        </div>
                        <!--end: Datatable -->
                        
                    </div>

                </div>
                
            </div>
        </div>

    </div>
    
</template>

<script>

import moment from 'moment';
import store from "../../../store";

export default {

    props:['hub','dashboard'],

    data() {

        return {
            date: null,
            activeTutor: null,
            inActiveTutor: null,
            table: null,
            url: '',
        }

    },

    watch: {

        activeTutor: function() {

            if(this.activeTutor == true) {

                this.url += "&active=true";
                console.log(this.activeTutor);

            } else {

                if (this.dashboard) {
                    this.url = window.location.origin+"/ahamapi/common/tutors/dataTable";
                } else {
                    this.url = window.location.origin+"/ahamapi/hub/all-tutors?location="+this.hub.slug;
                }

            }
        
           this.table.destroy();
           this.tutorDataTable();
        },

        inActiveTutor: function() {

            if (this.inActiveTutor == true) {
                this.url += "&active=false";
               
                console.log(this.inActiveTutor)
            } else {
                
                if (this.dashboard) {
                    this.url = window.location.origin+"/ahamapi/common/tutors/dataTable";
                } else {
                    this.url = window.location.origin+"/ahamapi/hub/all-tutors?location="+this.hub.slug;
                }

            }

            this.table.destroy();
            this.tutorDataTable();
        },

    },

    mounted() {

        store.dispatch("setHeading", "All Tutors");
        this.date = moment().format('Y-MM-DD');

        setTimeout(() => {

            if (this.dashboard) {
                this.url = window.location.origin+"/ahamapi/common/tutors/dataTable";
            } else {
                this.url = window.location.origin+"/ahamapi/hub/all-tutors?location="+this.hub.slug;
            }

            var vm = this;

            $(document).ready(function(){
               
                vm.tutorDataTable(vm);
                
            });

        }, 1000);

    },   

    methods: {

        tutorDataTable(vm) {

            // Tutor Table
            this.table =  $('#tutorTable').mDatatable({

                "serverSide": true,
                    data:  {
                            type: 'remote',
                            source: {
                                read: 
                                    {
                                        method: 'GET',
                                        url: vm.url,
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

                    search: {
                        input: $('#tutorSearch')
                    },

                    columns: [
                                {
                                    field: "code",
                                    title: "Code",
                                    filterable: true,
                                    textAlign: 'center',
                                    type: 'number'
                                },
                                {
                                    field: "name",
                                    title: "Name",
                                    filterable: true,
                                    textAlign: 'center'
                                },  
                                {
                                    field: "email",
                                    title: "Email",
                                },                     
                    ],

            }); 

        },

    }

}

</script>
