<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>recipe view</title>

    <!-- VIEWPORT -->
    <meta name="viewport"             content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
    <meta property="og:url"           content="" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="" />
    <meta property="og:description"   content="" />
    <meta property="og:image"         content="https://s3.ap-northeast-2.amazonaws.com/cookplay-recipe/1507827020_20171012_165708_539.png" />

    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body>
<?php include 'inc/gnb.php' ?>
<div class="recipeView container">
    <div class="content" data-role="page">
        <div class="wrapper">
            <div class="recipeViewWrap">
                <section class="viewTop">
                    <div class="viewTit">
                        <div class="backColor"></div>
                        <div class="profilePic"></div>
                        <p class="nickName"></p>
                        <p class="recipeTit"></p>
                        <div class="follow" data-follow=""><a href="#"><span style="color: #FFF">팔로우</span></a></div>
                    </div>
                    <div class="viewTxt">
                        <p class="caption"></p>
                        <ul class="recipeTags"></ul>
                        <ul class="recipeMethod">
                            <li class="timer"><span></span></li>
                            <li class="division"><span></span>인분</li>
                            <li class="level"><span></span></li>
                        </ul>
                    </div>
                </section>
                <section class="recipeIngredients">
                    <div class="sectionTop">
                        <h2>재료(Ingredients)</h2>
                        <div class="Scale"><a href="#"><span class="hid">저울</span></a></div>
                    </div>
                    <div class="recipeContent">
                        <div class="recipeDivision">
                            <p><span class="divisonNum"></span><span>인분</span></p>
                            <div class="cal">
                                <a href="#" class="minusBtn"><span class="hid">minus</span></a>
                                <a href="#" class="plusBtn"><span class="hid">plus</span></a>
                            </div>
                        </div>
                        <div class="ingredients">
                            <h4>재료</h4>
                            <div class="simple" data-simple="0"><a href="#">한줄보기</a></div>
                            <div class="listBox" data-ratio=1></div>
                        </div>
                        <div class="season">
                            <h4>양념</h4>
                            <div class="listBox" data-ratio=1></div>
                        </div>
                        <div class="getRecipe">
                            <h4>가져온 레시피</h4>
                            <div class="listBox" data-ratio=1></div>
                        </div>
                    </div>
                    <button class="resetBtn" name="resetBtn" type="button"><span class="hid">reset</span></button>
                </section> <!-- recipeIngredients END -->
                <section class="process">
                    <div class="sectionTop">
                        <h2>조리과정(Recipe)</h2>
                        <div class="recipeSimple" data-simple2="0"><a href="#">요약보기</a></div>
                    </div>
                    <div class="stepList"></div>
                </section> <!-- process END -->
            </div> <!-- recipeViewWrap END -->
        </div> <!-- wrapper END -->
    </div> <!-- content END -->
</div> <!-- container END -->
<script src="js/recipeV.js"></script>
<script>
    function RecipePlay() {
        var num = parseInt($(".divisonNum").html());    //인분
        if($('.listBox').attr('data-ratio')) {  //비율
            var ratio = $('.listBox').attr('data-ratio');
        }
        var formData = 'appcall://recipe_play?'+num+'&'+ratio;

        location.href = formData;
    } //play 화면으로 parameter 전송


    $(document).ready(function () {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        var RecipeSerial = vars['recipeserial'];
        var snsUrl = 'http://test.cookplay.net/test/recipe_view.php?recipeserial=' + RecipeSerial;
        var snsTitle = $('.recipeTit').text();
        var snsDesc = $('.caption').text();
        var snsImg = $('.viewTit').css('background-image').split('"');

        $('meta[property="og:url"]').attr('content', snsUrl);
        $('meta[property="og:title"]').attr('content', snsTitle);
        $('meta[property="og:description"]').attr('content', snsDesc);
        $('meta[property="og:image"]').attr('content', snsImg[1]);

        console.log(document.getElementsByTagName("META")[2].content);
        console.log(document.getElementsByTagName("META")[4].content);
        console.log(document.getElementsByTagName("META")[5].content);
        console.log(document.getElementsByTagName("META")[6].content);

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    });
</script>
</body>
</html>