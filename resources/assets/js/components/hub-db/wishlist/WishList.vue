<template>

    <Wishlist   :topics="topics" 
                :hub="hub"
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
            hub: null,
            topics: null,
            selectedTopics: [],
            selectedTopicIds: null,
            wishlist: 'wishlist',
        }
    },
    mounted() {
        this.hub = this.$route.params.hub;
        this.getHubTopics();
        this.getWishList();
    },
    methods: {

        getHubTopics() {

             axios
                .get('common/wishlist/hub-db/'+this.$route.params.learner+'/'+this.hub+'/hub-wishlist-topics')
                .then((response) => {
                    this.topics = response.data;
                })  
                .catch((error) => {
                    console.log(error);
                })

        },

        getWishList() {

            axios
                .get('common/wishlist/'+this.$route.params.learner+'/view-topics')
                .then((response) => {
                    this.selectedTopics = response.data;
                })  
                .catch((error) => {
                    console.log(error);
                })

        },

        addWishList(topicIds) {

             let data = {
                            student_id : this.$route.params.learner,
                            added_by: 'by_admin',
                            topics: topicIds,
                            hub_id: this.hub,
                        };

            axios
                .post('common/wishlist/hub-db/'+this.$route.params.learner+'/'+this.hub+'/add',data)
                .then((response) => {
                    this.getWishList();
                    this.getHubTopics();
                })  
                .catch((error) => {
                    console.log(error);
                })

        },

        remove(id,e) {

            e.stopPropagation();

            axios
                .delete('common/wishlist/hub-db/'+this.$route.params.learner+'/'+this.hub+'/destroy/'+id,)
                .then(response => {
                    this.getWishList();
                })
                .catch(error => {

                })

        }
        
    }

}
</script>

<style>

</style>
