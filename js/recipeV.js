$(document).ready(function () {
    $('.follow').attr('data-follow', '');

    followAjax();
    infoDetail();
    stepRecipe();
    infoRecipe();
}); // 페이지 열리면 실행

$('.Scale').on('click', function (e) {
    e.preventDefault();

    var num = parseInt($(".divisonNum").html());
    if($('.listBox').attr('data-ratio')) {
        var ratio = $('.listBox').attr('data-ratio');
    }
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var RecipeSerial = vars['recipeserial'];
    var formData = 'appcall://changeweight?'+RecipeSerial+'&'+num+'&'+ratio;

    location.href = formData;
}); // 저울 페이지 이동

$('.simple').on('click', function (e) {
    e.preventDefault();

    if($(this).attr('data-simple') == '1') {
        $(this).removeClass('simpleAfter').attr('data-simple', 0);
        $(this).children('a').removeClass('colorChange').text('한줄보기');
        $('.sum').hide();

        infoRecipe();
    } else if($(this).attr('data-simple') == '0') {
        $('div.simple').addClass('simpleAfter').attr('data-simple', 1);
        $('div.simple').children('a').addClass('colorChange').text('원본보기');
        $('.ingredientsList').hide();
        $('.sum').show();

        listSum();
    }
}); // 재료 리스트 한줄/원본보기 클릭

$('.resetBtn').on('click', function () {
    var serving = $('li.division').children().text();
    $('.divisonNum').text(serving);
    $('.listBox').attr('data-ratio', 1);

    if($('.simple').attr('data-simple') == '0') {
        infoRecipe();
    } else if($('.simple').attr('data-simple') == '1') {
        listSum();
    }

    if($('.recipeSimple').attr('data-simple2') == '0') {
        var data = {'listType': 'original'};

        stepRecipe(data);
    } else if($('.recipeSimple').attr('data-simple2') == '1') {
        var data = {'listType': 'recipeSum'};

        stepRecipe(data);
    }
}); // 재료 리스트 초기화

$('.recipeSimple').on('click', function (e) {
    e.preventDefault();

    if($(this).attr('data-simple2') == '1') {
        $(this).removeClass('simpleAfter').attr('data-simple2', 0);
        $(this).children('a').removeClass('colorChange').text('요약보기');
        $('.stepWrap').children().remove();
        var data = {'listType': 'original'};

        stepRecipe(data);
    } else if($(this).attr('data-simple2') == '0') {
        $(this).addClass('simpleAfter').attr('data-simple2', 1);
        $(this).children('a').addClass('colorChange').text('원본보기');
        $('.stepWrap').children().remove();
        var data = {'listType': 'recipeSum'};

        stepRecipe(data);
    }
}); // 조리과정 요약/원본보기 클릭

$('.plusBtn').on('click', function(e) {
    e.preventDefault();

    var num = parseInt($(".divisonNum").html());

    if(num < 20) {
        num++;
        $(".divisonNum").html(num);

    } else {
        location.href = 'appcall://message?20인분까지 가능합니다 :D';
        return false;
    }

    if($('.simple').attr('data-simple') == '0') {
        infoRecipe();
    } else if($('.simple').attr('data-simple') == '1') {
        listSum();
    }

    if($('.recipeSimple').attr('data-simple2') == '0') {
        var data = {'listType': 'original'};

        stepRecipe(data);
    } else if($('.recipeSimple').attr('data-simple2') == '1') {
        var data = {'listType': 'recipeSum'};

        stepRecipe(data);
    }
}); //1인분 추가

$('.minusBtn').on('click', function(e) {
    e.preventDefault();

    var num = parseInt($(".divisonNum").html());

    if(num > 1) {
        num--;
        $(".divisonNum").html(num);
    } else {
        location.href = 'appcall://message?1인분부터 가능합니다 :D';
        return false;
    }

    if($('.simple').attr('data-simple') == '0') {
        infoRecipe();
    } else if($('.simple').attr('data-simple') == '1') {
        listSum();
    }

    if($('.recipeSimple').attr('data-simple2') == '0') {
        var data = {'listType': 'original'};

        stepRecipe(data);
    } else if($('.recipeSimple').attr('data-simple2') == '1') {
        var data = {'listType': 'recipeSum'};

        stepRecipe(data);
    }
}); //1인분 감소

///////////////////////////////////////////////////////////////////////////////////////////////////////////

