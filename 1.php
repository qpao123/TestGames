<?php
    //time33哈希算法
    function myHash($str) {
        $hash = 0;
        $s = md5($str);
        $seed = 5;
        $len = 32;
        for ($i = 0; $i < $len; $i++) {
            $hash = ($hash << $seed) + $hash + ord($s{$i});
        }
        return $hash & 0x7FFFFFFF;
    }

    class hashList {
        private $server_arr = [];
        private $sort = false;

        public function __construct($arr)
        {
            array_map(function($item){
                $this->addServer($item);
            },$arr);
        }

        private function myHash($key)
        {
            $hash = 0;
            $s = md5($key);
            $seed = 5;
            $len = 32;
            for ($i = 0; $i < $len; $i++) {
                $hash = ($hash << $seed) + $hash + ord($s{$i});
            }
            return $hash & 0x7FFFFFFF;
        }

        public function addServer($server)
        {
            $hash = $this->myHash($server);
            if(!isset($this->server_arr[$hash])){
                $this->server_arr[$hash] = $server;
            }
            $this->sort = false;
            return true;
        }

        public function removeServer($server)
        {
            $hash = $this->myHash($server);
            if(isset($this->server_arr[$hash])){
                unset($this->server_arr[$hash]);
            }
            $this->sort = false;
            return true;
        }

        public function lookup($key)
        {
            $hash = $this->myHash($key);
            if(!$this->sort){
                ksort($this->server_arr);
                $this->sort = true;
            }
            foreach($this->server_arr as $k=>$v){
                if($hash <= $k){
                    return $v;
                }
            }
            return reset($this->server_arr);
        }
    }

//    $mc = new hashList();
//    $mc->addServer('192.168.1.2');
//    $mc->addServer('192.168.1.3');
//    $mc->addServer('192.168.1.4');
//    $mc->addServer('192.168.1.5');
    $arr = [
        '192.168.1.1',
        '192.168.1.2',
        '192.168.1.3',
        '192.168.1.4',
        '192.168.1.5',
    ];
    $mc = new hashList($arr);
    echo $mc->lookup('key1').'<br/>';
    echo $mc->lookup('key2').'<br/>';
    echo $mc->lookup('key3').'<br/>';
    echo $mc->lookup('key4').'<br/>';
    echo $mc->lookup('key5').'<br/>';
    echo $mc->lookup('key6').'<br/>';
    echo $mc->lookup('key7').'<br/>';
    echo $mc->lookup('key8').'<br/>';
    echo $mc->lookup('key9').'<br/>';
    echo $mc->lookup('key10').'<br/>';







