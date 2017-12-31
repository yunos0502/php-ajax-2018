<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>

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
<div class="signPage container">
    <div class="content" data-role="page">
        <div class="wrapper">
            <div class="signWrap">
                <form id="signup_form" name="signup_form" method="get" onsubmit="return false;">
                    <fieldset>
                        <legend><span class="hid">login</span></legend>
                        <ul class="inputGroup">
                            <li>
                                <input type="text" name="nickname" id="nickname" placeholder="닉네임(10자 이내)" Role="presentation" & autocomplete="off" data-validate="0" maxlength="10" autocapitalize="off" autocorrect="off">
                            </li>
                            <li>
                                <input type="email" name="email" id="email" placeholder="example@test.com" Role="presentation" & autocomplete="off" data-validate="0" style="ime-mode:inactive;" autocapitalize="off" autocorrect="off">
                            </li>
                            <li><input type="password" name="pwd" id="pwd" placeholder="비밀번호(영문,숫자 포함 6글자 이상)" placeholder="password" autocomplete="new-password"></li>
                            <li><input type="password" name="pwdCheck" id="pwdCheck" placeholder="비밀번호 확인" placeholder="password" autocomplete="new-password"></li>
                            <li>
                                <div class="birthGroup">
                                    <input type="tel" name="birth" id="birth" placeholder="예)19870928" Role="presentation" & autocomplete="off" onkeydown="onlyNumber(this)" maxlength="8" pattern="[0-9]*">
                                </div>
                                <ul class="genderGroup">
                                    <li>
                                        <input type="radio" name="gender" class="gender" value="M" checked><label for="gender">남</label>
                                    </li>
                                    <li>
                                        <input type="radio" name="gender" class="gender" value="F"><label for="gender">여</label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </fieldset>
                    <section class="agreementGroup">
                        <p class="agree">
                            <input type="checkbox" id="allAgree" name="agree">
                            <label for="allAgree">
                                <span class="linkTxt">과</span>
                                <span class="linkTxt">을 동의합니다.</span>
                            </label>
                            <a href="tos.html" class="tos"><span>이용약관</span></a>
                            <a href="privacy.html" class="privacy"><span>개인정보취급방침</span></a>
                        </p>

                        <p class="agree"><input type="checkbox" id="marketingAgree" name="agree" value="0"><label for="marketingAgree">마케팅 수신 동의(선택)</label></p>
                    </section>
                    <p id="signUpSubmit"><button type="button">완료</button></p>
                </form>
            </div>
        </div> <!-- wrapper END -->
    </div> <!-- content END -->
</div> <!-- container END -->
<script src="js/sub.js"></script>
<script>
    function onlyNumber(obj) {
        $(obj).keyup(function(){
            $(this).val($(this).val().replace(/[^0-9]/g,""));
        });
    } //생년월일 숫자만 입력
</script>
</body>
</html>