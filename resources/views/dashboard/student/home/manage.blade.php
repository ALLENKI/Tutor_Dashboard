@extends('dashboard.student.layouts.master')

@section('content')

@include('dashboard.student.home.avatar')

<div class="row" id="app">

<div class="col-md-3">
    @include('dashboard.student.home.sidebar')
</div>

<div class="col-md-9">

    <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="modal-title">manage user</h4>
        </div>

          <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12">
                    <div class="form-group">
                      <label>user name</label>
                      <input class="form-control" type="text" name="username" v-model="username">
                      <br/>
                        <label>mobile number</label>
                      <input class="form-control" type="number" name="mobilenumber" v-model="mobilenumber">
                    </div>

                      <div class="form-group">
                        <label>days of week</label>
                        <br>
                        <label  v-for="day_of_week in selected_days_of_week">
                              <p v-if="day_of_week"> @{{ day_of_week  }} </p>
                         </label>
                      </div>

                      <div class="form-group">
                        <label>curriculum</label>
                        <input type="text" class="form-control" v-model="student.curriculum">
                      </div>

                      <div class="form-group">
                        <label>grade</label>
                        <br>
                            <p> @{{ selected_grade  }} </p>
                      </div>
                    </div>
                      <button type="button" class="btn btn-success" @click="savemanageuser()">
                            save
                      </button>
                  </div>
        </div> <!-- panel body -->
</div><!-- panel default -->
</div>
@stop

@section('scripts')
@parent
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>

<script >
    var token = "<?php echo "$token" ?>";
    var studentid = "<?php echo "$student->id" ?>";

     new Vue({
        el: "#app",
        data: {
          request: null,
          header: {
                    "Accept": 'application/x.aham.v1+json',
                    "Authorization": 'Bearer '+token,
                    'Content-Type': 'application/json',
                  },
          studentId: studentid,
          userName: null,
          mobileNumber: null,
          student:null,
          student_id: null,
          today:null,
          tomorrow:null,
          week:null,
          todayClasses:[],
          weekClasses:[],
          customClasses:[],
          from_date: null,
          to_date:null,
        selected_days_of_week: [],
          times_of_day:[
            {label: 'Early Morning', title: 'Early Morning', value: 'early_morning'},
            {label: 'Morning', title: 'Morning', value: 'morning'},
            {label: 'Afternoon', title: 'Afternoon', value: 'afternoon'},
            {label: 'Evening', title: 'Evening', value: 'evening'},
            {label: 'Late Evening', title: 'Late Evening', value: 'late_evening'}
          ],
          selected_times_of_day: [],
          selected_grade:null,
          subjects: [],
          selected_subjects: []
        },
        mounted: function () {
            var vm = this;
            var baseUrl = window.baseUrl;
            console.log("mounting");
            axios.get(window.baseUrl+'/ala/students/'+this.studentId,{
            headers: vm.header,
           }).then((response) => {
                console.log(response);
                this.student = response.data;
                this.userName = response.data.name;
                this.mobileNumber = response.data.mobile;
                this.selected_days_of_week = response.data.selected_days_of_week;
                this.weekly_times_of_day = response.data.selected_times_of_day;
                this.selected_subjects = response.data.selected_subjects;
                this.selected_grade = response.data.grade;
          })
          .catch((error) => {
          });
        },
      methods: {
        saveManageUser() {
        var vm = this;
             let payload = {
                'selected_days_of_week': this.selected_days_of_week,
                'weekly_times_of_day': this.weekly_times_of_day,
                'selected_times_of_day': this.selected_times_of_day,
                'selected_grade': this.selected_grade,
                'curriculum': this.student.curriculum,
                'school': this.student.school,
                'selected_subjects': _.map(this.selected_subjects,subject => subject.id),
                'username': this.userName,
                'mobilenumber': this.mobileNumber
                };
                console.log("saveManageUser", payload);
                axios.post(window.baseUrl+'/ala/student/post-interest/'+this.studentId,payload,{
                    headers: vm.header,
                }).then((response) => {
                    this.student = response.data;
                    this.userName = response.data.user.name;
                    this.mobileNumber = response.data.mobile;
                    this.selected_days_of_week = response.data.selected_days_of_week;
                    this.weekly_times_of_day = response.data.selected_times_of_day;
                    this.selected_subjects = response.data.selected_subjects;
                    this.selected_grade = response.data.grade;
                })
                .catch((error) => {
                });
            },
        },
    });
</script>
@stop
