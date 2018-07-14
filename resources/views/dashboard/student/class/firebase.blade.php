
<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">Class Chat</h6>
	</div>

	<div class="panel-body">
		<ul class="media-list chat-list content-group" id="chat-list">


		</ul>

    	<textarea name="enter-message" class="form-control content-group" rows="3" cols="1" placeholder="Enter your message..." id="user_message"></textarea>

    	<div class="row">
    		<div class="col-xs-6">
                    <form id="image-form" action="#">
                      <input id="mediaCapture" type="file" style="float: left">
                      <img id="output" src="" style="width:100px;float: left">
                      <small id="filename"></small>
                      <button type="submit" class="btn btn-xs bg-teal-400 btn-labeled btn-labeled-right legitRipple"><b><i class="icon-circle-right2"></i></b> Send File</button>
                    </form>
    		</div>

    		<div class="col-xs-6 text-right">
                <button type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right legitRipple" id="send_message"><b><i class="icon-circle-right2"></i></b> Send</button>
    		</div>
    	</div>
	</div>
</div>


@section('styles')
@parent
<style type="text/css">
.media h6{
  font-size: 10px;
  margin:0px;
  text-decoration: underline;
}
</style>
@stop

@section('scripts')
@parent
<script src="https://www.gstatic.com/firebasejs/3.7.6/firebase.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>

<script id="ownChatTextTemplate" type="x-tmpl-mustache">
      <li class="media reversed" id="chat_@{{timeStamp}}">
        <div class="media-body">
          <div class="media-content">
          <h6>@{{altName}}</h6>
          @{{chatMessage}}
          </div>
          <span class="media-annotation display-block mt-10">@{{agoTime}}</span>
        </div>

        <div class="media-right">
          <img src="@{{profileUrl}}" class="img-circle" alt="">
        </div>
      </li>
</script>

<script id="otherChatTextTemplate" type="x-tmpl-mustache">
      <li class="media" id="chat_@{{timeStamp}}">
        <div class="media-left">
          <img src="@{{profileUrl}}" class="img-circle" alt="">
        </div>

        <div class="media-body">
          <h6>@{{altName}}</h6>
          @{{chatMessage}}
          <span class="media-annotation display-block mt-10">@{{agoTime}}</span>
        </div>
      </li>
</script>


<script id="ownChatImageTemplate" type="x-tmpl-mustache">
      <li class="media reversed" id="chat_@{{timeStamp}}">
        <div class="media-body">
          <div class="media-content">
          <h6>@{{altName}}</h6>

          <a href="@{{imageUrl}}" target="_blank">
          <img src="@{{imageUrl}}" style="width:200px;" />
          </a>

          </div>
          <span class="media-annotation display-block mt-10">@{{agoTime}}</span>
        </div>

        <div class="media-right">
          <img src="@{{profileUrl}}" class="img-circle" alt="">
        </div>
      </li>
</script>


<script id="otherChatImageTemplate" type="x-tmpl-mustache">
      <li class="media" id="chat_@{{timeStamp}}">
        <div class="media-left">
          <img src="@{{profileUrl}}" class="img-circle" alt="">
        </div>

        <div class="media-body">
          <h6>@{{altName}}</h6>
          <a href="@{{imageUrl}}" target="_blank">
          <img src="@{{imageUrl}}" style="width:200px;" />
          </a>
          <span class="media-annotation display-block mt-10">@{{agoTime}}</span>
        </div>
      </li>
</script>


<script id="ownChatFileTemplate" type="x-tmpl-mustache">
      <li class="media reversed" id="chat_@{{timeStamp}}">
        <div class="media-body">
          <div class="media-content">
          <h6>@{{altName}}</h6>

          <a href="@{{fileUrl}}" target="_blank">
            @{{fileName}}
          </a>

          </div>
          <span class="media-annotation display-block mt-10">@{{agoTime}}</span>
        </div>

        <div class="media-right">
          <img src="@{{profileUrl}}" class="img-circle" alt="">
        </div>
      </li>
</script>


<script id="otherChatFileTemplate" type="x-tmpl-mustache">
      <li class="media" id="chat_@{{timeStamp}}">
        <div class="media-left">
          <img src="@{{profileUrl}}" class="img-circle" alt="">
        </div>

        <div class="media-body">
          <h6>@{{altName}}</h6>

          <a href="@{{fileUrl}}" target="_blank">
            @{{fileName}}
          </a>
          <span class="media-annotation display-block mt-10">@{{agoTime}}</span>
        </div>
      </li>
