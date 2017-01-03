<?php
    class HashNode{
        public $key;
        public $value;
        public $nextNode;
        public function __construct($key, $value, $nextNode=Null){
            $this->key = $key;
            $this->value = $value;
            $this->nextNode = $nextNode;
        }
    }
    class NewHashTable{
        private $arr;
        private $size = 10;
        public function __construct(){
            $this->arr = new SplFixedArray($this->size);
        }
        private function simpleHash($key){
            $asciiTotal = 0;
            $len = strlen($key);
            for($i=0; $i<$len; $i++){
                $asciiTotal += ord($key[$i]);
            }
            return $asciiTotal % $this->size;
        }
        public function set($key, $value){
            $hash = $this->simpleHash($key);
            if(isset($this->arr[$hash])){
                $newNode = new HashNode($key, $value, $this->arr[$hash]);
            }else{
                $newNode = new HashNode($key, $value, null);
            }
            $this->arr[$hash] = $newNode;
            return true;
        }
        public function get($key){
            $hash = $this->simpleHash($key);
            $current = $this->arr[$hash];
            while(!empty($current)){
                if($current->key == $key){
                    return $current->value;
                }
                $current = $current->nextNode;
            }
            return NULL;
        }
        public function getList(){
            return $this->arr;
        }
    }

    $db = new NewHashTable();
    $db->set('key1','value1');
    $db->set('key2','value2');
    $db->set('key12','value12');

    echo '<pre>';
    print_r($db->getList());