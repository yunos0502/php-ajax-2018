<?php
$RecipeSerial   = isset($_GET['RecipeSerial']) ? $_GET['RecipeSerial'] : 0;
$Users_Serial   = '3'; //임시 아이디부여

$mysql_hostname = '';
$mysql_username = '';
$mysql_password = '';
$mysql_database = '';
$mysql_port = '3306';
$mysql_charset = 'utf8';

//1. DB 연결
$connect = @mysqli_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password);

//2. DB 선택
@mysqli_select_db($connect, $mysql_database) or die(mysqli_error());

//3. 문자셋 지정
mysqli_query($connect, $mysql_charset);

//4. 쿼리 생성
$query = "INSERT INTO RecipeRecommendation (Recipe_Serial, Users_Serial, CreatedDate) 
                                    VALUES ('$RecipeSerial', '$Users_Serial', now())";
$countQuery = "SELECT COUNT(*) as CNT FROM RecipeRecommendation WHERE Recipe_Serial='$RecipeSerial'";

//5. 쿼리 실행
$result = mysqli_query($connect, $query) or die(mysqli_error());
$countResult = mysqli_query($connect, $countQuery) or die(mysqli_error());
$count = mysqli_fetch_array($countResult);
$count = $count['CNT'];

//6. 결과 처리
if($result) {
    $sendParam = array(
        'result'    => 'success',
        'msg'       => '등록이 완료되었습니다',
        'listNum'   => $RecipeSerial,
        'count'     => $count,
//        'range'     => $range,
    );
    echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
}else {
    $sendParam = array(
        'result' => 'error',
        'msg'    => '등록이 실패했습니다'
    );
    echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
}

//6. 연결 종료
mysqli_close($connect);