/*===========================================================================
*
*  TTS Dashboard 
*
*============================================================================*/
let previous_language;
let previous_voice = '';
let previous_selection = 0;
let textarea_language;
let text_length_limit;
let voices_limit;

$(document).ready(function() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: 'clone/configuration',
        success: function(data) {
            text_length_limit = data['char_limit'];
            voices_limit = data['voice_limit'];
        },
        error: function(data) {
            text_length_limit = 60000;   
            voices_limit = 5;      
        }
    })
});

$(document).ready(function(){

    "use strict";

    $('.avoid-clicks').on('click',false);

    $('#clear-text').on("click", function(e){
        e.preventDefault();
        $('textarea').val('');

        countCharacters();
    });

    $('#clear-effects').on("click", function(e){
        e.preventDefault();        

        $("textarea").each(function(){
            let text = this.value;
            text = "<span>" + text + "</span>";
            text = $(text).text();
            this.value = text;
        });      

        countCharacters();
    });

    $('#delete-all-lines').on("click", function(e){
        e.preventDefault();

        $('.textarea-row').each(function() {
            if(this.id != 'maintextarea') {
                $(this).remove();
            } else {
                let main_img = document.getElementById('ZZZOOOVVVIMG');
                main_img.setAttribute('src', textarea_img);
        
                let main_voice = document.getElementById('ZZZOOOVVVZ');
                main_voice.setAttribute('data-voice', textarea_voice_id);
        
                let instance = tippy(document.getElementById('ZZZOOOVVVIMG'));
                instance.setProps({
                    animation: 'scale-extreme',
                    theme: 'material',
                    content: textarea_voice_details,
                });
  
                $('#ZZZOOOVVVZ').val('');
            }
        });

        total_rows = 1;

        countCharacters();

    });

    let language = document.getElementById("languages");
    previous_language = language.value;
    textarea_language = language.options[language.selectedIndex].text;

    let voice = document.getElementById("voices");
    previous_voice = 'current-' + voice.value;

    insertNewLine();

})

function default_voice(value) {

    "use strict";

    previous_voice = 'current-' + value;
}


/*===========================================================================
*
*  Process Select Voices 
*
*============================================================================*/
let textarea_voice_details;
let textarea_voice_id;
let textarea_img;
let selectedTextarea;
function voice_select(value) {
    console.log(value)
    "use strict";

    previous_voice = 'current-' + value;

    let sample = document.getElementById(value);
    let name = sample.getAttribute('data-voice');
    let img = sample.getAttribute('data-img');
    let type = sample.getAttribute('data-type');
    let gender = sample.getAttribute('data-gender');
    let voice_id = sample.getAttribute('data-id');

    textarea_voice_id = voice_id;
    textarea_img = img;
    textarea_voice_details = name + '(' + gender + ')' + '(' + type.charAt(0).toUpperCase() + type.slice(1) + ')';

    let length = document.querySelectorAll('.textarea-row').length;

    if (length == 1) {
        let main_img = document.getElementById('ZZZOOOVVVIMG');
        main_img.setAttribute('src', img);

        let main_voice = document.getElementById('ZZZOOOVVVZ');
        main_voice.setAttribute('data-voice', textarea_voice_id);

        let instance = tippy(document.getElementById('ZZZOOOVVVIMG'));
        instance.setProps({
            animation: 'scale-extreme',
            theme: 'material',
            content: textarea_voice_details,
        });

    }


}


function ssmlText(openTag, closeTag) {

    "use strict";

    let textarea = $('#' + selectedTextarea);

    if (textarea.val() != undefined) {
        let length = textarea.val().length;
        let start = textarea[0].selectionStart;
        let end = textarea[0].selectionEnd;
        let selectedText = textarea.val().substring(start, end);
        let replacement = openTag + selectedText + closeTag;
        textarea.val(textarea.val().substring(0, start) + replacement + textarea.val().substring(end, length));
    
        countCharacters(); 
    }
}


