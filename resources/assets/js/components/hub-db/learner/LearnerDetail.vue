<template>

<div>
    
    <div class="row">

        <div class="col-xl-12">
            <!--begin:: Widgets/Support Tickets -->
            <div class="m-portlet m-portlet--full-height ">
                <div class="m-portlet__head">

                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                
                            </h3>
                        </div>
                    </div>

                    <div class="m-portlet__head-tools">

                        <router-link :to="{name: 'learner-details1',params: { } }" exact class="m-menu__item">

                            <a href="" class="m-menu__link ">
                                <i class="m-menu__link-icon flaticon-user"></i>

                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">

                                        <span class="m-menu__link-text">
                                        Manage
                                        </span>
                                        
                                    </span>
                                </span>
                                
                            </a>

                        </router-link>

                        <router-link :to="{name: 'wishlist',params: { } }" exact class="m-menu__item">

                            <a href="" class="m-menu__link">
                                <i class="m-menu__link-icon flaticon-user"></i>

                                <span class="m-menu__link-title">
                                    <span class="m-menu__link-wrap">

                                        <span class="m-menu__link-text">
                                        WishList
                                        </span>
                                        
                                    </span>
                                </span>
                                
                            </a>
                                                                                        
                        </router-link>

                    </div>

                </div>
                <div class="m-portlet__body">
                    <div class="m-widget3">
                        <div class="m-widget3__item">
                            <div class="m-widget3__header">
                                <div class="m-widget3__user-img">
                                    <img class="m-widget3__img" :src="this.learnerProfile.avatar" alt="">
                                </div>
                                <div class="m-widget3__info">
                                    <span class="m-widget3__username">
                                        {{this.learnerProfile.name}}
                                    </span>
                                    <br>
                                    <span class="m-widget3__time">
                                        <mark>Email: {{this.learnerProfile.email}}</mark> 
                                    </span>
                                    
                                    <span class="m-widget3__time">
                                        
                                            <span class="m-badge m-badge--danger m-badge--dot"></span>
                                        
                                    <mark>  Mobile: {{this.learnerProfile.mobile}} </mark>
                                    </span>

                                    <span class="m-widget3__time m-badge m-badge--warning m-badge--wide">
                                        
                                            <span class="m-badge m-badge--danger m-badge--dot"></span>
                                        
                                        Credits Remaining: {{this.learnerProfile.total_remaining}}
                                    </span>
                                </div>
                                <span class="m-widget3__status m--font-info">
                                    
                                </span>
                            </div>
                            <div class="m-widget3__body">
                                <p class="m-widget3__text">
                                    
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Support Tickets -->
        </div>

        <div class="col-12">

            <div class="m-portlet m-portlet--mobile">

                <div class="m-portlet__head">

                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Classes from {{ tomorrow }} to {{ week }} (NEXT WEEK)
                            </h3>
                        </div>
                    </div>
        
                </div>

                <div class="m-portlet__body">
                        <!--begin: Datatable -->
                        <table class="m-datatable" id="html_table" width="100%">

                            <thead>
                                <tr>
                                    <th title="Field #1">
                                        Date
                                    </th>
                                    <th title="Field #2">
                                        #
                                    </th>
                                    <th title="Field #3">
                                        Status
                                    </th>
                                    <th title="Field #4">
                                        Unit
                                    </th>
                                    <th title="Field #5">
                                        Topic
                                    </th>
                                    <th title="Field #6">
                                        Timing
                                    </th>
                                    <th title="Field #7">
                                        Classroom
                                    </th>
                                </tr>
                            </thead>
                        
                            <tbody v-if="nextWeek != null">
                                <tr v-for="item in nextWeek">
                                    <td>
                                        {{item.slot_id}}
                                    </td>
                                    <td>

                                        <router-link tag="a" :to="{ name: 'view-class', params: {class: item.aham_class.id} }">
                                            {{item.aham_class.code}}
                                        </router-link>

                                    </td>
                                    <td>
                                        {{item.aham_class.status}}
                                    </td>
                                    <td>
                                        {{item.class_unit.name}}
                                    </td>
                                    <td>
                                        {{item.aham_class.topic_name}}
                                    </td>
                                    <td>
                                        {{item.start_time}} To {{item.end_time}}
                                    </td>
                                    <td v-if="item.classroom">
                                        {{item.classroom.name}}
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <!--end: Datatable -->
                </div>

            </div>
            
        </div>

        <div v-if="hub" class="col-12">
            <div id="calendar"> </div>
        </div>

        <!-- <div class="col-12">
                <div class="m-portlet m-portlet--mobile">
                
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">
                                    Feedback
                                </h3>
                            </div>
                        </div>

                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="m-portlet__body">

                        <div class="m-scrollable" id="m_quick_sidebar_tabs_messenger" role="tabpanel">

                            <div class="m-messenger m-messenger--message-arrow m-messenger--skin-light">

                                <div class="m-messenger__messages">  

                                            <div v-for="item in feedBack"  class="m-messenger__message m-messenger__message--out class-note">

                                                <div class="m-messenger__message-body">

                                                    <div class="m-messenger__message-arrow"></div>

                                                        <div class="m-messenger__message-content">
                                                            <div class="m-messenger__message-username">
                                                                <div v-for="item in item">
                                                                    <bold> {{item.name}} </bold>
                                                                        <span class="classCode" v-html="item.code" > </span>
                                                                </div>
                                                            </div>
                                                            <div class="m-messenger__message-text">
                                                                <div v-for="item in item">
                                                                        {{item.feedback}}
                                                                </div>
                                                            </div>
                                                            <div class="m-messenger__message-username">
                                                                <div v-for="item in item">
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>


                                                </div>

                                            </div>

                                </div>

                                <div class="m-messenger__seperator"></div>

                            </div>

                        </div>

                    </div>

                </div>
        </div> -->

        
    </div>

    <div class="row my-5">

        <div class="m-portlet m-portlet--head-solid-bg m-portlet--bordered col-12">

            <div class="m-portlet__head">

                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            FeedBack
                        </h3>
                    </div>
                </div>

            </div>

            <div class="m-portlet__body">

                <div class="row" >

                    <div class="col-xl-4" v-for="item in feedBack">

                        <div class="m-portlet m-portlet--bordered m-portlet--rounded m-portlet--last">

                            <div class="m-portlet__head">

                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">
                                            <span v-html=" item.code "> </span>
                                        </h3>
                                    </div>
                                </div>
                            
                            </div>

                            <div class="m-portlet__body" style="padding: 0.2rem 1.2rem;">
                                
                                <div class="m-widget19">

                                    <div class="m-widget19__content">

                                        <div class="m-widget19__header">

                                            <!-- <div class="m-widget19__user-img">
                                                <img class="m-widget19__img" :src="aham_class.topic.avatar" alt="">
                                            </div> -->

                                            <div class="m-widget19__info" style="line-height: 1.5rem;height:62px;">

                                                <span class="m-widget19__username">
                                                    <!-- <router-link :to="{ name: 'view-class', params: {class: aham_class.id} }">
                                                        {{ aham_class.topic_name }}                                
                                                    </router-link> -->
                                                    {{item.tutorName}} 
                                                
                                                </span>

                                                <span  class="m-widget4__status m--font-info" style="display: flex">
                                                    Topic: {{  item.topicName}} 
                                                </span>

                                            </div>

                                        </div>

                                        <div class="m-widget19__body">

                                            <div class="m-list-search">
                                                <div class="m-list-search__results">

                                                    <span href="#" class="m-list-search__result-item" v-if="item.feedback">
                                                        <span class="m-list-search__result-item-icon">
                                                            <i class="flaticon-calendar-1 m--font-warning"></i>
                                                        </span>
                                                        <span class="m-list-search__result-item-text">
                                                            Feedback:   {{ item.feedback }}
                                                        </span>
                                                    </span>
                                                    
                                                    <span href="#" class="m-list-search__result-item" v-if="item.remarks">
                                                        <span class="m-list-search__result-item-icon">
                                                            <i class="flaticon-calendar-1 m--font-warning"></i>
                                                        </span>
                                                        <span class="m-list-search__result-item-text">
                                                            
                                                            Remarks:   {{ item.remarks }}
                                                        </span>
                                                    </span>

                                                    <span href="#" class="m-list-search__result-item" v-if="item.date">
                                                        <span class="m-list-search__result-item-icon">
                                                            <i class="flaticon-calendar-1 m--font-warning"></i>
                                                        </span>
                                                        <span class="m-list-search__result-item-text">
                                                            
                                                            Start Date:   {{ item.date }}
                                                        </span>
                                                    </span>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                
                </div>
                
            </div>

            <div class="m-portlet__foot">

                <div class="row align-items-center">
                
                    <div class="text-center">

                    
                        <button id="showtoast" type="button" class="btn m-btn--pill m-btn--air         btn-outline-accent m-btn m-btn--custom m-btn--outline-2x" @click="previous()">
                            previous
                        </button>
                        <button id="showtoast" type="button" class="btn m-btn--pill m-btn--air         btn-outline-accent m-btn m-btn--custom m-btn--outline-2x" @click="next()">
                            next
                        </button>

                    </div>
                    
                </div>

            </div>

        </div>

    </div>

