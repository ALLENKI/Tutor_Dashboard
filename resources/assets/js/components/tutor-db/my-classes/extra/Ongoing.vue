<template>
<div>
  <div class="m-portlet m-portlet--accent m-portlet--head-solid-bg m-portlet--head-sm" m-portlet="true"
  id="m_portlet_tools_1">

    <div class="m-portlet__body"  v-if="classes.length">
      <div v-for="item in classes">
               <Card :ahamClass="item"></Card>
          </div>
        <div class="m-portlet-foot">
        <paginator1 :source="pagination" @navigate="navigate"></paginator1>
         </div>
         </div>
         <div class="m-portlet__body" v-else>
        No Ongoing Classes
      </div>
  </div>
  
</div>
</template>
<script>
import store from "../../../store";
import Card from "./Card";
import paginator1 from "./paginator1";


export default {
  components: {
    Card,
    paginator1,
  
  },

  data() {
    return {
      classes: [],
      pagination : null
      
    };
  },

  mounted() {

   this.getOngoing(),
   this.navigate()

  },

  methods:{

    getOngoing()
    {
       axios.get('/tutor/classes?filter=on-going')
      .then((response) => {
            this.classes = response.data.data;
            //this.pagination=response.data;
      })
      .catch((error) => {
        console.log(error);
      });
    },
    
    

    navigate(page){
    axios.get('/tutor/classes?filter=on-going&page='+page)
    .then((response) => {
            this.classes = response.data.data;
            this.pagination=response.data;
            
      })
     

},

}}
</script> 
<style lang="scss" scoped>
.scrolling-wrapper-flexbox {
  display: flex;
  flex-wrap: nowrap;
  overflow-x: auto;
  
  .class-card {
    flex: 0 0 auto;
  }
}

.m-portlet .m-portlet__head.m-portlet__head--fit {
  z-index: 2;
}
</style>
