<?php

    //pack unpack函数运用
    /*实例说明，接口开发要求：
    参数 描述
    用户名 20字节，字符型
    密码 10字节，字符型
    年龄 1字节，无符char型
    出生年月 4字节，整型（19800101）
    邮箱 50字节，字符串
    各字段间用："\0"分割*/

    $rule = [
        'username' => ['A20','zhangsan123'],
        'pass'     => ['A10','123456'],
        'age'      => ['C',23],
        'birthday' => ['I','19901030'],
        'email'    => ['A50','zhangsan@qq.com']
    ];
    $stream = join("\0",packByArr($rule));
    file_put_contents("./1.txt",$stream);    //将流保存起来便于下面读取

    function packByArr($arr) {
        $new_arr = array_map(function($item){
            return pack($item[0],$item[1]);
        },$arr);
        return $new_arr;
    }


    $unRule = [
        'username' => ['A20'],
        'pass'     => ['A10'],
        'age'      => ['C'],
        'birthday' => ['I'],
        'email'    => ['A50']
    ];

    $stream = file_get_contents("./1.txt");
    $res = unPackByArr($stream,$unRule);

    //注意解包规则和打包要一致，解包的结果数组索引从1开始
    function unPackByArr($stream,$unRule) {
        $strArr = explode("\0",$stream);
        $i = 0;
        foreach ($unRule as $k => $v) {
            $arr = unpack($v[0],$strArr[$i]);
            $res[$k] = $arr[1];
            $i++;
        }
        return $res;
    }


    echo '<pre>';
    print_r($res);



