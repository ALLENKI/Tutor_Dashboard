@extends('dashboard.student.layouts.master')

@section('content')

@include('dashboard.student.home.avatar')

{{-- <div class="page-header page-header-inverse has-cover" style="    background: url(http://res.cloudinary.com/ahamlearning/image/upload/c_scale,h_860,l_black_opacity_60_sgz5cd,w_1841/v1466663558/Collaboration-Technology_zncjmr.jpg);background-size: cover;background-position-y: -135px;">
  <div class="page-header-content">
    <div class="page-title" style="padding: 20px 36px 20px 0;">
      <h2 style="font-size: 36px;">
        <span class="text-bold">
        Your Settings
        </span>
      </h2>
    </div>
  </div>
</div> --}}

<div class="row">
<div class="col-md-3">
    @include('dashboard.student.home.sidebar')
</div>

<div class="col-md-9">

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Profile <small>({{ $user->email }})</small> </h3>
  </div>
  <div class="panel-body" id="app">

      <div class="form-group">
      <label class="control-label m-bottom-10">
        Profile Picture
      </label>

      <div class="row">
        <div class="col-md-2 text-center">
          <?php
            $image_url = cloudinary_url($user->present()->picture);
          ?>
          <img src="{{ $image_url }}" alt="" class="img-responsive" style="border: 1px solid #D0D0D0; border-radius:5px;">
          <small><strong><a href="javascript:;"id="changeAvatar" >Change Image</a></strong></small>
        </div>
      </div>

      </div>

      <!-- {!! BootForm::open()->action(route('student::settings.profile')) !!}

      {!! BootForm::bind($user) !!}

      {!! BootForm::text('Name', 'name')
                            ->placeholder('Name')
                            ->attribute('required','true')
                            ->attribute('value',Input::old('name',$user->name)) !!}
      -->
      <div>
          <input type="submit" value="Upload Pic" class="btn btn-small btn-thick-border btn-circle btn-skin-green">
      </div>

      <!-- {!! BootForm::close() !!} -->
<br>
          <div>
              <div class="row">
                    <div class="col-md-12">

                    <div class="">
                      <div class="form-group">
                      <label style="font-size:15px">User Name</label>
                      <input class="form-control" type="text" name="username" v-model="userName">
                      <br/>
                        <label style="font-size:15px">Mobile Number</label>
                    <input class="form-control" type="number" name="mobilenumber" v-model="mobileNumber">
                    <br>
                      <button type="button" class="btn btn-success" @click="saveManageUser()">
                            Save
                      </button>
                      </div>
                    </div>

                        <label style="font-size:15px">Time of Day</label>
                        <br>
                        <table class="table table-bordered">
                          <thead>
                              <th>Day</th>
                                <th v-for="time_of_day in times_of_day">
                                  @{{ time_of_day.label }}
                                </th>
                            </thead>
                          <tbody>
                            <tr v-for="day_of_week in days_of_week">
                              <td>
                                @{{ day_of_week.label }}
                              </td>
                              <td v-for="time_of_day in times_of_day">
                                <input type="checkbox" disabled v-model="weekly_times_of_day[day_of_week.value]" :value="time_of_day.value">
                              </td>
                            </tr>
                          </tbody>
                        </table>

                        <br>

                      <div class="form-group">
                          <ul class="list-inline">
                          <li style="font-size:15px">Selected Subjects</li>
                          <span class="label label-primary" v-for="selected_subject in selected_subjects">
                           @{{selected_subject.name}}
                            &nbsp;
                            &nbsp;
                            &nbsp;
                          </span>
                        </ul>
                      </div>

                      <div class="form-group">
                        <ul class="list-inline">
                          <li style="font-size:15px">curriculum</li>
                          <span v-if="student" class="label label-primary">@{{student.curriculum}}</span>
                        </ul>
                      </div>

                      <div class="form-group">
                        <ul class="list-inline">
                          <li style="font-size:15px"> Grade </li>
                          <span class="label label-primary label-xs"> @{{ selected_grade  }} </span>
                        </ul>
                      </div>

                      <h5>**To change the prefered timings, subjects, curriculum and grade, please contact the Administration.</h5>


                    </div>
                  </div>
            </div>

  </div><!--app-->
</div>

</div>

</div>

@stop

@section('scripts')
@parent

<script type="text/javascript">
  $(document).ready(function(){
    
    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

    $('#interested_subjects').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $('#changeAvatar').on('click',function(){
      $('#avatarModal').modal();

    });

  });

</script>

<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>

<script>
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
        days_of_week:[
            {label: 'Sunday', title: 'Sunday', value: 'sunday'},
            {label: 'Monday', title: 'Monday', value: 'monday'},
            {label: 'Tuesday', title: 'Tuesday', value: 'tuesday'},
            {label: 'Wednesday', title: 'Wednesday', value: 'wednesday'},
            {label: 'Thursday', title: 'Thursday', value: 'thursday'},
            {label: 'Friday', title: 'Friday', value: 'friday'},
            {label: 'Saturday', title: 'Saturday', value: 'saturday'}
          ],
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
          selected_subjects: [],
          weekly_times_of_day: []
        },
        mounted: function () {
            var vm = this;
            console.log("mounting");
            axios.get(window.BASE+'ahamapi/ala/students/'+this.studentId,
                      {headers: vm.header}).
                      then((response) => {
                          console.log('DATA:',response);
                          this.student = response.data;
                          this.userName = response.data.name;
                          this.mobileNumber = response.data.mobile;
                          this.selected_days_of_week = response.data.selected_days_of_week;
                          this.weekly_times_of_day = response.data.selected_times_of_day;
                          this.selected_subjects = response.data.selected_subjects;
                          this.selected_grade = response.data.grade;
                    }).catch((error) => {
                      console.log(error);
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
