<template>
<paginate
    :page-count="this.source.meta.pagination.total_pages"
    :page-range="3"
    :margin-pages="2"
    :click-handler="clickCallback"
    :container-class="'pagination'"
    :page-class="'page-item'">

    <span slot="prevContent" style="padding:2px; text-align:center; width:20%;"><button type="button" :disabled="first_page">&laquo</button></span>
    <span slot="nextContent" style="padding:2px; text-align:center; width:20%;"><button type="button" :disabled="last_page">&raquo</button></span>

  </paginate>
</template>



<script>

export default {
  props :['source'],
  
    data(){
      return{
        first_page :false,
        last_page :false
      }
    },
    methods: {

    clickCallback (page) {
      this.first_page=false;
      this.last_page=false;
      if(page==1)
        this.first_page=true;
      else if(page==this.source.meta.pagination.total_pages)
         this.last_page=true;
      this.$emit('navigate',page)

    },
  

  }
}
</script> 
 
<style lang="css">
.pagination {
  margin:auto;
  width:40%;
}
.page-item {
  border:1px solid #DCDCDC;
  padding:2px;
  text-align:center;
  width:10%;


}
.page-item:hover{
  background-color:CornflowerBlue;
  text-color:white;
  font-weight:bold;

}
button.disabled{
  cursor:not allowed;
}


</style> 