function startSynthesizeMode(length, map, all_text) {

    let text_object = Object.fromEntries(map);
    let data = $('#synthesize-text-form').serializeArray();

    data.push({name: 'length', value: length});
    data.push({name: 'input_text_total', value: all_text});
    data.push({name: 'input_text', value: JSON.stringify(text_object)});

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        type: "POST",
        url: $('#synthesize-text-form').attr('action'),
        data: data,
        beforeSend: function() {
            $('#synthesize-text').prop('disabled', true);
            let btn = document.getElementById('synthesize-text');					
            btn.innerHTML = loading;  
            document.querySelector('#loader-line')?.classList?.remove('opacity-on');  
            $('#waveform-box').slideUp('slow')      
        },
        complete: function() {
            $('#synthesize-text').prop('disabled', false);
            let btn = document.getElementById('synthesize-text');					
            btn.innerHTML = 'Synthesize';
            document.querySelector('#loader-line')?.classList?.add('opacity-on');             
         },
        success: function(data) {     
            animateValue("balance-number", data['old'], data['current'], 2000);
            $("html, body").animate({scrollTop: $("#results-header").offset().top}, 200);
			$("#resultTable").DataTable().ajax.reload();
        },
        error: function(data) {
            if (data.responseJSON['error']) {
                Swal.fire('Text to Speech Notification', data.responseJSON['error'], 'warning');
            }

            $('#synthesize-text').prop('disabled', false);
            let btn = document.getElementById('synthesize-text');					
            btn.innerHTML = 'Synthesize';
            document.querySelector('#loader-line')?.classList?.add('opacity-on');              
        }
    }).done(function(data) {})
}


function startListenMode(length, map, all_text) {

    let text_object = Object.fromEntries(map);
    let data = $('#synthesize-text-form').serializeArray();

    data.push({name: 'length', value: length});
    data.push({name: 'input_text_total', value: all_text});
    data.push({name: 'input_text', value: JSON.stringify(text_object)});

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        type: "POST",
        url: $('#synthesize-text-form').attr('listen'),
        data: data,
        beforeSend: function() {
            $('#listen-text').prop('disabled', true);
            let btn = document.getElementById('listen-text');					
            btn.innerHTML = loading;  
            document.querySelector('#loader-line')?.classList?.remove('opacity-on');           
            $('#waveform-box').slideUp('slow')   
        },
        complete: function() {
            $('#listen-text').prop('disabled', false);
            let btn = document.getElementById('listen-text');					
            btn.innerHTML = 'Listen';
            document.querySelector('#loader-line')?.classList?.add('opacity-on');                 
        },
        success: function(data) {
            animateValue("balance-number", data['old'], data['current'], 2000);
            $('#waveform-box').slideDown('slow');
        },
        error: function(data) {
            if (data.responseJSON['error']) {
                Swal.fire('Text to Speech Notification', data.responseJSON['error'], 'warning');
            }

            $('#listen-text').prop('disabled', false);
            let btn = document.getElementById('listen-text');					
            btn.innerHTML = 'Listen';
            document.querySelector('#loader-line')?.classList?.add('opacity-on');      
            $('#waveform-box').slideUp('slow');            
        }
    }).done(function(data) {

        let download = document.getElementById('downloadBtn');

        if (download) {
            document.getElementById('downloadBtn').href = data['url'];
        }

        let main_audio = document.getElementById('media-element');
        main_audio.setAttribute('src', data['url']);
        main_audio.setAttribute('type', data['audio_type']);
        wavesurfer.load(main_audio);

        wavesurfer.on('ready',     
            wavesurfer.play.bind(wavesurfer),
            playBtn.innerHTML = '<i class="fa fa-pause"></i>',
            playBtn.classList.add('isPlaying'),
        );

    })

};


let playBtn = document.getElementById('playBtn');
let stopBtn = document.getElementById('stopBtn');
let forwardBtn = document.getElementById('forwardBtn');
let backwardBtn = document.getElementById('backwardBtn');
let wave = document.getElementById('waveform');

