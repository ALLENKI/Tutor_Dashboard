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
			<ul class="m-portlet__nav" v-if="units.length">
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


<div class="m-portlet__body" v-if="units.length">
  <div class="row scrolling-wrapper-flexbox" id="today_classes">
	<div class="col-xl-4 class-card" v-for="unit in units">
		<!--begin:: Widgets/Blog-->
		<div class="m-portlet m-portlet--bordered-semi">
			<div class="m-portlet__head m-portlet__head--fit">
				<div class="m-portlet__head-caption">
					<div class="m-portlet__head-action">
						<button type="button" class="btn btn-sm m-btn--pill  btn-brand">
							Today {{ unit.start_time }} to {{ unit.end_time }}
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
						<img :src="unit.topic_avatar" alt="">
						<h4 class="m-widget19__title m--font-light">
							{{ unit.unit }} - {{ unit.topic }}
						</h4>
						<br>
						<div class="m-widget19__shadow"></div>
					</div>
					<div class="m-widget19__content" style="margin-bottom:0px;">
						<div class="m-widget19__header">
							<div class="m-widget19__user-img">
								<img class="m-widget19__img" :src="unit.tutor_avatar" alt="">
							</div>
							<div class="m-widget19__info">
								<span class="m-widget19__username">
									{{ unit.tutor }}
								</span>
								<br>
								<span class="m-widget19__time">
									{{ unit.tutor_email }}
								</span>
							</div>
							<div class="m-widget19__stats">
								<span class="m-widget19__number m--font-brand">
									{{ unit.enrolled }}
								</span>
								<span class="m-widget19__comment">
									Enrollments
								</span>
							</div>
						</div>
						<div class="m-widget19__body">

							<div class="m-list-search">
							<div class="m-list-search__results">

							<span href="#" class="m-list-search__result-item">
							<span class="m-list-search__result-item-icon">
								<i class="flaticon-location m--font-warning"></i>
							</span>
							<span class="m-list-search__result-item-text">
								{{ unit.location }}
							</span>
							</span>

							<span class="m-list-search__result-item">
							<span class="m-list-search__result-item-icon">
								<i class="flaticon-calendar-1 m--font-warning"></i>
							</span>
							<span class="m-list-search__result-item-text">
								{{ unit.date.date }}, {{ unit.start_time }} to {{ unit.end_time }}
							</span>
							</span>

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

<div class="m-portlet__body" v-else>
	No Classes Today
</div>

</div>
</div>

</template>

<script>
import store from "../../store";
export default {
  data() {
    return {
    	units:[]
    };
  },
  mounted() {
  	this.fetchTodayUnits();
    // store.dispatch("setHeading", "Learner Dashboard");
  },
  methods: {
  	fetchTodayUnits(){

		axios.get('/learner/enrolled-units',{
			params:{
				filter:'today'
			}
		})
		  .then((response) => {
		  		console.log(response.data.data);
		        this.units = response.data.data;
		  })
		  .catch((error) => {
		    console.log(error);
		  });

  	},

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