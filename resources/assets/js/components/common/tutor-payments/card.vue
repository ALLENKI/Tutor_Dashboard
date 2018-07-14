<template>

    <div class="row my-5">

        <div class="m-portlet m-portlet--head-solid-bg m-portlet--bordered col-12">

            <div class="m-portlet__head">

                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            {{ data.label }}

                             <a @click="showModel(data.day)" class="btn m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                                <i class="flaticon-add">

                                </i>
                             </a>

                        </h3>
                    </div>
                </div>

            </div>

            <div class="m-portlet__body">

                <table class="table m-table m-table--head-separator-danger">

                        <thead> 

                            <tr>
                                <th>
                                Timings
                                </th>
                                <th>
                                Commission Type
                                </th>
                                <th>
                                Settlement Type
                                </th>
                                <th>
                                Commission Value
                                </th>
                                <th>
                                Min Enrollment
                                </th>
                                <th>
                                Max Enrollment
                                </th>
                            </tr> 

                        </thead>

                        <tbody v-for="item in data.timings">

                            <tr>

                                <th scope="row">
                                    From: {{ item.from }} || To: {{ item.to }}
                                </th>

                                <th scope="row">
                                    {{ item.commission_type}}
                                </th>

                                <th scope="row">
                                    {{ item.settlement_type}}
                                </th>

                                <th scope="row">
                                    {{ item.commission_value}}
                                </th>

                                <th scope="row">
                                    {{ item.min_enrollment}}
                                </th>

                                <th scope="row">
                                    {{ item.max_enrollment}}
                                </th>

                                <a @click="destroy(data.day,item)" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                                    <i class="flaticon-circle">

                                    </i>
                                </a>
                                
                                <a @click="edit(data.day,item,'edit')" class="btn btn-success m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill">
                                    <i class="flaticon-edit-1">

                                    </i>
                                </a>

                            </tr>
        
                        </tbody>

                </table>

            </div>

        </div>

    </div>

</template>

<script>

export default {

    props: ['data'],

    data() {
        return {

            tutorPreferences: {
                    "tutor_id": 1,
                    "location_id": 10,
                    "timings": 
                    [
                        {
                            "day": "sunday",
                            "timings": 
                            [
                                {"from": "9:00", "to":"18:00", "commission_type":"percentage","settlement_type":"fixed","commission_value": 30,"min_enrollment": 5,"max_enrollment": 10},
                                {"from": "6:00", "to":"18:00", "commission_type":"percentage","settlement_type":"fixed"}
                            ]
                        },
                        {
                            "day": "monday",
                            "timings": 
                            [
                                {"from": "6:00", "to":"18:00", "commission_type":"percentage"},
                                {"from": "6:00", "to":"18:00", "commission_type":"percentage","settlement_type":"fixed"}
                            ]
                        }
                    ]
            },

        }
    },
    mounted() {
         
    },

    methods: {

        showModel(day) {
            console.log('changeing to: ',day);
            this.$parent.setDay(day);
            $('#m_modal_1').modal('show');
        },

        destroy(day,item) {
            console.log('deleting: ',day,item);
            this.$parent.destroy(day,item);
        },

        edit(day,item,edit) {
            this.$parent.edit(item,day);
        },

    },

}

</script>

<style>

</style>
