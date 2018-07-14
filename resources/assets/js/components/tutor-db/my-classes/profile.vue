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
                          Profile({{user.email}})
                        </h3>
                      </div>
                    </div>
                  </div>
                  <div class="m-portlet__body">
                      <el-form :label-position="labelPosition" :model="form" ref="form"  label-width="120px">

                      <el-upload
                            class="upload-demo"
                            action="ahamapi/tutor/upload-avatar"
                            :headers = headerList
                            :on-preview="handlePreview"
                            :on-change="handleOnchange"
                            :on-remove="handleRemove"
                            :file-list="fileList6"
                            accept=".jpg"
                            list-type="picture">
                            <el-button size="small" type="primary">Click to upload</el-button>
                        </el-upload>

                         <el-form-item label="Name">
                            <el-input v-model="form.name"></el-input>
                          </el-form-item>

                           <el-form-item label="Preferred City">
                             <el-select v-model="form.region" placeholder="Select a city of ur convenience">
                                <el-option label="Hyderabad" value="1"></el-option>
                                <el-option label="Vizag" value="2"></el-option>
                                <el-option label="Guntur" value="3"></el-option>
                    
                             </el-select>
                           </el-form-item>


                            <el-form-item label="Why are u passionate about Teaching?">
                                <el-input type="textarea" v-model="form.reason"></el-input>
                            </el-form-item>


                            <el-form-item label="Current Profession">
                              <el-select v-model="form.profession" placeholder="Select ur current profession">
                              <el-option  label="Teacher"    value="Teacher"></el-option>
                              <el-option  label="Research"   value="Research"></el-option>
                              <el-option  label="Business"   value="Business"></el-option>
                              <el-option  label="Student"    value="Student"></el-option>
                              <el-option  label="Other"      value="other"></el-option>
                              </el-select>
                            </el-form-item>

                  
                               <el-form-item label="Preferred Subjects">
                            <el-checkbox-group v-model="form.type">
                              <el-checkbox label="Mathematics" name="type"></el-checkbox>
                              <el-checkbox label="Physics" name="type"></el-checkbox>
                              <el-checkbox label="Science" name="type"></el-checkbox>
                              <el-checkbox label="Programming" name="type"></el-checkbox>
                            </el-checkbox-group>
                          </el-form-item>

                            <el-form-item label="Linkedin Profile">
                              <el-input v-model="form.linkedin" placeholder="Give the url of ur linkedin Profile"></el-input>
                            </el-form-item>

                            
                         <el-form-item label="Please Upload ur resume">
                            <el-upload
                              class="upload-demo"
                              action="ahamapi/tutor/upload-resume"
                              :headers = headerList
                              :on-change="handleChange" 
                              :file-list="fileList1"
                               multiple  :limit="1"
                              :on-exceed="handleExceed"
                              >
                              
                              <el-button size="small">Click to upload</el-button>
                              <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
                            </el-upload>
                          </el-form-item>

                          <el-form-item label="Please Upload ur Aadhar Card">
                            <el-upload
                              class="upload-demo"
                              action="ahamapi/tutor/upload-resume"
                              :headers = headerList
                              :on-change="handleChange2"
                              :file-list="fileList2"
                              multiple  :limit="1"
                              :on-exceed="handleExceed">
                              <el-button size="small">Click to upload</el-button>
                              <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
                            </el-upload>
                          </el-form-item>  

                            <el-form-item label="Please Upload ur Pan Card">
                            <el-upload
                              class="upload-demo"
                              action="ahamapi/tutor/upload-resume"
                              :headers = headerList
                              :on-change="handleChange3"
                              :file-list="fileList3"
                              multiple  :limit="1"
                              :on-exceed="handleExceed"
                              >
                              <el-button size="small">Click to upload</el-button>
                              <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
                            </el-upload>
                          </el-form-item>  
                            
                            
                             <el-form-item label="Please Upload ur Form 16">
                             <el-upload
                              class="upload-demo"
                              action="ahamapi/tutor/upload-resume"
                              :headers = headerList
                              :on-change="handleChange4"
                              :file-list="fileList4"
                              multiple  :limit="1"
                              :on-exceed="handleExceed">
                                <el-button size="small">Click to upload</el-button>
                              <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
                            </el-upload>
                            </el-form-item>
                            

                             <el-form-item label="Upload cancelled cheque for bank details">
                            <el-upload
                              class="upload-demo"
                              action="ahamapi/tutor/upload-resume"
                              :headers = headerList
                              :on-change="handleChange5"
                              :file-list="fileList5"
                              multiple  :limit="1"
                              :on-exceed="handleExceed">
                              <el-button size="small">Click to upload</el-button>
                              <div slot="tip" class="el-upload__tip">jpg/png files with a size less than 500kb</div>
                            </el-upload>
                            </el-form-item>

                             <el-form-item>
                              <el-button type="primary" @click="onSubmit">Update Details</el-button>
                              <el-button>Cancel</el-button>
                            </el-form-item>
                      </el-form>
                  </div>
                </div>                
              </div>
            </div>