</div>

</template>

<script>

import store from "../../../store";
import axios from 'axios';
import swal from 'sweetalert2';

export default {

    props: ['hub'],

    data() {
        return {
            tomorrow: '',
            week: '',
            nextWeek: null,
            learnerProfile: null,
            feedBack: null,
            page: 1,
        }
    },

    mounted() {
        store.dispatch("setHeading", "Learner Details");
        this.tomorrow = moment().add(1, 'day').format("YYYY-MM-DD");
        this.week = moment().add(6, 'day').format("YYYY-MM-DD");

        this.fetch();
        this.profile();
        this.getFeedBack();
       

        let vm = this;

        setTimeout(() => {

            $(document).ready(function(){
                vm.initCalendar();
                vm.dataTables();
                
            });

        },3000);

    },

    methods: {

        fetch(){
            axios.get("hub/learner/"+this.$route.params.learner+"/classes?from_date="+this.tomorrow+"&to_date="+this.week)
            .then((response) => {
                this.nextWeek = response.data.data;
            })
            .catch((error) => {

            })
        },

        previous(){

            if(this.page > 1){
                this.page--;
                this.getFeedBack();
            }

        },

        next(){
            this.page++;
            this.getFeedBack();
        },

        profile(){
            axios.get("hub/user-profile/"+this.$route.params.learner)
            .then((response) => {
                this.learnerProfile = response.data;
            })
            .catch((error) => {

            })
        },

        getFeedBack(){

            axios
                 .get("hub/learner-profile/"+this.$route.params.learner+"/get-feedback?page="+this.page)
                 .then(response => {

                      this.feedBack = response.data.data;
                    
                    // if(response.data.data.length !== 0) {
                    //     this.feedBack = response.data.data;
                    // } else {
                    //     // this.page--;
                    //     // this.toast();

                    //     if(this.page > 1){
                    //          swal('No Feedbacks', 'Empty List', 'error');
                    //     }

                    // }
                     
                     this.addScroll();
                 })
                 .catch(error => {

                 });

        },

        addScroll(){
            $(function(){

                var messenger = $('#m_quick_sidebar_tabs_messenger');  
                var messengerMessages = messenger.find('.m-messenger__messages');
                messengerMessages.css('height', 300);
                mApp.initScroller(messengerMessages, {});
            
             });
             
        },

        initCalendar(){
            var vm = this;
            console.log("Init Calendar");

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month'
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week: 'week',
                    day: 'day'
                },
                dayClick: function(date, jsEvent, view) {
                    console.log(view);
                    if (view.name === "month") {
                        $('#calendar').fullCalendar('gotoDate', date);
                        $('#calendar').fullCalendar('changeView', 'agendaDay');
                    }
                },
                eventClick: function(calEvent, jsEvent, view) {

                    console.log('Event:',calEvent)
                    console.log('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                    console.log('View: ' + view.name);

                    //consol.log(vm.$router.push({ name: 'class-detail', params: { location: vm.location_slug, class:calEvent.class_id }}));

                    return vm.$router.push({ name: 'view-class', params: { class:calEvent.class_id }})

                },
                eventMouseOver: function(event, jsEvent, view) {
                        $(this).popover({
                        trigger: 'hover',
                        html: true,
                        content: function() {
                                return event.title;
                        },
                        container: 'body',
                        placement: 'right'
                    });

                },
                //Random events
                events: '/ahamapi/hub/learner/'+this.$route.params.learner+'/classes-calendar',
            });

        },

        dataTables(){

            var datatable = $('.m-datatable').mDatatable({
                search: {
                    input: $('#generalSearch'),
                },
                columns: 
                        [
                          {
                              field: "Date" 
                          },
                          {
                              field: "#"
                          },
                          {
                              field: "Status"
                          },
                          {
                              field: "Unit"
                          },
                          {
                              field: "Topic"
                          },
                          {
                              field: "Timing"
                          },
                          {
                              field: "Classroom"
                          },
                        ],
            });

        },

        toast(){

            $('#showtoast').click(function() {

                 toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };

                toastr.error("Empty Feedback")

            });
            
        }

    }  

}

</script>
<style>
   .m-messenger .m-messenger__messages .m-messenger__message.class-note {
    float: none;
   } 

   /* a.classCode {
       color: white;
       text-decoration: underline;
   }

   a.classCode:hover {
        color: aliceblue;
        text-decoration: underline;
   } */

</style>
