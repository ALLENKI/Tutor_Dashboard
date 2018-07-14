<template>

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
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                                    <i class="la la-ellipsis-h m--font-brand"></i>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    <li class="m-nav__item">
                                                        <a href="" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                            <span class="m-nav__link-text">
                                                                .......
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-widget3">
                        <div class="m-widget3__item">
                            <div class="m-widget3__header">
                                <div class="m-widget3__user-img">
                                    <img class="m-widget3__img" :src="this.tutorProfile.avatar" alt="">
                                </div>
                                <div class="m-widget3__info">
                                    <span class="m-widget3__username">
                                        {{this.tutorProfile.name}}
                                    </span>
                                    <br>
                                    <span class="m-widget3__time">
										<mark>
                                            Email: {{this.tutorProfile.email}} |
										</mark>
                                    </span>
                                    
                                    <span class="m-widget3__time">
                                        
											<span class="m-badge m-badge--danger m-badge--dot"></span>
										<mark>
                                            Mobile: {{this.tutorProfile.mobile}} |
										</mark>
                                        
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
                                        {{item.aham_class.code}}
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

        <div class="col-md-12" style="display: flex;flex-wrap: wrap;justify-content: flex-end;">
            <div  v-for="color in colors" style="padding: 0px 20px;">
                <span class='m-badge' v-bind:style="{'background-color':color.color}"></span> {{color.name.slice(color.name.indexOf('@')+1)}}
            </div>
        </div>
        <div v-if="hub" class="col-12">
    
            <div id="calendar"></div>
        </div>

    </div>
  
</template>

<script>

import store from "../../../store";

export default {

     props: ['hub'],

    data() {
        return {
            tomorrow: '',
            week: '',
            nextWeek: null,
            tutorProfile: null,
            colors:null,
        }
    },
    
    mounted() {
        store.dispatch("setHeading", "Tutor Details");
        this.tomorrow = moment().add(1, 'day').format("YYYY-MM-DD");
        this.week = moment().add(6, 'day').format("YYYY-MM-DD");

        this.fetch();
        this.profile();
        this.fetchColors();

        let vm = this;

        setTimeout(() => {

            $(document).ready(function(){
                vm.initCalendar();
                vm.dataTables();
            });

        },1000);

    },

    methods: {

        fetch(){
            axios.get("hub/tutor/"+this.$route.params.tutor+"/classes?from_date="+this.tomorrow+"&to_date="+this.week)
            .then((response) => {
                this.nextWeek = response.data.data;
            })
            .catch((error) => {

            })
        },

         profile(){

            axios.get("hub/tutor-profile/"+this.$route.params.tutor)
            .then((response) => {
                this.tutorProfile = response.data;
            })
            .catch((error) => {

            })
        },

        fetchColors(){
            axios.get("hub/colors/")
            .then((response) => {
                this.colors = response.data;
            })
            .catch((error) => {

            })
        },


        initCalendar(){
            var vm = this;
                        console.log("Init Calendar");

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month myCustomButton'
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
                events: '/ahamapi/hub/tutor/'+this.$route.params.tutor+'/classes-calendar',
                eventRender: function(event, element) {
                    //element.attr('title', event.title);
                     $(element).tooltip({title: event.title});
                }

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

        }

    }  
  
}
</script>
