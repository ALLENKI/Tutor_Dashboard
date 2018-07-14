<template>
              <div class="row">
                <div class="m-portlet col-md-2" style="height:250px;margin-left:15px">
                  <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                      <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                         Settings
                        </h3>
                      </div>
                    </div>
                  </div>
                  <div class="m-portlet__body">
                  <router-link :to="{path: '/my-classes/profile' }" exact class="m-menu__item">
                  <li>Profile</li></router-link>
                  <router-link :to="{path: '/my-classes/password' }" exact class="m-menu__item">
                  <li>Password</li>
                  </router-link>
                  <router-link :to="{path: '/my-classes/update_no' }" exact class="m-menu__item">
                  <li>Mobile Number</li></router-link>
                  </div>
                </div>
                 <div class="m-portlet col-md-9" style="margin-left:40px" >
                  <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                      <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                          Change Mobile Number
                        </h3>
                      </div>
                    </div>
                  </div>
                  <div class="m-portlet__body">
                   
                    <el-form :label-position="labelPosition" :model="form" ref="form"  label-width="120px">
                    <div class="row" style="margin-left:5px;margin-right:20px;padding-right:5px;">
                     <el-form-item>
                      <el-col :span="100">
                       <el-input  v-model="form.no"></el-input>

                       </el-col>
                     </el-form-item>
                     <div style="margin-right:10px;">
                     <el-form-item>
                     <el-col :span="15">
                     <el-button type="secondary" @click="Generate(event)">Generate Otp</el-button>
                      </el-col>
                      </el-form-item>
                      </div>
                     </div>

                     <el-form-item>
                       <el-input  v-model="form.otp" placeholder="enter valid otp"></el-input>
                     </el-form-item>

                      <el-form-item>
                              <el-button type="primary" @click="onSubmit()">Update Mobile</el-button>
                        </el-form-item>


                    </el-form>
              </div>
              </div>
              </div>
</template>
<script>
export default{
  data(){
    return{
      labelPosition:'top',
        form: {
          no: '',
          otp:'',
          
        },
  }
},
methods:{
  onSubmit()
  {},
  Generate(event){

  },
}
}
</script>