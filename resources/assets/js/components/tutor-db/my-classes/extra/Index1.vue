<template>
<div class="m-portlet m-portlet--tabs m-portlet--success m-portlet--head-solid-bg m-portlet--head-sm">
            <div class="m-portlet__head">
                <div class="m-portlet__head-tools" style="text-align :center">
                    <ul class="nav nav-tabs m-tabs m-tabs-line  m-tabs-line--right" role="tablist">
                        <li class="nav-item m-tabs__item">

                            <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#m_tabs_7_1" style="width:300px" role="tab" aria-selected="false">
                            <i class="m-menu__link-icon la la-ticket"></i>

                                ONGOING
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_7_2" style="width:300px" role="tab" aria-selected="true">
                            <i class="m-menu__link-icon la la-plane"></i>
                                UPCOMING
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_7_3" style="width:300px" role="tab">
                            <i class="m-menu__link-icon la la-shopping-cart"></i>
                                COMPLETED
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

                <div class="m-portlet__body"> 
                    <div class="tab-content">
                         <div class="tab-pane active show" id="m_tabs_7_1" role="tabpanel">
                         <Ongoing></Ongoing>
                         </div>
                         <div class="tab-pane" id="m_tabs_7_2" role="tabpanel">
                         <Upcoming></Upcoming>
                         </div>
                         <div class="tab-pane " id="m_tabs_7_3" role="tabpanel">
                         <Completed></Completed>
                         </div>
                    <div>
                  </div>
                </div>    

           
    </div>
</div>
</template>
<script>
import Ongoing from "./Ongoing";
import Upcoming from "./Upcoming";
import Completed from "./Completed"

export default {
    components: {
    Ongoing,
    Upcoming,
    Completed
  },
}
</script>
<style>

.m-portlet .m-portlet__head {
    display: table;
    padding: 0;
    width: 100%;
    padding: 0 2.2rem;
    height: 4.1rem;
}
</style>  

