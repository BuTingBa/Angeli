<?php

/**
 * 安个利数据读写类
 * 作者：不停(张原粽)
 * 版权：深圳市鑫峰互联有限公司
 * 最后更新时间：2019年9月2日18:51:01
 */
class angeliadmin
{
    private $mysqli;
    private $wxAppid;
    private $wxSessionKey;
    /*
     * 构造函数，初始化数据库信息
     */
    public function __construct($config)
    {
        $this->mysqli=new mysqli($config['dbHost'],$config['dbUsername'],$config['dbPassword'],$config['dbName'])
        or die('数据库链接出错:'.$this->mysqli->connect_error);
        $this->mysqli->query('set names utf8mb4');
        $this->wxAppid=$config['wxAppid'];
        $this->wxSessionKey=$config['wxSessionKey'];
    }

}
?>