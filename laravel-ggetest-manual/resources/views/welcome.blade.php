<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>gt-php-sdk-demo</title>
    <style>
        body {
            margin: 50px 0;
            text-align: center;
        }
        .geetest_holder.geetest_wind {
            margin: 0 auto;
        }
        .inp {
            border: 1px solid gray;
            padding: 0 10px;
            width: 200px;
            height: 30px;
            font-size: 18px;
        }
        .btn {
            /* border: 1px solid gray; */
            width: 100px;
            height: 30px;
            font-size: 16px;
            cursor: pointer;
            /* width: 100%; */
            margin-top: 30px;
        }
        #embed-captcha {
            width: 300px;
            margin: 0 auto;
        }
        .show {
            display: block;
        }
        .hide {
            display: none;
        }
        #notice {
            color: red;
        }
    </style>
</head>
<body>
<h1>极验验证SDKDemo</h1>
<form class="popup" action="{{ route('login') }}" method="get">
    <h2>嵌入式Demo，使用表单形式提交二次验证所需的验证结果值</h2><br>
    <p>
        <label for="username2">用户名：</label>
        <input class="inp" id="username2" type="text" placeholder="请输入用户名">
    </p>
    <br>
    <p>
        <label for="password2">密&nbsp;&nbsp;&nbsp;&nbsp;码：</label>
        <input class="inp" id="password2" type="password" placeholder="请输入密码">
    </p>

    <div id="embed-captcha"></div>
    <p id="wait" class="show">正在加载验证码......</p>
    <p id="notice" class="hide">请先完成验证</p>

    <input class="btn" id="embed-submit" type="submit" value="提交">
</form>
<script src="//cdn.bootcss.com/jquery/2.1.0/jquery.min.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<script>
    var handlerEmbed = function(captchaObj) {
        $('#embed-submit').click(function(){
            var validate = captchaObj.getValidate();
            // 完成安全校验
            $("#embed-captcha").closest('form').submit(function(e) {
                var validate = captchaObj.getValidate();
                if (!validate) {
                    alert('{{ Config::get('geetest.client_fail_alert')}}');
                    e.preventDefault();
                }
            });
            captchaObj.appendTo("#geetest-captcha");
            captchaObj.onReady(function() {
                $("#wait")[0].className = "hide";
            }); 

            // 用户名和密码进行校验
            if($('#username2').val() != '' && $('#password2').val() != '') {
                return true;
                // return redirect()->route('login',[params => 2]);
            } else {
                alert("验证不通过");
                return false;
            }
        })

        // console.log('表单验证状态码',statusData);    
        captchaObj.appendTo("#embed-captcha");
        captchaObj.onReady(function () {
            $("#wait")[0].className = "hide";
        });
    };
    $.ajax({
        // 获取id，challenge，success（是否启用failback）
        url: "{{ route('getVerify',array('t'=>time())) }}", // 加随机数防止缓存
        type: "get",
        dataType: "json",
        success: function (data) {
            console.log('successs',data)
            // 使用initGeetest接口
            // 参数1：配置参数
            // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
            initGeetest({
                // width:'100%',
                gt: data.gt,
                challenge: data.challenge,
                lang: '{{ Config::get('geetest.lang') }}',
                product: "float", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
            }, handlerEmbed);
        }
    });
</script>
</body>
</html>