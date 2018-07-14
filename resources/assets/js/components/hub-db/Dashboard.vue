<template>

    <div class="m-container">

        <!-- analytics -->
        <div class="m-portlet m-portlet--mobile">

            <div class="m-portlet__body  m-portlet__body--no-padding">

                <div class="row m-row--no-padding m-row--col-separator-xl">

                    <div v-for="analytic in analytics" class="col-md-12 col-lg-6 col-xl-3">
                           
                            <!--begin::Total Profit-->
                            <div v-if="analytic.name" class="m-widget24 m-portlet.m-portlet--skin-dark">
                               
                                <div class="m-widget24__item">

                                    <h1 class="m-widget24__title">
                                        {{ analytic.name }}
                                    </h1>

                                    <br>
                                    
                                    <span class="m-widget24__desc">
                                    </span>

                                    <span  class="m-widget24__stats m--font-brand">
                                        <h1> {{ analytic.value }} </h1>
                                    </span>

                                </div>

                            </div>
                            <!--end::Total Profit-->
                            
                    </div>
                    
                </div>

            </div>
            
        </div>

        <!-- student Invitations -->
       <div class="m-portlet m-portlet--mobile">

           <div class="m-portlet__body  m-portlet__body--no-padding">

               <div class="row m-row--no-padding m-row--col-separator-xl">

                   <div v-if="learnerInvitations" v-for="invitation in learnerInvitations" class="col-md-6 col-lg-6 col-xl-3">
                        
                           <div class="m-widget24 m-portlet.m-portlet--skin-dark">

                               <div class="m-widget24__item">

                                   <h1 class="m-widget24__title">
                                       {{ invitation.name }}
                                   </h1>

                                   <br>
                                  
                                   <span class="m-widget24__desc">
                                   </span>

                                   <span  class="m-widget24__stats m--font-brand">
                                       <h1> {{ invitation.value }} </h1>
                                   </span>

                               </div>

                           </div>
                           <!--end::Total Profit-->
                          
                   </div>
                  
               </div>

           </div>
          
       </div>

        <!-- Filters, units-->
        <div class="m-portlet m-portlet--mobile" v-if="hub">

            <div class="m-portlet__head">

                <div class="m-portlet__head-caption">

                    <div class="m-portlet__head-title">

                        <span class="m-portlet__head-icon">

                            <a href="#" @click="previous" class="btn btn-outline-primary m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--outline-2x m-btn--pill m-btn--air">
                                        <i class="la la-arrow-left"></i>
                            </a>

                        </span>

                        <div class="m-portlet__head-text">

                            <div class="">
                                <el-date-picker
                                    v-model="date"
                                    type="date"
                                    placeholder="Pick a day">
                                </el-date-picker>
                            </div>

                        </div>

                        <span class="pl-3 m-portlet__head-icon">

                            <a href="#" @click="next" class="btn btn-outline-primary m-btn--custom m-btn m-btn--icon m-btn--icon-only m-btn--outline-2x m-btn--custom m-btn--pill m-btn--air">
                                <i class="la la-arrow-right"></i>
                            </a>

                        </span>

                        <span class="m-portlet__head-icon">
                            
                                <a href="#" @click="reloadOnPicker" class="btn btn-secondary m-btn m-btn--custom m-btn--icon m-btn--pill m-btn--air">

                                    <span>
                                        <i class="fa flaticon-search-magnifier-interface-symbol"></i>
                                        <span>
                                            Search
                                        </span>
                                    </span>

                                </a>

                        </span>
                        
                    </div>

                </div>


                <div class="m-portlet__head-tools">
                    
                </div>

            </div>
        
            <div class="">

                <div class="">

                    <div class="panel panel-default">
                            
                        <div v-if="hub" class="panel-body">

                            <LocationTimeUnits  v-bind:clocation="hub.slug" :aham_units="from6Classes" bgColor="#6699cc" btnColor="#7ecdf7" title="Classes from 06:00 to 08:00"></LocationTimeUnits>

                            <LocationTimeUnits  v-bind:clocation="hub.slug" :aham_units="from8Classes" bgColor="#6699cc" btnColor="#7ecdf7" title="Classes from 08:00 to 10:00"></LocationTimeUnits>

                            <LocationTimeUnits v-bind:clocation="hub.slug" :aham_units="from10Classes" bgColor="#6699cc" btnColor="#7ecdf7" title="Classes from 10:00 to 12:00"></LocationTimeUnits>

                            <LocationTimeUnits v-bind:clocation="hub.slug" :aham_units="from12Classes" bgColor="#6699cc" btnColor="#7ecdf7" title="Classes from 12:00 to 14:00"></LocationTimeUnits>

                            <LocationTimeUnits v-bind:clocation="hub.slug" :aham_units="from14Classes" bgColor="#6699cc" btnColor="#7ecdf7" title="Classes from 14:00 to 16:00"></LocationTimeUnits>

                            <LocationTimeUnits v-bind:clocation="hub.slug" :aham_units="from16Classes" bgColor="#6699cc" btnColor="#7ecdf7" title="Classes from 16:00 to 18:00"></LocationTimeUnits>

                            <LocationTimeUnits v-bind:clocation="hub.slug" :aham_units="from18Classes" bgColor="#6699cc" btnColor="#7ecdf7" title="Classes from 18:00 to 20:00"></LocationTimeUnits> 
                           

                        </div>

                    </div>

                </div>

            </div>

        </div>
        
         <!-- DataTables -->
        <div class="row">
            <div class="col-lg-12">

                <div class="m-portlet m-portlet--mobile">

                    <div class="m-portlet__head">

                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Students	
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
                                                <input type="text" class="form-control m-input" placeholder="Search..." id="learnerSearch">
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
                            <div class="m_datatable" id="learnerTable">
                            </div>
                        <!--end: Datatable -->
                        

                    </div>

				</div>
                
            </div>
        </div>

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
import store from "../../store"
import LocationTimeUnits from "./classes/LocationHome";
import axios from "axios";

