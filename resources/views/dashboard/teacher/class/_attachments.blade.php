	
  @if($uploader == 'teacher')
  {!! BootForm::open()->action(route('teacher::class.attachments',$class->code)) !!}
  @endif

  @if($uploader == 'student')
  {!! BootForm::open()->action(route('student::class.attachments',$class->code)) !!}
  @endif

  <div class="row" style="padding-top:20px;">
    <div class="col-xs-12">

    <table class="table table-striped">
      <thead>
        <th width="20%">File</th>
        <th width="50%">Remarks</th>
        <th width="10%">Uploader</th>
        <th width="10%">Uploaded At</th>
        <th width="10%">Actions</th>
      </thead>

      <tbody id="picture_filelist">
        @foreach($class->attachments as $attachment)
        <tr id="{{ $attachment->identifier }}">
          <td>
            <input type="hidden" name="attachments[{{ $attachment->identifier }}][file]" value="{{ $attachment->file_path }}">
            <input type="hidden" name="attachments[{{ $attachment->identifier }}][original]" value="{{ $attachment->file_name }}">
            <input type="hidden" name="attachments[{{ $attachment->identifier }}][uploader_id]" value="{{ $attachment->uploader_id }}">
            <input type="hidden" name="attachments[{{ $attachment->identifier }}][uploader_role]" value="{{ $attachment->uploader_role }}">
            
            <a href="https://s3.amazonaws.com/ahamattachments/file/{{$attachment->file_path}}" target="_blank">
            <i class="icon-file-download position-left"></i>
            
              {{ $attachment->file_name }}
            </a>
          </td>

          <td>
            @if($attachment->uploader_id == $user->id)
            <textarea class="form-control" name="attachments[{{ $attachment->identifier }}][description]" placeholder="Description">{{ $attachment->description }}</textarea>
            @else
            {{ $attachment->description }}
            <input type="hidden" name="attachments[{{ $attachment->identifier }}][description]" value="{{ $attachment->description }}">
            @endif
          </td>

          <td>
            {{ $attachment->uploader->name }} 
            <small>
              ({{ $attachment->uploader_role }})
            </small>
          </td>

          <td>
            {{ $attachment->created_at }}
          </td>

          <td>
            @if($class->status != 'completed')
            @if($attachment->uploader_id == $user->id)
            <span style="cursor:pointer;">
              <i class="icon-cross2 position-right" style="float:right;color:red;" onclick="removeFile('{{ $attachment->identifier }}')"></i>
            </span>
            @endif
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <br />

    <div class="alert alert-danger" id="picture_file_upload_error" style="display:none;">
      
    </div>

    @if($class->status != 'completed')
    <div id="picture_container">
        <small>Please upload files less than 10mb and of types jpg,png,jpeg,doc,pdf,xls,docx,xlsx,ppt,pptx</small>
        <br>
        <br>
        <a id="pick_pictures" href="javascript:;" class="btn btn-default">Add File</a> 
        <button type="submit" class="btn btn-success pull-right">Submit</a> 
    </div>
    @endif

    </div>
  </div>

  {!! BootForm::close() !!}


@section('scripts')
@parent


<script id="addPictureTemplate" type="x-tmpl-mustache">

	   <tr id="@{{id}}">

        <td>
        <input type="hidden" name="attachments[@{{id}}][uploader_id]" value="{{ $user->id }}">
        <input type="hidden" name="attachments[@{{id}}][uploader_role]" value="{{ $uploader }}">
	   		<a href="#">
	   		<i class="icon-file-download position-left"></i>
        @{{name}}
	   		</a>
	   		
        <div class="progress" style="margin-bottom:0px;height:5px;margin-top:10px;">
          <div class="progress-bar" role="progressbar" style="width: 10%;">
          </div>
        </div>

        </td>

        <td>
          <textarea class="form-control" name="attachments[@{{id}}][description]" placeholder="Description"></textarea>
        </td>

        <td>
          {{ $user->name }}
        </td>

        <td>
        NA
        </td>

        <td>
	   		<span>
	   			<i class="icon-cross2 position-right" style="float:right;color:red;" onclick="removeFile('@{{id}}')"></i>
	   		</span>
        </td>

	   </tr>

</script>

<script type="text/javascript">

// Custom example logic
var prefix = 'file';

var time = Date.now || function() {
  return +new Date;
};

function removeFile(name)
{
    name = '#'+name.replace(/\./g, '_');
    $(name).remove();
}

