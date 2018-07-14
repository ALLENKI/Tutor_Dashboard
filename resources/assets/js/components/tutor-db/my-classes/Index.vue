<template>
  <div>
     <div class="row">
        <div class="m-portlet col-md-12">
                  <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                      <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                          My Classes
                        </h3>
                      </div>
                    </div>
                  </div>
                  <div class="m-portlet__body">
                    <ul class="nav nav-tabs nav-fill" role="tablist">

                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#today" @click = "getTodayClasses">
                          <i class="m-menu__link-icon flaticon-bell"></i>
                            TODAY
                        </a>
                      </li>

                      <li class="nav-item"> 
                        <a class="nav-link" data-toggle="tab" href="#upcoming" @click =" getUpcomingClasses">
                          <i class="m-menu__link-icon flaticon-paper-plane"></i>
                            UPCOMING
                        </a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#completed" @click ="getCompletedClasses">
                          <i class="m-menu__link-icon flaticon-gift"></i>
                          COMPLETED
                        </a>
                      </li>

                    </ul>
                    <div class="tab-content">

                      <div class="tab-pane active" id="today" role="tabpanel" aria-expanded="false">
                        <div class="row">                          
                            <div class="col-md-3" v-for="ahamclass in todayclasses">
                                <Card1 :ahamclass='ahamclass'></Card1>
                            </div>
                            <div v-if="todayclasses.length <= 0" class="alert m-alert--default col-md-12" role="alert"> No Ongoing Classes </div>
                         

                            <div class="clearfix"></div>  

                            <el-button type="info" plain icon="el-icon-search" @click="getTodayClasses(pagination.current_page+1)"  v-if="pagination && pagination.current_page != pagination.total_pages">
                              Load More From Page {{ pagination.current_page+1 }}
                            </el-button>
 
                        </div>
                      </div>

                      <div class="tab-pane" id="upcoming" role="tabpanel">
                        <div class="row">                            
                            <div class="col-md-3" v-for="ahamclass in upcomingclasses">
                                <Card1 :ahamclass='ahamclass'></Card1>
                            </div>    
                             <div v-if="upcomingclasses.length <= 0 " class="alert m-alert--default col-md-12" role="alert"> No Upcoming Classes</div>
                            
                            <div class="clearfix"></div>

                            <el-button type="info" plain icon="el-icon-search" @click="getUpcomingClasses(pagination.current_page+1)"  v-if="pagination && pagination.current_page != pagination.total_pages">
                              Load More From Page {{ pagination.current_page+1 }}
                            </el-button>
                                                        
                        </div>
                      </div>

                      <div class="tab-pane" id="completed" role="tabpanel">
                        <div class="row">
                            <div class="col-md-3" v-for="ahamclass in completeclasses"> 
                              <Card1 :ahamclass='ahamclass'></Card1>
                            </div>
                            <div v-if="completeclasses.length <= 0 " class="alert m-alert--default col-md-12" role="alert"> No Complete Classes</div>
                            <div class="clearfix"></div>
                            <el-button type="info" plain icon="el-icon-search" @click="getCompletedClasses(pagination.current_page+1)"  v-if="pagination && pagination.current_page != pagination.total_pages">
                              Load More From Page {{ pagination.current_page+1 }}
                            </el-button>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>
                  
                </div>
        
            
        
      </div>
      
 </div>
</template>

<script>
import store from "../../../store";
import Card1 from "./Card1";
export default {
  components: {
    Card1,
  },
  data() {
    return {
       
         student : [],
        upcomingclasses : [],
        completeclasses : [],
        todayclasses : [],
        pagination : false,  
    };
  },
  mounted() {
    
    this.getTodayClasses(); 
  },
  watch: {
  },
  methods: {

      getTodayClasses(page)
      {
         page = page || 1;
        let payload = {'page' : page ,'filter' : 'on-going'};
        axios
              .get("tutor/classes/",{params : payload})
              .then(response => {
                
                if (page>1) {
                  this.todayclases = _.union(this.todayclasses, response.data.data);
                }else {
                  this.todayclasses = response.data.data;
                }
               console.log('todayclasses',this.todayclasses);
                this.pagination = response.data.meta.pagination;
               console.log('pagination',this.pagination);
                
              })
              .catch(error => {
                // console.log(error);
              });
      },
      getUpcomingClasses(page)
      {
        page = page || 1;
        let payload = {'page' : page ,'filter' : 'upcoming'};
        axios
              .get("tutor/classes/",{params : payload})
              .then(response => {
                
                if (page>1) {
                  this.upcomingclasses = _.union(this.upcomingclasses, response.data.data);
                }else {
                  this.upcomingclasses = response.data.data;
                }
                this.pagination = response.data.meta.pagination;
                
              })
              .catch(error => {
                // console.log(error);
              });
            },
      getCompletedClasses(page)
      {
         page = page || 1;
        let payload = {'page' : page ,'filter' : 'completed'};
        axios
              .get("tutor/classes/",{params : payload})
              .then(response => {
                
                if (page>1) {
                  this.completeclasses = _.union(this.completeclasses, response.data.data);
                }else {
                  this.completeclasses = response.data.data;
                }
                //console.log('completeclasses',this.completeclasses);
                this.pagination = response.data.meta.pagination;
                //console.log('pagination',this.pagination);
                
              })
              .catch(error => {
                // console.log(error);
              });
      },
   


}
}
</script>



