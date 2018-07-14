<template>
<nav aria-label="Page navigation">
  <ul class="pagination">
     
                    <div class="tab-content">
                      <div class="tab-pane active" id="m_buttons_default" role="tabpanel">                      
                        <div class="m-section">                          
                          <div class="m-section__content">
                            <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                              <div class="m-demo__preview">
                                <div class="btn-group m-btn-group-justified" role="group" aria-label="...">

                                    <li :class="{ disabled: source.meta.pagination.current_page == 1}">
                                     <button type="button" @click="nextPrev($event,source.meta.pagination.current_page-1)" class="btn btn-secondary" aria-label="Next">
                                        <span aria-hidden="true">&laquo;</span>
                                      </button>
                                    </li>  
                                    <li v-for="page in pages" :class="{active: source.meta.pagination.current_page==page}" >
                                       <button type="button" @click.preventDefault="navigate($event,page)" class="btn btn-secondary">
                                         {{page}}
                                       </button>
                                     </li>
                                    <li :class="{ disabled: source.meta.pagination.current_page == source.meta.pagination.total_pages}">
                                     <button type="button"  @click="nextPrev($event,source.meta.pagination.current_page+1)" class="btn btn-secondary" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                      </button>
                                    </li>

                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

  </ul>
</nav>
</template>
<script>
import store from "../../../store";


export default {
  
  props: ['source'],
   
   data()
   {
    return {
      pages: [],
    
    }
   },

  
  mounted() {
    this.pages = this.source.meta.pagination.total_pages;
    this.navigate();
    this.nextPrev();
  },

   methods: {

       navigate(ev,page){   
        this.$emit('navigate',page)
       },

       nextPrev(ev,page)
       {
        
        if(page == 0 || page==this.source.meta.pagination.total_pages+1){
                 return;
        }
        this.navigate(ev,page)
       },
  


  },
  }
</script>