let wavesurfer = WaveSurfer.create({
    container: wave,
    waveColor: '#ff9d00',
    progressColor: '#1e1e2d',
    selectionColor: '#d0e9c6',
    backgroundColor: '#ffffff',
    barWidth: 2,
    barHeight: 4,
    barMinHeight: 1,
    height: 50,
    responsive: true,				
    barRadius: 1,
    fillParent: true,
    plugins: [
        WaveSurfer.timeline.create({
            container: "#wave-timeline",
            timeInterval: 1,
        }),
        WaveSurfer.cursor.create({
            showTime: true,
            opacity: 1,
            customShowTimeStyle: {
                'background-color': '#000',
                color: '#fff',
                padding: '2px',
                'font-size': '10px'
            }
        }),
    ]
});



playBtn.onclick = function(e) {
    e.preventDefault();

    wavesurfer.playPause();
    if (playBtn.innerHTML.includes('fa-play')) {
        playBtn.innerHTML = '<i class="fa fa-pause"></i>';
        playBtn.classList.add('isPlaying');
    } else {
        playBtn.innerHTML = '<i class="fa fa-play"></i>';
        playBtn.classList.remove('isPlaying');
    }
}

stopBtn.onclick = function(e) {
    e.preventDefault();

    wavesurfer.stop();	
    playBtn.innerHTML = '<i class="fa fa-play"></i>';
    playBtn.classList.remove('isPlaying');
}

forwardBtn.onclick = function(e) {
    e.preventDefault();
    
    wavesurfer.skipForward(3);	
}

backwardBtn.onclick = function(e) {
    e.preventDefault();

    wavesurfer.skipBackward(3);	
}

wavesurfer.on('finish', function() {
    playBtn.innerHTML = '<i class="fa fa-play"></i>';
    playBtn.classList.remove('isPlaying');
    wavesurfer.stop();	
});


/*===========================================================================
*
*  Create New Row 
*
*============================================================================*/
let total_rows = 1;
$('#addTextRow').on('click', function (e) {

    'use strict';

    e.preventDefault();

    if (total_rows != voices_limit) {
        let rowCode = insertNewRow("");

        tippy.delegate('.textarea-buttons', { 
            target: '[data-tippy-content]',
            animation: 'scale-extreme',
            theme: 'material',
        });

        insertNewLine();        
        
        $('#' + rowCode + ' textarea').focus();

    } else {
        Swal.fire('Voice Lines Limit Reached', 'You have reached maximum number of lines for text', 'info');
    }
    
}); 


function insertNewRow(input_text="") {
    let newID = generateID(10);
    let newRow = '<div class="textarea-row pl-2 pr-0" id="' + newID + '">' +
                    '<div class="textarea-voice pl-0 mr-2">' +
                        '<div class="ml-1 mt-1"><img src="' + textarea_img + '" alt="" data-tippy-content="' + textarea_voice_details + '"></div>' +
                    '</div>' +
                    '<div class="textarea-text ml-0 mr-3">' +
                        '<textarea class="form-control textarea" id="' + newID + 'Z" data-voice="' + textarea_voice_id + '" onkeyup="countCharacters();" onmousedown="mouseDown(this);" name="textarea[]" rows="1" maxlength="5000">' + input_text + '</textarea>' +
                    '</div>' +
                    '<div class="textarea-actions">' +
                        '<div class="textarea-buttons">' +
                            '<button class="btn buttons addPause mr-0" id="' + newID + 'P" onclick="addPause(this); return false;" data-tippy-content="Add Pause After Text"><i class="fa-regular fa-hourglass-clock"></i></button>' +
                            '<button class="btn buttons deleteText mr-0" id="' + newID + 'DEL" onclick="deleteRow(this); return false;" data-tippy-content="Delete This Text Block"><i class="fa-solid fa-trash"></i></button>' +
                        '</div>' +
                    '</div>' +
                '</div>';
    
    $("#textarea-row-box").append(newRow);

    tippy.delegate('.textarea-voice', { 
        target: '[data-tippy-content]',
        animation: 'scale-extreme',
        theme: 'material',
    });

    
    if (total_rows < voices_limit) {
        total_rows++;
    }

    if (input_text == '') {
        countCharacters();
    }

    return newID;
}


