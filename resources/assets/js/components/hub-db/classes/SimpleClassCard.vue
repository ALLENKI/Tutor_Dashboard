<template>
                
<div class="m-portlet">

    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    # {{ aham_class.code }}
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                    <a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                        <i class="la la-ellipsis-h m--font-brand"></i>
                    </a>
                    <div class="m-dropdown__wrapper">
                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                        <div class="m-dropdown__inner">
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav">

                                        <router-link tag="li" :to="{ name: 'schedule-class', params: {class: aham_class.id} }" class="m-nav__item">
                                            <a class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                <span class="m-nav__link-text">
                                                    Manage Schedule
                                                </span>
                                            </a>
                                        </router-link>

                                        <router-link tag="li" :to="{ name: 'view-class', params: {class: aham_class.id} }" class="m-nav__item">
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

    <div class="m-portlet__body" style="padding: 0.2rem 1.2rem;">
        
        <div class="m-widget19">

            <div class="m-widget19__content">

                <div class="m-widget19__header">

                    <div class="m-widget19__user-img">
                        <img v-if="aham_class.topic" class="m-widget19__img" :src="aham_class.topic.avatar" alt="">
                    </div>

                    <div class="m-widget19__info" style="line-height: 1.5rem;height:62px;">

                        <span class="m-widget19__username">
                            <router-link :to="{ name: 'view-class', params: {class: aham_class.id} }">
                                {{ aham_class.topic_name }}                                
                            </router-link>
                        </span>

                         <span class="m-widget4__status m--font-info" style="display: flex">
							    {{ aham_class.status }}
					    </span>

                    </div>

                </div>

                <div class="m-widget19__body">

					<div class="m-list-search">
						<div class="m-list-search__results">

                            <span href="#" class="m-list-search__result-item" v-if="aham_class.teachers">
                            <span class="m-list-search__result-item-icon">
                                <i class="flaticon-calendar-1 m--font-warning"></i>
                            </span>
                            <span class="m-list-search__result-item-text">
                                Tutors:
                                <span v-for="(tutor,index) in aham_class.teachers">
                                    {{ tutor.name }}
                                    <span v-if="index+1 < aham_class.teachers.length">,</span>
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


							<span href="#" class="m-list-search__result-item" v-if="aham_class.start_date">
							<span class="m-list-search__result-item-icon">
								<i class="flaticon-calendar-1 m--font-warning"></i>
							</span>
							<span class="m-list-search__result-item-text">
								Start Date: {{ aham_class.start_date }}
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
                                Units: {{ aham_class.topic.units.length }}
                            </span>
                            </span>


                            <span href="#" class="m-list-search__result-item">
                            <span class="m-list-search__result-item-icon">
                                <i class="flaticon-calendar-1 m--font-warning"></i>
                            </span>
                            <span class="m-list-search__result-item-text">
                                Enrollment: {{ aham_class.enrolled }}/{{ aham_class.maximum_enrollment }}
                            </span>
                            </span>

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
  props: ['aham_class']
};
</script>
