<template>
  
  <div>

	<div class="m-portlet">

	<div class="m-portlet__head">
		<div class="m-portlet__head-caption">
			<div class="m-portlet__head-title">
				<h3 class="m-portlet__head-text">
					Classes Today
				</h3>
			</div>
		</div>

		<div class="m-portlet__head-tools">
			<ul class="m-portlet__nav">
				<li class="m-portlet__nav-item">
					<a class="m-portlet__nav-link m-portlet__nav-link--icon" style="cursor:pointer" @click="scrollLeft()">
						<i class="la la-angle-left"></i>
					</a>
				</li>
				<li class="m-portlet__nav-item">
					<a class="m-portlet__nav-link m-portlet__nav-link--icon" style="cursor:pointer" @click="scrollRight()">
						<i class="la la-angle-right"></i>
					</a>
				</li>
			</ul>
		</div>

	</div>


<div class="m-portlet__body">
  <div class="row scrolling-wrapper-flexbox" id="today_classes">
	<div class="col-xl-4 class-card" v-for="index in 10">
		<!--begin:: Widgets/Blog-->
		<div class="m-portlet m-portlet--bordered-semi">
			<div class="m-portlet__head m-portlet__head--fit">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-action">
						<button type="button" class="btn btn-sm m-btn--pill  btn-brand">
							Today 11 am to 1 pm
						</button>
					</div>
				</div>

				<div class="m-portlet__head-tools">
					<ul class="m-portlet__nav">
						<li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
							<a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl">
								<i class="la la-ellipsis-h m--font-light"></i>
							</a>
							<div class="m-dropdown__wrapper">
								<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 22.5px;"></span>
								<div class="m-dropdown__inner">
									<div class="m-dropdown__body">
										<div class="m-dropdown__content">
											<ul class="m-nav">
												<li class="m-nav__section m-nav__section--first">
													<span class="m-nav__section-text">
														Quick Actions
													</span>
												</li>
												<li class="m-nav__item">
													<a href="" class="m-nav__link">
														<i class="m-nav__link-icon flaticon-share"></i>
														<span class="m-nav__link-text">
															Activity
														</span>
													</a>
												</li>
												<li class="m-nav__item">
													<a href="" class="m-nav__link">
														<i class="m-nav__link-icon flaticon-chat-1"></i>
														<span class="m-nav__link-text">
															Messages
														</span>
													</a>
												</li>
												<li class="m-nav__item">
													<a href="" class="m-nav__link">
														<i class="m-nav__link-icon flaticon-info"></i>
														<span class="m-nav__link-text">
															FAQ
														</span>
													</a>
												</li>
												<li class="m-nav__item">
													<a href="" class="m-nav__link">
														<i class="m-nav__link-icon flaticon-lifebuoy"></i>
														<span class="m-nav__link-text">
															Support
														</span>
													</a>
												</li>
												<li class="m-nav__separator m-nav__separator--fit"></li>
												<li class="m-nav__item">
													<a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">
														Cancel
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>

			</div>
			<div class="m-portlet__body">
				<div class="m-widget19">
					<div class="m-widget19__pic m-portlet-fit--top m-portlet-fit--sides">
						<img src="/dist/media/img/blog/blog1.jpg" alt="">
						<h4 class="m-widget19__title m--font-light">
							Unit 1 - One Dimensional Kinematics
						</h4>
						<br>
						<div class="m-widget19__shadow"></div>
					</div>
					<div class="m-widget19__content" style="margin-bottom:0px;">
						<div class="m-widget19__header">
							<div class="m-widget19__user-img">
								<img class="m-widget19__img" src="/dist/media/img/users/user1.jpg" alt="">
							</div>
							<div class="m-widget19__info">
								<span class="m-widget19__username">
									Ajitha Molly
								</span>
								<br>
								<span class="m-widget19__time">
									UX/UI Designer, Google
								</span>
							</div>
							<div class="m-widget19__stats">
								<span class="m-widget19__number m--font-brand">
									18
								</span>
								<span class="m-widget19__comment">
									Enrollments
								</span>
							</div>
						</div>
						<div class="m-widget19__body">

							<div class="m-list-search">
								<div class="m-list-search__results">
									


							<a href="#" class="m-list-search__result-item">
							<span class="m-list-search__result-item-icon">
								<i class="flaticon-location m--font-warning"></i>
							</span>
							<span class="m-list-search__result-item-text">
								Aham Gachibowli
							</span>
							</a>

							<a href="#" class="m-list-search__result-item">
							<span class="m-list-search__result-item-icon">
								<i class="flaticon-calendar-1 m--font-warning"></i>
							</span>
							<span class="m-list-search__result-item-text">
								26th December 2017, 11:00 AM to 01:00 PM
							</span>
							</a>

								</div>
							</div>

						

						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end:: Widgets/Blog-->
	</div>

</div>

</div>
</div>
</div>

</template>

<script>
import store from "../../store";
export default {
  data() {
    return {
    	previousScrollLeft: -1
    };
  },
  mounted() {
    // store.dispatch("setHeading", "Learner Dashboard");
  },
  methods: {
  	scrollLeft(){
  		$('#today_classes').animate({
		    scrollLeft: '-='+Math.ceil($('#today_classes').outerWidth()/3)
		  }, 400, 'swing');
  	},
  	scrollRight(){

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

        console.log($width, $scrollWidth, $scrollLeft);

  		// if(this.previousScrollLeft !== $('#today_classes').scrollLeft())
  		// {
  		// 	this.previousScrollLeft = $('#today_classes').scrollLeft();
  		// }
  		// else{
  		// 	this.previousScrollLeft = -1;
  		// 	$('#today_classes').animate({
			 //    scrollLeft: 0
			 // }, 400, 'swing');
  		// }

  		
  	}
  }
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