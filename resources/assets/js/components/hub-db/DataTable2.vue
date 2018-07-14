<template>
    <div class="m-portlet" v-if="hub">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Welcome to {{ hub.name }}</div>

                    <div class="panel-body">
                    </div>
                </div>
            </div>
        </div>


        <div class="panel panel-white">
            <table class="table datatable-basic" id="myTable">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Reviewer</th>
                    <th>Date</th>
                    <th>Rating</th>
                    <th>Business</th>
                    <th>Serivce</th>
                    <th>Review</th>
                    <th>Response</th>
                    <th>Status</th>
                   
                </tr>
            </thead>
            <tbody>
            </tbody>
            </table>
        </div>

    </div>
</template>

<script>
import store from "../../store";
import { initDataTable } from '../../initDataTable.js';

export default {
  props:['hub'],
  mounted() {
    store.dispatch("setHeading", "Admin Dashboard");


            $(document).ready(function() {

                initDataTable();

                var table =  $('#myTable').DataTable({
                    "ajax": {
                      "url": baseUrl+"/admin/all-reviews?format=dt",
                      "type": "GET"
                    },
                    initComplete: function() {
                        $.fn.dataTable.defaults.customInitComplete('myTable',table);
                    },
                    "columns": [
                        { "data": "orders.code", "name": "code"  },
                        { "data": "orders.review_user_name", "name": "user"  },
                        { "data": "orders.created_at", "name": "created_at"  },   
                        { "data": "orders.review_rating", "name": "rating"  },
                        { "data": "orders.business_name", "name": "name"  },
                        { "data": "orders.spg_name", "name": "spg_name"  },
                        { "data": "orders.review_comment", "name": "comment"  },
                        { "data": "orders.review_response", "name": "response"  },
                        { "data": "orders.approved", "name": "approved"  },   
                    ],
                });

            });

  }
};
</script>
