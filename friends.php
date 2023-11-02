<?php
require_once 'header.php';

if (!$obj->loggedin($obj)) {

    header("Location: login.php");
}
if (!$obj->acountVerified($obj)) {
    header("Location: verifyemail.php");
}
$loged_user_email = $_SESSION['logged_user'];
$user_id = $obj->getUserIdByEmail($obj, $loged_user_email);

$user = $obj->getUserByEmail($obj, $loged_user_email);
$user_name = $user['user_name'];


$query = "SELECT user2.* FROM users
        LEFT JOIN user_has_friend ON users.id = user_has_friend.user_id2
        LEFT JOIN users AS user2 ON user2.id = user_id1
        WHERE user_id2 = '$user_id' AND accepted = 0;";

$friend_requests = $obj->executeQuery($query);


$query = "SELECT user2.* FROM users
        LEFT JOIN user_has_friend ON users.id = user_has_friend.user_id2
        LEFT JOIN users AS user2 ON user2.id = user_id1
        WHERE user_id2 = '$user_id' AND accepted = 1;";

$friends = $obj->executeQuery($query);


?>

<div class="freind-list">
    <div class="title">
        new requests
    </div>
    <div class="friend-list-box">
        <?php
        while ($row = mysqli_fetch_assoc($friend_requests)) {
            echo '<div class="friends">
            <div class="d-flex">
                <div class="friends-image">
                    <img src="' . $row['profile_image'] . '" alt="">
                </div>
                <div class="friend-details">
                    <p>' . $row['user_name'] . '</p>
                    <p>@USER</p>
                </div>
            </div>

            <div class="cals d-flex">
                <form action="accepte_or_reject_friend.php" method="post">
                    <input type="text" name="accepte" value="' . $row['id'] . '" id="" style="display:none">
                    <button type="submit"><i class="fa-solid fa-check"></i> accepte</button>
                </form>
                
            

                <form action="accepte_or_reject_friend.php" method="post">
                    <input type="text" name="rejecte" value="' . $row['id'] . '" id="" style="display:none">
                    <button type="submit"><i class="fa-solid fa-ban"></i> reject</button>
                </form>
                
            </div>
        </div>';
        }
        ?>
    </div>

</div>



<div class="freind-list">
    <div class="title">
        friend list
    </div>
    <div class="friend-list-box">

        <?php
        while ($row = mysqli_fetch_assoc($friends)) {
            echo '<div class="friends">
            <div class="d-flex">
                <div class="friends-image">
                    <img src="' . $row['profile_image'] . '" alt="">
                </div>
                <div class="friend-details">
                    <p>' . $row['user_name'] . '</p>
                    <p>@USER</p>
                </div>
            </div>

            <div class="cals d-flex">
                <form action="messages.php" method="POST">
                    <input type="text" name="target_user_mail" value="' . $row['email'] . '"  style="display:none">
                    <button type="submit" class="message-button"><i class="fa-solid fa-message"></i></button>
                </form>
                <button class="green" onclick="calluser(\'' . $row['user_name'] . '\', false)" href=""><i class="fa-solid fa-phone"></i></button>
                <button class="red" onclick="calluser(\'' . $row['user_name'] . '\', true)" href=""><i class="fa-solid fa-video"></i></button>
            </div>
        </div>';
        }
        ?>



    </div>

    <div class="videos">
        <video id="localVideo" autoplay></video>
        <video id="remoteVideo" autoplay></video>
    </div>
</div>




<script src="https://unpkg.com/peerjs@1.5.1/dist/peerjs.min.js"></script>
<script>
    var peer;
    var ringLoop;
    var isvideo = true;
    window.onload = function () {
        peer = new Peer("<?php echo $user_name; ?>");
        peer.on('open', function (id) {
            console.log('open', id);
        });


        peer.on('connection', function (conn) {
            conn.on('data', function (data) {
                console.log(data);
                if (data == 'isAudioCall') {
                    isvideo = false;
                }

            });
        });


        peer.on('call', function (call) {

            if (isvideo) {
                console.log("isvideo");
            } else {
                console.log("isaudio");
            }

            if (confirm("you have a call")) {
                var getUserMedia = navigator.mediaDevices.getUserMedia || navigator.mediaDevices.webkitGetUserMedia || navigator.mediaDevices.mozGetUserMedia;
                getUserMedia({ video: isvideo, audio: true })
                    .then(function (stream) {
                        call.answer(stream); // Answer the call.

                        call.on('stream', function (remoteStream) {

                            let remoteVideo = document.getElementById('remoteVideo');
                            remoteVideo.srcObject = remoteStream;
                        });

                        let localVideo = document.getElementById('localVideo');
                        localVideo.srcObject = stream;
                        if (!isvideo) {
                            stream.getVideoTracks()[0].stop();
                            localVideo.srcObject = null;
                        }


                    })
                    .catch(function (err) {
                        console.log('Failed to get local stream reciever', err);
                    });

            } else {
                let callerId = call.peer;
                let reciever = peer.connect(callerId);
                // on open will be launch when you successfully connect to PeerServer
                reciever.on('open', function () {
                    reciever.send('refused');
                });
            }


        });
    }
    function calluser(userid, isVideo) {


        if (!isVideo) {
            let reciever = peer.connect(userid);
            // on open will be launch when you successfully connect to PeerServer
            reciever.on('open', function () {
                reciever.send('isAudioCall');
            });
        }


        var getUserMedia = navigator.mediaDevices.getUserMedia || navigator.mediaDevices.webkitGetUserMedia || navigator.mediaDevices.mozGetUserMedia;
        getUserMedia({ video: isVideo, audio: true })
            .then(function (stream) {

                var call = peer.call(userid, stream);

                console.log("calling ...", userid);
                var aud = new Audio('/images/ringing.mp3');

                var aud_replay_duration = 0;
                aud.onloadeddata = function (data) {
                    aud_replay_duration = aud.duration;
                    aud.play();
                    ringLoop = setInterval(function () {
                        aud.play();
                    }, aud_replay_duration + 3000);
                }

                call.on('stream', function (remoteStream) {
                    clearInterval(ringLoop);
                    console.log("call accepted", userid);

                    let localVideo = document.getElementById('localVideo');
                    localVideo.srcObject = stream;

                    let remoteVideo = document.getElementById('remoteVideo');
                    remoteVideo.srcObject = remoteStream;

                });

                peer.on('connection', function (conn) {
                    conn.on('data', function (data) {
                        if (data == 'refused') {
                            clearInterval(ringLoop);
                            console.log('call refused');
                        }
                    });
                });

            })
            .catch(function (err) {
                console.log('Failed to get local stream caller', err);
            });


    }
</script>


<?php
include_once 'footer.php';
?>