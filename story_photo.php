<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>story photo</title>

    <!-- VIEWPORT -->
    <meta name="viewport" content="user-scalable=yes, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width, height=device-height, target-densitydpi=medium-dpi">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <link rel="stylesheet" href="css/swiper.min.css">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/base.css">
    <style>
        body { background-color: #000; }
        .storyPhoto.container { background-color: #000; }
        .photoWrap { position: relative; }
        .backIcon { width: 24px; height: 24px; background: url("images/icBack@2x.png") no-repeat; background-size: 24px; position: absolute; top: 30px; left: 10px; z-index: 50; }
        .storyPhoto .pageNum { position: absolute; left: 50%; font-size: 14px; }
        .storyPhoto .imgOpacity { width: 50px; height: 20px; line-height: 20px; border-radius: 10px; background-color: rgba( 0, 0, 0, 0.5 ); color: #FFF; text-align: center; letter-spacing: -2px; transform: translateX(-50%); }
        .storyPhoto .storyImg { width: 100% !important; -ms-touch-action: double-tap-zoom; -ms-touch-action: manipulation; -ms-touch-action: pinch-zoom; }
        .storyPhoto .storyImg img { width: 100%; }
    </style>
</head>
<body>
<div class="storyPhoto container">
    <div class="content" data-role="page">
        <div class="wrapper">
            <div class="storyPhotoWrap">
                <div class="photoWrap">
                    <div class="backIcon"></div>
                    <div class="storyImgWrap swiper-container">
                        <div class="storyImgBox swiper-wrapper"></div>
                        <div class="pageNum">
                            <div class="swiper-pagination imgOpacity"></div>
                        </div>
                    </div>
                </div>
            </div> <!-- storyPhotoWrap END -->
        </div> <!-- wrapper END -->
    </div> <!-- content END -->
</div> <!-- container END -->

<script src="js/sub.js"></script>
<script src="js/storyV.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.0.7/js/swiper.min.js"></script>
<script>
    $(document).ready(function () {
        var param = $(location).attr('search').slice($(location).attr('search').indexOf('=') + 1);
        var data = {'serial': param, 'type': 'photo'};

        slide(data);
    });
</script>
</body>
</html>