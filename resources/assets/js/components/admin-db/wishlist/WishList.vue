<template>

    <Wishlist :topics="topics" 
              :selectedTopics="selectedTopics"
              :type="wishlist" 
              :add="addWishList"
              :remove="remove">
    </Wishlist>

</template>

<script>

import Wishlist from '../../common/wishlist/WishList';

export default {

    components: {
        Wishlist,
    },
    data() {
        return {
            topics: null,
            wishlist: 'wishlist',
            selectedTopics: [],
        }
    },
    mounted() {
        this.getTopicsForAdmindb();
        this.getWishList();
    },
    methods: {

            getTopicsForAdmindb() {

                axios
                    .get('common/wishlist/admin-db/'+this.$route.params.learner+'/admin-wishlist-topics')
                    .then(response => {
                        this.topics = response.data;
                    })
                    .catch(error => { 
                        console.log(error); 
                    })

            },

            getWishList() {

                axios
                    .get('common/wishlist/'+this.$route.params.learner+'/view-topics')
                    .then(response => {
                        this.selectedTopics = response.data;
                    })
                    .catch(error => {
                        
                    })

            },

            addWishList(topicIds) {

                let data = {
                                student_id : this.$route.params.learner,
                                added_by: 'by_admin',
                                topics: topicIds,
                           }

               axios
                    .post('common/wishlist/admin-db/'+this.$route.params.learner+'/add',data)
                    .then(response => {
                        this.getWishList();
                        this.getTopicsForAdmindb();
                    })
                    .catch(error => {
                        
                    })

            },
            
            onClicked (payload) {
                console.log(payload);
            },

            remove(id) {

                axios
                    .delete('common/wishlist/admin-db/'+this.$route.params.learner+'/destroy/'+id,)
                    .then(response => {

                        this.getWishList();
                        
                    })
                    .catch(error => {

                    })

            },

    }

}

</script>

<style>

</style>