function generateID(length) {
    let result           = '';
    let characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    let charactersLength = characters.length;

    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }

    return result;
}


function addPause(row) {

    let id = row.id;
    id = id.slice(0, -1);

    var cursorPosition = document.getElementById(id + 'Z').selectionStart;
    var content = $("#" + id + "Z").val();

    var text = '<div class="pt-4"><span id="swal2-wait-time" class="font-weight-bold text-primary">+1s</span></div>';

    Swal.fire({
        title: 'Add Pause',
        html: text,
        input: 'range',
        inputAttributes: {
            min: 0,
            max: 5,
            step: 1
        },
        inputValue: 1,
        showCancelButton: true,
        confirmButtonText: 'Add',
        reverseButtons: true,
        showLoaderOnConfirm: false,
        didOpen: () => {
            const inputRange = Swal.getInput()
            inputRange.nextElementSibling.style.display = 'none'
            inputRange.style.width = '100%'

            $('.swal2-range input[type=range]').on('input change', function() {
                $('#swal2-wait-time').html("+" + $(this).val() + "s")
            });
        },

    }).then((result) => {
        if (result.isConfirmed) {
            var timeValue = result.value;

            let textWait = " <break time=\"" + timeValue + "ms\"/> ";
            $("#" + id + "Z").val(content.slice(0, cursorPosition).trimEnd() + textWait + content.slice(cursorPosition).trimStart());
        }
    })
}


function mouseDown(row) {
    selectedTextarea = row.id;
}


function insertNewLine() {
    let textarea = document.getElementsByTagName("textarea");

    for (let i = 0; i < textarea.length; i++) {
        textarea[i].setAttribute("style", "height:" + (textarea[i].scrollHeight) + "px;overflow-y:hidden;");
        textarea[i].addEventListener("input", onEnterButton, false);
    }
}


function onEnterButton() {
    this.style.height = "auto";
    this.style.height = (this.scrollHeight) + "px";
}


function countCharacters() {
    let all = document.querySelectorAll(".textarea");
    let lines = all.length;
    let total = '';

    all.forEach(function (item, index) {
        var text = document.getElementsByClassName("textarea")[index].value;
        total += text.trim() + ' ';
    });

    var chars = total.trim().length;
    if (lines == 1) {
        $('#total-characters').text(chars + ' characters, ' + lines + ' line');
    } else {
        $('#total-characters').text(chars + ' characters, ' + lines + ' lines');
    }
}


function processText(text) {

    if (text.length > text_length_limit) {
        Swal.fire('Maximum Text Length Reached', 'Maximum text length of the uploaded text file can be up to ' + text_length_limit + ' characters. Selected file contains ' + text.length + ' characters', 'warning');
    } else {
        let text_chunks = chunkString(text, 5000);

        for (var i = 0; i < text_chunks.length; i++) {

            if (text_chunks.length == 1) {
                document.getElementById('ZZZOOOVVVZ').value = text_chunks[0];
            } else {
                if (i == 0) {
                    document.getElementById('ZZZOOOVVVZ').value = text_chunks[0];
                } else {
                    if (i < voices_limit) {
                        insertNewRow(text_chunks[i]);
                    } else {
                        Swal.fire('Maximum Text Length Reached', 'Maximum text length of the uploaded text file can be up to ' + text_length_limit + ' characters. Selected file contains ' + text.length + ' characters.', 'warning');
                        break;
                    }                
                }
            }
        }
    }
}

function chunkString(str, length) {
    return str.match(new RegExp('.{1,' + length + '}', 'g'));
}

function animateValue(id, start, end, duration) {
    if (start === end) return;
    var range = end - start;
    var current = start;
    var increment = end > start? 1 : -1;
    var stepTime = Math.abs(Math.floor(duration / range));
    var obj = document.getElementById(id);
    var timer = setInterval(function() {
        current += increment;
        obj.innerHTML = current;
        if (current == end) {
            clearInterval(timer);
        }
    }, stepTime);
}




  
 