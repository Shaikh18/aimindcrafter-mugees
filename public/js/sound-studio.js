/*===========================================================================
*
*  AUDIO FILE UPLOAD - FILEPOND PLUGIN
*
*============================================================================*/

FilePond.registerPlugin( 

   FilePondPluginFileValidateSize,
   FilePondPluginFileValidateType

);

let pond = FilePond.create(document.querySelector('.filepond'));
let maxFileSize = '100MB';

FilePond.setOptions({
    
    allowMultiple: false,
    maxFiles: 1,
    allowReplace: true,
    maxFileSize: maxFileSize,
    labelIdle: "Drag & Drop your music file or <span class=\"filepond--label-action\">Browse</span><br><span class='restrictions'>[MP3 | WAV | OGG]</span>",
    required: false,
    instantUpload: false,
    storeAsFile: true,
    acceptedFileTypes: ['audio/ogg', 'audio/mpeg', 'audio/wav'],
    labelFileProcessingError: (error) => {
      console.log(error);
    }

});





/*===========================================================================
*
*  UPLOAD AUDIO FILE FOR BACKGROUND
*
*============================================================================*/
let loading = `<span class="loading">
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						<span style="background-color: #fff;"></span>
						</span>`;
		let loading_dark = `<span class="loading">
						<span style="background-color: #1e1e2d;"></span>
						<span style="background-color: #1e1e2d;"></span>
						<span style="background-color: #1e1e2d;"></span>
						</span>`;
            
 $('#upload-music').on('click',function(e) {

  "use strict";

  e.preventDefault();
  
  var inputAudio = [];
  var duration;
 
  
    if (pond.getFiles().length !== 0) {   
      pond.getFiles().forEach(function(file) {
      inputAudio.push(file);
    });

    var audio = document.createElement('audio');
    var objectUrl = URL.createObjectURL(inputAudio[0].file);
  
    audio.src = objectUrl;
    audio.addEventListener('loadedmetadata', function(){
      duration = audio.duration;
    },false);
  
    var formData = new FormData();
  
    setTimeout(function() {
  
      
      formData.append('audiofile', inputAudio[0].file);
      formData.append('audiolength', duration);
  
      $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          type: "POST",
          url: 'studio/music/upload',
          data: formData,
          contentType: false,
          processData: false,
          cache: false,
          beforeSend: function() {
              $('#upload-music').prop('disabled', true);
              let btn = document.getElementById('upload-music');					
              btn.innerHTML = loading;  
              document.querySelector('#loader-line')?.classList?.remove('opacity-on');          
          },
          complete: function() {
              $('#upload-music').prop('disabled', false);
              let btn = document.getElementById('upload-music');					
              btn.innerHTML = 'Upload Music File';
              document.querySelector('#loader-line')?.classList?.add('opacity-on');            
          },
          success: function(data) {
            if (!data) {
              Swal.fire('File Upload Error', 'Selected background music upload failed, please try again', 'error');
            } else {
              Swal.fire('File Upload Success', 'Selected background music file was uploaded successfully', 'success');
            }
          },
          error: function(data) {
  
              if (!data) {
                Swal.fire('File Upload Error', 'Selected background music upload failed, please try again', 'error');
              }
  
              $('#upload-music').prop('disabled', false);
              let btn = document.getElementById('upload-music');					
              btn.innerHTML = 'Upload Music File';
              document.querySelector('#loader-line')?.classList?.add('opacity-on');   
              
              if (pond.getFiles().length != 0) {
                  for (var j = 0; j <= pond.getFiles().length - 1; j++) {
                      pond.removeFiles(pond.getFiles()[j].id);
                  }
              }
            
              inputAudio = [];
          }
      }).done(function(data) {
        if (pond.getFiles().length != 0) {
            for (var j = 0; j <= pond.getFiles().length - 1; j++) {
                pond.removeFiles(pond.getFiles()[j].id);
            }
        }
      
        inputAudio = [];

        $("#backgroundMusicTable").DataTable().ajax.reload();	

      })
  
    }, 500);
    
  } else {
    Swal.fire('Missing Audio File', 'Selected background audio file before uploading', 'warning');
    return;
  }

});



/*===========================================================================
*
*  LISTEN BACKGROUND MUSIC
*
*============================================================================*/
let currentAudio = '';
let audioButton = new Audio();

function music_select(value) {
  let background = document.getElementById(value);
  let url = background.getAttribute('data-url');

  document.getElementById('listen-music').setAttribute("src", url);
}

function previewMusic(element) {
  let src = $(element).attr('src');
  let id = $(element).attr('id');

  let isPlaying = false;

  if (src == '') {

    Swal.fire('Background Audio Not Selected', 'Select preferred background audio first before listening it', 'warning');
  
  } else {
  
    audioButton.src = src; 

    if (currentAudio == id) {
        audioButton.pause();
        isPlaying = false;
        document.getElementById(id).innerHTML = '<i class="fa fa-play"></i>';
        currentAudio = '';

    } else {    
        if(isPlaying) {
            audioButton.pause();
            isPlaying = false;
            document.getElementById(id).innerHTML = '<i class="fa fa-play"></i>';
            currentAudio = '';
        } else {
            audioButton.play();
            isPlaying = true;
            if (currentAudio) {
                document.getElementById(currentAudio).innerHTML = '<i class="fa fa-play"></i>';
            }
            document.getElementById(id).innerHTML = '<i class="fa fa-stop"></i>';
            currentAudio = id;
        }
    }

    audioButton.addEventListener('ended', (event) => {
        document.getElementById(id).innerHTML = '<i class="fa fa-play"></i>';
        isPlaying = false;
        currentAudio = '';
    });    
  }  
}
