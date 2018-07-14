<template>
	<div class="m-portlet" >
		<div class="m-portlet__head">
			<div class="m-portlet__head-caption">
				<div class="m-portlet__head-title">
					<h3 class="m-portlet__head-text">
						{{ahamClass.topic_name}}
					</h3>
				</div>
			</div>
			<div class="m-portlet__head-tools">
				<ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
					<li class="nav-item m-tabs__item">
						<a class="nav-link m-tabs" data-toggle="tab" role="tab">
							{{ahamClass.class_code}}
						</a>
					</li>
					<li class="nav-item m-tabs__item">
						<a class="nav-link m-tabs" data-toggle="tab" role="tab">
							{{ahamClass.enrolled}} enrolled
						</a>
					</li>
					<li class="nav-item m-tabs__item">
						<a class="nav-link m-tabs" data-toggle="tab" role="tab">
							<span class="btn btn-metal" style="padding: 0.65rem 0.75rem">
		                        {{ahamClass.status}}
		                    </span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="m-portlet__body" style="background: white">
			<div class="tab-content">
				<div class="tab-pane active" aria-expanded="true">
					<div class="m-widget5">
						<div class="m-widget5__item">
							<div class="m-widget5__pic">
								 <img class="m-widget7__img" :src="ahamClass.ahamClass.topic.avatar">
							</div>
							<div class="m-widget5__content">
								<h4 class="m-widget5__title">
									{{ahamClass.unit_name}} of {{ahamClass.topic_name}}
								</h4>
								<span class="m-widget5__desc">
									on {{ahamClass.start_date}} from {{ahamClass.start_time}} to {{ahamClass.end_time}}
								</span>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="m-portlet__foot">
			 <router-link :to="{path: '/my-classes/classpage/'+this.ahamClass.class_id  }" exact class="m-menu__item">
				<div class="m-widget6__action m--align-right">
					<button type="button" class="btn m-btn--pill btn-secondary m-btn m-btn--hover-brand m-btn--custom">
						GO TO CLASS PAGE
					</button>
				</div>
			</router-link>
		</div>
	</div>

</template>
<script>
import store from "../../../store";
export default {
  props: ['ahamClass']
};
</script>
<style>
.m-portlet__head{
	height: 50px;
}
</style>




