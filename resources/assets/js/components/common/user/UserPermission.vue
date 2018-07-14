<template>
    
    <div>

        <div class="m-portlet m-portlet--full-height ">

            <div class="m-portlet__head">

                <div v-if="tutor" class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Edit User - {{ tutor.email }}
                        </h3>
                    </div>
                </div>

                <div class="m-portlet__head-tools">
                    
                </div>

            </div>

            <div class="m-portlet__body">

                <div class="m-widget3">
                    <div class="m-widget3__item">
                        <div class="m-widget3__header">
                            
                            <div class="m-widget3__info">

                                <span class="m-widget3__username">

                                    <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                        <input v-model="userPermission.isSuperAdmin" type="checkbox">
                                        is Super Admin
                                        <span></span>
                                    </label>
                                    
                                </span>

                            </div>

                        </div>
                        <div class="m-separator m-separator--space m-separator--dashed"></div>
                        <div class="m-widget3__body">

                            <div  class="m-list-search">
                                <div class="m-list-search__results">
                                    <h3> Global Permissions  </h3>
                                    <span v-for="item in userPermission.globalPermission" href="#" class="m-list-search__result-item">

                                        <label class="m-checkbox m-checkbox--bold m-checkbox--state-success" >
                                            <input v-model="item.value" type="checkbox">
                                            {{ item.description }} ( {{ item.label }} )
                                            <span></span>
                                        </label>

                                    </span>
                                </div>
                            </div>

                            <div v-for="data in userPermission.hubs">

                                <div class="m-separator m-separator--space m-separator--dashed"></div>

                                <h3> {{ data.hubLabel }} </h3>

                                <div class="m-list-search">
                                    <div class="m-list-search__results">
                                        <span v-for="item in data.data" href="#" class="m-list-search__result-item">

                                            <label class="m-checkbox m-checkbox--bold m-checkbox--state-success">
                                                <input  v-model="item.value" type="checkbox">
                                                        {{ item.description }}
                                                <span></span>
                                            </label>

                                        </span>
                                    </div>
                                </div>

                            </div>
                            
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="m-portlet__foot m-portlet__foot--fit">

                <div class="m-widget13__action m--align-right">
                        <button @click="update" type="button" class="btn m-btn--pill m-btn--air         btn-outline-warning m-btn m-btn--outline-2x ">
					update	
                    </button>
                </div>

            </div>

        </div>
       
    </div>

</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            userPermission: { 
                isSuperAdmin: null,
                globalPermission: [],
                hubs: [],
            },
            permission: null,
            tutor: null,
            payload: {
                hubs: [],
            },
            hubs:[],
        }
    },
    watch: {

    },
    mounted() {
        this.getUser();
        this.getPermissionHubData();
    },
    methods: {

        updateThePayload() {

            if (this.userPermission.isSuperAdmin) {
                this.payload.isSuperAdmin = true;
            } else {
                delete this.payload.isSuperAdmin;
            }

            this.payload.globalPermission = 
                _.filter(this.userPermission.globalPermission, function(permission) {        
                    return permission.value;
                });

            var vm  = this;
            this.payload.hubs = [];

            _.forIn(this.userPermission.hubs,function(value,key){

                vm.payload.hubs.push(
                            
                                { "hubLabel" : value.hubLabel,
                                   "data" : _.filter(value.data, function(value) {        
                                                    return value.value;
                                                }) 
                                }
                            
                );

                    
            })

            // console.log(value);
            

        },

        getPermissionHubData() {

                axios.get('common/permissionData').then(response => {
                    this.userPermission.globalPermission = response.data;
                }).catch(error => {
                    console.log(error);
                });

                axios.get('common/locationData/'+this.$route.params.user).then(response => {
                    this.userPermission.hubs = response.data;
                }).catch(error => {
                    console.log(error);
                });

                axios.get('common/getUserPermission/'+this.$route.params.user).then(response => {
                    this.userPermission.globalPermission = response.data;
                }).catch(error => {
                    console.log(error);
                });

        },
        
        update() {

            this.updateThePayload();

            console.log('update payload ',this.payload);

            axios
                .post("user/"+this.$route.params.user+"/manage/permission",this.payload)
                .then(response => {
                    // console.log(response);
                })
                .catch(error => {
                    console.log(error);
                });

        },
       
        getUser() {
              axios
              .get("/common/tutors/"+this.$route.params.user)
              .then(response => {
                this.tutor = response.data;
              })
              .catch(error => {
                console.log(error);
              });
        },

    }
}
</script>

<style>

</style>
