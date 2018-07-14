<template>
	<div class="m-portlet">
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">

		
						{{classes.name}}
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">


					<li class="nav-item m-tabs__item">


					
						<a class="nav-link m-tabs" data-toggle="tab" role="tab">
							{{classes.code}}
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="m-portlet__body">

		
			<div class="m-portlet m-portlet--tabs">
				<div class="m-portlet__head">
					<div class="m-portlet__head-tools">
						<ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">


						<!--<router-link :to="{path: '/my-classes/changetopic' }" exact class="m-menu__item">-->

							<li class="nav-item m-tabs__item">
								<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_1" role="tab">
									<i class="la la-desktop"></i>
										CLASS DETAILS
								</a>
							</li>

						<!--</router-link>-->

							<li class="nav-item m-tabs__item">
								<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_2" role="tab">
									<i class="la la-user"></i>
										LEARNERS
								</a>
							</li>
							<li class="nav-item m-tabs__item">
								<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_3" role="tab">
									<i class="la la-desktop"></i>
										COURSE DETAILS
								</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="m-portlet__body">
					<div class="tab-content">
					
						<div class="tab-pane active" id="m_tabs_6_1" role="tabpanel">
							<Classdetail :classDet="classes"></Classdetail>
					    </div>
							

				
						<div class="tab-pane" id="m_tabs_6_2" role="tabpanel">
							<Learners></Learners>					
						</div>
						<div class="tab-pane" id="m_tabs_6_3" role="tabpanel">
							<Coursedetail :classDet="classes"></Coursedetail>				
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</template>
<script>
import Classdetail from "./Classdetail";
import Coursedetail from "./Coursedetail";

import changetopic from "./changetopic";
import Learners from "./Learners"


export default {

	components: {
    Classdetail,
    Coursedetail,

    changetopic,

    Learners,
  },

  data() {
    return {
      classes: null,
    };
  },

  mounted() {

    axios.get('/tutor/classes/'+this.$route.params.ahamclassid)
      .then((response) => {


            this.classes = response.data;
      })
      .catch((error) => {
        console.log(error);
      });

  },
};
</script>
<style>
</style>
