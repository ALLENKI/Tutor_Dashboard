<template>


    <div class="col-md-4">

         <div class="m-portlet m-portlet--mobile">

            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                      <h3 class="m-portlet__head-text">
                        Details
                      </h3>
                    </div>
                </div>
            </div>
         </div>


    <div class="m-portlet-body">

      <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" @click="changeTopicModal">
                      Change Topic
      </button>
      
    <div id="change_topic_modal" class="modal fade animated" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Change Topic</h4>
                </div>

                <div class="modal-body">

                <el-select v-model="createClassForm.topic" filterable clearable placeholder="Select">
                  <el-option
                    v-for="item in topicTree"
                    :key="item.id"
                    :label="item.name +' ('+item.units.length+'-Units)'"
                    :value="item.id">
                  </el-option>
                </el-select>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-warning" @click="changeTopic">
                    Change Topic
                    </button>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>

</template>

<script>
export default{
data(){
  return{
    topicTree:[]
  };
  
},
methods:{

  getTopicTree() {
          axios.get("/hub/topics/"+this.hub.slug)
            .then(response => {
              this.topicTree = response.data;
             // this.topicList = response.data;
            })
            .catch(error => {
              console.log(error);
            });
      },

  changeTopicModal(){
      $('#change_topic_modal').modal('show');
  },

}
}

</script>