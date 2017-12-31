<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>비밀번호 찾기</title>

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
            <div class="pwdWrap">
                <form id="pwd_form" name="pwd_form" method="post">
                    <div>
                        <input type="email" name="forgot_email" id="forgot_email" title="이메일 입력" value="" placeholder="example@test.com" Role="presentation" & autocomplete="off">
                        <p class="pwdtxt"><span>암호가 메일로 전송됩니다.</span></p>
                        <p id="pwdSubmit"><button type="submit">비밀번호 찾기</button></p>
                    </div>
                </form>
            </div>
        </div> <!-- wrapper END -->
    </div> <!-- content END -->
</div> <!-- container END -->
<script src="js/sub.js"></script>
</body>
</html>