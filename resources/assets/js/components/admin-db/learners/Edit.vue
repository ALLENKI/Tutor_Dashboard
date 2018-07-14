<template>
    <div>
      <div class="row justify-content-center">
        <div class="col-md-6">


          <div class="m-portlet">
              <div class="m-portlet__head">
                  <div class="m-portlet__head-caption">
                      <div class="m-portlet__head-title">
                          <div class="m-portlet__head-text">
                              Learner Profile
                          </div>
                      </div>
                  </div>
              </div>

              <div class="m-portlet__body">

                <el-form :model="updateLearnerProfile" :rules="updateLearnerProfileRules" ref="updateLearnerProfile">

                  <el-form-item label="Name" prop="name">
                    <el-input v-model="updateLearnerProfile.name"></el-input>
                  </el-form-item>

                  <el-form-item label="Preferred Locations" prop="preferred_locations">
                  <el-select v-model="updateLearnerProfile.preferred_locations" multiple placeholder="Select">
                      <el-option
                      v-for="item in locations"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id">
                      </el-option>
                  </el-select>
                  </el-form-item>

                  <el-form-item>
                    <el-button type="primary" @click="submitForm('updateLearnerProfile')">Create</el-button>
                  </el-form-item>

                </el-form>

              </div>

          </div>



        </div>
      </div>


    </div>
</template>

<script>
import store from "../../../store";
import learner from "../../../services/learner.js";

export default {
    data() {
        return {
            'learner_id': null,
            'learner': null,
            'locations': null,
            'user': null,

            updateLearnerProfile: {
              name:'',
              preferred_locations: []
            },

            updateLearnerProfileRules: {
                name: [
                  {
                    required: true,
                    message: "Please fill name",
                    trigger: "blur"
                  }
                ]
            },
        }
    },
    watch:{

    },
    mounted() {


        this.learner_id = this.$route.params.learner;
        this.getLearner();
        
        axios.get('/hub/available-locations')
          .then((response) => {
                this.locations = response.data.locations;
          })
          .catch((error) => {
            console.log(error);
          });

    },
    computed: {
        error() {
          return this.$store.state.error;
        }
    },
    methods: {

        getLearner(){

            learner.find(this.learner_id).then((response) => {
              this.learner = response.data;
              this.updateLearnerProfile.preferred_locations = _.map(this.learner.preferred_locations, (l) => {
                return l.id;
              });
              this.updateLearnerProfile.name = this.learner.name;
              store.dispatch("setHeading", "Learner - Edit - "+this.learner.name+' ('+this.learner.email+')');
            });

        },

        submitForm(formName) {
          this.$refs[formName].validate(valid => {
            if (valid) {
              if (formName == "updateLearnerProfile") {
                this.update();
              }
            } else {
              console.log("error submit!!");
              return false;
            }
          });
        },
        resetForm(formName) {
          this.$refs[formName].resetFields();
        },

        update(){
          let payload = {
            name: this.updateLearnerProfile.name,
            preferred_locations: this.updateLearnerProfile.preferred_locations
          };

          console.log(payload);

          axios
            .put("admin/learners/"+this.learner_id, payload)
            .then(response => {
              console.log(response);
            })
            .catch(error => {
              console.log(error);
            });

        }


    }
};
</script>
<style>
    .el-select {
        width: 100%;
    }
</style>
