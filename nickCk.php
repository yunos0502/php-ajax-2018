<?php
$nickname          = $_POST['nickname'];
//$nickname = 'test';

$mysql_hostname = '';
$mysql_username = 'root';
$mysql_password = '';
$mysql_database = '';
$mysql_port = '3306';
$mysql_charset = 'utf8';

//1. DB 연결
$connect = @mysqli_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password);

//2. DB 선택
@mysqli_select_db($connect, $mysql_database) or die('DB 선택 실패');

//3. 문자셋 지정
mysqli_query($connect, $mysql_charset);

//4. 쿼리 생성
$query = "SELECT COUNT(*) as CNT FROM Users WHERE Nickname='$nickname'";

//5. 쿼리 실행
$result = mysqli_query($connect, $query) or die(mysqli_error());
$count = mysqli_fetch_array($result);

//6. 결과 처리
if($count['CNT'] > 0) {
    $sendParam = array(
        'result' => 'error',
        'msg'    => '사용중인 닉네임입니다'
    );
    echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
}else {
    $sendParam = array(
        'result'        => 'success',
        'msg'           => '사용가능한 닉네임입니다'
    );
    echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
}

//6. 연결 종료
mysqli_close($connect);