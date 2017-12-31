<style>
    header#header { width: 100%; height: 55px; background: #FF7500; position: relative; }
    #gnb { width: 55px; height: 100%; float: left; overflow: hidden; } /* background: #FF7500 url("../images/icMenu@2x.png") no-repeat 24px; background-size: 24px;  */
    #menu_icon {
        position: relative;
        width: 24px;
        height: 24px;
        transform: rotate(0deg);
        transition: 0.5s ease-in-out;
        cursor: pointer;
        top: 28%;
        margin: 0 auto;
        z-index: 100;
    }
    #menu_icon span {
        display: block;
        position: absolute;
        width: 100%;
        height: 2px;
        background-color: #FFF;
        opacity: 1;
        left: 0;
        transform: rotate(0deg);
        transition: 0.25s ease-in-out;
    }
    #menu_icon span:nth-child(1) { top: 2px; }
    #menu_icon span:nth-child(2) { top: 42%; }
    #menu_icon span:nth-child(3) { bottom: 4px; }
    #menu_icon.open span:nth-child(1) { top: 15px; transform: rotate(-45deg); background-color: #585858; }
    #menu_icon.open span:nth-child(2) { opacity: 0; }
    #menu_icon.open span:nth-child(3) { top: 15px; transform: rotate(-135deg); background-color: #585858; }
    #gnb ul { width: 70%; height: 100vh; background-color: #FFF; margin: 0; padding: 0; top: 0; left: 0; position: absolute; z-index: 50; left: -70%; }
    #gnb ul li {
        list-style: none;
        line-height: 55px;
        font-size: 14px;
        padding-left: 7%;
        border-bottom: 1px solid #DDD;
    }
    #gnb ul li:first-child { padding-top: 55px; }
    #gnb ul li a { color: #585858; }
</style>

<header id="header">
    <nav id="gnb">
        <div id="menu_icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul>
            <li><a href="./login.php">로그인/회원가입</a></li>
            <li><a href="./recipe_view.php?recipeserial=221">레시피</a></li>
            <li><a href="./story_view.php?storyserial=8">스토리</a></li>
        </ul>
    </nav>
</header>

<script>
    $(document).ready(function () {
        $('#menu_icon').on('click', function () {
            $(this).toggleClass('open');
        });
    });
    $(function () {
        var now = false;

        $('#gnb').on('click', '#menu_icon', function () {
            if(now === false) {
                $('#gnb ul').animate({'left': '0'}, 700, 'linear');
                $('#gnb ul').css({'display': 'block', 'boxShadow': '0px 0px 28px 0px lightgrey'});
                now = true;
            }else {
                $('#gnb ul').animate({'left': '-70%'}, 700, 'linear');
                $('#gnb ul').css({'boxShadow': '0px 0px 0px 0px'});
                now = false;
            }
        });
    });
</script>