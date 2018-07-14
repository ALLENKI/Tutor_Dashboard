<template>

    <div v-if="hub != null">

        <!-- DataTables -->
        <div class="row">
            <div class="col-lg-12">

                <div class="m-portlet m-portlet--mobile">

                    <div class="m-portlet__head">

                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Learners	
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
                                                    <input v-model="activeLearners" type="checkbox"  name="active">
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
                                                <input v-model="inActiveLearners" type="checkbox" name="inactive">
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

    </div>
    
</template>

<script>
import store from "../../../store";

export default {

    props:['hub'],

    data() {
        return {
            date: null,
            activeLearners: false,
            inActiveLearners: false,
            url: '',
            table: null,
        }
    },
    
    components: {
    },

    watch: {

        activeLearners: function() {

            if(this.activeLearners == true) {
                this.url += "&active=true";
                this.inActiveLearners = false;
                console.log(this.activeLearners)
            } else {
                this.url = window.location.origin+"/ahamapi/hub/all-learners?location="+this.hub.slug;
            }
        
           this.table.destroy();
           this.learnerDatatable();
        },

        inActiveLearners: function() {

            if(this.inActiveLearners == true) {
                this.url += "&active=false";
                this.activeLearners = false;
                console.log(this.inActiveLearners)
            } else {
                this.url = window.location.origin+"/ahamapi/hub/all-learners?location="+this.hub.slug;
            }

            this.table.destroy();
            this.learnerDatatable();
        },

    },
    
    mounted() {
        store.dispatch("setHeading", "All Learners");
        this.date = moment().format('Y-MM-DD');
        
        setTimeout(() => {
             this.url = window.location.origin+"/ahamapi/hub/all-learners?location="+this.hub.slug;
             this.learnerDatatable();
        }, 3000);
       
    },

   methods:{

        learnerDatatable() {

            // Learner Table
            this.table =  $('#learnerTable').mDatatable({

                    data: {
                        type: 'remote',
                        source: {
                            read: 
                                {
                                    method: 'GET',
                                    url: this.url,
                                },
                                params: 
                                {
                                    // custom query params
                                    query: {
                                        search: '',
                                    }
                                },
                        },   
                        pageSize: 10,                
                    },
                
                    search: {
                        input: $('#learnerSearch')
                    },
                
                    columns: [
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
                                {
                                    field: "credits",
                                    title: "Credits",
                                    filterable: true,
                                    textAlign: 'center',
                                    type: 'number',
                                    template: function(t){
                                        return '<span class="m-badge m-badge--brand m-badge--wide">' + t.credits + "</span>";
                                    }
                                },                   
                    ],

            });

        },

   }
  
}
</script>
