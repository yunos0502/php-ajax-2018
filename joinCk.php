<?php
$nickname       = (isset($_POST['nickname']) && $_POST['nickname']) ? $_POST['nickname'] : '';
$email          = (isset($_POST['email']) && $_POST['email']) ? $_POST['email'] : '';
$user           = base64_encode($email);
$pwd            = (isset($_POST['pwd']) && $_POST['pwd']) ? $_POST['pwd'] : NULL;
$birth          = (isset($_POST['birth']) && $_POST['birth']) ? $_POST['birth'] : NULL;
$birth          = date('Y-m-d', strtotime($birth));
$gender         = (isset($_POST['gender']) && $_POST['gender']) ? $_POST['gender'] : NULL;
$ip             = $_SERVER['REMOTE_ADDR'];
$AgreeMarketing = (isset($_POST['AgreeMarketing']) && $_POST['AgreeMarketing']) ? $_POST['AgreeMarketing'] : 0;
$key_value      = (isset($_POST['sign_key']) && $_POST['sign_key']) ? $_POST['sign_key'] : 'sign';

// 보내는 사람
$admin_email    = 'yunos82@cookplay.net';
$admin_name     = 'cookplay';
$header         = "Return-Path: ".$admin_email."\n";
$header         .= "From: =?EUC-KR?B?".base64_encode($admin_name)."?= <".$admin_email.">\n";
$header         .= "MIME-Version: 1.0\n";
$header         .= "X-Priority: 3\n";
$header         .= "X-MSMail-Priority: Normal\n";
$header         .= "X-Mailer: FormMailer\n";
$header         .= "Content-Transfer-Encoding: base64\n";
$header         .= "Content-Type: text/html;\n \tcharset=utf-8\n";

$mysql_hostname = '';
$mysql_username = 'root';
$mysql_password = '';
$mysql_database = '';
$mysql_port     = '3306';
$mysql_charset  = 'utf8';

//1. DB 연결
$connect = @mysqli_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password);

//2. DB 선택
@mysqli_select_db($connect, $mysql_database) or die('DB 선택 실패');

//3. 문자셋 지정
mysqli_query($connect, $mysql_charset);

// 메일 내용
if($key_value == 'sign' || $key_value == 'resend') {
    if($key_value == 'sign') { // 신규 가입
        $query = "INSERT INTO Users (Nickname, Email, Pwd, Birthday, Gender, ip, CreatedDate, LastUpdatedDate, AgreeMarketing) 
                                    VALUES ('$nickname', '$email', '$pwd', '$birth', '$gender', '$ip', now(), now(), $AgreeMarketing)";
    } else if($key_value == 'resend') { // 메일 재발송
        $query = "SELECT COUNT(*) as CNT FROM Users WHERE email='$email'";
    }

    $subject = "=?utf-8?B?".base64_encode('쿡플레이 회원가입 인증메일입니다.')."?=\n";
    $message = base64_encode('<div style="width: 536px; height: 332px; border: 1px solid #B2B2B2; border-radius: 5px; margin: 0 auto; padding: 30px; color: #585858; text-align:center; font-size: 12px;">
                            <h1 style="color:#B42524; font-size: 25px; letter-spacing: 1.5px;"><strong>cook</strong>play</h1>
                            <p><span style="font-size: 18px; line-height: 45px; font-weight: 500;">가입해 주셔서 감사합니다.</span><br>
                            이제 이메일 주소를 인증하시면 모든 기능을 이용하실 수 있습니다. </p>
                            <p style="margin-top: 20px;">클릭으로 인증되지 않을 경우, 고객센터 이메일 <a href="mailto:cookplay.cookplay.com" style="color: #326FFF; text-decoration: underline;">cookplay@cookplay.com</a> 또는 <br>전화 <a href="tel:0000-0000" style="color: #326FFF; text-decoration: underline;">0000-0000</a>으로 연락주시기 바랍니다.</p>
                            <p style="width: 120px; margin: 0 auto; margin-top: 38px;">
                            <a href="http://test.cookplay.net/test/emailkey.php?user='.$user.'" style="width: 120px; height: 40px; display: block; font-weight: bold; background-color: #FF7500; color: #FFF; border-radius: 5px; line-height: 40px;">이메일 인증</a>
                            </p>
                            </div>
                            ');
//5. 쿼리 실행
    $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
}else if($key_value == 'forgot') { //비밀번호 찾기
    $query = "SELECT COUNT(*) as CNT FROM Users WHERE email='$email'";
    $result = mysqli_query($connect, $query) or die(mysqli_error($connect));
    $count = mysqli_fetch_array($result);

    if ($count['CNT'] == 0) {
        $sendParam = array(
            'result' => 'not_found',
            'msg' => '등록된 이메일이 아닙니다.'
        );
        echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
        return false;
    } else {
        $sign_query = "SELECT Nickname, Pwd, Name FROM Users WHERE email='$email'";
        $sign_result = mysqli_query($connect, $sign_query) or die(mysqli_error($connect));
        $sign = mysqli_fetch_array($sign_result);

        $pwd = $sign['Pwd'];
        $name = $sign['Nickname'];

        // 메일 내용
        $subject = "=?utf-8?B?".base64_encode('쿡플레이 비밀번호 안내입니다.')."?=\n";
        $message = base64_encode('<div style="width: 536px; height: 332px; border: 1px solid #B2B2B2; border-radius: 5px; margin: 0 auto; padding: 30px; color: #585858; text-align:center; font-size: 12px;">
                <h1 style="color:#B42524; font-size: 25px; letter-spacing: 1.5px;"><strong>cook</strong>play</h1>
                <p><span style="font-size: 18px; line-height: 40px; font-weight: 500;">안녕하세요, '.$name .'님</span><br>
                가입하실 때 입력하신 비밀번호를 보내드립니다.<br>로그인 후 비밀번호 변경을 권해드립니다.</p>
                <p style="font-size: 18px; line-height: 30px; text-decoration: #585858">'.$pwd.'</p>
                <p>로그인에 문제가 있을 경우, 고객센터 이메일 <a href="mailto:cookplay.cookplay.com" style="color: #326FFF; text-decoration: underline;">cookplay@cookplay.com</a> 또는 <br>전화 <a href="tel:0000-0000" style="color: #326FFF; text-decoration: underline;">0000-0000</a>으로 연락주시기 바랍니다.</p>
                </div>
                ');
    }
}

//6. 결과 처리
if($result) {
    if($key_value == 'sign') {
        $sendParam = array(
            'result'    => 'success',
            'msg'       => '회원가입이 완료되었습니다',
        );
    }
    $email_send = mail($email, $subject, $message, $header, "-f " . $admin_email);

    if(!$email_send) {
        $sendParam = array(
            'mail_result'   => 'error',
            'mail_msg'      => '메일 발송이 실패했습니다',
        );
        echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
        return false;
    } else {
        if($key_value == 'forgot') {
            $sendParam = array(
                'result' => 'remailSuccess',
                'msg' => '비밀번호가 이메일로 전송되었습니다.'
            );
        }else {
            $sendParam = array(
                'mail_result'   => 'success',
                'mail_msg'      => '메일 발송이 성공했습니다',
            );
        }
        echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
    } // 메일 발송

} else {
    $sendParam = array(
        'result' => 'error',
        'msg'    => '회원가입이 실패했습니다',
    );
    echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
    return false;
} // 회원가입

//6. 연결 종료
mysqli_close($connect);