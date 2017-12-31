<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>login</title>

    <!-- VIEWPORT -->
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include 'inc/gnb.php' ?>
<div class="loginPage container">
    <div class="content" data-role="page">
        <div class="wrapper">
            <div class="loginWrap">
                <form id="login_form" name="login_form" method="get" onsubmit="return false;">
                    <fieldset>
                        <legend><span class="hid">login</span></legend>
                        <ul class="inputGroup">
                            <li><input type="email" name="loginEmail" id="loginEmail" title="이메일 입력" value="" placeholder="example@test.com" Role="presentation" & autocomplete="off"></li>
                            <li><input type="password" name="loginPwd" id="loginPwd" title="비밀번호 입력" value="" placeholder="password" autocomplete="new-password"></li>
                        </ul>
                    </fieldset>
                    <ul class="confirmTxt">
                        <li><a href="forgot_password.php">비밀번호 찾기</a></li>
                        <li><a href="sign_up.php">회원가입하기</a></li>
                    </ul>
                    <p id="loginSubmit"><button type="button">로그인</button></p>
                    <p id="loginRemember"><input type="checkbox" name="autologin" id="autologin" value="1" checked="checked"><label for="autologin">로그인 상태 유지</label></p>
                </form>
            </div>
        </div> <!-- wrapper END -->
    </div> <!-- content END -->
</div> <!-- container END -->
<!--<script src="js/cookies.js"></script>-->
<script>
    /////////////////////////////////////////////////////////////
    $( document ).ready(function () {
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }

        var callEmail = getParameterByName('email');
        var callAutologin = getParameterByName('autologin');
        var email = document.getElementById('loginEmail');

        if(callAutologin == 0) {
            email.value = '';
            email.style.color = '#BDBDBD';
            $('#autologin').attr('checked', false);
            $('#autologin').val(0);
        } else {
            email.value = callEmail;
            email.style.color = "#303030";
            $('#autologin').attr('checked', true);
            $('#autologin').val(1);
        }

        var paramEmail = getParameterByName('email_address');

        if(paramEmail) {
            change_email(paramEmail);
        }
    }); // app 파라미터 전달받음

    function change_email(email_address) {
        var changeEmail = email_address;
        var email = document.getElementById('loginEmail');
        email.value = changeEmail;
    } // app 파라미터 전달받음(email value)

    function resend_email(email) {
        var email = email;

        // 메일 발송 처리
        $.ajax({
            type: "POST",
            url: "joinCk.php",
            cache: false,
            async:false,
            dataType: "json",
            data: {'email': email, 'sign_key': 'resend'},
            success: function (data) {
                if(data.mail_result == 'success') {
                    location.href = 'appcall://close_message?인증 메일이 다시 발송되었습니다 :D';
                } else {
                    location.href = 'appcall://message?가입하신 이메일이 맞는지 다시 확인해주세요 :D';
                }
            },
            error: function(request, error){
                alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });
    } // app 파라미터 전달받음(email 재발송)
</script>
<script src="js/sub.js"></script>
</body>
</html>