</template>
<script>
  export default {
    data() {
      return {
        labelPosition:'top',
        fileList1:[],
        fileList2:[],
        fileList3:[],
        fileList4:[],
        fileList5:[],
        
        // fileList1: [{url: null,name: null}],
        // fileList2: [{url: null,name: null}],
        // fileList3: [{url: null,name: null}],
        // fileList4: [{url: null,name: null}],
        // fileList5: [{url: null,name: null}],
         fileList6: [{url: null,name: null}],
        form: {
          type: [],
          name: '',
          region: '',
          reason: '',
          profession: '',
          linkedin:'',
          
        },
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

  mounted(){
     this.headerList  =  {
                                "Accept": "application/x.aham.v1+json",
                                "Authorization": "Bearer " + authToken,
                                "Content-Type": "application/json"
                            };
                            
      this.getProfile();
       
   
  },

 
    methods: {

      onSubmit() {
        let payload = {
          name:this.form.name,
          city_id:this.form.region,
          why_teacher:this.form.reason,
          current_profession: this.form.profession,
          interested_subjects: this.form.type,
          linkedin:this.form.linkedin,
          resume:this.form.resume,
        }
        console.log('payload',payload);
        axios.post('http://localhost:8000/ahamapi/tutor/profile-settings',payload)
         .then(response=>{
          console.log("success");
         })
         
    },

      getProfile(){
      axios.get('http://localhost:8000/ahamapi/tutor/profile-settings')
      .then(response=>{
        console.log(response.data);
        this.form.name=response.data.name;
        this.form.region=response.data.city_id.name;
        this.form.reason=response.data.why_teacher;
        this.form.profession=response.data.current_profession;
        this.form.linkedin=response.data.linkedin;
        this.form.avatar=response.data.avatar;
        //this.fileList6[0].url = response.data.avatar_image;
        // //this.fileList1[0].name = 'resume';
        // this.fileList2[0].url = response.data.Aadhar_card;
        // this.fileList2[0].name = 'Aadhar_card';
        // this.fileList3[0].url = response.data.pan_card;
        // this.fileList3[0].name = 'pan_card';
        // this.fileList4[0].url = response.data.form_16;
        // this.fileList4[0].name = 'Form_16';
        // this.fileList5[0].url = response.data.cheque;
        // this.fileList5[0].name = 'cheque';

      })
      .catch(error => {
                    console.log(error);
                });
    },
    
     handleChange(file,fileList){
       this.uploadResume(file,"resume_file");
     },
     
      handleChange2(file,fileList){
       this.uploadResume(file,"Aadhar_card");
     },

      handleChange3(file,fileList){
       this.uploadResume(file,"pan_card");
     },

      handleChange4(file,fileList){
       this.uploadResume(file,"form_16");
     },

      handleChange5(file,fileList){
       this.uploadResume(file,"cheque");
     },


     uploadResume(file,data) {
      console.log('file',file.raw);
            let formData = new FormData();
            formData.append('file',file.raw);
            formData.append('filename',data);
            axios.post('http://localhost:8000/ahamapi/tutor/upload-resume',formData) 
            .then((response => {
                console.log('uploaded successfully');
            })).catch(error => {
            });   
        },


        handleExceed(files,fileList){
              this.$message.warning('You can select only 1 file');
          },
              
        handleOnchange(file,filelist) {

            console.log('on-change','file: ',file,'filelist: ',filelist);
            this.uploadAvatar(file);
        },

        uploadAvatar(file) {
            console.log('upload avatar',file.raw);
            let formData = new FormData();
            formData.append('avatar', file.raw);
        
            axios.post('http://localhost:8000/ahamapi/tutor/upload-avatar',formData,{
                headers: {
                        'Content-Type': 'multipart/form-data'
                }
            }).then((response => {
                console.log('uploaded successfully');
            })).catch(error => {
            });
            
        },

        handleRemove(file, fileList) {
            console.log('handle remove',file, fileList);
        },
        handlePreview(file) {
            console.log('preview',file);
            this.uploadAvatar(file);
        },

    }
  }
  
    </script>

      
