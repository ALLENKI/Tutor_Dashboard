<template>
    <div class="row">
        <div class="col-lg-8">
            <div class="m-portlet">
				<div class="m-portlet__body">
					<div v-for="item in classDet.timings">
						<h1>{{item.unit}}</h1>
						<span style="font-size:10px;font-weight:100">
							<span style="font-size:10px;font-weight:100" v-html="item.class_unit.description" ></span>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="m-portlet m-portlet--tab">
				<div class="m-portlet__body" style="height:600px">
					<div class="m-widget13">
						<div style="height:300px;">
							<div>
								<img :src="classDet.topic.avatar" style="height:280px;width:230px">
							</div><br />
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									{{classDet.name}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									units: {{classDet.no_of_units}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									subcategory: {{classDet.topic.subCategory}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									subject: {{classDet.topic.subject}}
								</span>
							</div>
							<div class="m-widget13__item">
								<span class="m-widget13__desc">
									Tags: 
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
  props: ['classDet']
  };
</script>