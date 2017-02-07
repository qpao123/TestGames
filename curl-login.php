<?php
    $url = 'http://www.zixue.it/member.php?mod=logging&action=login&loginsubmit=yes&infloat=yes&lssubmit=yes&inajax=1';
    $post_data = [
        'fastloginfield'=>'username',
        'username'=>'qpao123',
        'password'=>'111111',
        'quickforward'=>'yes',
        'handlekey'=>'ls'
    ];

    /*
     $url = "http://login.sina.com.cn/sso/login.php?client=ssologin.js(v1.4.18)";
     $post_data = [
         'entry' => 'weibo',
         'gateway'=>'1',
         'from'=>'',
         'savestate'=>'7',
         'useticket'=>'1',
         'pagerefer'=>'http://login.sina.com.cn/sso/logout.php?entry=miniblog&r=http%3A%2F%2Fweibo.com%2Flogout.php%3Fbackurl%3D%252F',
         'vsnf'=>'1',
         'su'=>'NDE0OTI0OTI3JTQwcXEuY29t',
         'service'=>'miniblog',
         'servertime'=>'1486434222',
         'nonce'=>'OBR0Z1',
         'pwencode'=>'rsa2',
         'rsakv'=>'1330428213',
         'sp'=>'aaa5246d5fead78b193679ee7a335fc8a00bdb3d51bdf0c5d7d2dc46f6931eca55c878c0e74d7ad7840e33e06e28759ab2255c1f514f522cb0a7e5ec05797696191763f4b93a8db312900813ee59dc573a6ad59c6d9a090a668c2a968690c635f01635629a6eb2700546c071bcfcb51d3f453d15d3c6a14fa1b97ba0ccfbb10d',
         'sr'=>'1680*1050',
         'encoding'=>'UTF-8',
         'prelt'=>'445',
         'url'=>'http://weibo.com/ajaxlogin.php?framelogin=1&callback=parent.sinaSSOController.feedBackUrlCallBack',
         'returntype'=>'META'
     ]; */


    $cookie_file = tempnam('./temp', 'cookie'); //创建唯一名称文件

    $ch=curl_init($url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
    curl_setopt($ch,CURLOPT_COOKIEJAR,$cookie_file); //存储提交后得到的cookie数据
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //如果返回header头有重定向，循环重定向
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); //解压gzip压缩，乱码问题

    $result = curl_exec($ch);
    if(curl_errno($ch)){
        die(curl_error($ch));
    }
    curl_close($ch);


    $code=mb_detect_encoding($result,array('ASCII','GB2312','GBK','UTF-8'));//检测字符串编码
    $result = iconv($code,"UTF-8",$result); //把GBK转成UTF-8
    //var_dump($result); exit;


    $url ='http://www.zixue.it';
    //$url="http://weibo.com/";
    $ch=curl_init($url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_COOKIEFILE,$cookie_file); //使用提交后得到的cookie数据做参数，免登陆
    $contents=curl_exec($ch);
    curl_close($ch);

    echo $contents;
    exit;
