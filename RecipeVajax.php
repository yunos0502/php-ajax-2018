<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

$RecipeSerial   = isset($_GET['RecipeSerial']) ? $_GET['RecipeSerial'] : 0;
$listType       = isset($_GET['listType']) ? $_GET['listType'] : 'original';
$ratio          = isset($_GET['ratio']) ? $_GET['ratio'] : 1;

$mysql_hostname = '';
$mysql_username = 'root';
$mysql_password = '';
$mysql_database = '';
$mysql_port     = '3306';
$mysql_charset  = 'utf8';

//1. DB 연결
$connect        = @mysqli_connect($mysql_hostname.':'.$mysql_port, $mysql_username, $mysql_password);

//2. DB 선택
@mysqli_select_db($connect, $mysql_database) or die('DB 선택 실패');

//3. 문자셋 지정
mysqli_query($connect, $mysql_charset);

//4. 쿼리 생성
$query      = "SELECT * 
                FROM vRecipeView
                WHERE RecipeSerial='$RecipeSerial' ORDER BY StepSeq ASC";

$InfoQuery  = "SELECT *
                FROM vRecipeInfo
                WHERE RecipeSerial='$RecipeSerial' ORDER BY StepSeq ASC";

$StepQuery  = "SELECT *
                FROM vRecipeViewStep
                WHERE RecipeSerial='$RecipeSerial' ORDER BY StepSeq ASC, Seq ASC";

$ListQuery  = "SELECT *, round(sum(ifnull(`Amount`,0)),1) AS sum
                FROM vRecipeViewStep
                WHERE RecipeSerial='$RecipeSerial'
                GROUP BY `Title`,`Unit`
                ORDER BY StepSeq ASC, Seq ASC";

//5. 쿼리 실행
$result     = mysqli_query($connect, $query) or die(mysqli_error());
$InfoResult = mysqli_query($connect, $InfoQuery) or die(mysqli_error());
$StepResult = mysqli_query($connect, $StepQuery) or die(mysqli_error());
$ListResult = mysqli_query($connect, $ListQuery) or die(mysqli_error());

while ($dataList    = mysqli_fetch_array($InfoResult)) {
    $InfoRows[]     = $dataList;
}
while ($dataList    = mysqli_fetch_array($ListResult)) {
    $ListRows[]     = $dataList;
}

if($InfoRows[0]['Category']) {
    $categoryList   = $InfoRows[0]['Category'];
    $category       = explode(';', $categoryList);

    for($i = 0; $i < count($category); $i++) {
        $CategoryQuery      = "SELECT *
                                FROM Category
                                WHERE CategoryCode = '$category[$i]' AND CategoryType = 'RECIPEC'";
        $CategoryResult     = mysqli_query($connect, $CategoryQuery) or die(mysqli_error());
        $CategoryRow[$i]    = mysqli_fetch_array($CategoryResult);
        $CategoryName[]     = $CategoryRow[$i]['CategoryName'];
    }
}

$serving    = isset($_GET['serving']) ? $_GET['serving'] : $InfoRows[0]['Servings']; //인분
$type       = array( 0 => '재료', 1 => '양념', 2 => '온도', 3 => '시간', 4 => '레시피링크' );
$StepType   = array( 0 => 'Ingredient', 1 => 'Source', 2 => 'temp', 3 => 'Timer', 4 => 'Import' );
$list       = array( 0 => '', 1 => '', 2 => '', 3 => '' );
$itemList   = array(); $StepSub = array(); $StepTime = array();
$Category   = array(
    "Level" => array(
        0   => '하',
        1   => '중',
        2   => '상'
    ),
    "Time"  => array(
        0   => '5분 이내',
        1   => '10분 이내',
        2   => '20분 이내',
        3   => '40분 이내',
        4   => '1시간 이내',
        5   => '1시간 이상'
    )
);

//if($result) {
while ($dataList    = mysqli_fetch_array($result)) {
    $rows[]         = $dataList;
}
while ($dataList    = mysqli_fetch_array($StepResult)) {
    $StepRows[]     = $dataList;
}

