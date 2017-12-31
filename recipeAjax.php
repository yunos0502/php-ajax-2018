<?php
$RecipeSerial   = isset($_GET['RecipeSerial']) ? $_GET['RecipeSerial'] : 0;
$ListData       = isset($_GET['range']) ? $_GET['range'] : '';
//$page       = isset($_GET['page']) ? $_GET['page'] : '';
$UserID         = date("YmdHis");

$mysql_hostname = '';
$mysql_username = 'root';
$mysql_password = '';
$mysql_database = '';
$mysql_port = '3306';
$mysql_charset = 'utf8';

//1. DB 연결
$connect = @mysqli_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password);

//if(!$connect){
//    echo '[연결실패] : '.mysqli_error().'<br>';
//    die('MySQL 서버에 연결할 수 없습니다.');
//} else {
//    echo '[연결성공]<br>';
//}
//2. DB 선택
@mysqli_select_db($connect, $mysql_database) or die('DB 선택 실패');

//3. 문자셋 지정
mysqli_query($connect, $mysql_charset);

//4. 쿼리 생성
if($ListData == 'new' || $ListData == '') {
    $query = "SELECT Serial, Title, PictureSquare, UpdatedDate, Nickname, RecommendationCount, CommentCount FROM vRecipeBest ORDER BY UpdatedDate DESC";
} elseif ($ListData == 'best') {
    $query = "SELECT Serial, Title, PictureSquare, UpdatedDate, Nickname, RecommendationCount, CommentCount FROM vRecipeLastest ORDER BY RecommendationCount DESC LIMIT 200";
}

//5. 쿼리 실행
$result = mysqli_query($connect, $query) or die(mysqli_error());

while ($recipeData = mysqli_fetch_array($result)) {
    $rows[] = $recipeData;
}

$img = '';
$i = 0;

foreach ($rows as $item) {
    $addClass = ($i % 2 == 1)?'ui-block-b marginLeft': 'ui-block-a';
    $nullImg = (!$item['PictureSquare'])?'<div class="nullImg"></div>':'<img src="https://s3.ap-northeast-2.amazonaws.com/cookplay-recipe/'.$item['PictureSquare'].'" alt="'.$item['Title'].' 사진">';

    $img .= '<li class="'.$addClass.'">
                <a href="#">
                    '.$nullImg.'
                    <span class="recipeTit">'.$item['Title'].'</span>
                </a>
                <ul id="list" class="recipeResponse">
                    <li class="favoriteImg"><a href="#" data-listNum="'.$item['Serial'].'" data-count="'.$item['RecommendationCount'].'"><span>'.$item['RecommendationCount'].'</span></a></li>
                    <li class="commentImg"><a href="#"><span>'.$item['CommentCount'].'</span></a></li>
                </ul>
            </li>';
    $i++;
}

//6. 결과 처리
if($result) {
    $sendParam = array(
        'result'        => 'success',
        'msg'           => '등록이 완료되었습니다',
        'img'           => $img,
        'range'         => $ListData,
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