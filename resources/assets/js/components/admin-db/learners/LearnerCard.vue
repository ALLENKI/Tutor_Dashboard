<template>
                
<div class="m-portlet">

    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    {{ learner.name }}
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

                                        <router-link tag="li" :to="{ name: 'learner-credits', params: {learner: learner.id} }"class="m-nav__item">
                                            <a class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                <span class="m-nav__link-text">
                                                    Manage Credits
                                                </span>
                                            </a>
                                        </router-link>
                                        
                                        <router-link tag="li" :to="{ name: 'learner-edit', params: {learner: learner.id} }"class="m-nav__item">
                                            <a class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                <span class="m-nav__link-text">
                                                    Edit
                                                </span>
                                            </a>
                                        </router-link>

                                        <router-link tag="li" :to="{ name: 'wishlist', params: {learner: learner.id} }"class="m-nav__item">
                                            <a class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                <span class="m-nav__link-text">
                                                    WishList
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
                        <img class="m-widget19__img" :src="learner.avatar" alt="">
                    </div>
                    <div class="m-widget19__info">
                        <span class="m-widget19__username">
                            {{ learner.name }}
                        </span>
                        <br>
                        <span class="m-widget19__time">
                            {{ learner.email }}
                        </span>
                    </div>
                </div>
                <div class="m-widget19__body">

					<div class="m-list-search">
						<div class="m-list-search__results">


							<span href="#" class="m-list-search__result-item">
							<span class="m-list-search__result-item-icon">
								<i class="flaticon-calendar-1 m--font-warning"></i>
							</span>
							<span class="m-list-search__result-item-text">
								Since {{ learner.since }}
							</span>
							</span>

                            <span href="#" class="m-list-search__result-item" v-if="learner.lifetime">
                            <span class="m-list-search__result-item-icon">
                                <i class="flaticon-calendar-1 m--font-warning"></i>
                            </span>
                            <span class="m-list-search__result-item-text">
                                
                                <span class="m-badge m-badge--success m-badge--wide m-badge--rounded">
                                        Lifetime {{ learner.lifetime.coupon.coupon }}
                                </span>

                            </span>
                            </span>

                            <span href="#" class="m-list-search__result-item">
                            <span class="m-list-search__result-item-icon">
                                <i class="flaticon-calendar-1 m--font-warning"></i>
                            </span>
                            <span class="m-list-search__result-item-text">
 
                                Home Location(s) :
                                <span v-if="learner.preferred_locations.length">
                                    <ul class="list-unstyled">
                                        <li v-for="l in learner.preferred_locations">
                                            <small>{{ l.name }}</small>
                                        </li>
                                    </ul>
                                </span>
                                <span v-else>Not Set</span>


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
  props: ['learner']
};
</script>
