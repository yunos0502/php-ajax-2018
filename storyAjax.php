<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$serial = isset($_GET['serial']) ? $_GET['serial'] : '';
//$serial = 2;
$type = isset($_GET['type']) ? $_GET['type'] : '';

$mysql_hostname = '';
$mysql_username = 'root';
$mysql_password = '';
$mysql_database = '';
$mysql_port     = '3306';
$mysql_charset  = 'utf8';

$conn           = @mysqli_connect($mysql_hostname. ':' .$mysql_port, $mysql_username, $mysql_password);
@mysqli_select_db($conn, $mysql_database) or die('error');
mysqli_query($conn, $mysql_charset);

$query          = "SELECT *
                    FROM StoryContents S 
                    JOIN vStoryList SV ON S.Story_Serial = SV.Serial
                    WHERE Story_Serial = '.$serial.' AND ValueType = 12";
$result         = mysqli_query($conn, $query);

while ($dataList = mysqli_fetch_array($result)) {
    $storyRow[] = $dataList;
}

for($i = 0; $i < count($storyRow); $i++) {
    if($type == 'view') {
        $ImgUrl = 'https://s3.ap-northeast-2.amazonaws.com/cookplay-story/' . $storyRow[$i]['PictureRectangle'];
        $storyPic .= '<div class="swiper-slide storyImg si'.$i.'">
                        <img src="'.$ImgUrl.'">
                      </div>';
    }else {
        $ImgUrl = 'https://s3.ap-northeast-2.amazonaws.com/cookplay-story/' . $storyRow[$i]['Picture'];
        $storyPic .= '<div class="swiper-slide storyImg si'.$i.'" data-hash="slide'.($i+1).'">
                            <div class="swiper-zoom-container">
                                <img src="'.$ImgUrl.'">
                            </div>
                      </div>';
    }
}

if($result) {
    $sendParam = array(
        'result'        => 'success',
        'user'          => $storyRow[0]['UserNickname'],
        'pic'           => $storyRow[0]['UserPicture'],
        'storyPic'      => $storyPic,
        'desc'          => $storyRow[0]['Description'],
        'type'          => $type,
        'StoryCategory' => $storyRow[0]['StoryCategory'],
    );
    echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
}else {
    $sendParam = array(
        'result'        => 'error',
    );
    echo json_encode($sendParam, JSON_UNESCAPED_UNICODE);
}

mysqli_close($conn);