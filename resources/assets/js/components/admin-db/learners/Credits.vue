<template>
    <div>
        <div class="row">

            <div class="col-md-6">

                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-portlet__head-text" v-if="analytics">
                                    Buckets
                                    (Total Remaining: {{ analytics.total_remaining }})
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="m-portlet__body">

                    <span class="m-badge m-badge--success m-badge--wide">
                    Purchased
                    </span>

                    <span class="m-badge m-badge--info m-badge--wide">
                    Promotional
                    </span>

                    <span class="m-badge m-badge--warning m-badge--wide">
                    Hub Only
                    </span>

                    <hr>

                        <table class="table table-bordered m-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Total Pur/Pro/Hub</th>
                                    <th>Total</th>
                                    <th>Rem Pur/Pro/Hub</th>
                                    <th>Remaining</th>
                                    <th>Type</th>
                                    <th>Hub</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="bucket in buckets">
                                    <td>{{ bucket.id }}</td>
                                    <td>
                                        <span class="m-badge m-badge--success m-badge--wide" v-if="bucket.purchased_total > 0">
                                            {{ bucket.purchased_total }}
                                        </span>
                                        <span class="m-badge m-badge--info m-badge--wide" v-if="bucket.promotional_total > 0">
                                            {{ bucket.promotional_total }}
                                        </span>
                                        <span class="m-badge m-badge--warning m-badge--wide" v-if="bucket.hub_only_total > 0">
                                            {{ bucket.hub_only_total }}
                                        </span>
                                    </td>
                                    <td>{{ bucket.total_credits }}</td>
                                    <td>
                                        <span class="m-badge m-badge--success m-badge--wide" v-if="bucket.purchased_total > 0">
                                            {{ bucket.purchased_remaining }}
                                        </span>
                                        <span class="m-badge m-badge--info m-badge--wide" v-if="bucket.promotional_total > 0">
                                            {{ bucket.promotional_remaining }}
                                        </span>
                                        <span class="m-badge m-badge--warning m-badge--wide" v-if="bucket.hub_only_total > 0">
                                            {{ bucket.hub_only_remaining }}
                                        </span>
                                    </td>
                                    <td>{{ bucket.total_remaining }}</td>
                                    <td>{{ bucket.currency_type }}</td>
                                    <td><small>{{ bucket.hub }}</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-portlet__head-text">
                                    Purchased

                                    <span class="m-badge m-badge--success m-badge--wide m-badge--rounded" v-if="lifetime">
                                        Lifetime {{ lifetime.coupon.coupon }}
                                    </span>

                                </div>
                            </div>
                        </div>

                      <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                          <li class="m-portlet__nav-item">
                            <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" data-toggle="modal" data-target="#add_purchased_credits">
                              Add
                            </button>
                          </li>
                        </ul>
                      </div>

                    </div>

                    <div class="m-portlet__body">
                        <table class="table table-bordered m-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Credits</th>
                                    <th>Price</th>
                                    <th>Method</th>
                                    <th>Hub</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="purchase in purchased">
                                    <td>{{ purchase.id }}</td>
                                    <td>{{ purchase.credits }} {{ purchase.currency_type }}</td>
                                    <td>{{ purchase.price }}</td>
                                    <td>{{ purchase.method }}</td>
                                    <td><small>{{ purchase.hub }}</small></td>
                                    <td><small>{{ purchase.remarks }}</small></td>
                                    <td><small>{{ purchase.added_on }}</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-portlet__head-text">
                                    Promotional
                                </div>
                            </div>
                        </div>

                      <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                          <li class="m-portlet__nav-item">
                            <button type="button" class="m-portlet__nav-link btn btn-success m-btn m-btn--pill m-btn--air" data-toggle="modal" data-target="#add_promotional_credits">
                              Add
                            </button>
                          </li>
                        </ul>
                      </div>

                    </div>

                    <div class="m-portlet__body">
                        <table class="table table-bordered m-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Credits</th>
                                    <th>Coupon</th>
                                    <th>Hub</th>
                                    <th>Remarks</th>
                                    <th>Purchased?</th>
                                    <th>Date?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="purchase in promotional">
                                    <td>{{ purchase.id }}</td>
                                    <td>{{ purchase.credits }} {{ purchase.currency_type }}</td>
                                    <td><small>{{ purchase.coupon }}</small></td>
                                    <td><small>{{ purchase.hub }}</small></td>
                                    <td><small>{{ purchase.remarks }}</small></td>
                                    <td>
                                        <span v-if="purchase.purchased_item">
                                            <ul class="list-unstyled">
                                                <li><small>Refer to {{ purchase.purchased_item.id }}</small></li>
                                                <li><small>Bought {{ purchase.purchased_item.credits }} credits</small></li>
                                            </ul>
                                        </span>
                                    </td>
                                    <td><small>{{ purchase.added_on }}</small></td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>

            <div class="col-md-6">

                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-portlet__head-text" v-if="analytics">
                                    Usage (Used: {{ analytics.used }}, Refunded: {{ analytics.refunded }})
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="m-portlet__body">
                        <table class="table table-bordered m-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Credits</th>
                                    <th>Refunded</th>
                                    <th>Class</th>
                                    <th>Bucket</th>
                                </tr>
                            </thead>
                            <tbody style="height:200px;overflow-y:auto;">
                                <tr v-for="use in used">
                                    <td>{{ use.id }}</td>
                                    <td>{{ use.credits }}</td>
                                    <td>{{ use.refunded }}</td>
                                    <td>{{ use.class }}</td>
                                    <td>{{ use.credits_type }} from {{ use.bucket_id }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-portlet__head-text">
                                    Hub Only
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="m-portlet__body">
                        <table class="table table-bordered m-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Credits</th>
                                    <th>Price</th>
                                    <th>Method</th>
                                    <th>Hub</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="purchase in hub_only">
                                    <td>{{ purchase.id }}</td>
                                    <td>{{ purchase.credits }} {{ purchase.currency_type }}</td>
                                    <td>{{ purchase.price }}</td>
                                    <td>{{ purchase.method }}</td>
                                    <td><small>{{ purchase.hub }}</small></td>
                                    <td><small>{{ purchase.remarks }}</small></td>
                                    <td><small>{{ purchase.added_on }}</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

             <div class="col-md-12">

                 <div class="m-portlet">
                    
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <div class="m-portlet__head-text">
                                    Credits Statment
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li v-if="creditsStatments" class="m-portlet__nav-item">
                                  total Credits:  {{summary.total_credits}}  &nbsp;
                                  total Debits:  {{summary.total_debits}}  &nbsp;
                                  Balance: {{summary.balance}}  &nbsp;
                                </li>
                            </ul>
                        </div>
                    </div>

                    <table class="table table-bordered m-table">
                        <thead>
                            <tr>
                                <th>
                                    Date
                                </th>
                                <th>
                                    Method
                                </th>
                                <th>
                                    Credit
                                </th>
                                <th>
                                    Debit
                                </th>
                                <th>
                                    Remarks
                                </th>
                                <th>
                                    Invoice
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="statement in creditsStatments">
                                   <td> {{ statement.date }} </td>
                                   <td> {{ statement.method }} </td>
                                   <td v-if="statement.type == 'credit'"> {{ statement.credits }} </td>
                                   <td> </td>
                                   <td v-if="statement.type == 'debit'"> {{ statement.credits }} </td>
                                   <td > <span v-html=" statement.remarks "> </span> </td>
                                   <td v-if="statement.invoice"> <span v-html="statement.invoice">{{statement.invoice}}</span> </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

        <div class="modal fade" id="add_purchased_credits" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                    Add Purchased Credits
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                    </button>
                    </div>

                    <el-form :model="purchasedCreditsForm" :rules="purchasedCreditsFormRules" ref="purchasedCreditsForm">

                    <div class="modal-body">

                    <el-form-item label="Hub" prop="hub">
                        <el-select label="Hub" v-model="purchasedCreditsForm.hub" placeholder="Select">
                                <el-option
                                v-for="item in locations"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                                </el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="No. of Credits" prop="credits">
                        <el-input v-model="purchasedCreditsForm.credits"></el-input>
                    </el-form-item>

                    <el-form-item label="Unit Price" prop="unit_price">
                        <el-input v-model="purchasedCreditsForm.unit_price" :value="1100"></el-input>
                    </el-form-item>

                    <el-form-item label="Total Amount" prop="amount">
                        <el-input v-model="purchasedCreditsForm.amount" :value="purchasedCreditsForm.credits*purchasedCreditsForm.unit_price">
                        </el-input>
                    </el-form-item>

                    <el-form-item label="Method" prop="method">
                        <el-select label="Hub" v-model="purchasedCreditsForm.method" placeholder="Select">
                                <el-option
                                v-for="item in methods"
                                :key="item.name"
                                :label="item.name"
                                :value="item.name">
                                </el-option>
                        </el-select>
                    </el-form-item>

                    <el-form-item label="Coupon" prop="coupon" v-if="this.purchasedCreditsForm.credits_type == 'global'">
                        <el-input v-model="purchasedCreditsForm.coupon"></el-input>
                    </el-form-item>

                    <el-form-item label="Remarks" prop="remarks">
                        <el-input
                        type="textarea"
                        :rows="2"
                        v-model="purchasedCreditsForm.remarks">
                        </el-input>
                    </el-form-item>

                    <div class="alert alert-danger" v-if="error" v-html="error">
                    </div>

                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('purchasedCreditsForm')">
                    Close
                    </button>
                    <button type="button" class="btn btn-primary"  @click="submitForm('purchasedCreditsForm')">
                    Save changes
                    </button>
                    </div>

                    </el-form>

                </div>
            </div>
        </div>

        <div class="modal fade" id="add_promotional_credits" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Add Promotional Credits
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">
                &times;
              </span>
            </button>
            </div>

            <el-form :model="promotionalCreditsForm" :rules="promotionalCreditsFormRules" ref="promotionalCreditsForm">

            <div class="modal-body">


            <el-form-item label="Hub" prop="hub">
                <el-select label="Hub" v-model="promotionalCreditsForm.hub" placeholder="Select">
                        <el-option
                        v-for="item in locations"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                        </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="Method" prop="method">
                <el-select label="Hub" v-model="promotionalCreditsForm.method" placeholder="Select">
                        <el-option
                        v-for="item in promotional_methods"
                        :key="item.name"
                        :label="item.name"
                        :value="item.name">
                        </el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="No. of Credits" prop="credits" v-if="this.promotionalCreditsForm.method == 'credits'">
                <el-input v-model="promotionalCreditsForm.credits"></el-input>
            </el-form-item>

            <el-form-item label="Coupon" prop="coupon" v-if="this.promotionalCreditsForm.method == 'coupon'">
            <el-input v-model="promotionalCreditsForm.coupon"></el-input>
            </el-form-item>

            <el-form-item label="Remarks" prop="remarks">
            <el-input
              type="textarea"
              :rows="2"
              v-model="promotionalCreditsForm.remarks">
            </el-input>
            </el-form-item>

            <div class="alert alert-danger" v-if="error" v-html="error">
            </div>

            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="resetForm('promotionalCreditsForm')">
              Close
            </button>
            <button type="button" class="btn btn-primary"  @click="submitForm('promotionalCreditsForm')">
              Save changes
            </button>
            </div>

             </el-form>

        </div>
        </div>
        </div>

    </div>
</template>

<script>
import store from "../../../store";

export default {
    data() {
        return {
            'learner_id': null,
            'learner': null,
            'user': null,
            'analytics': null,
            'purchased': null,
            'promotional': null,
            'hub_only': null,
            'used': null,
            'buckets': null,
            'locations': [],
            'lifetime':null,
            'summary': null,

            methods: [
                {'name': 'cash'},
                {'name': 'cheque'},
                {'name': 'online_transfer'},
            ],

            promotional_methods: [
                {'name': 'coupon'},
                {'name': 'credits'},
            ],

            promotionalCreditsForm: {
                method: "coupon",
                coupon:null,
                credits:0,
                remarks:'',
                hub:''
            },

            promotionalCreditsFormRules: {
                hub: [
                  {
                    required: true,
                    message: "Please select Hub",
                    trigger: "blur"
                  }
                ]
            },

            purchasedCreditsForm: {
                hub: "",
                credits_type:null,
                unit_price:1100,
                coupon:'',
                amount:0,
                remarks:'',
                credits:'',
                method:'cash'
            },
            purchasedCreditsFormRules: {
                hub: [
                  {
                    required: true,
                    message: "Please select Hub",
                    trigger: "blur"
                  }
                ],
                credits: [
                  {
                    required: true,
                    message: "Please select Hub",
                    trigger: "blur"
                  }
                ],
                amount: [
                  {
                    required: true,
                    message: "Please select Hub",
                    trigger: "blur"
                  }
                ]
            },
            creditsStatments : null,
        }
    },
    watch:{
        'purchasedCreditsForm.hub' : function(){
            let location = _.find(this.locations, (location) => { return location.id == this.purchasedCreditsForm.hub; });
            this.purchasedCreditsForm.credits_type = location.credits_type;
        }
    },
    mounted() {

        this.$watch(
            vm => [
                vm.purchasedCreditsForm.credits, 
                vm.purchasedCreditsForm.unit_price
            ].join(), 
            val => {
                this.purchasedCreditsForm.amount = parseFloat(this.purchasedCreditsForm.credits)*parseFloat(this.purchasedCreditsForm.unit_price);
            }
        );

        this.$watch('purchasedCreditsForm.hub',function(){
            let location = _.find(this.locations, (location) => { return location.id == this.purchasedCreditsForm.hub; });
            this.purchasedCreditsForm.credits_type = location.credits_type;

            if(this.lifetime)
            {
                this.purchasedCreditsForm.coupon = this.lifetime.coupon.coupon;
            }
            
        });


        this.$watch('promotionalCreditsForm.method',function(){
            this.promotionalCreditsForm.credits = 0;
            this.promotionalCreditsForm.coupon = '';
        });

        this.learner_id = this.$route.params.learner;
        store.dispatch("setHeading", "Credits");
        this.getCredits();
        this.getStatements();
    },
    computed: {
        error() {
          return this.$store.state.error;
        }
    },
    methods: {

        submitForm(formName) {
          this.$refs[formName].validate(valid => {
            if (valid) {
              if (formName == "purchasedCreditsForm") {
                this.addPurchasedCredits();
              }

              if (formName == "promotionalCreditsForm") {
                this.addPromotionalCredits();
              }
            } else {
              console.log("error submit!!");
              return false;
            }
          });
        },
        resetForm(formName) {
          this.$refs[formName].resetFields();
        },

        addPromotionalCredits(){
            let payload = {
                'hub_id': this.promotionalCreditsForm.hub,
                'credits': this.promotionalCreditsForm.credits,
                'coupon': this.promotionalCreditsForm.coupon,
                'method': this.promotionalCreditsForm.method,
                'remarks': this.promotionalCreditsForm.remarks,
            };

            axios
                .post("/learners/"+this.learner_id+"/add-promotional-credits",payload)
                .then(response => {

                  this.getCredits();
                  this.getStatements();
                  $("#add_promotional_credits").modal("hide");

                })
                .catch(error => {
                  console.log(error);
                });


            console.log(payload);
        },




        addPurchasedCredits() {
            let payload = {
                'hub_id': this.purchasedCreditsForm.hub,
                'credits': this.purchasedCreditsForm.credits,
                'amount': this.purchasedCreditsForm.amount,
                'coupon': this.purchasedCreditsForm.coupon,
                'credits_type': this.purchasedCreditsForm.credits_type,
                'method': this.purchasedCreditsForm.method,
                'remarks': this.purchasedCreditsForm.remarks,
            };

            axios
                .post("/learners/"+this.learner_id+"/add-purchased-credits",payload)
                .then(response => {

                  this.getCredits();
                  $("#add_purchased_credits").modal("hide");

                })
                .catch(error => {
                  console.log(error);
                });

            console.log(payload);
        },

        getCredits() {

        axios
            .get("/learners/"+this.learner_id+"/credits")
            .then(response => {

              this.learner = response.data.learner;
              this.user = response.data.user;
              this.purchased = response.data.purchased;
              this.promotional = response.data.promotional;
              this.hub_only = response.data.hub_only;
              this.used = response.data.used;
              this.analytics = response.data.analytics;
              this.buckets = response.data.buckets;
              this.locations = response.data.locations;
              this.lifetime = response.data.lifetime;

              store.dispatch(
                "setHeading",
                "Credits History : " + this.user.name 
              );

            })
            .catch(error => {
              console.log(error);
            });

        },

        getStatements() {
             axios
                .get("/learners/"+this.learner_id+"/get-credits")
                .then(response => {
                    this.creditsStatments = response.data.data;
                    this.summary = response.data.summary;
                })
                .catch(error => {
                 console.log(error);
                });

        },

    }
};
</script>
<style>
    .el-select {
        width: 100%;
    }
</style>
