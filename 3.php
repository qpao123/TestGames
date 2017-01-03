<?php

    class hashDataBase {
        public $dataArr = [];
        private $size = 10;

        private function hash($key) {
            $strArr = str_split($key);
            $hash = array_reduce($strArr,function($res,$val){
                $res += ord($val);
                return $res;
            });
            return $hash % $this->size;
        }

        public function insert($key,$val) {
            $index = $this->hash($key);
            $this->dataArr[$index][$key] = $val;
        }

        public function delete($key) {
            $index = $this->hash($key);
            if (isset($this->dataArr[$index])) {
                foreach ($this->dataArr[$index] as $k => $v) {
                    if ($k == $key) {
                        unset($this->dataArr[$index][$k]);
                    }
                }
            }
        }

        public function update($key,$val) {
            $index = $this->hash($key);
            foreach ($this->dataArr[$index] as $k => $v) {
                if ($k == $key) {
                    $this->dataArr[$index][$k] = $val;
                }
            }
        }

        public function find($key) {
            $index = $this->hash($key);
            foreach ($this->dataArr[$index] as $k => $v) {
                if ($k == $key) {
                    return $v;
                }
            }
            return;
        }
    }

    $db = new hashDataBase;
    $db->insert('key1','value1');
    $db->insert('key2','value2');
    $db->insert('key12','value12');
    //$db->update('key12','nihao,vivi');

    echo '<pre>';
    print_r($db->dataArr);
    echo $db->find('key1').'<br/>';
    echo $db->find('key2').'<br/>';
    echo $db->find('key12').'<br/>';

