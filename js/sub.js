$(function () {
    $('#autologin').on('click', function () {
        if($(this).attr('value') == '' || $(this).attr('value') == '0') {
            $(this).attr('value', '1');
            $('#autologin').attr('checked', true);
        } else {
            $(this).attr('value', '0');
            $('#autologin').attr('checked', false);
        }
    }); // 로그인 상태 유지 변경

    $('#loginEmail').on({
        'change': function (e) {
            e.preventDefault();

            $(this).siblings().remove();
            $(this).parent().removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');
            var emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            var loginEmail = document.getElementById('loginEmail');
            $(this).after('<p></p>');

            if(!loginEmail.value.match(emailFormat)) {
//                if(email.value) email.value = '';
                $('#loginEmail').siblings().addClass('infoTxt').html('이메일을 정확히 입력해주세요');
                $('#loginEmail').parent().addClass('lineAdd');
                loginEmail.focus();
                return false;
            } //이메일 형식 체크

            $('#loginEmail').css({'color': '#303030'});
            $('#loginEmail').attr('data-validate', 1);
        }, //이메일 체크
    });

    $('#loginPwd').on({
        'change': function (e) {
            e.preventDefault();

            $(this).siblings().remove();
            $(this).parent().removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');
            $(this).after('<p></p>');

            if(!loginPwd.value || (loginPwd.value.length < 6 || loginPwd.value.length > 20)) {
                $('#loginPwd').siblings().addClass('infoTxt').html('비밀번호를 6~20자 사이로 입력해주세요');
                $('#loginPwd').parent().addClass('lineAdd');
                loginPwd.focus();
                return false;
            }

            $('#loginPwd').css({'color': '#303030'});
        },
    }); //비밀번호 체크

    $('#loginSubmit').on('click', function(e) {
        e.preventDefault();

        $('.inputGroup li').removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');
        $('.inputGroup li').after('<p></p>');

        var emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var loginEmail = document.getElementById('loginEmail');
        var loginPwd = document.getElementById('loginPwd');
        var autologin = document.getElementById('autologin');

        if(!loginEmail.value.match(emailFormat) || !loginEmail.value) {

            $('.inputGroup li p').remove();
            $('.inputGroup li #loginEmail').after('<p></p>');
            $('#loginEmail').siblings().addClass('infoTxt').html('이메일을 정확히 입력해주세요');
            loginEmail.focus();
            $('#loginEmail').parent().addClass('lineAdd');
            return false;
        }

        if(!loginPwd.value || (loginPwd.value.length < 6 || loginPwd.value.length > 20)) {
            // $('.inputGroup li').children().removeClass('lineAdd');
            $('.inputGroup li p').remove();
            $('.inputGroup li #loginPwd').after('<p></p>');
            $('#loginPwd').siblings().addClass('infoTxt').html('비밀번호를 6~20자 사이로 입력해주세요');
            loginPwd.focus();
            $('#loginPwd').parent().addClass('lineAdd');
            return false;
        }

        var formData = 'appcall://login?'+loginEmail.value+'&'+loginPwd.value+'&'+autologin.value;

        location.href = formData;
    }); // login page(로그인)
}); // 로그인

