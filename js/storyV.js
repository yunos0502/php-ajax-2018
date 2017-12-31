function slide(data) {
    var data = data;

    $.ajax({
        type: 'GET',
        url: './storyAjax.php',
        dataType: 'json',
        data: data,
        success: function (data) {
            var user            = data.user;
            var pic             = (!data.pic) ? '<img src="./images/noneProfile.png">' : '<img src="https://s3.ap-northeast-2.amazonaws.com/cookplay-users/' + data.pic + '">';
            var desc            = data.desc;
            var type            = data.type;
            var StoryCategory   = data.StoryCategory;
            var Category        = {0: '일상/스토리', 1: '맛집소개', 2: '요리정보'};
            var swiper;

            $('.userNic').html(user);
            $('.userPic').html(pic);
            $('.storyImgBox').prepend(data.storyPic);
            $('.storyTag').html('#'+Category[StoryCategory]);
            $('.storyDesc').html(desc);

            if(type == 'view') {
                swiper = new Swiper('.swiper-container', {
                    pagination: {
                        el: '.swiper-pagination',
                        type: 'fraction',
                    },
                });
            }else {
                swiper = new Swiper('.swiper-container', {
                    height: $(window).height(),
                    spaceBetween: 0,
                    slidesPerView: 'auto',
                    simulateTouch: true,
                    touchMoveStopPropagation: true,
                    touchReleaseOnEdges: true,
                    preventClicks: true,
                    slideToClickedSlide: true,
                    pagination: {
                        el: '.swiper-pagination',
                        type: 'fraction',
                    },
                    zoom: {
                        maxRatio: 3,
                        minRatio: 1,
                        toggle: true,
                    },
                    autoHeight: true,
                    followFinger: false,
                    mousewheel: {
                        invert: true,
                        releaseOnEdges: true,
                    },
                    keyboard: true,
                    hashNavigation: true,
                });

                $('.swiper-wrapper').css('height', 'auto');
                $('.storyImg').css({
                    'width' : $(window).width(),
                    'height': $(window).height(),
                });

                $('.swiper-zoom-container').on("touchmove", function (e) {
                    if (e.originalEvent.touches.length === 1) {
                        e.preventDefault();

                        var scale = swiper.zoom.scale;
                        if (scale > 1) {
                            swiper.allowTouchMove = false;
                            $('.backIcon').hide();
                        } else {
                            swiper.allowTouchMove = true;
                            $('.backIcon').show();
                        }
                    }
                }).on("touchstart", function (e) {
                    // e.preventDefault();
                }).on("touchend", function (e) {
                    e.preventDefault();
                });

                $(window).on("orientationchange", function(event){
                    // 세로->가로 모드
                    if(window.matchMedia("(orientation: portrait)").matches){
                        location.reload();
                        // 가로->세로 모드
                    }else if(window.matchMedia("(orientation: landscape)").matches){
                        location.reload();
                    }
                });
            }
        },
        error: function (request,error, data) {
            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

$(function () {
    $('.backIcon').on('click', function () {
        window.close();
    });
});