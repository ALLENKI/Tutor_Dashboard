<template>
    <div>

    <el-date-picker
      v-model="dateRange"
      type="daterange"
      range-separator="To"
      start-placeholder="Start date"
      end-placeholder="End date">
    </el-date-picker>

    <button type="button" @click="filter">Fetch</button>

    <div class="row">
    	<div class="col-md-12" v-if="response">
    		
    		<div class="m-portlet">
    			<div class="m-portlet__head">

  					<div class="m-portlet__head-caption">
  						<div class="m-portlet__head-title">
  							<h3 class="m-portlet__head-text">
  								Earnings - From {{ response.start_date }} to {{ response.end_date }}
  							</h3>
  						</div>
  					</div>

    			</div>

          <div class="m-portlet__body">

            <div class="row">

              <div class="col-md-6">
              <table class="table table-bordered">
                <thead>
                  <th>Total Earned</th>
                  <th>From Purchased</th>
                  <th>From Promotional</th>
                  <th>From Hub Only</th>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ response.totals.final_total }}</td>
                    <td>{{ response.totals.purchased }}</td>
                    <td>{{ response.totals.promotional }}</td>
                    <td>{{ response.totals.hub_only }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            
              <div class="col-md-3">
                <h5>According to Enrollments</h5>
                <ul class="list-unstyled">
                  <li>Total (with refunds): {{ response.credits_sum_from_enrollments }}</li>
                  <li>Total: {{ response.credits_total_from_enrollments }}</li>
                  <li>
                      <span class="m-badge m-badge--success m-badge--wide" v-if="response.credits_sum_usage_table == response.credits_sum_from_enrollments">
                      Recon Success
                      </span>
                      <span class="m-badge m-badge--danger m-badge--wide" v-else>
                      Recon Failed
                      </span>
                  </li>
                </ul>
              </div>

              <div class="col-md-3">
                <h5>Actually From Credits</h5>
                <ul class="list-unstyled">
                  <li>Total (with refunds): {{ response.credits_sum_usage_table }}</li>
                  <li>Total: {{ response.credits_total_usage_table }}</li>
                  <li>Refunds: {{ response.refunds_from_usage_table }}</li>
                </ul>
                <h6>Outliers</h6>
                <ul class="list-unstyled">
                  <li v-for="outlier in response.outliers">
                    {{ outlier.class_id }} on {{ outlier.date }}
                  </li>
                </ul>
              </div>
            </div>

            <hr>

            <span class="m-badge m-badge--success m-badge--wide">
            D - Deducted
            </span>

            <span class="m-badge m-badge--success m-badge--wide">
            R - Refunded
            </span>

            <table class="table table-bordered">
              <thead>
                <th>#</th>
                <th>Topic</th>
                <th>Date</th>
                <th>
                  Breakdown
                </th>
                <th>Total Earned</th>
              </thead>
              <tbody>
                <tr v-for="classWiseCreditUsage in response.classWiseCreditsUsage">
                  <td>{{ classWiseCreditUsage.class_id }}</td>
                  <td>
                  {{ classWiseCreditUsage.class }}
                  </td>
                  <td>{{ classWiseCreditUsage.start_date }}</td>
                  <td>
                    <table class="table credit-enrollment">
                      <tbody>
                        <tr>
                          <td>User</td>
                          <td>Credits</td>
                          <td>U.Price</td>
                          <td>Type</td>
                          <td>Earned</td>
                        </tr>
                        <tr v-for="creditUsage in classWiseCreditUsage.credits_usage">
                          <td>{{ creditUsage.user }}</td>
                          <td>{{ creditUsage.refund_remaining }} (D- {{ creditUsage.credits }}, R- {{ creditUsage.credits-creditUsage.refund_remaining }})</td>
                          <td>{{ creditUsage.credit_price }}</td>
                          <td>{{ creditUsage.credits_type }}</td>
                          <td>{{ creditUsage.earned }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td>{{ classWiseCreditUsage.totalEarned }}</td>
                </tr>
              </tbody>
            </table>

            <hr>

            <div class="row">
              <div class="col-md-4">
                <h5>According to Enrollments</h5>
                <ul class="list-unstyled">
                  <li>Total (with refunds): {{ response.credits_sum_from_enrollments }}</li>
                  <li>Total: {{ response.credits_total_from_enrollments }}</li>
                </ul>
              </div>

              <div class="col-md-4">
                <h5>Actually From Credits</h5>
                <ul class="list-unstyled">
                  <li>Total (with refunds): {{ response.credits_sum_usage_table }}</li>
                  <li>Total: {{ response.credits_total_usage_table }}</li>
                  <li>Refunds: {{ response.refunds_from_usage_table }}</li>
                </ul>
              </div>
            </div>

            <div>
              <h5>Failed Reconcialition:</h5>
              <hr>
              <div v-for="failed in response.recon_enrollment_failed" style="display:block;" v-if="response.recon_enrollment_failed.length">

                <h5>Class Id: {{ failed.class_id }}</h5>

                <ul class="list-unstyled">
                  
                  <li>Actual Credits Deducted: {{ failed.credits_actually_deducted }}</li>
                  <li>Actual Should be Deducted: {{ failed.credits_should_deducted }}</li>
                  <li>Total: {{ response.credits_total_from_enrollments }}</li>
                </ul>

                <table class="table">
                  <thead>
                    <th>Student</th>
                    <th>Status</th>
                    <th>Should Credits</th>
                    <th>Actual Credits</th>
                  </thead>
                  <tbody>
                    <tr v-for="enrollment in failed.enrollments">
                      <td>{{ enrollment.student }}</td>
                      <td>{{ enrollment.status }}</td>
                      <td>{{ enrollment.credits_should_deducted }}</td>
                      <td>{{ enrollment.credits_actually_deducted }}</td>
                    </tr>
                  </tbody>
                </table>
                <hr>
              </div>
              <div v-else>
                NONE
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
import moment from "moment";

export default {

  components: {

  },

  data() {
    return {
      dateRange:[],
    	hub_slug: null,
      hub: null,
    	response: null,
    };
  },

  mounted() {
    store.dispatch("setHeading", "Hub - Earnings");

    this.dateRange.push(moment().startOf('month').format('YYYY-MM-DD'));
    this.dateRange.push(moment().endOf('month').format('YYYY-MM-DD'));

    this.hub_slug = this.$route.params.hub;

    axios.get('/hub/location/'+this.hub_slug).then((response) => {            	
    	this.hub = response.data.location;
      store.dispatch("setHeading", "Hub - Earnings - "+this.hub.name);
    })
    .catch((error) => {

    	console.log(error);

    });

    this.filter();

  },

  methods:{

    filter(){
      axios.get('/hub/'+this.hub_slug+'/earnings',{
        params:{
          'dateRange': this.dateRange
        }
      }).then((response) => {              
        this.response = response.data;
      })
      .catch((error) => {

        console.log(error);

      });
    }

  }

}

</script>

<style type="text/css" scoped>
	.el-select{
		width: 100%;
	}
  .el-date-editor {
    width: 50%;
  }
  .credit-enrollment th, .credit-enrollment td {
      border: 1px solid #548db2;
  }
</style>