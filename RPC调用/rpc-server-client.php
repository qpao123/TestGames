<?php
    //服务端监听端口
    $socket = stream_socket_server('tcp://0.0.0.0:8000',$errno,$errstr);
    if(!$socket) {
        die('create socket fail');
    }

    while(true) {
        //获取客户端连接
        $client = stream_socket_accept($socket);
        //读取客户端发送来的信息
        $buf = fread($client,8024);

        //\s正在匹配空格 i忽略大小写
        if(preg_match('/GET\s\/(.*?)\sHTTP\/1.1/i',$buf,$matches)) {
            //匹配得到访问路径
            $path = $matches[1];
            if(file_exists($path)) {
                require_once $path;
                $classes = get_declared_classes();
                $obj_class_name =end($classes);
                $obj = new $obj_class_name();

                if(preg_match('/ZX-SERVICE-PROTOCOL\s(.*?)\s/i',$buf,$matches)){
                    //获取约定格式的方法名
                    $methodName = $matches[1];
                    if(!method_exists($obj,$methodName)){
                        fwrite($client,'对象方法不存在');
                    }else{
                        $result = $obj->$methodName();
                        fwrite($client,$result);
                    }
                } else {
                    fwrite($client,'非法访问，不符合约定规则');
                }
            } else {
                fwrite($client,'页面不存在');
            }
        } else {
            fwrite($client,'hello socket');
        }

        fclose($client);
    }

    fclose($socket);


    //服务端对象写法
    class RpcServer
    {
        private $link;

        public function __construct($host,$port) {
            $this->link = stream_socket_server('tcp://'.$host.':'.$port);
            if(!$this->link) {
                die('create socket fail');
            }
            $this->run();
        }

        public function run() {
            while(true) {
                $client = stream_socket_accept($this->link);
                $data = fread($client,8024);
                $path = $this->getPath($data);
                if($path){
                    $obj = $this->createObj($path);
                    if($obj){
                        $methodName = $this->getMethod($data);
                        if(method_exists($obj,$methodName)){
                            $result = $obj->$methodName();
                            fwrite($client,'返回结果是：'.$result);
                        }else{
                            fwrite($client,'文件中类没有此方法');
                        }
                    }else{
                        fwrite($client,'请求路径文件不存在');
                    }
                }else{
                    fwrite($client,'错误请求，不符合协议规范');
                }
                fclose($client);
            }
        }

        //得到客户端传输过来数据的请求路径
        public function getPath($data) {
            if(preg_match('/GET\s\/(.*?)\sHTTP\/1.1/i',$data,$matches)){
                return $matches[1];
            }
            return false;
        }

        //引入文件，创建对象
        public function createObj($path) {
            if(file_exists($path)){
                require_once $path;
                $classes = get_declared_classes(); //得到所有的类的数组
                $objClassName =end($classes);
                return new $objClassName();
            }
            return false;
        }

        //获得我们约定的格式的方法名称
        public function getMethod($data) {
            if(preg_match('/ZX-SERVICE-PROTOCOL\s(.*?)\s/i',$data,$matches)){
                return $matches[1];
            }
            return false;
        }
    }

    $server = new RpcServer('127.0.0.1',8000);




    //客户端对象写法
    class RpcClient
    {
        private $link;
        private $headData;

        public function __construct($url) {
            $urlData = parse_url($url);
            $protocolUrl = str_replace($urlData['path'],'',$url);
            $this->link = stream_socket_client($protocolUrl);
            if(!$this->link) {
                die('connect server socket fail');
            }
            $this->headData = 'GET '.$urlData['path'].' HTTP/1.1'.PHP_EOL;
        }

        public function __call($name,$arg) {
            $this->headData .= 'ZX-SERVICE-PROTOCOL '.$name.PHP_EOL;
            fwrite($this->link,$this->headData);
            $res = fread($this->link,8024);
            fclose($this->link);
            return $res;
        }
    }

    $client = new RpcClient('http://127.0.0.1:8000/news.php');
    echo $client->play();

    //news.php 文件
    class fn {
        public function hello() {
            return 'hello qpao123';
        }

        public function play() {
            return 'I like play basketball';
        }
    }