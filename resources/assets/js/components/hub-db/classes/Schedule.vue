<template>
    <div class="m-portlet">
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-md-2">

                <div class="form-group" v-if="aham_class">
                    <label>Scheduling Rule</label>
                    <select v-model="schedulingRule" class="form-control">
                        <option v-for="rule in aham_class.scheduling_rules" :value="rule">
                            {{ rule }}
                        </option>
                    </select>
                </div>

                <h5>Units to Schedule</h5>
                <div id='external-events' style="border:1px dashed #eee; min-height:50px;">
                    
                </div>
                <hr>
                <div>
                    <h5>Scheduled Units {{ can_save }}</h5>
                    <div class="m-widget4" style="border:1px dashed #eee; padding:0 5px;min-height:50px;">
                        <div class="m-widget4__item" v-for="timing in timings" v-if="timing.isScheduled">
                            <div class="m-widget4__info">
                                <span class="m-widget4__text">
                                    {{ timing.title }}
                                    <br>
                                    <small>
                                        {{ timingFormat(timing) }}
                                    </small>
                                    
                                </span>
                            </div>
                            <div class="m-widget4__ext">
                                <a class="m-widget4__icon" @click="cancelTiming(timing)">
                                    <i class="la la-remove"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <button class="btn btn-primary" v-if="can_save" @click="saveTimings">Save</button>
                </div>

                </div>
                <div class="col-md-10">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// https://codepen.io/subodhghulaxe/pen/qEXLLr
import moment from 'moment';
import store from "../../../store";

