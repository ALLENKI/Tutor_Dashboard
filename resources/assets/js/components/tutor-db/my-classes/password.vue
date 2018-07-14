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
                          Change Password
                        </h3>
                      </div>
                    </div>
                  </div>
                  <div class="m-portlet__body">
                      <el-form :label-position="labelPosition" :model="form" ref="form"  label-width="120px">
                         <el-form-item
                          label="Old Password"
                          prop="old_password"
                          :rules= "[{ required: true, message: 'Please input old password', 
                          trigger: 'blur' }]"
                          :error="error1">
               
                            <el-input type="Password" v-model="form.old_password" placeholder="Enter ur old password"></el-input>
                          </el-form-item>

                          <el-form-item label="New Password">
                            <el-input  type="Password" v-model="form.password"  placeholder="Enter ur new password"></el-input>
                          </el-form-item>

                          <el-form-item label="Confirm New Password"
                          prop="password_confirmation"
                          :error="error">
                            <el-input  type="Password" v-model="form.password_confirmation"  placeholder="Enter ur new password for confirmation"></el-input>
                          </el-form-item>

                          <el-form-item>
                              <el-button type="primary" @click="onSubmit('form')">Update Password</el-button>
                          </el-form-item>

                      </el-form>
                   </div>
                  </div>
                </div>
</template>


<script>
  export default {
    data() {
      return {
        labelPosition:'top',
        form: {
          old_password: '',
          password: '',
          password_confirmation: '',
          
        },
        success:'',
        error:'',
        error1:'',
      }
    },

     computed:{
      user() {
      return this.$store.state.user;
        
    },

    loading() {
      return this.$store.state.loading;
    }
  },

  methods:{
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
          if (valid) {
            if(this.form.password==this.form.password_confirmation)
            {
              this.error="";
                let payload={
                      password:this.form.password,
                      old_password:this.form.old_password,}
                axios.post('http://localhost:8000/ahamapi/tutor/change_password',payload)
                .then(response=>{
                            this.success=response.data.success;
                            console.log(this.success);
                                 })
                  if(this.success)
                     {this.error1="wrong old password";}
                  else
                      alert("Updated successfully!");
                
            } 
            else
            {
              this.error="Doesn't matches with new password!";
            }
          }
          else {
            console.log('error submit!!');
            return false;
          }
        });
      },
    
}
}
</script>