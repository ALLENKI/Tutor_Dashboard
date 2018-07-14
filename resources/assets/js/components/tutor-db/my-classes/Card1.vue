<template>
             
<div class="m-portlet">

     <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    {{ahamclass.class_code}}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                    
                </li>
            </ul>
        </div>
    </div>
 
     <div class="m-portlet__body" style="padding: 0.2rem 1.2rem;">
        
        <div class="m-widget19">

            <div class="m-widget19__content">

                <div class="m-widget19__header">

                    <div class="m-widget19__user-img">
                        <img class="m-widget19__img" :src="ahamclass.ahamClass.topic.avatar" alt="">
                    </div>

                    <div class="m-widget19__info" style="line-height: 1.5rem;height:62px;">

                        <span class="m-widget19__username">
                          <router-link :to="{path: '/my-classes/classpage/'+this.ahamclass.class_id  }">
                                {{ ahamclass.unit_name }}  of {{ ahamclass.ahamClass.topic.name }}                               
                          </router-link>
                        </span>

                         <span class="m-widget4__status m--font-info" style="display: flex">
							    {{ ahamclass.status }}
					    </span>

                    </div>
 
                </div>

                 <div class="m-widget19__body">

					<div class="m-list-search">
						<div class="m-list-search__results">

                            <span href="#" class="m-list-search__result-item" v-if="ahamclass.unit_teacher">
                            <span class="m-list-search__result-item-icon">
                                <i class="flaticon-calendar-1 m--font-warning"></i>
                            </span>
                            <span class="m-list-search__result-item-text">
                                Tutors:
                                    {{ ahamclass.unit_teacher }}
                                
                            </span>
                            </span>
                            <span href="#" class="m-list-search__result-item" v-else>
                            <span class="m-list-search__result-item-icon">
                                <i class="flaticon-calendar-1 m--font-warning"></i>
                            </span>
                            <span class="m-list-search__result-item-text">
                                Tutors:
                                Not Assigned
                            </span>
                            </span>


							<span href="#" class="m-list-search__result-item" v-if="ahamclass.ahamClass.start_date">
							<span class="m-list-search__result-item-icon">
								<i class="flaticon-calendar-1 m--font-warning"></i>
							</span>
							<span class="m-list-search__result-item-text">
								Start Date: On {{ moment(ahamclass.ahamClass.timings.date).format('Do MMMM YYYY') }}<br> From {{ ahamclass.start_time }} To {{ ahamclass.end_time }}
							</span>
							</span>
                            <span href="#" class="m-list-search__result-item" v-else>
                            <span class="m-list-search__result-item-icon">
                                <i class="flaticon-calendar-1 m--font-warning"></i>
                            </span>
                            <span class="m-list-search__result-item-text">
                                Not Scheduled
                            </span>
                            </span>

                            <span href="#" class="m-list-search__result-item">
                            <span class="m-list-search__result-item-icon">
                                <i class="flaticon-calendar-1 m--font-warning"></i>
                            </span>
                            <span class="m-list-search__result-item-text">
                                Enrollment: {{ ahamclass.enrolled }}/{{ ahamclass.maximum_enrollment }}
                            </span>
                            </span>

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
import moment from 'moment';
Vue.prototype.moment = moment;
export default {
  props: ['ahamclass'],
  components: {
    
  },
  data() {
    return {
        unit_date : null,        
    };
  },
  mounted() {
    
    this.unit_date = moment(this.ahamclass.ahamClass.timings.date).format('YYYY');
    //console.log("datee",this.unit_date);
  },
};
</script>