for ($i = 0; $i < count($StepRows); $i++) {
    // amount : 분수 => 소수점으로 변환
    $basicAmount[] = $StepRows[$i]['Amount'];
    $basicWeight[] = $StepRows[$i]['ItemWeightValue'];

    if (explode('/', $basicAmount[$i])) {
        $amount[$i] = explode('/', $basicAmount[$i]);
        if (count($amount[$i]) == 2) {
            $basicAmount[$i] = (float)($amount[$i][0]) / (float)($amount[$i][1]);
        } else {
            $basicAmount[$i] = (float)($amount[$i][0]);
        }
    }
    $oneServing[]   = (float)($basicAmount[$i]) / floatval($StepRows[0]['Servings']); //1인분 Amount 계산
    $eachServing[]  = ((float)($oneServing[$i]) * (float)($serving)) * (float)($ratio);
    $oneWeight[]    = (float)($basicWeight[$i]) / floatval($StepRows[0]['Servings']); //1인분 Weight 계산
    $eachWeight[]   = ((float)($basicWeight[$i]) * (float)($serving)) * (float)($ratio);
    $roundAmount    = round($eachServing[$i], 1);
    $weight         = ($StepRows[$i]['ItemWeightValue'] > 0) ? '<strong class="weight"> ' . round($eachWeight[$i], 1) . '</strong>g' : '';
    $title          = ($StepRows[$i]['ItemType'] == 4) ? '<a href="#">' . $StepRows[$i]['Title'] . '</a>' : $StepRows[$i]['Title'];
    $eachAmount     = (!$StepRows[$i]['Amount']) ? '<span class="amount">' . $StepRows[$i]['Amount'] . '</span>'  : '<span class="amount">' . $roundAmount . '</span>' . $StepRows[$i]['Unit'];



    // 조리과정 리스트
    for ($j = 0; $j < count($InfoRows); $j++) {
        $weight     = ($StepRows[$i]['ItemWeightValue'] > 0) ? ' <strong class="weight"> (' . round($eachWeight[$i], 1) . '</strong>g)' : '';
        $eachAmount = (!$StepRows[$i]['Amount']) ? $StepRows[$i]['Amount']  : $roundAmount . $StepRows[$i]['Unit'];

        if (($StepRows[$i]['ItemType'] == 0) || ($StepRows[$i]['ItemType'] == 1)) {
            if ($StepRows[$i]['StepSeq'] == ($j + 1)) {
                $StepSub[$j] .= '<span>' . $title . ' ' . $eachAmount . $weight . ', </span>';
            }
        }
    }

    if ($StepRows[$i]['ItemTimeValue']) {
        $timeTit = ($StepRows[$i]['Title']) ? '<strong class="timeTit"> ' . $StepRows[$i]['Title'] . '</strong><br>' : ' <br>';
        $StepTime[$StepRows[$i]['StepSeq']] .= '<span>' . $StepRows[$i]['ItemTimeValue'] . $timeTit . '</span>';
    }

    if ($StepRows[$i]['ItemType'] == 4) {
        $GetRecipe[$StepRows[$i]['StepSeq']] .= '<span class="Import">' . $title . ' ' . $eachAmount . $weight . '</span>';
    }
}

