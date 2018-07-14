<template>
  

  <div class="row">

        <div v-if="learnerProfile" class="col-xl-12">
            <!--begin:: Widgets/Support Tickets -->
            <div class="m-portlet m-portlet--full-height ">

                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                              <mark>  {{ this.learnerProfile.name }} </mark>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="m-portlet__body">

                    <!--begin::Form-->
                    <form class="m-form m-form--fit m-form--label-align-right">
                        <div  class="m-portlet__body">
                            <div v-if="formErrors" class="form-group m-form__group m--margin-top-10">
                                <div class="alert m-alert m-alert--danger" role="alert">
                                    
                                    <div  class="m-alert m-alert--outline m-alert--outline-2x alert alert-warning alert-dismissible fade show" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                        <strong>
                                            Warning!
                                        </strong>
                                      <!-- errors here -->
									</div>


                                </div>
                            </div>

                            <div class="form-group m-form__group">
                                <label for="exampleInputEmail1">
                                    Email address
                                </label>
                                <input type="text" class="form-control m-input m-input--air" id="exampleInputEmail1" v-model="learnerProfile.email" aria-describedby="emailHelp" placeholder="Enter email">
                                <span class="m-form__help">
                                  
                                </span>
                            </div>

                             <div class="form-group m-form__group">
                                <label for="learnerName">
                                    Name
                                </label>
                                <input  type="text" class="form-control m-input m-input--air" id="learnerName" v-model="learnerProfile.name" aria-describedby="learnerNameHelp" placeholder="Enter Name">
                                <span class="m-form__help">
                                    
                                </span>
                            </div>

                             <div class="form-group m-form__group">
                                <label for="mobileNumber">
                                    Mobile
                                </label>
                                <input type="number" class="form-control m-input m-input--air" id="mobileNumber" v-model="learnerProfile.mobile" aria-describedby="learnerMobileHelp" placeholder="Enter Mobile Number">
                                <span class="m-form__help">
                                    
                                </span>
                             </div>

                             <div class="form-group m-form__group">
                                <label for="grade">
                                    Grade
                                </label>
                                <select  class="form-control m-input m-input--air" id="learnerGrade">
                                    <option>
                                        Middle School(Grade 5-8)
                                    </option>
                                    <option>
                                        High School(Grade 9-12
                                    </option>
                                    <option>
                                        Under Grad
                                    </option>
                                    <option>
                                        Grad or Higher
                                    </option>
                                    <option>
                                        Working Professional
                                    </option>
                                    <option>
                                        Other
                                    </option>
                                </select>
                             </div>

                             <div class="form-group m-form__group">
                                <label for="learnerName">
                                    Curriculum
                                </label>
                                <input type="text" class="form-control m-input m-input--air" id="learnerCurriculum" v-model="learnerProfile.curriculum" aria-describedby="learnerCurriculumHelp" placeholder="Enter Curriculum">
                                <span class="m-form__help">
                                    
                                </span>
                            </div>

                             <div class="form-group m-form__group">
                                <label for="learnerName">
                                    School
                                </label>
                                <input type="text" class="form-control m-input m-input--air" id="learnerSchool" v-model="learnerProfile.school" aria-describedby="learnerSchoolHelp" placeholder="Enter School">
                                <span class="m-form__help">
                                    
                                </span>
                            </div>

                            <div class="form-group m-form__group">
                                <label for="exampleSelect1">
                                    Community
                                </label>
                                 <input type="text" class="form-control m-input m-input--air" id="learnerCommunity" v-model="learnerProfile.community" aria-describedby="learnercommunityHelp" placeholder="Enter community">
                                <span class="m-form__help">
                                    
                                </span>
                                
                            </div>
                            
                            <div class="form-group m-form__group m-checkbox-inline">
                                <mark> <label>Days of Week</label> </mark>
                                <br>
                                <label class="m-checkbox m-checkbox--solid m-checkbox--air    m-checkbox--success" v-for="day_of_week in days_of_week">
                                    <input  type="checkbox" v-model="selected_days_of_week" :value="day_of_week.value">
                                    {{ day_of_week.label }}  
                                    <span></span>
                                </label> 
                            </div>
                            
                            <div class="form-group m-form__group">
                                <mark> <label>Time of Day</label> </mark>
                                <br>

                                <table class="table table-bordered">

                                    <thead>
                                        <th>Day</th>
                                        <th v-for="time_of_day in times_of_day">
                                            {{ time_of_day.label }}
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr v-for="day_of_week in days_of_week">
                                        <td>
                                            {{ day_of_week.label }}
                                        </td>
                                        <td v-for="time_of_day in times_of_day">

                                            <label class="m-checkbox m-checkbox--solid m-checkbox--air m-checkbox--success">

                                                <input type="checkbox" v-model="weekly_times_of_day[day_of_week.value]" :value="time_of_day.value">

                                                <span></span>
                                            </label>

                                        </td>
                                        </tr>
                                    </tbody>

                                </table>

                            </div>

                        </div>

                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">

                                <button type="reset" @click="submit" class="btn btn-brand">
                                    Submit
                                </button>
                                
                            </div>
                        </div>

                    </form>
                    <!--end::Form-->

                </div>
            </div>
            <!--end:: Widgets/Support Tickets -->
        </div>


    </div>
        
</template>

<script>
import store from "../../../store";
import axios from 'axios';
// import VeeValidate from 'vee-validate';

// Vue.use(VeeValidate);

export default {

    data() {
        return {
           formErrors: null,
            learnerProfile: null,
            times_of_day:[
                {label: 'Early Morning', title: 'Early Morning', value: 'early_morning'},
                {label: 'Morning', title: 'Morning', value: 'morning'},
                {label: 'Afternoon', title: 'Afternoon', value: 'afternoon'},
                {label: 'Evening', title: 'Evening', value: 'evening'},
                {label: 'Late Evening', title: 'Late Evening', value: 'late_evening'}
            ],
            selected_times_of_day: [],
            selected_days_of_week: [],
            days_of_week:[
                {label: 'Sunday', title: 'Sunday', value: 'sunday'},
                {label: 'Monday', title: 'Monday', value: 'monday'},
                {label: 'Tuesday', title: 'Tuesday', value: 'tuesday'},
                {label: 'Wednesday', title: 'Wednesday', value: 'wednesday'},
                {label: 'Thursday', title: 'Thursday', value: 'thursday'},
                {label: 'Friday', title: 'Friday', value: 'friday'},
                {label: 'Saturday', title: 'Saturday', value: 'saturday'}
            ],

            weekly_times_of_day:{
                'sunday' : [],
                'monday' : [],
                'tuesday' : [],
                'wednesday' : [],
                'thursday' : [],
                'friday' : [],
                'saturday' : [],
            },

        }
    },

    mounted() {
        store.dispatch("setHeading", 'Manage Learner Details');

        this.profile();
    },

    methods: {

        profile() { 
            axios.get("hub/learner-profile/"+this.$route.params.learner)
            .then((response) => {
                this.learnerProfile = response.data;
                this.selected_days_of_week = response.data.selected_days_of_week;
                this.weekly_times_of_day = response.data.selected_times_of_day;
            })
            .catch((error) => {

            })
        },

        submit() {
           let payload = {
                Email: this.learnerProfile.email,
                Name: this.learnerProfile.name,
                Mobile: this.learnerProfile.mobile,
                Grade: this.learnerProfile.grade,
                Curriculum: this.learnerProfile.curriculum,
                Community: this.learnerProfile.community,
                School: this.learnerProfile.school,
                Day_of_Week: this.selected_days_of_week,
                Time_of_Day: this.weekly_times_of_day,
            };

            axios.post("hub/learner-profile/"+this.$route.params.learner,{payload})
            .then((response) => {
                this.profile();
                this.$router.push({
                    name: 'learner-details'
                });
            })  
            .catch((error) => {
                
            })
        },

    },

}
</script>

<style>

    

</style>
