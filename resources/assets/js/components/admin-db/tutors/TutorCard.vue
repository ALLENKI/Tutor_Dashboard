<template>
                
<div class="m-portlet">

    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <h3 class="m-portlet__head-text">
                    {{ tutor.name }}
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

                                        <router-link tag="li" :to="{ name: 'tutor-payment-settings', params: {tutor: tutor.id} }"class="m-nav__item">
                                            <a class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                <span class="m-nav__link-text">
                                                    Payment Settings
                                                </span>
                                            </a>
                                        </router-link>
                                        
                                        <router-link tag="li" :to="{ name: 'tutor-settlements', params: {tutor: tutor.id} }"class="m-nav__item">
                                            <a class="m-nav__link">
                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                <span class="m-nav__link-text">
                                                    Settlements
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
                        <img class="m-widget19__img" :src="tutor.avatar" alt="">
                    </div>
                    <div class="m-widget19__info">
                        <span class="m-widget19__username">
                            {{ tutor.name }}
                        </span>
                        <br>
                        <span class="m-widget19__time">
                            {{ tutor.email }}
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
								Since {{ tutor.since }}
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
  props: ['tutor']
};
</script>