function fileNameToID(name,suffix)
{
  var ID = name.replace(/[^a-z0-9\.]+/gi,"_").toLowerCase().replace(/\./g, '_');
  return md5(ID+suffix);
}


var pictureuploader = new plupload.Uploader({
  runtimes : 'html5,flash,silverlight,html4',
  browse_button : 'pick_pictures',
  container: document.getElementById('picture_container'),
  url : "https://<?php echo $bucket; ?>.s3.amazonaws.com:443/",
  
  multipart: true,
  multipart_params: {
    'key': '${filename}',
    'Filename': '${filename}',
    'acl': 'public-read',
    'AWSAccessKeyId' : '<?php echo $accessKeyId; ?>',   
    'policy': '<?php echo $policy; ?>',
    'signature': '<?php echo $signature; ?>'
  },
  
  file_data_name: 'file',
  flash_swf_url : '/assets/dashboard/js/plugins/forms/plupload/Moxie.swf',
  silverlight_xap_url : '/assets/dashboard/js/plugins/forms/plupload/Moxie.xap',
  
  filters : {
    max_file_size : '10mb',
    mime_types: [
      {title : "Image files", extensions : "jpg,png,jpeg"},
      {title : "Documents", extensions : "doc,pdf,xls,docx,xlsx,ppt,pptx"}
    ]
  },

  init: {

    UploadProgress: function(up, file) {
      var new_name = fileNameToID(file.name,file.id);

      var extension = file.name.substr(file.name.lastIndexOf('.')+1);

      var file_name = fileNameToID(file.name,file.id)+'.'+extension;

      console.log('Upload Progress:'+file_name+' '+file.percent);

      $('#'+new_name).find('.progress').find('.progress-bar').css('width',file.percent + '%');
    },

    Error: function(up, err) {
      document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
    }
  }
});

pictureuploader.init();

pictureuploader.bind('FilesAdded', function(up, files) {

    $('#picture_file_upload_error').html('').hide();

    var deleteHandle = function(name) {
        console.log('register delete handle',name);
        return function(event) {
            console.log('deleteFile');
            event.preventDefault();
            $('#'+name).remove();
        };
    };

    for (var i in files) {

	    var file = files[i];

	    console.log('File Added: ',file);

	    var new_name = fileNameToID(file.name,file.id);

	    $('#'+new_name).remove();

		var template = $('#addPictureTemplate').html();
	  	Mustache.parse(template);
	  	var rendered = Mustache.render(template, {id:new_name, name: file.name });

	    $('#picture_filelist').append(rendered);

	    // $('#deleteFile' + new_name).click(deleteHandle(new_name));

    }  

    up.start();  
});

pictureuploader.bind('BeforeUpload', function (up, file) {

    var extension = file.name.substr(file.name.lastIndexOf('.')+1);

    var file_name = fileNameToID(file.name,file.id)+'.'+extension;

    file_name = prefix+'/'+file_name;

    var file_type = file.type;

    console.log("Before Upload ", file_type, up.settings.multipart_params['Content-Type']);

    up.settings.multipart_params.Filename = file_name;
    up.settings.multipart_params.key = file_name;
    up.settings.multipart_params['Content-Type'] = file_type;

    // console.log("Before Upload 1", file_type, up.settings.multipart_params['Content-Type']);

});

pictureuploader.bind('FileUploaded', function (up, file) {

    var new_name = fileNameToID(file.name,file.id);

    var extension = file.name.substr(file.name.lastIndexOf('.')+1);

    var file_name = fileNameToID(file.name,file.id)+'.'+extension;

	var template = '<input type="hidden" name="attachments[@{{id}}][file]" value="@{{name}}" /><input type="hidden" name="attachments[@{{id}}][original]" value="@{{original}}" />';
  	Mustache.parse(template);   // optional, speeds up future uses
  	var rendered = Mustache.render(template, {image: "https://s3.amazonaws.com/ahamattachments/file/"+file_name, name: file_name, id:new_name, original:file.name });

  	$('#'+new_name).prepend(rendered);

    console.log('File Uploaded:'+file_name);

});

pictureuploader.bind('Error',function(up, args) {

  console.log(args);

  $('#picture_file_upload_error').html('Please upload files less than 10mb and of types jpg,png,jpeg,doc,pdf,xls,docx,xlsx,ppt,pptx').show();

  return false;

});

</script>

@stop