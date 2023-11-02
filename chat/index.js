var peer1 = null;


(function(){

    let myid = prompt('enter your id');
    peer1 = new Peer(myid);
    peer1.on('open', function(id) {
        var person1idcontainer = document.getElementById('person1id');
        person1idcontainer.innerHTML = id;
    });


    peer1.on('connection', function(conn) {
        let result = document.getElementById('result');
        conn.on('data', function(data){
          result.innerHTML = result.innerHTML + data;
        });
    });


       

    peer1.on('call', function(call) {
        var getUserMedia = navigator.mediaDevices.getUserMedia || navigator.mediaDevices.webkitGetUserMedia || navigator.mediaDevices.mozGetUserMedia;
        getUserMedia({ video: true, audio: true })
            .then(function (stream) {
                let localVideo = document.getElementById('localVideo');
                localVideo.srcObject = stream;
                let confirmcall = confirm("you have a call");
               
                if(confirmcall){
                    call.answer(stream); // Answer the call with an A/V stream.
                    call.on('stream', function (remoteStream) {
                        let remoteVideo = document.getElementById('remoteVideo');
                        remoteVideo.srcObject = remoteStream;
                    });
                }
                
            })
            .catch(function (err) {
                console.log('Failed to get local stream', err);
            });
    });
        
        
})();

// send message
function callperson(){
    var personid = document.getElementById("personid").value;
    var person1 = peer1.connect(personid);
    // on open will be launch when you successfully connect to PeerServer
    person1.on('open', function(){
    // here you have conn.id
    let message = document.getElementById('personmessage').value;
    person1.send(message);
    });
}

function videocall() {
    var getUserMedia = navigator.mediaDevices.getUserMedia || navigator.mediaDevices.webkitGetUserMedia || navigator.mediaDevices.mozGetUserMedia;
    getUserMedia({ video: true, audio: true })
    .then(function (stream) { 

        let localVideo = document.getElementById('localVideo');
        localVideo.srcObject = stream;

        var personid = document.getElementById("personid").value;
        var call = peer1.call(personid, stream);
        call.on('stream', function (remoteStream) {

            let remoteVideo = document.getElementById('remoteVideo');
            remoteVideo.srcObject = remoteStream;
            // Show the stream in a video or canvas element.
        });
    })
    .catch(function (err) {
        console.log('Failed to get local stream', err);
    });
}



