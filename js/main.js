// window 시작 시 실행
window.onload = function () {
    $('.newList').addClass('selectBtn'); // 정렬 스타일 초기값

    recipeList();

    $(window).scroll(function() {
        var height = $(document).scrollTop();

        if(height == 0) {
            location.reload();
        }
    }); // Web Pull To Refresh
} //윈도우 로딩 시 totalQuantity() 호출

function recipeList() {
    recipeajax(); // 페이지 로딩

    $('.recipeWrap').on('click', '.favoriteImg', function (e) {
        e.preventDefault();
        var listNum = $(this).children().attr('data-listNum');
        var countData = $(this).children().attr('data-count');
        insertajax(listNum, countData);
    }); // 클릭 시 하트 증가

    $('.recipeBtn li').on('click', function(e) {
        e.preventDefault();
        var range = $(this).data('list');
        $(this).parent().children().removeClass('selectBtn');
        $(this).addClass('selectBtn');

        recipeajax(range);
    }); // 레시피 정렬 순

    function insertajax(num, count) {
        // var listNum = num;
        var countData = parseInt(count)+1;
        var data = {'RecipeSerial' : num};

        $.ajax({
            type: "GET",
            url: "insertajax.php",
            dataType: "json",
            data: data, //임시 값
            success: function (data) {
                alert(data.msg);
                // console.log(data.count);
                // recipeajax();
                // console.log(parseInt(clickData)+1);
                console.log(countData); //나중에 수정
            },
            error: function(request,error, data){
                alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });
    } //insertajax()

    function recipeajax(range) {
        var data = {};
        if(range) {
            data = {'range':range}
        }

        $.ajax({
            type: "GET",
            url: "recipeAjax.php",
            dataType: "json",
            data: data,
            cache: true,
            async: false,
            success: function (data) {
                var img     = data.img;
                var range   = data.range;

                $('.recipeWrap').html(img);

                if(range == 'best') {
                    $('.recipeBtn li').eq(1).addClass('selectBtn');
                }

                // console.log('range: '+range);

                // 무한 스크롤
                $(window).scroll(function() {
                    var is_scrollable = ($(document).height() - $(window).height()) <= ($(window).scrollTop());

                    if (is_scrollable) {
                        // console.log($(document).height() - $(window).height());
                        $('.recipeWrap').html(img);
                    }
                })
            },
            // compleate: function () {
            //     window.location.reload();
            // },
            error: function () {
                alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });
    } //recipeajax()
}