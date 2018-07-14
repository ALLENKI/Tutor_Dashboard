<template>
  <div>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    
    <header>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1>VueJs Chat Component</h1>
          </div>
        </div>
      </div>
    </header>
    <section id="app" class="page">
      <div class="container">
        <div class="row">
          <div class="col-md-3 col-md-offset-9">
             <chat-component :conversation="conversation">
             </chat-component>
          </div>
                    <div class="col-md-3 col-md-offset-9">
             <chat-component :conversation="conversation">
             </chat-component>
          </div>
                    <div class="col-md-3 col-md-offset-9">
             <chat-component :conversation="conversation">
             </chat-component>
          </div>
                    <div class="col-md-3 col-md-offset-9">
             <chat-component :conversation="conversation">
             </chat-component>
          </div>          <div class="col-md-3 col-md-offset-9">
             <chat-component :conversation="conversation">
             </chat-component>
          </div>
        </div>
      </div>
    </section>
    <footer>
      <script
  src="http://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <!-- VueJs CDN -->
      <script src="https://fr.vuejs.org/js/vue.min.js"></script>
      <!-- VueJs CDN -->
    </footer>
 </div>

<script>
var Datas = {
 txt: '',
 messages: []
}

Vue.component('chatComponent', {
  props:['conversation'],
  data: function () {
    return Datas;
  },
  template:`
  <chat>
    <conversation>
      <message-line v-for="message in messages">
        <message :class="message.from">{{message.txt}}</message>
      </message-line>
    </conversation>
    <texting>
      <input v-model="txt" v-on:keyup.enter="send(txt)" placeholder="Say something" type="text">
      <input type="button" v-on:click="send(txt)"  value="Send">
    </texting>
  </chat>`,
  mounted:function(){
    this.init();
  },
  methods:{
    init:function(){
       let message = {from:'',txt:'Tell me something'};
       this.messages.push(message);
       this.ping();
    },
    send:function(txt){
      let message = {from:'me',txt:txt};
      this.messages.push(message);    
     
      setTimeout(()=>{
        let answer = {from:'',txt:'Lorem ipsum dolor sit amet...'};
        this.messages.push(answer);
      }, 1500);
    },
    ping:function(){
      // exemple of getting messages in API with conversations params
      setInterval(()=>{
       console.log('get messages');
      }, 500);
    }
  }
})

var app = new Vue({
  el: '#app',
  data:{
    conversation:[
      {
        url:'/get/conversation/123456',
        user:'exemple'
      }
    ]
  },
})
</script>

<style>
chat{
  position:relative;
  display:inline-block;
  box-shadow: 2px 2px 5px black;
  width:100%;
  height:360px;
  background-color:#eee;
  border: 1px solid #fff;
}
/* End Chat TAG */

/* Start Conversation TAG */
conversation{
  display:block;
  width:100%;
  height:310px;
  overflow:auto;
  background-color:#eee;
}
/* End Conversation TAG */

/* Start Texting TAG */
texting{
  display:block;
  text-align:center;
  padding:10px;
}
/* End Texting TAG */

/* Start message TAG */
message-line{
  position:relative;
  display:block;
  width:100%;
  min-height:40px;
}

message{
  display:block;
  float:left;
  max-width:80%;
  padding:5px 10px;
  margin:5px;
  color:#fff;
  background-color:#00c6da;
  border-radius:10px;
}

message.me{
  float:right;
  background-color:white;
  background-color:#ee591f;
}
/* End message TAG */

</style>