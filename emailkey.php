<script>
    function emailConfirm(data) {
        var data = data;
        if(data == 'ok') {
            alert('인증처리가 정상적으로 완료되었습니다');
        }else {
            alert('인증처리가 실패하였습니다.');
        }
        self.close();
    }

</script>

<?php
$user = base64_decode($_GET['user']);

$mysql_hostname = '';
$mysql_username = '';
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
$query = "SELECT count(*) as cnt
            FROM Users
              WHERE Email='$user' AND Deleted = 0";

//5. 쿼리 실행
$result = mysqli_query($connect, $query) or die(mysqli_error());
$cnt = mysqli_fetch_array($result);
$count = $cnt[0];

//6. 결과 처리
if($count > 0) {
    $query = "UPDATE Users SET Level = 1 WHERE Email='$user'";
    mysqli_query($connect, $query) or die(mysqli_error());

    echo "<script>emailConfirm('ok');</script>";
}else {
    echo "<script>emailConfirm('error');</script>";
}

//6. 연결 종료
mysqli_close($connect);
?>