$(function(){
    $('#allAgree').on('click', function () {
        if(!$(this).attr('checked')) $(this).attr('checked', true);
        else $(this).attr('checked', false);
    }) // 전체 동의 변경

    $('#marketingAgree').on('click', function () {
        if(!$(this).attr('checked')) $(this).attr({
            checked: true,
            value: 1
        });
        else $(this).attr({
            checked: false,
            value: 0
        });
    }) // 마케팅 동의 변경

    $('#nickname').on({
        'keyup': function (e) {
            e.preventDefault();
            var preNick;
            var nick = $(this).val();

            if(nick === preNick) {
                $(this).css({'color': '#303030'});
            }else {
                $(this).css({'color': '#BDBDBD'});
                $(this).attr('data-validate', 0);
                preNick = nick;
            }
        },
        'change': function (e) {
            e.preventDefault();
            $(this).siblings().remove();
            $(this).parent().removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');

            var nickname = document.getElementById('nickname');
            var data = {'nickname' : nickname.value};

            $(this).after('<p></p>');

            if(!nickname.value || nickname.value.length > 10) {
                $(this).siblings().addClass('infoTxt').html('닉네임을 10자 이내로 입력해주세요');
                $(this).parent().addClass('lineAdd');
                nickname.focus();
            } else {
                $.ajax({
                    type: "POST",
                    url: "nickCk.php",
                    cache: false,
                    async:false,
                    dataType: "json",
                    data: data,
                    success: function (data) {
                        if (data.result == 'success') {
                            $('#nickname').css({'color': '#303030'});
                            $('#nickname').attr('data-validate', 1);
                            $('#nickname').siblings().remove();
                            email.focus();
                        } else {
                            $("#nickname").siblings().addClass('infoTxt').html(data.msg);
                            $('#nickname').parent().addClass('lineAdd');
                            nickname.focus();
                        }
                    },
                    error: function (request, error, data) {
                        alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                });
            }
        }
    }); // 닉네임 중복 확인

    $('#email').on({
        'keyup': function (e) {
            e.preventDefault();
            var preEmail;
            var email = $(this).val();

            if(email === preEmail) {
                $(this).css({'color': '#303030'});
            }else {
                $(this).css({'color': '#BDBDBD'});
                $(this).attr('data-validate', 0);
                preEmail = email;
            }
        },
        'change': function (e) {
            e.preventDefault();
            $(this).siblings().remove();
            $(this).parent().removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');

            var emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            var email = document.getElementById('email');
            var emailOk;

            $(this).after('<p></p>');

            if(!email.value.match(emailFormat)) {
                // if(email.value) email.value = '';
                $(this).siblings().addClass('infoTxt').html('다시 한번 체크해주세요 :D');
                $(this).parent().addClass('lineAdd');
                email.focus();
                return false;
            } //이메일 형식 체크

            $.ajax({
                type: "POST",
                url: "emailCk.php",
                cache: false,
                async:false,
                dataType: "json",
                data: {'email' : email.value},
                success: function (data) {
                    if (data.result == 'success') {
                        $("#email").siblings().addClass('infoTxt').html(data.msg);
                        $('#email').css({'color': '#303030'});
                        $('#email').attr('data-validate', 1);
                        $('#email').siblings().remove();
                        emailOk = email.value;
                        pwd.focus();
                    } else {
                        $("#email").siblings().addClass('infoTxt').html(data.msg);
                        $('#email').parent().addClass('lineAdd');
                        email.focus();
                    }
                },
                error: function (request, error, data) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            });
        }
    }); //이메일 중복 확인

    $('#pwd').on({
        'keyup': function (e) {
            e.preventDefault();
            var prePwd;
            var pwd = $(this).val();

            if(pwd === prePwd) {
                $(this).css({'color': '#303030'});
            }else {
                $(this).css({'color': '#BDBDBD'});
                prePwd = pwd;
            }
        },
        'change': function (e) {
            e.preventDefault();
            $(this).siblings().remove();
            $(this).parent().removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');
            $(this).after('<p></p>');

            if(!pwd.value || (pwd.value.length < 6 || pwd.value.length > 20)) {
                $(this).siblings().addClass('infoTxt').html('비밀번호를 6~20자 사이로 입력해주세요.');
                $(this).parent().addClass('lineAdd');
                pwd.focus();
                return false;
            }

            $(this).css({'color': '#303030'});
            pwdCheck.focus();
        }
    }); //비밀번호 체크

    $('#pwdCheck').on('change', function (e) {
        e.preventDefault();
        $(this).siblings().remove();
        $(this).parent().removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');
        $(this).after('<p></p>');

        if(pwd.value != pwdCheck.value) {
            if(pwdCheck.value) pwdCheck.value = '';
            $(this).siblings().addClass('infoTxt').html('입력하신 비밀번호가 다릅니다.');
            $(this).parent().addClass('lineAdd');
            pwdCheck.focus();
            return false;
        }

        $(this).css({'color': '#303030'});
        birth.focus();
    }); //비밀번호확인 체크

    $('#birth').on({
        'keyup': function (e) {
            e.preventDefault();
            var preBirth;
            var birth = $(this).val();

            if(birth === preBirth) {
                $(this).css({'color': '#303030'});
            }else {
                $(this).css({'color': '#BDBDBD'});
                preBirth = birth;
            }
        },
        'change': function (e) {
            e.preventDefault();
            $(this).siblings().remove();
            $(this).removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');
            var birth = document.getElementById('birth');
            $(this).after('<p class="infoTxt"></p>');

            var TRIM_PATTERN = /(^\s*)|(\s*$)/g;
            var trimbirth = birth.value.replace(TRIM_PATTERN, "");

            if(birth.value.length < 8) {
                birth.focus();
                $(this).addClass('lineAdd');
                birth.nextElementSibling.innerHTML = '다시 한번 체크해주세요 :D';
            }
            if(! /^(\d+)$/.test(trimbirth) || trimbirth.length != 8 || trimbirth.search(/^[0-9]*$/g) == -1) {
                birth.focus();
                $(this).addClass('lineAdd');
                birth.nextElementSibling.innerHTML = '다시 한번 체크해주세요 :D';
                return false;
            } else {
                var year = Number(trimbirth.substring(0, 4));
                var month = Number(trimbirth.substring(4, 6));
                var day = Number(trimbirth.substring(6, 8));
                var nowYear = new Date().getFullYear();
                var maxDaysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                var maxDay = maxDaysInMonth[month - 1];

                if (month < 1 || month > 12 || year < 1900 || year > nowYear) {
                    birth.focus();
                    $(this).addClass('lineAdd');
                    birth.nextElementSibling.innerHTML = '다시 한번 체크해주세요 :D';
                    return false;
                }
                if (month == 2 && ( year % 4 == 0 && year % 100 != 0 || year % 400 == 0 )) maxDay = 29; // 윤년 체크
                if (day <= 0 || day > maxDay) {
                    birth.focus();
                    $(this).addClass('lineAdd');
                    birth.nextElementSibling.innerHTML = '다시 한번 체크해주세요 :D';
                    return false;
                }
                $('#birth').css({'color': '#303030'});
                allAgree.focus();
            }
        }
    }); //생년월일 체크

    $('#signUpSubmit').on({
        'click': function() {
            // e.preventDefault();
            // $('.inputGroup li').removeClass('lineAdd').css('border-bottom', '1px solid #D8D8D8');

            var nickname = document.getElementById('nickname');
            var email = document.getElementById('email');
            var pwd = document.getElementById('pwd');
            var pwdCheck = document.getElementById('pwdCheck');
            var birth = document.getElementById('birth');
            var gender = $(":input:radio[name=gender]:checked").val();
            var allAgree = document.getElementById('allAgree');
            var marketingAgree = document.getElementById('marketingAgree');
            var AgreeMarketing = parseInt(marketingAgree.value);

            if($('#nickname').attr('data-validate') == 0) {
                if($('#nickname').val()) {
                    $('#nickname').siblings('p').remove();
                    $('#nickname').after('<p class="infoTxt">다시 한번 체크해주세요 :D</p>');
                    nickname.focus();
                    $('#nickname').parent().addClass('lineAdd');
                    return false;
                }
            } //아이디/이메일 인증 확인

            if($('#email').attr('data-validate') == 0) {
                if($('#email').val()) {
                    $('#email').siblings('p').remove();
                    $('#email').after('<p class="infoTxt">다시 한번 체크해주세요 :D</p>');
                    email.focus();
                    $('#email').parent().addClass('lineAdd');
                    return false;
                }
            } //아이디/이메일 인증 확인

            var user = [nickname, email, pwd, pwdCheck, birth];

            for(var i = 0; i < user.length; i++) {
                if(!user[i].value) {
                    // $(user[i]).siblings('p').remove();
                    // $(user[i]).after('<p class="infoTxt">다시 한번 체크해주세요 :D</p>');
                    // $(user[i]).parent().addClass('lineAdd');
                    $(user[i]).trigger('change');
                    user[i].focus();
                    return false;
                }
            } // 빈값 체크

            if(!allAgree.checked) {
                alert('이용약관 및 개인정보 취급방침에\n모두 동의하셔야 회원가입이 가능합니다');
                // location.href = 'appcall://message?이용약관 및 개인정보 취급방침에\n모두 동의하셔야 회원가입이 가능합니다';
                return false;
            } // 동의 체크

            var data = {'nickname' : nickname.value, 'email' : email.value, 'pwd' : pwd.value, 'birth' : birth.value, 'gender' : gender, 'AgreeMarketing' : AgreeMarketing, 'sign_key' : 'sign'};

            $.ajax({
                type: "POST",
                url: "joinCk.php",
                cache: false,
                async:false,
                dataType: "json",
                data: data,
                success: function (data) {
                    location.replace('login_confirm.html?email='+email.value);
                    // location.replace('appcall://newpage?login_confirm.html&'+email.value);
                },
                error: function(request, error, data){
                    alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            });
        }
    });
}); //sign up page(회원가입)

$(function () {
    $('#forgot_email').on('change blur', function () {
        $('#forgot_email').removeClass('lineAdd');
        $('#forgot_email').css({'color': '#303030'});
    });

    $('#pwdSubmit').on('click', function (e) {
        e.preventDefault();

        $('#forgot_email').removeClass('lineAdd');
        var emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        var forgot_email = document.getElementById('forgot_email');
        var data = {'email' : forgot_email.value, 'sign_key' : 'forgot'};

        if(!forgot_email.value || !forgot_email.value.match(emailFormat)) {
            if(forgot_email.value) forgot_email.value = '';
            $('#forgot_email').addClass('lineAdd').focus().attr('placeholder', '이메일을 다시 확인해주세요');
            // location.href = 'appcall://message?이메일 주소가 정확하지 않습니다';
            return false;
        } //이메일 형식 체크

        $.ajax({
            type: "POST",
            url: "joinCk.php",
            cache: false,
            async:false,
            dataType: "json",
            data: data,
            success: function (data) {
                if(data.result == 'not_found') {
                    alert(data.msg);
                    $('#forgot_email').addClass('lineAdd');
                    $('#forgot_email').css({'color': '#D8D8D8'});
                    // location.href = 'appcall://message?'+data.msg;
                    return false;
                }else {
                    // console.log(email.value);
                    alert(data.msg);
                    $('#forgot_email').css({'color': '#303030'});
                    // location.href = 'appcall://message?'+data.msg;
                    // return false;
                    location.replace('appcall://close_register?'+forgot_email.value);
                }
            },
            error: function(request, error, data){
                alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });
    });
}); //비밀번호 찾기