function listSum() {
    var num             = parseInt($(".divisonNum").html());
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var RecipeSerial = vars['recipeserial'];
    if(!ratio) {
        if($('.listBox').attr('data-ratio')) {
            var ratio = $('.listBox').attr('data-ratio');
        }
    } else {
        ratio = ratio;
    }
    var data = {'RecipeSerial': RecipeSerial, 'listType': 'sum', 'serving': num, 'ratio': ratio};

    $.ajax({
        type: "GET",
        url: "recipeVajax.php",
        dataType: "json",
        data: data,
        cache: true,
        async: false,
        success: function (data) {
            var recipeData = data.list;

            $('.ingredients .listBox').html('<p class="sum">'+recipeData[0].slice(0, -2)+'</p>');
            $('.season .listBox').html('<p class="sum">'+recipeData[1].slice(0, -2)+'</p>');
            $('.getRecipe .listBox').html('<p class="sum">'+recipeData[4].slice(0, -2)+'</p>');

            var data = ($('.recipeSimple').attr('data-simple2') == '0') ? {'listType': 'original'} : {'listType': 'recipeSum'};
            stepRecipe(data);
        },
        error: function (request,error, data) {
            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
} // listSum() : 재료 리스트 한줄/원본보기 클릭 시 실행

function stepRecipe(data) { //recipe
    var num             = parseInt($(".divisonNum").html());
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var RecipeSerial = vars['recipeserial'];
    if(!ratio) {
        if($('.listBox').attr('data-ratio')) {
            var ratio = $('.listBox').attr('data-ratio');
        }
    } else {
        ratio = ratio;
    }

    if(!data) var listType = 'original';
    else listType = data.listType;

    var data = {'RecipeSerial': RecipeSerial, 'listType': listType, 'serving': num, 'ratio': ratio};

    $.ajax({
        type: "GET",
        url: "recipeVajax.php",
        dataType: "json",
        data: data,
        cache: true,
        async: false,
        success: function (data) {
            var StepData    = data.StepData;
            var StepTime    = data.StepTime;
            var GetRecipe   = data.GetRecipe;

            $('.stepList').html(StepData);

            for(var i = 0; i < $('.stepIngredient').length; i++) {
                var slice = $('.stepWrap .stepIngredient:eq('+i+') span:last()').text();

                slice = slice.substring(0, slice.length -2);
                $('.stepWrap .stepIngredient:eq('+i+') span:last()').text(slice);
            }
            var len = $('.stepList').children('div').length;

            for(var i = 1; i <= len; i++) {
                // if(StepTime[i]) $('div.step'+i).find('p.stepIngredient').append(StepTime[i]);
                if(StepTime[i]) $('div.step'+i).find('.Detail').append('<p class="stepIngredient Timer">'+StepTime[i]+'</p>');

                if(GetRecipe) {
                    if((listType == 'recipeSum') && GetRecipe[i]) {
                        $('div.step'+i).find('p.stepIngredient span:last-child').after(', ');
                        $('div.step'+i).find('p.stepIngredient').append(GetRecipe[i]);
                        $('.Import').css({'display':'inline', 'margin':'0'});
                        $('.Import a').css({'color':'#585858', 'borderBottom':'0 none'});
                    }
                    if(listType == 'original') $('div.step'+i).find('p.stepIngredient').append(GetRecipe[i]);
                }
            }
        },
        error: function (request,error, data) {
            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
} // stepRecipe() : 조리과정 요약/원본보기 클릭 시 실행

function infoDetail() { //top
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var RecipeSerial = vars['recipeserial'];
    var data            = {'RecipeSerial': RecipeSerial, 'listType': 'original'};

    $.ajax({
        type: "GET",
        url: "recipeVajax.php",
        dataType: "json",
        data: data,
        cache: true,
        async: false,
        success: function (data) {
            var user        = data.user;
            var infoList    = data.infoList;
            var category    = data.Category;
            var img = "https://s3.ap-northeast-2.amazonaws.com/cookplay-recipe/"+infoList[0]['Picture'];

            if(data.CategoryName) {
                var categoryName = data.CategoryName;

                $.each(categoryName, function (key, value) {
                    // console.log(key +':'+ value);
                    $('.recipeTags').append('<li class="tag"><a href="#">#'+value+'</a></li>');
                })
            }

            if(!infoList[0]['UserPic']) {
                $('.profilePic').append('<img src="./images/noneProfile@2x.png">');
            }else {
                var userImg = '<img src="https://s3.ap-northeast-2.amazonaws.com/cookplay-users/'+user[0]['UserPic']+'">';
                $('.profilePic').css({
                    'background':'url("./images/icBorderRound@2x.png") center center / 62px no-repeat'
                }).append(userImg);
            }

            if(user) $('.nickName').html(user[0]['Nickname']);
            $('.viewTit').css({
                'backgroundImage':'url('+img+')',
                'backgroundPosition':'center center',
                'backgroundSize':'cover',
                'backgroundRepeat':'no-repeat',
                'backgroundColor':'rgb(0, 0, 0)',
            });
            $('.recipeTit').html(infoList[0]['Title']);
            $('.caption').html(infoList[0]['Description']);
            // console.log(infoList[0]['Servings']);
            (infoList[0]['MakingTime']) ? $('.timer span').html(category['Time'][infoList[0]['MakingTime']]) : $('.recipeMethod .timer').hide();
            (infoList[0]['Servings']) ? $('.division span, .divisonNum').html(infoList[0]['Servings']) : $('.recipeMethod .division').hide();
            (infoList[0]['MakingLevel']) ? $('.level span').html(category['Level'][infoList[0]['MakingLevel']]) : $('.recipeMethod .level').hide();
        },
        error: function (request,error, data) {
            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
} // infoDetail() : 상단 레시피 관련 정보 및 html

function infoRecipe(Person, ratio) { //list
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var RecipeSerial = vars['recipeserial'];

    if(!Person) {
        var num = parseInt($(".divisonNum").html());
    } else {
        num = Person;
    }

    if(!ratio) {
        if($('.listBox').attr('data-ratio')) {
            var ratio = $('.listBox').attr('data-ratio');
        }
    } else {
        ratio = ratio;
    }

    var data = {'RecipeSerial': RecipeSerial, 'listType': 'original', 'serving': num, 'ratio': ratio};

    $.ajax({
        type: "GET",
        url: "recipeVajax.php",
        dataType: "json",
        data: data,
        cache: true,
        async: false,
        success: function (data) {
            var recipeData  = data.list;
            var ratio       = data.ratio;

            if(ratio != 1) conRatio(ratio);

            if(recipeData[0]) {
                $('.ingredients .listBox').html(recipeData[0]);
            } else {
                $('.ingredients').hide();
            }
            if(recipeData[1]) {
                $('.season .listBox').html(recipeData[1]);
            } else {
                $('.season').hide();
            }
            if(recipeData[4]) {
                $('.getRecipe .listBox').html(recipeData[4]);
            } else {
                $('.getRecipe').hide();
            }

            // if($('.simple').attr('data-simple') == 1) listSum();

            var listData = ($('.recipeSimple').attr('data-simple2') == '0') ? {'listType': 'original'} : {'listType': 'recipeSum'};
            stepRecipe(listData);
        },
        error: function (request,error, data) {
            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
} // infoRecipe() : 재료 리스트 관련 정보 및 html

function op(data) {
    $.ajax({
        type: "GET",
        url: "recipeVajax.php",
        dataType: "json",
        data: data,
        cache: true,
        async: false,
        success: function (data) {
            var recipeData  = data.list;
            var StepData    = data.StepData;

            $('.ingredients .listBox').html(recipeData[0]);
            if(recipeData[1]) {
                $('.season .listBox').html(recipeData[1]);
            } else {
                $('.season').hide();
            }
            if(recipeData[4]) {
                $('.getRecipe .listBox').html(recipeData[4]);
            } else {
                $('.getRecipe').hide();
            }

            $('.stepList').html(StepData);
        },
        error: function (request,error, data) {
            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
} // op() : plus, minus btn 계산

function conRatio(data) {
    var ratio = data;

    $('.listBox').attr('data-ratio', ratio);
} // conRatio() : 무게 비율 변수 저장

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$('.follow').on('click', function (e) {
    e.preventDefault();

    var followCk = $(this).attr('data-follow');
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var loginUser = vars['userserial'];
    if(!loginUser) {
        // alert('로그인 후 이용가능한 서비스입니다 :D');
        location.href = 'appcall://message?로그인 후 이용가능한 서비스입니다 :D';
        return false;
    }

    if(followCk == '0' || !followCk) {
        $(this).css('backgroundImage', 'url("./images/icFollower@2x.png")').attr('data-follow', '1');
        $(this).html('<span style="color:#FF7500">팔로잉</span>');
    } else if(followCk == '1') {
        $(this).css('backgroundImage', 'url("./images/icFollowing@2x.png")').attr('data-follow', '0');
        $(this).html('<a href="#"><span style="color: #FFF">팔로우</span></a>');
    }

    followAjax();
}); // 팔로우 클릭 시 실행

function followAjax() {
    var followCk = $('.follow').attr('data-follow');
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var RecipeSerial    = vars['recipeserial'];
    var loginUser = vars['userserial'];
    if(!loginUser) {
        $('.follow').hide();
        return false;
    }

    var data = {'RecipeSerial' : RecipeSerial, 'loginUser' : loginUser, 'followClick' : followCk};

    $.ajax({
        type: "GET",
        url: "followAccess.php",
        dataType: "json",
        data: data,
        cache: true,
        async: false,
        success: function (data) {
            if(data.followState) var followState = data.followState;
            var followResult = data.result;

            if(followResult == 'userCk') {
                $('.follow').css('background', 'none');
                $('.follow').empty();
                $('.follow').off();
            }

            if((followState == '1') && (followResult == 'loading')) { // loading 초기값
                $('.follow').css('backgroundImage', 'url("./images/icFollowing@2x.png")').attr('data-follow', followState);
                $('.follow').find('span').css('color', '#FF7500').text('팔로잉');
            }
        },
        error: function (request,error, data) {
            // alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            // location.href = 'appcall://message?팔로우 대상자를 찾을 수 없습니다';
            $('.follow').css('backgroundImage', 'url("./images/icFollow@2x.png")').attr('data-follow', '0');
            $('.follow').html('<a href="#"><span style="color: #FFF">팔로우</span></a>');
        }
    });
} // 팔로우 처리 함수

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



function snsPost() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    var RecipeSerial = vars['recipeserial'];

    var url = $('meta[property="og:url]').context['baseURI'];
    var chgUrl = url+RecipeSerial;

    console.log($('meta[property="og:url]'));
}