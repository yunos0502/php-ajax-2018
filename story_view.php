<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>story view</title>

    <!-- VIEWPORT -->
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, height=device-height">
    <meta name="HandheldFriendly" content="true">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="css/swiper.min.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include 'inc/gnb.php' ?>
<div class="storyView container">
    <div class="content" data-role="page">
        <div class="wrapper">
            <div class="storyViewWrap">
                <header>
                    <h2 class="hid">info</h2>
                    <div class="userPic"></div>
                    <span class="userNic"></span>
                </header>
                <div class="storyWrap">
                    <div class="storyContent">
                        <div class="storyImgWrap swiper-container">
                            <div class="storyImgBox swiper-wrapper"></div>
                            <div class="swiper-pagination imgOpacity"></div>
                        </div>
                        <div class="storyTxt">
                            <p class="storyTag">#일상/스토리</p>
                            <p class="storyDesc"></p>
                        </div>
                    </div>
                </div>
            </div> <!-- storyViewWrap END -->
        </div> <!-- wrapper END -->
    </div> <!-- content END -->
</div> <!-- container END -->
<script src="js/storyV.js"></script>
<script src="js/swiper.min.js"></script>
<script>
    $(document).ready(function () {
        $('.userPic').css('width', $('.storyView header').height());
        $('.userNic').css('lineHeight', $('.storyView header').height() + 'px');
        var param = $(location).attr('search').slice($(location).attr('search').indexOf('=') + 1);
        var data = {'serial': param, 'type': 'view'};

        slide(data);

        $('.storyImgBox').on('click', '.storyImg', function () {
            var num = parseInt($(this).index()) + 1;
            window.open("story_photo.php?StorySerial="+param+"#slide"+num);
        });
    });
</script>
</body>
</html>