</script>

<script type="text/javascript">

  var fb_prefix = "{{ env('FB_PREFIX') }}";
  var messageThread = fb_prefix+'/messageThread/'+'{{$fbChat->thread}}';
  var messageThreadMetadata = fb_prefix+'/messageThreadMetadata/'+'{{$fbChat->thread}}';
  var hideName = false;
  var classId = {{ $class->id }};
  var locationId = {{ $class->location->id }};
  var photoURL = "{{ cloudinary_url($fbUser->user->present()->picture, ['secure' => true]) }}";
  var userId = {{ $fbUser->user->id }};
  var userName = "{{ $fbUser->user->name }}";
  var altName = "{{ $fbUser->user->name }}";
  var height = 920;
  var allChats = [];
  var newChats = [];
  var getChatLimit = 0;
  var file = false;
  var uploadInProgress = false;

  var config = {
    apiKey: "AIzaSyCfRDZOHoOCtkT0dolpYDWEB9HbNXqDR5Q",
    authDomain: "ahamchat.firebaseapp.com",
    databaseURL: "https://ahamchat.firebaseio.com",
    projectId: "ahamchat",
    storageBucket: "ahamchat.appspot.com",
    messagingSenderId: "668448326561"
  };

  firebase.initializeApp(config);

  firebase.auth().createUserWithEmailAndPassword('{{ $fbUser->email }}', '{{ $fbUser->password }}')
  .catch(function(error) {

  });

  firebase.auth().signInWithEmailAndPassword('{{ $fbUser->email }}', '{{ $fbUser->password }}')
  .catch(function(error) {
    
    console.log(error);

  });


  firebase.auth().onAuthStateChanged(function(user) {
    if (user) {
    // console.log("Got user",user);
      
		user.updateProfile({
			displayName: "{{ $fbUser->user->name }}",
			photoURL: "{{ cloudinary_url($fbUser->user->present()->picture, ['secure' => true]) }}"
		}).then(function() {
			// Update successful.
		}, function(error) {
			// An error happened.
		});

    }
    
  });

  var database = firebase.database();
  var storage = firebase.storage();
  var chatRef = database.ref(messageThread);

  var threadData = {
	threadId:'{{ $fbChat->thread }}',
	threadName:'{{ $fbChat->name }}',
  };

  var postData = {
  	userId:userId,
  	userName:userName,
  	chatMessage:"hi",
  	chatTimestamp:moment().unix(),
  	timeOffset:moment().utcOffset(),
  	altName:altName,
  	hideName:hideName,
  	chatType:"text",
  	locationId:locationId,
  	profileUrl:photoURL,
    classId: classId
  };

  function addChat(data,place)
  {
      if(data.val().chatType == "text")
      {
        var templateData = {
                  profileUrl:data.val().profileUrl, 
                  chatMessage: data.val().chatMessage,
                  altName:data.val().altName,
                  agoTime:moment(data.val().chatTimestamp*1000).fromNow(),
                  timeStamp:data.val().chatTimestamp
              }

        if(data.val().userId == userId)
        {
          var template = $('#ownChatTextTemplate').html();
        }
        else
        {
          var template = $('#otherChatTextTemplate').html();
        }

          Mustache.parse(template);
          var rendered = Mustache.render(template,templateData);
          appendOrPrepend(rendered,place);
      }

      if(data.val().chatType == "file")
      {
          if(data.val().contentType == 'image/jpeg' || data.val().contentType == 'image/png')
          {
              var templateData = {
                    profileUrl:data.val().profileUrl, 
                    imageUrl: data.val().fileUrl,
                    altName:data.val().altName,
                    agoTime:moment(data.val().chatTimestamp*1000).fromNow(),
                    timeStamp:data.val().chatTimestamp
                };


              if(data.val().userId == userId)
              {
                var template = $('#ownChatImageTemplate').html();
              }
              else
              {
                var template = $('#otherChatImageTemplate').html();
              }

              Mustache.parse(template);
              var rendered = Mustache.render(template, templateData);
              appendOrPrepend(rendered,place);
          }
          else
          {
                var templateData = {
                    profileUrl:data.val().profileUrl, 
                    fileUrl: data.val().fileUrl,
                    fileName: data.val().fileName,
                    altName:data.val().altName,
                    agoTime:moment(data.val().chatTimestamp*1000).fromNow(),
                    timeStamp:data.val().chatTimestamp
                };

              if(data.val().userId == userId)
              {
                var template = $('#ownChatFileTemplate').html();
              }
              else
              {
                var template = $('#otherChatFileTemplate').html();
              }

              Mustache.parse(template);
              var rendered = Mustache.render(template, templateData);
              appendOrPrepend(rendered,place);
          }

      }



  }

  function appendOrPrepend(rendered,place)
  {
      if(place == 'append')
      {
        $('#chat-list').append(rendered);
        height = height+50;
        $('#chat-list').scrollTop(height);
      }
      
      if(place == 'prepend')
      {        
        $('#chat-list').prepend(rendered);
      }

  }

  chatRef.orderByChild("chatTimestamp").limitToLast(8).on('child_added', function(data) {
      // console.log("child_added",data.val());
      allChats.push(data.val());
      addChat(data,'append');
  });


  $('#send_message').on('click',function(){

    var newPostKey = chatRef.child('chats').push().key;

    if($('#user_message').val().trim() == '')
    {
      return alert('Please enter a message');
    }

    postData['chatMessage'] = $('#user_message').val();
    postData['chatTimestamp'] = moment().unix();
    postData['chatTimestampN'] = -1*postData['chatTimestamp'];
    postData['chatType'] = "text";
    postData['fileUrl'] = "";
    postData['contentType'] = "";
    postData['fileName'] = "";

    console.log(newPostKey, postData);

    threadData['lastChatId'] = newPostKey;
    // participants

    var updates = {};
    updates['/'+messageThread+'/'+newPostKey] = postData;
    // updates['/'+messageThreadMetadata] = threadData;
    firebase.database().ref().update(updates);

    firebase.database().ref('/'+messageThreadMetadata+'/lastChatId').set(newPostKey);

    $('#user_message').val("");

  });

  function getPreviousChats()
  {
      chatRef.orderByChild("chatTimestampN").startAt(allChats[0].chatTimestampN).limitToFirst(6).on('child_added', function(data) {

        if(allChats[0].chatTimestamp != data.val().chatTimestamp)
        {
          allChats.unshift(data.val());
          addChat(data,'prepend');
        }

      });
  }

  $('#chat-list').scroll(function() {
      var pos = $('#chat-list').scrollTop();
      if (pos == 0) {

        getPreviousChats();
          
      }
  });
  
  $('#mediaCapture').on('change',function(event){
    file = event.target.files[0];

    if (file.type.match('image.*'))
    {
      var output = document.getElementById('output');
      output.src = URL.createObjectURL(event.target.files[0]);
      $('#output').show();
      $('#filename').val("");
    }
    else
    {
      document.getElementById('output').src = "";
      $('#output').hide();
    }

    //document.getElementById('image-form').reset()
  });

  $('#image-form').on('submit',function(event){
    event.preventDefault();

    if(file)
    {

      var filePath = '{{$fbChat->thread}}/' + file.name;
      var fileName = file.name;

      $.blockUI({ 
          message: '<i class="icon-spinner4 spinner"></i>',
          overlayCSS: {
              backgroundColor: '#1b2024',
              opacity: 0.8,
              zIndex: 1200,
              cursor: 'wait'
          },
          css: {
              border: 0,
              color: '#fff',
              padding: 0,
              zIndex: 1201,
              backgroundColor: 'transparent'
          }
      });

      storage.ref(filePath).put(file).then(function(snapshot) {
          var fullPath = snapshot.metadata.fullPath;

          file = null;
          document.getElementById('output').src = "";
          document.getElementById('image-form').reset();

          var newPostKey = chatRef.child('chats').push().key;
          postData['chatMessage'] = storage.ref(fullPath).toString();
          postData['fileUrl'] = snapshot.metadata.downloadURLs[0];
          postData['contentType'] = snapshot.metadata.contentType;
          postData['fileName'] = snapshot.metadata.name;
          postData['chatTimestamp'] = moment().unix();
          postData['chatTimestampN'] = -1*postData['chatTimestamp'];
          postData['chatType'] = 'file';
          // console.log(newPostKey, postData);

          threadData['lastChatId'] = newPostKey;

          var updates = {};
          updates['/'+messageThread+'/'+newPostKey] = postData;
          // updates['/'+messageThreadMetadata] = threadData;
          firebase.database().ref().update(updates);

          firebase.database().ref('/'+messageThreadMetadata+'/lastChatId').set(newPostKey);


          $.unblockUI();

      });

    }

  });


</script>
@stop
