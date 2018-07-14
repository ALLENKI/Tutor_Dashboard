<template>
                    
    <div class="m-portlet m-portlet--head-solid-bg m-portlet--bordered">

        <div class="m-portlet__head">

            <div class="m-portlet__head-caption">

                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-thumb-tack"></i>
                    </span>
                    <h5 class="m-portlet__head-text">
                        {{ title }} 
                    </h5>
               </div>

            </div>

        </div>

       <div class="m-portlet__body">

           <div class="row" v-if="aham_units">

                <div class="col-xl-4 class-card" v-for="aham_unit in aham_units">

                    <div class="m-portlet m-portlet--bordered m-portlet--rounded m-portlet--last">

                        <div class="m-portlet__head">

                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">
                                        # {{ aham_unit.class_code }}
                                    </h3>
                                </div>
                            </div>

                            <div class="m-portlet__head-tools">
                                <ul class="m-portlet__nav">
                                    <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                        <a href="#" class="m-portlet__nav-link btn btn-light m-btn m-btn--pill m-btn--air">
                                            <i class="la la-ellipsis-h m--font-brand"></i>
                                        </a>
                                        <div class="m-dropdown__wrapper">
                                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">
                                                        <ul class="m-nav">

                                                            <router-link tag="li" :to="{ name: 'schedule-class', params: {class: aham_unit.ahamClass.id} }" class="m-nav__item">
                                                                <a class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-share"></i>
                                                                    <span class="m-nav__link-text">
                                                                        Manage Schedule
                                                                    </span>
                                                                </a>
                                                            </router-link>

                                                            <router-link tag="li" :to="{ name: 'view-class', params: {class: aham_unit.ahamClass.id} }" class="m-nav__item">
                                                                <a class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-share"></i>
                                                                    <span class="m-nav__link-text">
                                                                    View Details
                                                                    </span>
                                                                </a>
                                                            </router-link>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>

                        <div class="m-portlet__body" style="">
                            
                            <div class="m-widget19">

                                <div class="m-widget19__content">   

                                    <div class="m-widget19__header">
                                        <div class="m-widget19__user-img">
                                            <img class="m-widget19__img" :src="aham_unit.ahamClass.topic.avatar" alt="">
                                        </div>
                                        <div class="m-widget19__info" style="line-height: 1.5rem;height:62px;">
                                            
                                            <span class="m-widget19__username">

                                                <router-link  :to="{ name: 'view-class', params: {class: aham_unit.ahamClass.id} }">
                                                    {{ aham_unit.topic_name }}
                                               </router-link>

                                            </span>
                                            
                                            <span class="m-widget4__status m--font-info" style="display: flex;">
                                                <b> {{ aham_unit.unit_name }} </b> 
                                            </span>
                                        </div>
                                    </div>

                                    <div class="m-widget19__body">

                                        <div class="m-list-search">
                                            <div class="m-list-search__results">

                                                <span href="#" class="m-list-search__result-item" v-if="aham_unit.unit_teacher">
                                                    <span class="m-list-search__result-item-icon">
                                                        <i class="flaticon-calendar-1 m--font-warning"></i>
                                                    </span>
                                                    <span class="m-list-search__result-item-text">
                                                        Tutors:
                                                        <span>
                                                            {{ aham_unit.unit_teacher }}
                                                        </span>
                                                    </span>
                                                </span>

                                                <span href="#" class="m-list-search__result-item" v-else>
                                                        <span class="m-list-search__result-item-icon">
                                                            <i class="flaticon-calendar-1 m--font-warning"></i>
                                                        </span>
                                                        <span class="m-list-search__result-item-text">
                                                            Tutors:
                                                            Not Assigned
                                                        </span>
                                                </span>

                                                <span href="#" class="m-list-search__result-item" v-if="aham_unit.start_time && aham_unit.end_time">
                                                    <span class="m-list-search__result-item-icon">
                                                        <i class="flaticon-calendar-1 m--font-warning"></i>
                                                    </span>
                                                    <span class="m-list-search__result-item-text">
                                                        Start from: {{ aham_unit.start_time }}
                                                        -
                                                        {{ aham_unit.end_time }}
                                                    </span>
                                                </span>

                                                <span href="#" class="m-list-search__result-item" v-else>
                                                    <span class="m-list-search__result-item-icon">
                                                        <i class="flaticon-calendar-1 m--font-warning"></i>
                                                    </span>
                                                    <span class="m-list-search__result-item-text">
                                                        Not Scheduled
                                                    </span>
                                                </span>

                                                <span href="#" class="m-list-search__result-item">
                                                    <span class="m-list-search__result-item-icon">
                                                        <i class="flaticon-calendar-1 m--font-warning"></i>
                                                    </span>
                                                    <span class="m-list-search__result-item-text">
                                                        Units: {{ aham_unit.ahamClass.topic.units.length }}
                                                    </span>
                                                </span>

                                                <span href="#" class="m-list-search__result-item">
                                                    <span class="m-list-search__result-item-icon">
                                                        <i class="flaticon-calendar-1 m--font-warning"></i>
                                                    </span>
                                                    <span class="m-list-search__result-item-text">
                                                        Enrolled: {{ aham_unit.enrolled }}
                                                    </span>
                                                </span>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

           </div>

       </div>
        
    </div>


</template>

<script>
import store from "../../../store";
export default {
     props: ['aham_units','clocation','title'],
    data() {
        return {

        };
    },
    methods: {

            scrollLeft() {
                $('#today_classes').animate({
                    scrollLeft: '-='+Math.ceil($('#today_classes').outerWidth()/3)
                }, 400, 'swing');
            },

            scrollRight() {

                $('#today_classes').animate({
                    scrollLeft: '+='+Math.ceil($('#today_classes').outerWidth()/3)
                }, 400, 'swing');


                var $width = parseInt($('#today_classes').outerWidth());
                var $scrollWidth = $('#today_classes')[0].scrollWidth; 
                var $scrollLeft = $('#today_classes').scrollLeft();

                if ($scrollWidth - $width === $scrollLeft){

                    $('#today_classes').animate({
                        scrollLeft: 0
                    }, 400, 'swing');

                }
                
            },

    },

};
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