export default {
    
    props:['hub'],
    data() {

        return {
            date: null, 

            analytics: null,
            learnerInvitations: null,

            from6Classes:[],
            from8Classes: [],
            from10Classes: [],
            from12Classes: [],
            from14Classes: [],
            from16Classes: [],
            from18Classes: [],

            learnerTable: null,
            tutorTable: null,

        }

    },
    components: {
        LocationTimeUnits,
    },
    mounted() {

        store.dispatch("setHeading", "Admin Dashboard:  Classes Today");
        store.dispatch("clearBreadcrumb");
        store.dispatch("", "");
            
        this.date = moment().format('Y-MM-DD');

        let vm = this;
        setTimeout(() => {

            vm.getClass(vm.hub.slug,vm.date);
            this.getAnalytics(this.hub.slug);

                vm.learnerDatatable();
                vm.tutorDataTable();    

        },2000);

    },
    methods: {

            learnerDatatable(){

                // Learner Table
                this.learnerTable =  $('#learnerTable').mDatatable({

                        data: {
                            type: 'remote',
                            source: {
                                read: {
                                    method: 'GET',
                                    url: window.location.origin+"/ahamapi/hub/learner-by-date?location="+this.hub.slug+"&date="+this.date
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
                            input: $('#learnerSearch')
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
                    
                        columns: 
                                [
                                    {
                                        field: "id",
                                        title: "#",
                                        filterable: true,
                                        width: 40,
                                        textAlign: 'center',
                                        type: 'number'
                                    },
                                    {
                                        field: "avatar",
                                        title: "Image",
                                        filterable: true,
                                        textAlign: 'center'
                                    },  
                                    {
                                        field: "student",
                                        title: "Student Name",
                                    },  
                                    {
                                        field: "class_code",
                                        title: "Class Code",
                                    },
                                    {
                                        field: "class_name",
                                        title: "Class Title",
                                    },                    
                                ]

                });

            },

            tutorDataTable(){

                // Tutor Table
                this.tutorTable =  $('#tutorTable').mDatatable({

                    "serverSide": true,
                    data:  {
                                type: 'remote',
                                source: {
                                    read: {
                                        method: 'GET',
                                        url: window.location.origin+"/ahamapi/hub/tutor-by-date?location="+this.hub.slug+"&date="+this.date
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

                    "columns": [
                                    {
                                        field: "id",
                                        title: "#",
                                    },
                                    {
                                        field: "avatar",
                                        title: "Image",
                                    },  
                                    {
                                        field: "teacher",
                                        title: "Tutor Name",
                                    },  
                                    {
                                        field: "class_code",
                                        title: "Class Code",
                                    },
                                    {
                                        field: "class_name",
                                        title: "Class Title",
                                    },  
                                ]

                }); 

            },

            reloadOnPicker() {
            this.date = moment(this.date).format('Y-MM-DD');
            console.log('reloading CLICKED');

            let vm = this;
                this.getClass(vm.hub.slug,vm.date);
                this.getAnalytics(this.hub.slug);

                this.learnerTable.destroy();
                this.tutorTable.destroy();
                this.learnerDatatable();
                this.tutorDataTable();
            
                
            },

            previous() {

                this.date = moment(this.date).add(-1, 'days').format('YYYY-MM-DD');
                this.selectedDay = moment(this.date,'YYYY-MM-DD').format('dddd');
                this.reloadOnPicker();

            },

            next() {

                this.date  = moment(this.date).add(1,'days').format('YYYY-MM-DD');
                this.selectedDay = moment(this.date,'YYYY-MM-DD').format('dddd');
                this.reloadOnPicker();

            },

            getClass(slug) {

                axios
                    .get("hub/get-classes-in-timings/"+slug+"?date="+this.date+"&from_time=06:00&to_time=07:59")
                    .then( response => {
                        this.from6Classes  = response.data.data;
                    })
                    .catch( error => {

                    });

                axios
                    .get("hub/get-classes-in-timings/"+slug+"?date="+this.date+"&from_time=08:00&to_time=09:59")
                    .then( response => {
                        this.from8Classes  = response.data.data;
                    })
                    .catch( error => {

                    });

                axios
                    .get("hub/get-classes-in-timings/"+slug+"?date="+this.date+"&from_time=10:00&to_time=11:59")
                    .then( response => {
                        this.from10Classes  = response.data.data;
                    })
                    .catch( error => {

                    });

                axios
                    .get("hub/get-classes-in-timings/"+slug+"?date="+this.date+"&from_time=12:00&to_time=13:59")
                    .then( response => {
                        this.from12Classes  = response.data.data;
                    })
                    .catch( error => {

                    });

                axios
                    .get("hub/get-classes-in-timings/"+slug+"?date="+this.date+"&from_time=14:00&to_time=15:59")
                    .then( response => {
                        this.from14Classes  = response.data.data;
                    })
                    .catch( error => {

                    });

                axios
                    .get("hub/get-classes-in-timings/"+slug+"?date="+this.date+"&from_time=16:00&to_time=17:59")
                    .then( response => {
                        this.from16Classes  = response.data.data;
                    })
                    .catch( error => {

                    });

                axios
                    .get("hub/get-classes-in-timings/"+slug+"?date="+this.date+"&from_time=18:00&to_time=20:00")
                    .then( response => {
                        this.from18Classes  = response.data.data;
                    })
                    .catch( error => {

                    });

            },

            getAnalytics() {

                axios
                    .get("hub/get-analytics/"+this.hub.slug+"?date="+this.date)
                    .then( response => {

                        this.analytics = response.data;

                        this.learnerInvitations = response.data[4]['learnerInvitations'];

                    })
                    .catch(error => {

                    });

            }

            

    }

};
</script>
