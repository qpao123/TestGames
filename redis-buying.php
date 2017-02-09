<?php
    //使用watch
    $redis = new redis();
    $result = $redis->connect('127.0.0.1',6379);

    $num = $redis->get('num');
    $total_num = 10; //抢购总数

    if($num<$total_num){
        $redis->watch('num');
        $redis->multi();

        //设置延迟，方便测试
        sleep(5);
        //插入抢购数据
        $redis->hSet('userlist','user_id'.mt_rand(1,9999),time());
        $redis->incr('num');
        $result = $redis->exec();
        if($result){
            $new_num = $redis->get('num');
            $userlist = $redis->hGetAll('userlist');
            echo '抢购成功<br/>';
            echo '剩余数量'.($total_num-$new_num).'<br/>';
            echo '<pre>';
            print_r($userlist);
        }else{
            die('抢购失败');
        }
    }else{
        die('抢购失败');
    }


    //使用列表方式
    $user_id = mt_rand(1,9999);

    $redis = new redis();
    $result = $redis->connect('127.0.0.1',6379);
    //先在redis中执行插入库存
    //for($i=1;$i<=10;$i++){
    //    $redis->lpush('goods',$i);
    //}

    //库存存在goods:1这个列表中
    $check = $redis->lpop('goods');
    if(!$check){
        exit('抢光了');
    }

    $result = $redis->lpush('order',$user_id);
    if($result){
        echo '抢购成功';
    }

