<template>
    
    <div class="m-portlet">

        <div class="m-portlet__body">

                <div class="row">
                    <div class="col-md-12">
                            <div id="calendar"></div>  
                    </div>
                </div>
            
        </div>

    </div>

</template>

<script>

import axios from "axios";
import store from "../../store";

export default {
    props:['hub'],

    data: function(){
        return {

        }
    },
    mounted() {

        setTimeout(() => {
            store.dispatch("setHeading", "Calendar( "+this.hub.name+" )");
        },500);

       var vm = this;

         $(document).ready(function() {

                setTimeout( ()=>{
                    vm.initCalendar();
                },500 );

        });
        
    },
    methods: {

        initCalendar()
        {
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
                    events: '/ahamapi/hub/classes/calendar/'+this.hub.slug,
                    eventRender: function(event, element) {
                        var a=event.title+'@'+event.class_id;
                        $(element).tooltip({title:event.title});
                        }
                });

        }

    }
    
}

</script>
