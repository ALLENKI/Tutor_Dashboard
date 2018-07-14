<template>

    <div>

       	<div class="m-portlet m-portlet--mobile">
				
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Notes
                        </h3>
                    </div>
                </div>

                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            
                        </li>
                    </ul>
                </div>
            </div>

            <div class="m-portlet__body">

                <div class="m-scrollable" id="m_quick_sidebar_tabs_messenger" role="tabpanel">

                    <div class="m-messenger m-messenger--message-arrow m-messenger--skin-light">

                        <div class="m-messenger__messages">  

                                    <div v-for="item in notes"  class="m-messenger__message m-messenger__message--in class-note">

                                        <div class="m-messenger__message-body">

                                            <div class="m-messenger__message-arrow"></div>

                                                <div class="m-messenger__message-content">
                                                    <div class="m-messenger__message-username">
                                                        {{item.user}}
                                                    </div>
                                                    <div class="m-messenger__message-text">
                                                        {{item.note}}
                                                    </div>
                                                    <div class="m-messenger__message-username">
                                                        {{item.created_at}}
                                                    </div>
                                                </div>


                                        </div>

                                    </div>

                            </div>

                        <div class="m-messenger__seperator"></div>

                        <div class="m-messenger__form">
                                <div class="m-messenger__form-controls">
                                    <input type="text" v-model="note" name="" placeholder="Add notes..." class="m-messenger__form-input">
                                </div>
                                <div class="m-messenger__form-tools">
                                    <button class="btn btn-success btn-sm" @click="addNote()">
                                    Add
                                    </button>
                                </div>
                        </div>

                    </div>

                </div>

            </div>

		</div>

    </div>
  
</template>
<style>
    
</style>

<script>
import axios from "axios";

export default {
    props:['ahamclass'],

    data() {
        return {
            note: "",
            notes: [],
        }
    },

    mounted() {

        console.log('Notes',this.ahamclass);
        this.getNotes();

    },

    methods: {

        addNote(){

           let payload = {
               note: this.note,
           };

           console.log('payload',payload);

            axios
                 .post("tutor/classes/"+this.$route.params.ahamclassid+"/notes",payload)
                 .then(response => {
                      this.$emit('refresh');
                      this.getNotes();
                 })
                 .catch(error => {

                 });

        },

        getNotes(){

            axios
                 .get("tutor/classes/"+this.$route.params.ahamclassid+"/notes")
                 .then(response => {
                     this.notes = response.data.data;
                     this.addScroll();
                 })
                 .catch(error => {

                 });

        },

        addScroll(){
            $(function(){

                var messenger = $('#m_quick_sidebar_tabs_messenger');  
                var messengerMessages = messenger.find('.m-messenger__messages');
                messengerMessages.css('height', 300);
                mApp.initScroller(messengerMessages, {});
            
             });
             
        },

    },

}

</script>

<style>
   .m-messenger .m-messenger__messages .m-messenger__message.class-note {
    float: none;
   } 
</style>
