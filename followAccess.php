<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$RecipeSerial   = isset($_GET['RecipeSerial']) ? $_GET['RecipeSerial'] : '';
$loginUser   = isset($_GET['loginUser']) ? $_GET['loginUser'] : '';
$followClick = $_GET['followClick'];

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

$query = "SELECT DISTINCT UserSerial 
            FROM vRecipeView
            WHERE RecipeSerial='$RecipeSerial'";

$result = mysqli_query($connect, $query) or die(mysqli_error());

while ($dataList = mysqli_fetch_array($result)) {
    $rows = $dataList;
}

$Follow = $rows['UserSerial'];
//$Follow = 3;

if($loginUser) {
    if($Follow == $loginUser) {
        $sendParam = array(
            'result' => 'userCk',
            'msg' => '동일인입니다',
        );
        echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
    }else {
        $FollowQuery = "SELECT Following FROM Follow WHERE Follow='$Follow' AND Follower='$loginUser'";
        $FollowResult = mysqli_query($connect, $FollowQuery) or die(mysqli_error());
        while ($dataList = mysqli_fetch_array($FollowResult)) {
            $FollowRows = $dataList;
        }
        $cnt = count($FollowRows['Following']);

        if ($cnt < 1) {
            $FollowQuery = "INSERT INTO Follow(Follow, Follower, CreatedDate)
              VALUES ('$Follow', '$loginUser', now())";
            $FollowResult = mysqli_query($connect, $FollowQuery) or die(mysqli_error($connect));

            if ($FollowResult) {
                $sendParam = array(
                    'result' => 'insert',
                    'msg' => '팔로잉 완료',
                    'cnt' => $cnt,
                    'followState' => $FollowRows['Following'],
                );
                echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
            }
        } else {
            if (($followClick == '0') || $followClick == '1') {
                $FollowQuery = "UPDATE Follow SET Following = '$followClick' WHERE follower = '$loginUser' AND follow = '$Follow'";
                $FollowResult = mysqli_query($connect, $FollowQuery) or die(mysqli_error($connect));

                if ($FollowResult) {
                    $sendParam = array(
                        'result' => 'update',
                        'msg' => '업데이트 완료',
                        'cnt' => $cnt,
                    );
                    echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
                }
            } else {
                $sendParam = array(
                    'result' => 'loading',
                    'Follow' => $Follow,
                    'loginUser' => $loginUser,
                    'followState' => $FollowRows['Following'],
                    'cnt' => $cnt,
                );
                echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
            }
        }
    }
}

//6. 연결 종료
mysqli_close($connect);