for($i = 0; $i < count($ListRows); $i++) {
    // amount : 분수 => 소수점으로 변환
    $basicAmount[$i] = $ListRows[$i]['sum'];
    $basicWeight[$i] = $ListRows[$i]['ItemWeightValue'];
//    print_r($basicAmount);
    if (explode('/', $basicAmount[$i])) {
        $amount[$i] = explode('/', $basicAmount[$i]);
        if (count($amount[$i]) == 2) {
            $basicAmount[$i] = (float)($amount[$i][0]) / (float)($amount[$i][1]);
        } else {
            $basicAmount[$i] = (float)($amount[$i][0]);
        }
    }
    $oneServing[$i]     = (float)($basicAmount[$i]) / floatval($ListRows[0]['Servings']); //1인분 Amount 계산
    $eachServing[$i]    = ((float)($oneServing[$i]) * (float)($serving)) * (float)($ratio);
    $oneWeight[$i]      = (float)($basicWeight[$i]) / floatval($ListRows[0]['Servings']); //1인분 Weight 계산
    $eachWeight[$i]     = ((float)($basicWeight[$i]) * (float)($serving)) * (float)($ratio);
    $roundAmount        = round($eachServing[$i], 1);
    $weight             = ($ListRows[$i]['ItemWeightValue'] > 0) ? '<strong class="weight"> ' . round($eachWeight[$i], 1) . '</strong>g' : '';
    $title              = ($ListRows[$i]['ItemType'] == 4) ? '<a href="#">' . $ListRows[$i]['Title'] . '</a>' : $ListRows[$i]['Title'];
    $eachAmount         = (!$ListRows[$i]['Amount']) ? '<span class="amount">' . $ListRows[$i]['Amount'] . '</span>'  : '<span class="amount">' . $roundAmount . '</span>' . $ListRows[$i]['Unit'];

    // 재료 리스트
    for ($j = 0; $j < count($type); $j++) {
        if (($ListRows[$i]['ItemType'] == 0) || ($ListRows[$i]['ItemType'] == 1) || ($ListRows[$i]['ItemType'] == 4)) {
            if ($ListRows[$i]['ItemType'] == $j) {
                if ($listType == 'original') { //원본보기
                    $list[$j] .= '<ul class="ingredientsList">
                                        <li class="ingredientsEach">
                                            <ul class="subdivide ">
                                                <li>' . $eachAmount . '</li>
                                                <li class="iTitle">' . $title . '</li>
                                                <li>' . $weight . '</li>
                                            </ul>
                                        </li>
                                    </ul>';
                } elseIf ($listType == 'sum') { //한줄보기
                    if ($StepRows[$i]['Unit'] == 'g' && $weight) {
                        $list[$j] .= '<span>' . $ListRows[$i]['Title'] . $weight . '</span>, ';
                    } else {
                        $list[$j] .= '<span>' . $ListRows[$i]['Title'] . ' ' . $eachAmount . $weight . '</span>, ';
                    }
                }
            }
        }
    }
}

while (list($key, $value) = each($list)) {
    $itemList[$key] = "$value";
}

while (list($key, $value) = each($StepSub)) {
    $StepLine[$key] = "$value";
}

for($i = 0; $i < count($InfoRows); $i++) {
    for($j = 0; $j < count($InfoRows); $j++) {
        $nullImg    = ($InfoRows[$j]['RM_PictureRectangle'] == NULL) ? '' : '<div class="stepImg"><img src="https://s3.ap-northeast-2.amazonaws.com/cookplay-recipe/' . $InfoRows[$j]['RM_PictureRectangle'] . '"></div>';
        $addClass   = (!$StepLine[$j]) ? '' : $StepType[0];

        if ($listType == 'original') {
            $StepData .= '<div class="step' . ++$i . '">
                        <div class="stepWrap stepList1">
                            <h4>STEP ' . $i . '/' . count($InfoRows) . '</h4>
                            <div class="stepContent">
                                ' . $nullImg . '
                                <p class="desc">' . $InfoRows[$j]['RM_Description'] . '</p>
                                <div class="Detail">
                                    <p class="stepIngredient ' . $addClass . '">
                                    ' . $StepLine[$j] . '
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>';
        } elseif ($listType == 'recipeSum') {
            $StepData .= '<div class="step' . ++$i . '">
                        <div class="stepWrap stepList2">
                            <h4>' . $i . '</h4>
                            <p class="desc">' . $InfoRows[$j]['RM_Description'] . '</p>
                            <div class="Detail">
                                <p class="stepIngredient ' . $addClass . '">
                                ' . $StepLine[$j] . '
                                </p>
                            </div>
                        </div>
                    </div>';
        }
    }
}
//}

//6. 결과 처리
if($result) {
    $sendParam = array(
        'result'        => 'success',
        'msg'           => '등록이 완료되었습니다',
        'user'          => $rows,
        'CategoryName'  => $CategoryName,
        'infoList'      => $InfoRows,
        'list'          => $itemList,
        'StepData'      => $StepData,
        'Category'      => $Category,
        'StepTime'      => $StepTime,
        'GetRecipe'     => $GetRecipe,
        'ratio'         => $ratio,
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