export default {

    props:['hub'],

    data: function()
    {
        return {
            class_id : null,
            processed : false,
            aham_class : null,
            can_save : false,
            unitTime : 1,
            defaultUnitDuration: '01:00:00',
            schedulingRule: '1-1-2',
            classRooms: [],
            timings : []
        };
    },
  watch: {
    '$route': function(){
        this.class_id = this.$route.params.class;

        this.fetchClass();
    },
    'schedulingRule' : function(){

        console.log("Changed scheduling rule");

        if(this.aham_class)
        {
            this.appendGroupId();
            this.cancelTiming(this.timings[0]);
        }

    }
  },
  mounted() {
    store.dispatch("setHeading", "Schedule Class");

    this.class_id = this.$route.params.class;

    this.fetchClass();

    $(function() {


    });

  },
  methods:{

    timingFormat(timing){
        return timing.startTime.format('MMMM Do YYYY, h:mm:ss a') + ' to ' + timing.endTime.format('h:mm:ss a');
    },

    fetchClass()
    {
          axios
            .get("hub/classes/"+this.class_id)
            .then(response => {
              this.aham_class = response.data;
              this.classRooms = this.aham_class.classrooms;
              this.unitTime = parseInt(this.aham_class.unit_duration);

              let defaultUnitDuration = this.hhmmss(this.unitTime*60*60);
              this.defaultUnitDuration = defaultUnitDuration;

              this.schedulingRule = this.aham_class.scheduling_rule;
              // Take last element for default scheduling rule

              _.each(this.aham_class.units,(unit) => {

                this.timings.push({
                    id: unit.id,
                    title: unit.name,
                    isScheduled: false,
                    startTime: false,
                    endTime: false,
                    group: false,
                    classroomId: false,
                    stick: true
                });

              });

              this.initCalendar();
              $('#calendar').fullCalendar( 'today' );
              this.$forceUpdate();
              this.setBreadcrumb();
              // this.processClassTimings();


            })
            .catch(error => {
              // console.log(error);
            });
    },

    setBreadcrumb()
    {
        console.log("setBreadcrumb",this.$router.resolve({ name: 'view-class', params: {hub: this.hub.id, class: this.aham_class.id } }));

        let breadcrumb = {
            links: []
        };

        breadcrumb.links.push({
          'link': this.$router.resolve({ name: 'view-class', params: {hub: this.hub.slug, class: this.aham_class.id } }).href,
          'active': false,
          'title': 'Class Detail'
        });

        console.log(breadcrumb);

        store.dispatch('setBreadcrumb',breadcrumb);

    },

    processClassTimings()
    {

        if(this.processed)
        {
            return false;
        }

      _.each(this.aham_class.timings,(ct) => {

        let timing = _.find(this.timings,(t) => {
            return t.id == ct.class_unit_id;
        });

        timing.resourceId = ct.classroom_id;
        timing.start= moment(ct.start_time_date,"YYYY-MM-DD HH:mm:ss");
        timing.end = moment(ct.end_time_date,"YYYY-MM-DD HH:mm:ss");

        $('#calendar').fullCalendar('renderEvent', timing, true);
        let event = this.findEvent(timing);
        this.updateTimingsArray(event);
        $('#calendar').fullCalendar( 'gotoDate', ct.date );
      });

      if(this.aham_class.timings.length)
      {
        $('#external-events').empty();
      }

      this.processed = true;
    },

    findEvent(timing){

        let foundEvents = $('#calendar')
                        .fullCalendar( 'clientEvents', function(event){

                            if(event.id)
                            {
                                return timing.id == event.id;
                            }

                            return false;
                            
                        });

        return foundEvents[0];

    },

    pad(num) {
        return ("0"+num).slice(-2);
    },
    hhmmss(secs) {
      var minutes = Math.floor(secs / 60);
      secs = secs%60;
      var hours = Math.floor(minutes/60)
      minutes = minutes%60;
      return this.pad(hours)+":"+this.pad(minutes)+":"+this.pad(secs);
    },

    cancelTiming(timing)
    {
        // $('#calendar').fullCalendar('removeEvents', timing._id);

        var afterTimings = _.filter(this.timings, function(t) { return t.id >= timing.id; });

        $('#external-events').empty();

        _.each(afterTimings,(afterTiming) => {

            console.log("cancelTiming",afterTiming.id);

            $('#calendar').fullCalendar('removeEvents', afterTiming.id);

            afterTiming.isScheduled = false;
            afterTiming.startTime = false;
            afterTiming.endTime = false;
            afterTiming.classroomId = false;
            delete afterTiming["_id"];
            this.appendToExternalEvents(afterTiming);
        });

        this.makeExternalEventsDraggable();
        this.canSave();
    },

    unitsOrderValidation(event) {
        var beforeTimings = _.filter(this.timings, function(timing) { 
            return timing.id < event.id; 
        });

        var valid = true;
        _.forEach(beforeTimings,function(beforeTiming) {
            valid = valid && 
            (moment(beforeTiming.endTime).isBefore(event.start) || 
            moment(beforeTiming.endTime).isSame(event.start));
        });

        return valid;
    },

    unitsGroupValidation(event) {

        var sameGroups = _.filter(this.timings, function(timing) { 
            return timing.group == event.group && timing.isScheduled == true; 
        });

        var sameGroupValid = true;

        var day = new Date(event.start.year(),event.start.month(), event.start.date());

        _.forEach(sameGroups,function(sameGroup) {
            sameGroupValid = sameGroupValid && (moment(new Date(sameGroup.endTime.year(),sameGroup.endTime.month(), sameGroup.endTime.date())).isSame(day));
        });

        var otherGroups = _.filter(this.timings, function(timing) { return timing.group < event.group && timing.isScheduled == true; });

        var otherGroupValid = false;
        _.forEach(otherGroups,function(otherGroup) {
            otherGroupValid = (moment(new Date(otherGroup.endTime.year(),otherGroup.endTime.month(), otherGroup.endTime.date())).isSame(day));
        });

        return sameGroupValid && !otherGroupValid;
    },

    areBeforeUnitsScheduled(id) {
        var beforeTimings = _.filter(this.timings, function(timing) { return timing.id < id; });
        var isScheduled = true;
         _.forEach(beforeTimings, function(timing){
                isScheduled = isScheduled && timing.isScheduled;
        });
        return isScheduled;
    },

    canSave()
    {
        var beforeTimings = _.filter(this.timings, function(timing) { return timing.isScheduled == false; });

        this.can_save = false;

        if(beforeTimings.length == 0)
        {
            this.can_save = true;
        }

    },

    updateTimingsArray(event) {

        _.find(this.timings,(timing)  => {
            if(event.id == timing.id) {
                timing._id = event._id;
                timing.isScheduled = true;
                timing.classroomId = event.resourceId;
                timing.startTime = event.start;
                timing.endTime = event.start.clone().add(this.unitTime,'hours');
            }
        });

        this.canSave();
    },

    appendGroupId() {

        _.each(this.timings,(timing) => {
            timing.group = false;
        });

        var schedulingRule = this.schedulingRule;
        var combinationRules = schedulingRule.split('-');
        //console.log(combinationRules);
        let falseGroupIds = [];
        var vm = this;
        _.forEach(combinationRules,function(combinationRule,index) {
                    
                //console.log(combinationRule);
                falseGroupIds = _.filter(vm.timings,function(timing) { return timing.group == false});
                falseGroupIds = _.take(falseGroupIds,combinationRule);
                // console.log(falseGroupIds);
           falseGroupIds.forEach(function(falseGroupId) {

                    vm.timings.forEach(function(timing) {
                        if(timing.id == falseGroupId.id) {
                            timing.group = index+1;
                        }
                    });

                });

        });
    },

    isEventOverDiv(x, y) {

        var external_events = $( '#external-events' );
        var offset = external_events.offset();
        offset.right = external_events.width() + offset.left;
        offset.bottom = external_events.height() + offset.top;

        if (x >= offset.left
            && y >= offset.top
            && x <= offset.right
            && y <= offset .bottom) { return true; }

        return false;

    },

    appendToExternalEvents(timing)
    {
        var test = "<div class='fc-event' data-id='"+timing.id+"' data-group='"+timing.group+"'>"+timing.title+" (Group: "+timing.group+")</div>";
        $('#external-events').append(test);
    },

    makeExternalEventsDraggable()
    {
        $('#external-events .fc-event').each(function () {
            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                id: $(this).data('id'),
                group: $(this).data('group'),
                stick: true
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0  //  original position after the drag
            });
        });
    },

    initCalendar() {

        var vm = this;

        this.appendGroupId();

        $.each(vm.timings,function(index,timing) {
            if(!timing.isScheduled)
            {
                vm.appendToExternalEvents(timing);
            }
        });

        vm.makeExternalEventsDraggable();

        var defaultUnitDuration = this.defaultUnitDuration;
        var classRooms = this.classRooms;

        $('#calendar').fullCalendar({
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            businessHours: {
                dow: [0, 1, 2, 3, 4, 5, 6 ], 
                start: '06:00', 
                end: '21:00',
            },
            slotWidth: '10px',
            resourceAreaWidth: '20%',
            minTime:'06:00',
            maxTime:'21:00',
            timezone: 'local',
            eventDurationEditable: false,
            editable: true,
            droppable: true,
            scrollTime: '00:00', // undo default 6am scrollTime
            slotDuration: '00:15:00',
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'timelineDay,timelineThreeDays,agendaWeek,month'
            },
            views: {
                day: { 
                    titleFormat: 'dddd, MMMM D, YYYY'
                }
            },
            defaultView: 'timelineDay',
            defaultTimedEventDuration: defaultUnitDuration,
            resourceLabelText: 'Rooms',
            resources: classRooms,
            events: [],
            events: baseUrl+'/hub/calendar/'+vm.class_id,
            eventAllow: function(dropLocation,draggedEvent) {
             let areBeforeUnitsScheduled = vm.areBeforeUnitsScheduled(draggedEvent.id);
             let unitsOrderValidation = vm.unitsOrderValidation(draggedEvent);
             let unitsGroupValidation = vm.unitsGroupValidation(draggedEvent);
             console.log("eventAllow",areBeforeUnitsScheduled,unitsOrderValidation,unitsGroupValidation);
             return areBeforeUnitsScheduled && unitsOrderValidation && unitsGroupValidation;
            },
            eventAfterAllRender: function() {
                if(!vm.processed)
                {
                    vm.processClassTimings();
                }
            },
            drop: function(date, jsEvent, ui, resourceId) {
                $(this).remove();
            },
            eventReceive: function(event) {
                vm.updateTimingsArray(event); // update the array
            },
            eventDrop: function(event) {
                vm.updateTimingsArray(event); // update timings array
            }
        });


    },

    saveTimings()
    {
        let payload = {
            'class_id': this.class_id,
            'scheduling_rule': this.schedulingRule,
        };

        var timings = [];

        _.each(this.timings,(t) => {
            timings.push({
                class_unit_id: t.id,
                classroom_id: parseInt(t.classroomId),
                date: t.startTime.format("YYYY-MM-DD"),
                start_time: t.startTime.format("HH:mm:ss"),
                end_time: t.endTime.format("HH:mm:ss"),
            });
        });

        payload['timings'] = timings;

        axios
        .post("hub/classes/schedule", payload)
        .then(response => {

          return this.$router.push({
            name: "view-class",
            params: { class: this.class_id }
          });

        })
        .catch(error => {
          // console.log(error);
        });


        console.log("Let's save timings",payload);
    }
  }
};
</script>

<style>

    #external-events h4 {
        font-size: 16px;
        margin-top: 0;
        padding-top: 1em;
    }

    #external-events .fc-event {
        margin: 10px 0;
        cursor: pointer;
    }

    #external-events p {
        margin: 1.5em 0;
        font-size: 11px;
        color: #666;
    }

    #external-events p input {
        margin: 0;
        vertical-align: middle;
    }
</style>
