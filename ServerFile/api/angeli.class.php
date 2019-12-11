<?php

/**
 * 安个利数据读写类
 * 作者：不停(张原粽)
 * 版权：深圳市鑫峰互联有限公司
 * 最后更新时间：2019年11月13日16:29:43
 */
class angeli
{
    private $mysqli;
    private $wxAppid;
    private $wxSessionKey;
    public $systeminfo;
    /*
     * 构造函数，初始化数据库信息
     */
    public function __construct($config)
    {
        if(isset($_SERVER['HTTP_SYSTEM'])){
            $this->systeminfo=json_decode($_SERVER['HTTP_SYSTEM'],true);
        }
        $this->mysqli=new mysqli($config['dbHost'],$config['dbUsername'],$config['dbPassword'],$config['dbName'])
        or die('数据库链接出错:'.$this->mysqli->connect_error);
        $this->mysqli->query('set names utf8mb4');
        $this->wxAppid=$config['wxAppid'];
        $this->wxSessionKey=$config['wxSessionKey'];
    }

    /**
     * 测试函数
     */
    public function test(){
        echo $this->getCommentId(7354,'看起来不错',1395);
        var_dump($this->systeminfo);

    }

    /**
     * 添加反馈
     * @param $auid
     * @param $text
     * @param $name
     * @param $phone
     * @return bool|string
     */
    public function addFankui($auid,$text,$name,$phone)
    {
        $time=time();
        $sql = "INSERT INTO angeli_fankui (auid, `text`,phone,`name`,addtime) values ('$auid', '$text','$phone','$name',$time)";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return $this->mysqli->error;
        }else{
            if($this->mysqli->affected_rows<1){
                return $this->mysqli->error;
            }else{
                return TRUE;
            }
        }
    }

    /**
     * 获取反馈列表
     * @return array|bool
     */
    public function getFankuiList()
    {
        $sql = "select * from angeli_fankui";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $aa=array(
                        'id'=>$row['id'],
                        'auid'=>$this->getInfo($row['auid']),
                        'text'=>$row['text'],
                        'phone'=>$row['phone'],
                        'name'=>$row['name'],
                        'addtime'=>$this->uc_time_ago($row['addtime'])
                    );
                    $data[]=$aa;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }


    public function setConfig($keyword,$value,$auid)
    {

        $sql = "SELECT * FROM angeli_config WHERE keyword='$keyword' AND auid='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $configId=$row['id'];
                }
                $sql = "UPDATE angeli_config SET `value`='$value' WHERE id=$configId";

            }else{
                //没有该配置项则新建一个
                $sql = "INSERT INTO angeli_config (keyword, `value`,auid) values ('$keyword', '$value',$auid)";
            }
            $result=$this->mysqli->query($sql) or die($this->mysqli->error);
            if(!$result){
                //表示操作失败
                return $this->mysqli->error;
            }else{
                if($this->mysqli->affected_rows<1){
                    return $this->mysqli->error;
                }else{
                    return TRUE;
                }
            }
        }
    }


    public function getConfig($auid,$keyword)
    {
        $sql = "SELECT * FROM angeli_config WHERE keyword='$keyword' AND auid='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $data=$row['value'];
                }
                return $data;
            }else{
                return false;
            }
        }
    }




    /**
     * @param $auid 用户ID
     * @param $open_type    操作类型
     * @param $record_id    操作的id
     * @param $brand        手机型号
     * @param $system       设备系统
     * @param $note         备注
     * @return int
     */

    public function addUserLog($auid=0,$open_type='未知',$record_id=0,$brand='未获取到',$system='未获取到',$note='')
    {
        $sql = $this->mysqli->prepare("INSERT INTO behavior (open_type,record_id,auid,brand,system,ip,opentime,note) 
                        VALUES (?,?,?,?,?,?,?,?)");
        $ip=$_SERVER["REMOTE_ADDR"];
        $time=time();
        $sql->bind_param("siisssss",$open_type,$record_id,$auid,$brand,$system,$ip,$time,$note);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取我关注的用户
     * @param $auid 欲查询的用户ID
     * @return array|bool
     */
    public function getMyfollows($auid)
    {
        $sql = "select * from angeli_guanzhu WHERE guanzhu=$auid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $aa=array(
                        'id'=>$row['id'],
                        'guanzhuId'=>$this->getInfo($row['guanzhu']),
                        'beiguanzhuId'=>$this->getInfo($row['beiguanzhu']),
                        'time'=>$this->uc_time_ago($row['opentime']),
                        'notMe'=>$this->getInfo($row['guanzhu']==$auid?$row['beiguanzhu']:$row['guanzhu'])
                    );
                    $data[]=$aa;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }


    /**
     * 新增提现请求
     * @param $auid
     * @param $jifen
     * @return bool
     */
    public function addTixian($auid,$fee,$openid)
    {

        $jifen=$fee*10;
        $data=$this->getJifen($auid);
        if($data['Points']<$jifen){
            return 3;//积分不足
        }
        $sql = $this->mysqli->prepare("INSERT INTO angeli_tixian (auid,jifen,status,fee,openid,addtime) VALUES (?,?,?,?,?,?)");
        $time=time();
        $sta='正在审核';
        $sql->bind_param("iisssi",$auid,$jifen,$sta,$fee,$openid,$time);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            return 1;
        }else{
            $this->setPoints($auid,'-',$jifen,'提现');
            return 0;
        }

    }


    /**
     * 删除帖子
     * @param $postid
     * @param $auid
     * @return bool
     */
    public function delPost($postid,$auid)
    {
        $sql = "UPDATE angeli_posts SET IsLock=2 WHERE PostsId=$postid AND AuthorId=$auid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows<1){
                return false;
            }else{
                return TRUE;
            }
        }



    }

    /**
     * 修改用户设置
     * @param $auid
     * @param $key 数据库字段名
     * @param $val
     * @return bool
     */
    public function setUserConfig($auid,$key,$val)
    {
        $sql="INSERT INTO angeli_config_user (auid,First_vip,upname) VALUES ($auid,1,0)  ON DUPLICATE KEY UPDATE First_vip=1";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows<1){
                return false;
            }else{
                return TRUE;
            }
        }
    }

    /*
     * 获取当天很火的用户
     */
    public function getDayHot($auid){
        $staTime=mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $endTime=$staTime+60*60*24;
        $sql="SELECT *,count(*) as lll FROM angeli_favorite WHERE addTime>$staTime AND addTime<$endTime GROUP BY AuthorId ORDER BY lll desc LIMIT 100 ";
        //echo $sql;
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{

            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $d = array(
                        'api' =>$row["lll"],
                        'AuId' =>$this->getInfo($row['AuthorId'],$auid),
                        'isMe'=>$row['AuthorId']==$auid?'true':'false'
                    );
                    $data['data'][]=$d;
                }
                return $data;
            }else{
                return FALSE;
            }
        }


    }

    /**
     * 修改个性签名
     * @param $auid
     * @param $txt
     * @return bool
     */
    function setms($auid,$txt)
    {
        $sql = "UPDATE angeli_user SET Synopsis='$txt' WHERE AuId=$auid";

        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows<1){
                return false;
            }else{
                return TRUE;
            }
        }
    }

    /**
     * 修改性别
     * @param $auid auid
     * @param $sex 性别
     * @return bool
     */
    function setSex($auid,$sex)
    {
        $sql = "UPDATE angeli_user SET Gender=$sex WHERE AuId=$auid";
        echo $sql."<br />";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows<1){
                return false;
            }else{
                return TRUE;
            }
        }
    }

    /**获取用户配置
     * @return array
     */
    public function getUserConfig($auid)
    {
        $sql="SELECT * FROM angeli_config_user WHERE auid='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $data=array(
                        'id'=>$row['id'],
                        'auid'=>$row['auid'],
                        'First_vip'=>$row['First_vip'],
                        'upname'=>$row['upname'],
                        'huodongvip'=>$row['huodongvip']
                    );
                }
                return $data;
            }else{
                $data=array(
                    'id'=>'0',
                    'auid'=>'0',
                    'First_vip'=>'0',
                    'upname'=>'0'
                );
                $sql="INSERT INTO angeli_config_user (auid,First_vip,upname) VALUES ($auid,0,0)";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                if(!$result){
                    //表示操作失败
                    return false;
                }else{
                    return $data;
                }

            }
        }
    }


    /**
     * 添加举报信息
     */
    public function addJuBao($postid,$auid,$beijubaoid,$yuanyin)
    {
        $sql = $this->mysqli->prepare("INSERT INTO angeli_jubao (postid,jubaoid,beijubaoid,reason,addtime) VALUES (?,?,?,?,?)");
        $time=time();
        $sql->bind_param("iiisi",$postid,$auid,$beijubaoid,$yuanyin,$time);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            return false;
        }else{
            if($this->vipIs($auid)){
                if($this->jubaoNum($auid)){ //如果当天举报超过三条
                    if($this->postDisable($postid)){
                        $this->addSystemMsg('你的举报信息我们已经收到,感谢你做出的净化社区贡献。',$auid);
                    }
                }else{
                    $this->addSystemMsg('你的举报信息我们已经收到,感谢你做出的净化社区贡献。',$auid);
                }
            }else{
                $this->addSystemMsg('你的举报信息我们已经收到,感谢你做出的净化社区贡献。',$auid);
            }
            return TRUE;
        }
    }

    public function jubaoNum($auid)
    {
        $starTime=strtotime(date("Y-m-d"));
        $endTime=$starTime+24*60*60;
        $sql = "SELECT * FROM angeli_jubao WHERE jubaoid ='$auid' AND addtime>'$starTime' AND addtime<'$endTime'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>=3){
                return FALSE;
            }else{
                return true;
            }
        }
    }


    public function postDisable($postid)
    {
        $postData=$this->getPostInfo($postid);
        $postjj=substr($postData['Content'],0,12);
        $sql = "UPDATE angeli_posts SET IsLock='2' WHERE PostsId=$postid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows<1){
                return false;
            }else{
                $this->addSystemMsg('您好，由于您的文章《'.$postjj.'...》可能涉嫌违规，现已屏蔽,等待人工复审.如有疑问，请询问客服。',$postData['AuthorId']);
                return TRUE;
            }
        }
    }
    public function postEnable($postid)
    {
        $postData=$this->getPostInfo($postid);
        $postjj=substr($postData['Content'],12);
        $sql = "UPDATE angeli_posts SET IsLock='0' WHERE PostsId=$postid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows<1){
                return false;
            }else{
                $this->addSystemMsg('您好，由于您的文章《'.$postjj.'》经人工审查，并无违规行为，已将帖子恢复访问。',$postData['AuthorId']);
                return TRUE;
            }
        }
    }

    /**
     * 获取微信token
     */
    public function getWxAccessToken()
    {
        $html = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->wxAppid."&secret=".$this->wxSessionKey);
        $arr=json_decode($html);
        return $arr->access_token;
    }

    //推广注册
    public function setTuiGuang($auid,$toAuid)
    {
        $time=time();
        $sql="INSERT INTO angeli_tuiguang(auid,to_auid,addtime) VALUES ($auid,$toAuid,$time)";
        //echo $sql;
        $result=$this->mysqli->query($sql);
        $this->getTuiGuang($auid);
    }
    //获取推广的信息
    public function getTuiGuang($auid)
    {
        $sql="SELECT * FROM angeli_tuiguang WHERE auid=$auid  ORDER BY addtime  DESC";
        $result=$this->mysqli->query($sql);
        if($this->mysqli->affected_rows<1){
            echo $this->mysqli->error;
            return false;
        }else{
            while($row = $result->fetch_assoc()){
                $count++;
                $d=array(
                    'id'=>$row['id'],
                    'TG'=>$this->getInfo($row['auid']),
                    'ToTG'=>$this->getInfo($row['to_auid']),
                    'time'=>date('Y-m-d H:i:s',$row['addtime'])
                );
                $data[]=$d;
            }

            $cc=$this->getUserConfig($auid);

            if($count>=3){
                if($cc['huodongvip']!=1){
                    if($this->setLongVip($auid,'+1month'))
                    {
                        $this->setUserH($auid);
                        $this->addSystemMsg('恭喜你，已经分享给三位好友，你已经获得了30天安个利VIP！膜拜大佬！',$auid);
                    }else{
                        $this->addSystemMsg('恭喜你，已经分享给三位好友，本来你是可以获取到30天VIP的，但是，系统好像出问题了，你可以直接跟客服联系，解决这个问题',$auid);
                    }
                }
            }

            return $data;
        }
    }

    public function setUserH($auid){
        $sql="INSERT INTO angeli_config_user (auid,First_vip,upname,huodongvip) VALUES ($auid,0,0,1)  ON DUPLICATE KEY UPDATE huodongvip=1";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows<1){
                return false;
            }else{
                return TRUE;
            }
        }
    }

    /*
     * 获取系统通知信息
     */
    public function getSystemMsg($auid){
        $sql="SELECT * FROM angeli_system_msg WHERE toid='all' OR toid LIKE '%$auid%' ORDER BY addtime  DESC ";
        $result=$this->mysqli->query($sql);
        if($this->mysqli->affected_rows<1){
            echo $this->mysqli->error;
            return 0;
        }else{
            while($row = $result->fetch_assoc()){
                $yidu=$this->chekSystemNotRead($row['id'],$auid);
                if($yidu){
                    $wd++;
                }

                $d=array(
                    'id'=>$row['id'],
                    'msg'=>$row['msg'],
                    'isRead'=>$yidu,
                    'time'=>$this->uc_time_ago($row['addtime']),
                    'type'=>$row['type'],
                    'typeVal'=>$row['type_value']
                );
                $data[]=$d;
            }
            return $data;
        }
    }


    /**
     * 查询提现状态
     * @param $auid
     * @param $addtime
     * @return array|bool
     */
    public function sakTixianStatus($auid,$addtime)
    {
        $sql = "SELECT * FROM angeli_tixian WHERE  auid=$auid AND addtime=$addtime";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows<1){
                return FALSE;
            }else{
                while($row = $result->fetch_assoc()){

                    $val=array(
                        'auid'=>$row['auid'],
                        'jifen'=>$row['jifen'],
                        'status'=>$row['status'],
                        'fee'=>$row['fee'],
                        'operator'=>$row['operator'],
                        'to'=>$row['openid'],
                        'addtime'=>$row['addtime']
                    );

                }
                return $val;

            }
        }
    }


    /*
     * 查询系统消息是否已读
     * true=已读，false= 未读
     */
    function chekSystemNotRead($sid,$auid)
    {
        $sql = "SELECT * FROM angeli_system_msg_read WHERE  auid=$auid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return TRUE;
        }else{
            if($this->mysqli->affected_rows<1){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }

    /*
     * 添加系统广播私信,多个接收用户用,隔开，发送全部广播不用填写接收用户ID
     */
    public function addSystemMsg($txt,$ToId='all',$type='1',$val='0')
    {
        $sql = $this->mysqli->prepare("INSERT INTO angeli_system_msg (msg,toid,addtime,type,type_value) VALUES (?,?,?,?,?)");
        $time=time();
        $sql->bind_param("sssis",$txt,$ToId,$time,$type,$val);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            return false;
        }else{
            if($ToId=='all'){
                $sql="DELETE FROM angeli_system_msg_read";
            }else{
                $sql="DELETE FROM angeli_system_msg_read WHERE auid=$ToId";
            }
            $result=$this->mysqli->query($sql);
            return TRUE;
        }
    }

    /**获取系统配置
     * @return array
     */
    public function getSystemConfig($configName)
    {
        $sql="SELECT * FROM angeli_config_system WHERE config_name='$configName'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $val=$row['config_val'];
                }
                return $val;
            }else{
                return FALSE;
            }
        }
    }




    /**获取我的好友列表
     * @return array
     */
    public function getMyhaoyou($auid,$page=1,$count=100)
    {
        $pageNum=($page-1)*$count;
        $sql = "select a.* from angeli_guanzhu as a left join angeli_guanzhu as b ON a.beiguanzhu=$auid AND a.guanzhu=b.beiguanzhu where (b.beiguanzhu=$auid OR b.guanzhu=$auid) AND (b.beiguanzhu=$auid OR b.guanzhu=$auid) ORDER BY opentime DESC LIMIT $pageNum, $count";
        //die($sql);
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $aa=array(
                       'id'=>$row['id'],
                        'guanzhuId'=>$this->getInfo($row['guanzhu']),
                        'beiguanzhuId'=>$this->getInfo($row['beiguanzhu']),
                        'time'=>$this->uc_time_ago($row['opentime']),
                        'notMe'=>$this->getInfo($row['guanzhu']==$auid?$row['beiguanzhu']:$row['guanzhu'])
                    );
                    $data[]=$aa;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }


    /*
     * 关注好友获取取消关注
     */
    public function gzORungz($guanzhuid,$beiguanzhu){

        if(!$this->isGuanzhu($guanzhuid,$beiguanzhu)){
            //关注
            $time=time();
            $sql="INSERT INTO angeli_guanzhu(guanzhu,beiguanzhu,opentime) VALUES ($guanzhuid,$beiguanzhu,$time)";
            $txt='关注成功';
            $result=$this->mysqli->query($sql);
            if(!$result){
                return false;
            }else{
                if($this->mysqli->affected_rows>0){
                    $sql="UPDATE angeli_user SET FollowerCount=FollowerCount+1 WHERE AuId=$beiguanzhu";
                    $this->mysqli->query($sql);
                    $sql="UPDATE angeli_user SET FollowedCount=FollowedCount+1 WHERE AuId=$guanzhuid";
                    $this->mysqli->query($sql);
                    return $txt;
                }else{
                    return false;
                }
            }
        }else{
            //取消关注
            $sql="DELETE FROM angeli_guanzhu WHERE guanzhu=$guanzhuid AND beiguanzhu=$beiguanzhu";
            $txt='取消关注成功';
            $result=$this->mysqli->query($sql);
            if(!$result){
                return false;
            }else{
                if($this->mysqli->affected_rows>0){
                    $sql="UPDATE angeli_user SET FollowerCount=FollowerCount-1 WHERE AuId=$beiguanzhu";
                    $this->mysqli->query($sql);
                    $sql="UPDATE angeli_user SET FollowedCount=FollowedCount-1 WHERE AuId=$guanzhuid";
                    $this->mysqli->query($sql);
                    return $txt;
                }else{
                    return false;
                }
            }
        }

    }

    /*
     * 获取我的关注信息
     */
    public function getMyGZMsg($auid){
        $sql="SELECT * FROM angeli_guanzhu WHERE beiguanzhu=$auid  ORDER BY opentime  DESC";
        $result=$this->mysqli->query($sql);
        if($this->mysqli->affected_rows<1){
            return 0;
        }else{
            while($row = $result->fetch_assoc()){
                $d=array(
                    'id'=>$row['postId'],
                    'guanzhuId'=>$this->getInfo($row['guanzhu']),
                    'myId'=>$this->getInfo($row['beiguanzhu']),
                    'time'=>$this->uc_time_ago($row['opentime']),
                    'isGZ'=>$this->isGuanzhu($auid,$row['guanzhu']),
                    'mark'=>$row['mark']

                );
                $data[]=$d;
            }
            return $data;
        }
    }


    /*
     * 检查是否是关注关系
     */
    public function isGuanzhu($auid,$beichaxun){
        $sql="SELECT * FROM angeli_guanzhu WHERE guanzhu=$auid AND beiguanzhu=$beichaxun";
        $result=$this->mysqli->query($sql);
        if(!$result){
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                return true;
            }else{
                return false;
            }
        }

    }

    /**
     * 获取分类帖子列表
     * @return array
     */
    public function getClassPostList($classId,$page=1,$count=20,$uid,$type)
    {
        $pageNum=($page-1)*$count;
        if($type=="new"){
            $sql = "SELECT * FROM angeli_posts WHERE Tag=$classId AND PostType='1' AND IsLock<>'2' ORDER BY PsotDate  DESC  LIMIT $pageNum, $count";
        }else{
            $sql = "SELECT * FROM angeli_posts WHERE Tag=$classId AND PostType='1' AND IsLock<>'2' ORDER BY ZhongcaoCount  DESC  LIMIT $pageNum, $count";
        }
        //echo $sql;
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $da = array(
                        'PostsId' =>$row["PostsId"],
                        'AuthorId' =>$row["AuthorId"],
                        'Content' =>$row["Content"],
                        'Tag' =>$this->getClassinfo($row["Tag"]),
                        'PsotDate' =>$this->uc_time_ago($row["PsotDate"]),
                        'ZhongcaoCount' =>$row["ZhongcaoCount"],
                        'ViewCount' =>$row["ViewCount"],
                        'PostType' =>$row["PostType"],
                        'PostSum' =>$row["PostSum"],
                        'RewardEndTime' =>$row["RewardEndTime"],
                        'PictureId' =>explode(',',$row["PictureId"]),
                        'AuthorInfo'=>$this->getInfo($row["AuthorId"]),
                        'Give'=>$this->checkGive($uid,$row["PostsId"])
                    );
                    $data[]=$da;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    /*
     * 查询打赏列表
     */


    public function getDashangList($auid,$postid){
        $sql="SELECT * FROM angeli_dashang WHERE postId=$postid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if($this->mysqli->affected_rows<1){


            return 0;
        }else{
            while($row = $result->fetch_assoc()){
                $index++;
                $d=array(
                    'id'=>$row['postId'],
                    'postId'=>$row['postId'],
                    'toAuid'=>$row['toAuid'],
                    'isMe'=>$row['fromAuid']==$auid?'true':'false',
                    'fromAuid'=>$this->getInfo($row['fromAuid']),
                    'number'=>$row['number'],
                    'time'=>date("Y-m-d H:i:s",$row['addtime'])
                );
                $data['data'][]=$d;
            }
            $data['count']=$index;
            return $data;
        }

    }

    /*
     * 打赏
     */

    public function setDashang($auid,$postid,$authorId,$number){
        if($this->checkDashang($auid,$postid)){
            return 2;//已存在
        }
        $data=$this->getJifen($auid);
        if($data['Points']<$number){
            return 3;//积分不足
        }
        $time=time();
        $sql="INSERT INTO angeli_dashang(postId,fromAuid,toAuid,number,addtime) VALUES ($postid,$auid,$authorId,$number,'$time')";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if($this->mysqli->affected_rows<1){
            //表示操作失败
            return 0;
        }else{
            $this->setPoints($auid,"-",$number,'打赏帖子');
            if($this->setPoints($authorId,"+",$number,'打赏帖子')){
                return 1;
            }

        }
    }

    /**获取我私信
     * @return array
     */
    public function getMyMsg($msgId,$auid,$page=1,$count=50)
    {
        $pageNum=($page-1)*$count;
        $sql = "SELECT * FROM angeli_msg WHERE FromId+ToId=$msgId ORDER BY MsgSendTime ASC LIMIT $pageNum, $count";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $d=array(
                        'FromId'=>$this->getInfo($row['FromId']),
                        'ToId'=>$this->getInfo($row['ToId']),
                        'Msg'=>$row['Msg'],
                        'type'=>$row['FromId']==$auid?'me':'he',
                        'ff'=>$this->getInfo($row['FromId']==$auid?$row['ToId']:$row['FromId']),
                        'mm'=>$this->getInfo($auid),
                        'MsgStatus'=>$row['FromId']==$auid?'1':$row['MsgStatus'],
                        'MsgSendTime'=>$this->uc_time_ago($row['MsgSendTime'])
                    );
                    $data[]=$d;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }


    /**获取我私信列表
     * @return array
     */
    public function getMyMsgList($auid,$page=1,$count=20)
    {
        $pageNum=($page-1)*$count;

        //$sql = "select * from angeli_msg WHERE FromId=$auid OR ToId=$auid  group by FromId+ToId ORDER BY MsgSendTime DESC LIMIT $pageNum, $count";
        $sql="select * from (select * from angeli_msg WHERE FromId=$auid OR ToId=$auid ORDER BY MsgId desc) as a group by a.FromId+a.ToId ORDER BY a.MsgSendTime desc LIMIT $pageNum, $count";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $d=array(
                        'FromId'=>$this->getInfo($row['FromId']),
                        'ToId'=>$this->getInfo($row['ToId']),
                        'Msg'=>$row['Msg'],
                        'type'=>$row['FromId']==$auid?'me':'he',
                        'MsgStatus'=>$this->chek($row['MsgStatus'],$auid,$row['ToId']),
                        'MsgSendTime'=>$this->uc_time_ago($row['MsgSendTime'])
                    );
                    $data[]=$d;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }
    function chek($status,$auid,$id){
        if($id==$auid){
            if($status==0){
                return 0;
            }else{
                return 1;
            }
        }else{
            return 1;
        }

    }

    /*
     * 添加私信
     */
    public function addMsg($FromId,$ToId,$Msg)
    {
        $sql = $this->mysqli->prepare("INSERT INTO angeli_msg (Msg,MsgSendTime,FromId,ToId) VALUES (?,?,?,?)");
        $time=time();
        $sql->bind_param("ssii",$Msg,$time,$FromId,$ToId);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            return false;
        }else{
            $openid=$this->getmsgid($FromId,$ToId,$Msg);
            $this->addUserLog($FromId,'发送私信信息',$openid,$this->systeminfo['phonebrand'],$this->systeminfo['phonesystem']);
            return TRUE;
        }
    }

    public function getmsgid($FromId,$toid,$msg)
    {
        $sql="SELECT * FROM angeli_msg WHERE FromId=$FromId AND ToId=$toid AND Msg='$msg'";
        $result=$this->mysqli->query($sql);
        if(!$result){
            return 0;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $id=$row['MsgId'];
                }
                return $id;
            }else{
                return 0;
            }
        }
    }

    /**获取我的账单信息
     * @return array
     */
    public function getMyBill($auid,$page=1,$count=20)
    {
        $pageNum=($page-1)*$count;
        $sql = "SELECT * FROM angeli_jifen WHERE auid='$auid' ORDER BY opentime DESC LIMIT $pageNum, $count";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            $data=[];
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    if($row['note']=='提现'){
                        $status=$this->sakTixianStatus($auid,$row['opentime']);
                        $aa=array(
                            'billId'=>$row['id'],
                            'type'=>$row['type'],
                            'number'=>$row['number'],
                            'note'=>$row['note'],
                            'status'=>$status['status'],
                            'time'=>date('Y-m-d H:i:s',$row['opentime'])
                        );
                    }else{
                        $aa=array(
                            'billId'=>$row['id'],
                            'type'=>$row['type'],
                            'number'=>$row['number'],
                            'note'=>$row['note'],
                            'status'=>'成功',
                            'time'=>date('Y-m-d H:i:s',$row['opentime'])
                        );
                    }



                    array_push($data,$aa);
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }



    /**
     * @return 标记已读信息
     */
    public function markNoRead($auid,$type,$msgId=0)
    {

        switch ($type){
            case 'zan':
                $sql="UPDATE angeli_favorite SET mark=1 WHERE AuthorId='$auid'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                //echo $sql;
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    return false;
                }else{
                    return true;
                }
                break;
            case 'pl':
                $sql="UPDATE angeli_reply SET mark=1 WHERE ReplyTo='$auid'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                $sql="UPDATE angeli_comments SET mark=1 WHERE AuthorId='$auid'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                //echo $sql;
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    return false;
                }else{
                    return true;
                }
                break;
            case 'fans':
                $sql="UPDATE angeli_guanzhu SET mark=1 WHERE beiguanzhu='$auid'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                //echo $sql;
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    return false;
                }else{
                    return true;
                }
                break;
            case 'msg':
                $sql="UPDATE angeli_msg SET MsgStatus=1  WHERE FromId+ToId=$msgId AND ToId=$auid";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                //echo $sql;
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    return false;
                }else{
                    return true;
                }
                break;
            case 'system':
                $sql="INSERT INTO angeli_system_msg_read (system_msg_id,auid)VALUES(0,$auid)";
                $this->mysqli->query($sql) or die($this->mysqli->error);
                return true;
                /*$sql="SELECT * FROM angeli_system_msg WHERE toid LIKE '%$auid%'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                if(!$result){
                    //表示操作失败
                    return false;
                }else{
                    if($this->mysqli->affected_rows>0){
                        while($row = $result->fetch_assoc()){
                            $msgid=$row['id'];
                            $sql="INSERT INTO angeli_system_msg_read (system_msg_id,auid)VALUES($msgid,$auid)";
                            $this->mysqli->query($sql) or die($this->mysqli->error);
                        }
                        return  true;
                    }else{
                        return FALSE;
                    }
                }*/


                break;
            default:

                break;
        }


    }

    /**获取我的点赞信息
     * @return array
     */
    public function getMyZan($auid,$page=1,$count=40)
    {
        $pageNum=($page-1)*$count;
        $sql = "SELECT * FROM angeli_favorite WHERE AuthorId='$auid'  ORDER BY addTime DESC LIMIT $pageNum, $count";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            $data=[];
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $aa=array(
                        'userInfo'=>$this->getInfo($row['AuId']),
                        'time'=>$this->uc_time_ago($row['addTime']),
                        'type'=>'zhongcao',
                        'mark'=>$row['mark'],
                        'toId'=>$this->getPostInfo($row['PostsId'],$auid)
                    );
                    array_push($data,$aa);
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }
    /**获取我的未读评论信息
     * @return array
     */
    public function getMyPinglun($auid,$page=1,$count=40)
    {
        $pageNum=($page-1)*$count;
        $sql = "SELECT * FROM angeli_comments WHERE AuthorId='$auid' AND CommentsFromUid<>'$auid' ORDER BY addTime DESC LIMIT $pageNum, $count";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            $data=[];
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $aa=array(
                        'userInfo'=>$this->getInfo($row['CommentsFromUid']),
                        'time'=>$this->uc_time_ago($row['AddTime']),
                        'type'=>'pinglun',
                        'mark'=>$row['mark'],
                        'txt' =>$row['CommentsContent'],
                        'toId'=>$this->getPostInfo($row['PostId'],$auid),
                        'timestr'=>$row['AddTime']
                    );
                    array_push($data,$aa);
                }
            }
        }
        $sql = "SELECT * FROM angeli_reply WHERE ReplyTo='$auid' AND ReplyUid<>'$auid' ORDER BY addTime DESC LIMIT $pageNum, $count";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $ab=array(
                        'userInfo'=>$this->getInfo($row['ReplyUid']),
                        'time'=>$this->uc_time_ago($row['AddTime']),
                        'type'=>'huifu',
                        'mark'=>$row['mark'],
                        'txt'=>$row['ReplyContent'],
                        'toId'=>$this->getPostInfo($row['PostId'],$auid),
                        'huifu'=>$this->huifuTotxt($row['CommentId']),
                        'timestr'=>$row['AddTime']
                    );
                    array_push($data,$ab);

                }
            }
        }
        $tempArray = array_column($data,'timestr');
        array_multisort($tempArray,SORT_DESC,$data);
        return $data;
    }

    /**查询我的未读消息数量
     * @return array
     */
    public function getMyNoreadNumber($auid)
    {
        $sql = "SELECT * FROM angeli_comments WHERE AuthorId='$auid' AND AuthorId<>$auid AND mark=0";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            $pinglun=0;
        }else{
            $pinglun=0;
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $pinglun++;
                }
            }else{
                $pinglun=0;
            }
        }
        $sql = "SELECT * FROM angeli_reply WHERE ReplyTo='$auid' AND ReplyUid<>$auid AND mark=0";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            $pinglun=0;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $pinglun++;
                }
            }
        }

        $sql = "SELECT * FROM angeli_favorite WHERE AuthorId='$auid' AND mark=0";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $Give=0;
        }else{
            $Give=0;
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $Give++;
                }
            }else{
                $Give=0;
            }
        }
        $sql = "SELECT * FROM angeli_msg WHERE ToId='$auid' AND MsgStatus=0 OR (ToId=1 AND FromId=1)";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $Msg=0;
        }else{
            $Msg=0;
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $Msg++;
                }
            }else{
                $Msg=0;
            }
        }

        $sql = "SELECT * FROM angeli_guanzhu WHERE beiguanzhu='$auid' AND mark=0";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $Guanzhu=0;
        }else{
            $Guanzhu=0;
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $Guanzhu++;
                }
            }else{
                $Guanzhu=0;
            }
        }

        $systemmsg=0;
        $sql="SELECT * FROM angeli_system_msg WHERE toid='all' OR toid LIKE '%$auid%' ORDER BY addtime  DESC";
        $result=$this->mysqli->query($sql);
        if($this->mysqli->affected_rows<1){
            echo $this->mysqli->error;
            $systemmsg=0;
        }else{
            while($row = $result->fetch_assoc()){
                if($this->chekSystemNotRead($row['id'],$auid)){
                    //$systemmsg++;
                    $systemmsg=1;
                }

            }
        }

        $count=$pinglun+$Give+$Msg+$Guanzhu+$systemmsg;
        $data=array(
            'pinglun'=>$pinglun,
            'Give'=>$Give,
            'Msg'=>$Msg,
            'Guanzhu'=>$Guanzhu,
            'SystemMsg'=>$systemmsg,
            'count'=>$count,
            'pay'=>'no'//该参数为是否打开苹果支付，yes为打开，no为关闭。主要是为了过审而做的一个动态接口

        );

        return $data;
    }




    /**
     * @return 种草点赞，收藏/删除收藏、取消点赞
     * $ziduan 欲增加的字段
     * $biao   欲增加的表
     * return int 1 ok,2已存在，0失败
     */
    public function postGive($postId,$auid,$type='add',$AuthorId)
    {
        if($type=='add'){
            $sql = "SELECT * FROM angeli_favorite WHERE PostsId ='$postId' AND AuId='$auid'";
            $result=$this->mysqli->query($sql) or die($this->mysqli->error);
            $time=time();
            if($result -> num_rows>0){
                $sql="UPDATE angeli_favorite SET `show`=0 WHERE PostsId ='$postId' AND AuId='$auid'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    return 2;
                }else{
                    return 1;
                }

            }else{
                $sql="INSERT INTO angeli_favorite(AuId,PostsId,addTime,AuthorId,`show`) VALUES ('$auid','$postId','$time','$AuthorId',0)";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    return 0;
                }else{
                    $sql="UPDATE angeli_user SET ZhongcaoCount=ZhongcaoCount+1 WHERE AuId='$AuthorId'";
                    $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                    if($this->mysqli->affected_rows<1){
                        //表示操作失败
                        return 0;
                    }else{
                        $sql="UPDATE angeli_posts SET ZhongcaoCount=ZhongcaoCount+1 WHERE PostsId='$postId'";
                        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                        //echo $sql;
                        if($this->mysqli->affected_rows<1){
                            //表示操作失败
                            return 0;
                        }else{
                            return 1;
                        }
                    }
                }
            }
        }else{
            if(empty($auid)){
                return 0;
            }
            if(empty($postId)){
                return 0;
            }
            //$sql="DELETE FROM angeli_favorite WHERE PostsId ='$postId' AND AuId='$auid'";
            $sql="UPDATE angeli_favorite SET `show`=1 WHERE PostsId ='$postId' AND AuId='$auid'";

            $result=$this->mysqli->query($sql) or die($this->mysqli->error);
            if($this->mysqli->affected_rows<1){
                //表示操作失败
                return 0;
            }else{
                $sql="UPDATE angeli_user SET ZhongcaoCount=ZhongcaoCount-1 WHERE AuId='$AuthorId'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    return 0;
                }else{
                    $sql="UPDATE angeli_posts SET ZhongcaoCount=ZhongcaoCount-1 WHERE PostsId='$postId'";
                    $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                    //echo $sql;
                    if($this->mysqli->affected_rows<1){
                        //表示操作失败
                        return 0;
                    }else{
                        return 1;
                    }
                }
            }
        }
    }




    /**获取我的收藏
     * @return array
     */
    public function getMyFavorite($auid,$page=1,$count=20)
    {
        $pageNum=($page-1)*$count;
        $sql = "SELECT * FROM angeli_favorite WHERE AuId='$auid' AND `show`=0 ORDER BY addTime DESC LIMIT $pageNum, $count";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            $data=[];
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){

                    $aa=array(
                        $this->getPostInfo($row['PostsId'],$auid)
                    );
                    array_push($data,$aa);
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    /**搜索帖子
     * @return array
     */
    public function searchPosts($type,$keyword,$page=1,$count=20,$uid)
    {
        $pageNum=($page-1)*$count;
        if($type=='postid'){
            $sql = "SELECT * FROM angeli_posts WHERE PostsId LIKE '%$keyword' LIMIT $pageNum, $count";
        }else{
            $sql = "SELECT * FROM angeli_posts WHERE Content LIKE '%$keyword%' LIMIT $pageNum, $count";
        }
        //echo $sql;
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $da = array(
                        'PostsId' =>$row["PostsId"],
                        'AuthorId' =>$row["AuthorId"],
                        'Content' =>$row["Content"],
                        'Tag' =>$this->getClassinfo($row["Tag"]),
                        'PsotDate' =>$this->uc_time_ago($row["PsotDate"]),
                        'ZhongcaoCount' =>$row["ZhongcaoCount"],
                        'ViewCount' =>$row["ViewCount"],
                        'PostType' =>$row["PostType"],
                        'PostSum' =>$row["PostSum"],
                        'RewardEndTime' =>$row["RewardEndTime"],
                        'PictureId' =>explode(',',$row["PictureId"]),
                        'AuthorInfo'=>$this->getInfo($row["AuthorId"]),
                        'Give'=>$this->checkGive($uid,$row["PostsId"])
                    );
                    $data[]=$da;

                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }


    /**搜索用户
     * @return array
     */
    public function searchUser($type,$keyword)
    {

        if($type=='auid'){
            $sql = "SELECT * FROM angeli_user WHERE AuId LIKE '%$keyword%'";
        }else if($type=='name'){
            $sql = "SELECT * FROM angeli_user WHERE UserName LIKE '%$keyword%'";
        }else if($type=='phone'){
            $sql = "SELECT * FROM angeli_user WHERE Phone LIKE '%$keyword%'";
        }
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                while ($row = $result->fetch_assoc())
                {
                    $d = array('Auid' =>$row['AuId'],
                        'name'=>$row['UserName'],
                        'AvatarUrl' => $row['AvatarUrl'],
                        'miaoshu' => $row['Synopsis']);
                    $data[]=$d;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }



    /**
     * @return Array List
     * 获取自己发已发的帖子
     */
    public function getMyPosts($uid,$count=10,$page="1")
    {

        $pageNum=($page-1)*$count;
        $sql = "SELECT * FROM angeli_posts WHERE AuthorId='$uid' AND  IsLock<>'2' ORDER BY PsotDate DESC  LIMIT $pageNum, $count";

        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $da = array(
                        'AuthorId' =>$row["AuthorId"],
                        'PostId' =>$row["PostsId"],
                        'Content' =>$row["Content"],
                        'Give' =>$row["ZhongcaoCount"],
                        'PostType'=>$row['PostType'],
                        'Time' =>$this->uc_time_ago($row["PsotDate"]),
                        'PictureId'=>explode(',',$row["PictureId"])
                    );
                    $data[]=$da;

                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }


    /**
     * @return array
     * 获取回复列表
     */
    public function getReplyList($commentsId)
    {
        $sql = "SELECT * FROM angeli_reply WHERE CommentId='$commentsId'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $da = array(
                        'ReplyId' =>$row["ReplyId"],
                        'CommentId' =>$row["CommentId"],
                        'ToReply' =>$row["ToReply"],
                        'ReplyType' =>$row["ReplyType"],
                        'AddTime' =>$this->uc_time_ago($row["AddTime"]),
                        'ReplyTo' =>$this->getInfo($row["ReplyTo"]),
                        'ReplyUid'=>$this->getInfo($row["ReplyUid"]),
                        'ReplyContent'=>$row["ReplyContent"]
                    );
                    $data[]=$da;

                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }


    /**
     * @return true/false
     * 添加回复
     *
     */
    public function addReply($commentsId,$Content,$postid,$uid,$touid,$huifuType,$toHuifuId)
    {
        $sql = $this->mysqli->prepare("INSERT INTO angeli_reply (CommentId,ToReply,ReplyType,ReplyContent,ReplyTo,ReplyUid,PostId,AddTime) VALUES (?,?,?,?,?,?,?,?)");
        $time=time();
        $sql->bind_param("iiisiiii",$commentsId,$toHuifuId,$huifuType,$Content,$touid,$uid,$postid,$time);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'操作失败！'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            $openid=$this->getReplyId($uid,$Content,$commentsId);
            $this->addUserLog($uid,'发布回复',$openid,$this->systeminfo['phonebrand'],$this->systeminfo['phonesystem']);
            return TRUE;
        }
    }

    /**
     * @param $auid 用户id
     * @param $txt  评论内容
     * @param $postid   帖子id
     * @return bool
     */
    public function getReplyId($auid,$txt,$CommentId)
    {
        $sql="SELECT * FROM angeli_reply WHERE ReplyUid=$auid AND ReplyContent='$txt' AND CommentId=$CommentId";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $id=$row['ReplyId'];
                }
                return $id;
            }else{
                return FALSE;
            }
        }
    }

    /**
     * @return 增加数据，点赞，浏览量等，自增方法
     * $ziduan 欲增加的字段
     * $biao   欲增加的表
     */
    public function commentsGive($id)
    {
        $sql="UPDATE angeli_comments SET Zan=Zan+1 WHERE CommentsId='$id'";

        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return true;
        }
    }

    /**获取单个评论
     * @return array
     */
    public function getComment($plid)
    {
        $sql="SELECT * FROM angeli_comments WHERE CommentsId=$plid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $data=array(
                        'CommentsId' =>$row["CommentsId"],
                        'PostId' =>$row["PostId"],
                        'Content' =>$row["CommentsContent"],
                        'FromUid' =>$row["CommentsFromUid"],
                        'Time' =>$this->uc_time_ago($row["AddTime"]),
                        'userinfo'=>$this->getInfo($row["CommentsFromUid"]),
                    );
                }
                return $data;
            }else{
                return false;
            }
        }
    }

    /**
     * @return 评论列表
     */
    public function getCommentList($postid,$xu='false',$count=20,$page="1")
    {
        $pageNum=($page-1)*$count;
        if($xu=='false'){
            $sql = "SELECT * FROM angeli_comments WHERE PostId='$postid' ORDER BY AddTime ASC  LIMIT $pageNum, $count";
        }else{
            $sql = "SELECT * FROM angeli_comments WHERE PostId='$postid' ORDER BY AddTime desc  LIMIT $pageNum, $count";
        }

        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $da = array(
                        'CommentsId' =>$row["CommentsId"],
                        'PostId' =>$row["PostId"],
                        'Content' =>$row["CommentsContent"],
                        'FromUid' =>$row["CommentsFromUid"],
                        'Time' =>$this->uc_time_ago($row["AddTime"]),
                        'Give' =>$row["Zan"],
                        'userinfo'=>$this->getInfo($row["CommentsFromUid"]),
                        'replyList'=>$this->get2CommentList($postid,$row["CommentsId"],2)
                    );
                    $data[]=$da;

                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    /**
     * @return 二级评论列表
     */
    public function get2CommentList($postid,$huifuid,$pluid=0,$count=20,$page="1")
    {
        $pageNum=($page-1)*$count;
        $sql = "SELECT * FROM angeli_reply WHERE PostId=$postid AND CommentId=$huifuid ORDER BY AddTime ASC  LIMIT $pageNum, $count";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $da = array(
                        'id' =>$row["ReplyId"],
                        'CommentId' =>$row["CommentId"],
                        'PostId' =>$row["PostId"],
                        'ReplyContent' =>$row["ReplyContent"],
                        'ReplyUid' =>$this->getInfo($row["ReplyUid"]),
                        'ReplyTo' =>$this->getInfo($row["ReplyTo"]),
                        'AddTime'=>$this->uc_time_ago($row["AddTime"]),
                        'mark'=>$this->getReplyList($row["mark"]),
                        'type'=>$row["ReplyTo"]==$pluid?0:1
                    );
                    $data[]=$da;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    /*
     * 添加评论
     */
    public function addComment($PostId,$Content,$auid,$AuthorId)
    {
        $sql = $this->mysqli->prepare("INSERT INTO angeli_comments (PostId,CommentsContent,CommentsFromUid,AddTime,AuthorId) VALUES (?,?,?,?,?)");
        $time=time();
        $sql->bind_param("isiii",$PostId,$Content,$auid,$time,$AuthorId);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'操作失败！'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            $openid=$this->getCommentId($auid,$Content,$PostId);
            $this->addUserLog($auid,'发布评论',$openid,$this->systeminfo['phonebrand'],$this->systeminfo['phonesystem']);
            return TRUE;
        }
    }

    /**
     * @param $auid 用户id
     * @param $txt  评论内容
     * @param $postid   帖子id
     * @return bool
     */
    public function getCommentId($auid,$txt,$postid)
    {
        $sql="SELECT * FROM angeli_comments WHERE CommentsFromUid=$auid AND CommentsContent='$txt' AND PostId=$postid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $id=$row['CommentsId'];
                }
                return $id;
            }else{
                return FALSE;
            }
        }
    }





    /*
    *   获取微信UID，并登录且返回UID等信息
    *   参数，字符串，微信授权登录后返回的code
    */
    public function getWxID($code){

        $data="https://api.weixin.qq.com/sns/jscode2session?appid=".$this->wxAppid."&secret=".$this->wxSessionKey."&js_code=".$code."&grant_type=authorization_code";
        $data=file_get_contents($data);
        return json_decode($data, true);
    }



    /*
    *   修改密码
    *   参数：用户uid类型，uid，新密码
    */
    public function setPassWord($type,$uid,$newPassWord)
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET PassWord=? WHERE WxUid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET PassWord=? WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET PassWord=? WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET PassWord=? WHERE AuId=?");
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        $npwd=$this -> passWordMD5($newPassWord);
        $sql->bind_param("ss",$npwd,$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'修改密码错误!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
        }
    }

    /*
        *   修改等级
        *   参数：用户uid类型，uid，新密码
        */
    public function setRank($auid,$number)
    {
        $sql = $this->mysqli->prepare("UPDATE angeli_user SET Rank=Rank+? WHERE AuId=?");
        $sql->bind_param("ii",$number,$auid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'升级错误!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
        }
    }

    /*
    *   修改邮箱地址
    *   参数：用户uid类型，uid，新邮箱
    */
    public function setEmail($type,$uid,$newEmail)
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET UserEmail=? WHERE WxUid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET UserEmail=? WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET UserEmail=? WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET UserEmail=? WHERE AuId=?");
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        $sql->bind_param("ss",$newEmail,$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'修改邮箱错误!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
        }
    }

    /*
    *   修改头像
    *   参数：用户uid类型，uid，新头像URL
    */
    public function setAvatar($type,$uid,$newAvatar)
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET AvatarUrl=? WHERE WxUid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET AvatarUrl=? WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET AvatarUrl=? WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET AvatarUrl=? WHERE AuId=?");
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        $sql->bind_param("ss",$newAvatar,$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'修改头像错误!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
        }
    }


    /*
    *   修改简介
    *   参数：用户uid类型，uid，新简介
    */
    public function setSynopsis($type,$uid,$newSynopsis)
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Synopsis=? WHERE WxUid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Synopsis=? WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Synopsis=? WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Synopsis=? WHERE AuId=?");
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        $sql->bind_param("ss",$newSynopsis,$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'修改简介错误!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
        }
    }


    /*
    *   修改昵称
    *   参数：用户uid类型，uid，新昵称
    */
    public function setUserName($type,$uid,$newUserName)
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET UserName=? WHERE WxUid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET UserName=? WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET UserName=? WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET UserName=? WHERE AuId=?");
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return '服务器错误';
                break;
        }
        if(!$this->checkUserName($newUserName)){
            return '用户名已存在';
        }
        $sql->bind_param("ss",$newUserName,$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            return '服务器错误';
        }else{
            $sql="INSERT INTO angeli_config_user (auid,First_vip,upname) VALUES ($uid,0,0)  ON DUPLICATE KEY UPDATE upname=upname+1";
            $result=$this->mysqli->query($sql) or die($this->mysqli->error);
            return 0;
        }
    }


    /**
     * 修改手机号
     * @param $auid  欲修改的用户ID
     * @param $newPhone 新的手机号
     * @return bool
     */
    public function setUserPhone($auid,$newPhone)
    {
       $sql="UPDATE angeli_user SET Phone=$newPhone WHERE AuId=$auid";
       //echo $sql;
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                return true;
            }else{
                return FALSE;
            }
        }
    }

    /**
     * 绑定openID以及unionid
     * @param $auid  欲修改的用户ID
     * @param $openID 新的手机号
     * @param $unionid
     * @return bool
     */
    public function setUserWX($auid,$openID,$unionid)
    {
        $sql="UPDATE angeli_user SET wxopenid='$openID',WxUid='$unionid' WHERE AuId=$auid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            echo $this->mysqli->error;
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                return true;
            }else{
                echo $this->mysqli->error;
                return FALSE;
            }
        }
    }

    /*
     * 创建订单
     */
    public function createOrder($auid,$name,$fee,$number,$wxOpenId,$orderId){
        $sql = $this->mysqli->prepare("INSERT INTO angeli_pay (auid,wxOpenId,orderId,name,number,payFee,orderTime,payStatus) VALUES (?,?,?,?,?,?,?,?)");
        $time=time();
        $status='待支付';
        $sql->bind_param("isssiiis",$auid,$wxOpenId,$orderId,$name,$number,$fee,$time,$status);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'操作失败！'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{

            return TRUE;
        }
    }

    /*
     * 更新订单
     */
    public function upOrder($wxOrderId,$endTime,$openId,$orderId){
        $sql = $this->mysqli->prepare("UPDATE angeli_pay SET wxOrderId=?,timeEnd=?,payStatus=? WHERE  orderId=?");
        $time=time();
        $status='已支付';
        $sql->bind_param("siss",$wxOrderId,$endTime,$status,$orderId);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'操作失败！'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{

            return TRUE;
        }
    }

    /*
    * 更新系统处理状态
    */
    public function upSystemStatus($orderId,$status){
        $sql = $this->mysqli->prepare("UPDATE angeli_pay SET systemStatus=? WHERE orderId=?");
        $time=time();
        $sql->bind_param("ss",$status,$orderId);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            return false;
        }else{
            return TRUE;
        }
    }

    /*
     * 查询积分
     */
    public function getJifen($auid)
    {
        $sql = "SELECT * FROM angeli_user WHERE AuId='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $data = array(
                        'auid' =>$row["auid"],
                        'guanzhu' =>$row["FollowedCount"],
                        'fensi' =>$row["FollowerCount"],
                        'Zhongcao'=>$row['ZhongcaoCount'],
                        'Points' =>$row["Points"],
                        'Rank'=>$row["Rank"]
                    );
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    /*
     * 查询订单
     */
    public function getOrder($order)
    {

        $sql = "SELECT * FROM angeli_pay WHERE orderId='$order'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $data = array(
                        'auid' =>$row["auid"],
                        'wxOpenId' =>$row["wxOpenId"],
                        'orderId' =>$row["orderId"],
                        'name'=>$row['name'],
                        'number' =>$row["number"],
                        'payFee'=>$row["payFee"],
                        'orderTime' =>$row["orderTime"],
                        'payStatus' =>$row["payStatus"]
                    );
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    /*
     * 查询订单和是否是VIP，用于VIP充值成功后查询
     */
    public function getOrderAndVip($orderId,$uid)
    {

        $sql = "SELECT * FROM angeli_pay WHERE orderId='$orderId' AND auid='$uid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $order = array(
                        'auid' =>$row["auid"],
                        'wxOpenId' =>$row["wxOpenId"],
                        'orderId' =>$row["orderId"],
                        'name'=>$row['name'],
                        'number' =>$row["number"],
                        'payFee'=>$row["payFee"],
                        'orderTime' =>$row["orderTime"],
                        'payStatus' =>$row["payStatus"],
                        'userInfo'=>$this->getUserInfo('auid',$uid)
                    );
                }
                return $order;
            }else{
                return false;
            }
        }

    }

    /*
    *   修改手机号
    *   参数：用户uid类型，uid，新手机号
    */
    public function setPhone($type,$uid,$newPhone)
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Phone=? WHERE WxUid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Phone=? WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Phone=? WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Phone=? WHERE AuId=?");
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        if(!$this->checkPhone($newPhone)){
            $outmsg = array('code' =>'0','msg'=>'该手机号已经被人抢注了哦','data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }
        $sql->bind_param("ss",$newPhone,$uid);
        $sql->execute();


        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'修改手机号错误!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
        }
    }

    /*
    *   删除用户（从数据库里删除）
    *   参数：用户uid类型，uid
    */
    public function deleteUser($type,$uid)
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("DELETE FROM angeli_user WHERE WxUid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("DELETE FROM angeli_user WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("DELETE FROM angeli_user WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("DELETE FROM angeli_user WHERE AuId=?");
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        $sql->bind_param("s",$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'操作失败！'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
        }
    }
    /*
    *   设置/解除封禁用户，默认为封禁
    *   参数：用户uid类型，uid，封禁状态1正常使用，2禁止登录封禁；禁封结束时间；封禁原因
    */
    public function setBan($type,$uid,$Status="2",$endtime="1923279132",$BanDeadline="违规操作")
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Status=?, BanDeadline=?,BanEndTime=? WHERE WxUid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Status=?, BanDeadline=?,BanEndTime=? WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Status=?, BanDeadline=?,BanEndTime=? WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Status=?, BanDeadline=?,BanEndTime=? WHERE AuId=?");
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        $sql->bind_param("ssss",$Status,$BanDeadline,$endtime,$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'操作失败！'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
        }
    }

    /*
    *   添加用户
    *   用户名，密码，姓名,手机号，微信ID，邮箱，账号类型，注册IP
    */
    public function addUser($userName,$passWord="angeli",$gender,$phone='',$wxid,$unionid,$email='',$type='1',$ipCreated='127.0.0.1',$AvatarUrl="1")
    {
        if(!$this->checkUserName($userName))
        {
            $userName=$userName.mt_rand(0,9999);
        }
        if(!$this -> checkPhone($phone))
        {
            $outmsg = array('code' =>'0','msg'=>'手机号已被绑定!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }
        if($AvatarUrl=="1"){
            if($AvatarUrl=="1"){
                $AvatarUrl="https://sz.oss.data.angeli.top/system/11.png";
            }else{
                $AvatarUrl="https://sz.oss.data.angeli.top/system/21.png";
            }
        }
        $time=time();
        $passWord=$this -> passWordMD5($passWord);
        $sql="INSERT INTO angeli_user (UserName, PassWord,UserEmail,Phone,WxUid,Type,Status,DateCreated,IPCreated,Gender,AvatarUrl,wxopenid)     
              VALUES('$userName', '$passWord','$email','$phone','$unionid','$type','1','$time','$ipCreated','$gender','$AvatarUrl','$wxid')";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            $outmsg = array('code' =>'0','msg'=>'系统错误!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){

                $outmsg = array('code' =>'1','msg'=>'注册成功！'.$this->mysqli->error,'data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
            }else{
                $outmsg = array('code' =>'0','msg'=>'系统错误！'.$this->mysqli->error,'data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /*获取种草书*/
    public function ZhongcaoCount($auid)
    {
        $sql="select count(*) as numbera FROM angeli_favorite where AuthorId=$auid";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return 0;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $data=$row['numbera'];
                }
                return $data;
            }else{
                return 0;
            }
        }
    }

    /*获取种草书 类型一为获取粉丝数，类型二为获取已关注数*/
    public function getFollowerCount($auid,$type=1)
    {
        if($type==1){
            $sql="select count(*) as numbera FROM angeli_guanzhu where beiguanzhu=$auid";
        }else{
            $sql="select count(*) as numbera FROM angeli_guanzhu where guanzhu=$auid";
        }
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return 0;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $data=$row['numbera'];
                }
                return $data;
            }else{
                return 0;
            }
        }
    }
    /*
    *   获取用户信息
    *   参数：查询关键字类型，查询关键字
    */
    public function getUserInfo($type,$keyword,$auid='0')
    {
        switch ($type) {
            case 'wxid':
                $sql="SELECT * from angeli_user WHERE WxUid='$keyword'";
                break;
            case 'phone':
                $sql="SELECT * from angeli_user WHERE Phone='$keyword'";
                break;
            case 'username':
                $sql="SELECT * from angeli_user WHERE UserName='$keyword'";
                break;
            case 'email':
                $sql="SELECT * from angeli_user WHERE UserEmail='$keyword'";
                break;
            case 'auid':
                $sql="SELECT * from angeli_user WHERE AuId='$keyword'";
                break;
            case 'openid':
                $sql="SELECT * from angeli_user WHERE wxopenid='$keyword'";
                break;
            case 'unionid':
                $sql="SELECT * from angeli_user WHERE WxUid='$keyword'";
                break;
            default:
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{

            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $data = array(
                        'Auid' =>$row["AuId"],
                        'UserName' =>$row["UserName"],
                        'Email' =>$row["UserEmail"],
                        'Phone' =>$row["Phone"],
                        'Wxid' =>$row["WxUid"],
                        'wxOpenId'=>$row["wxopenid"],
                        'Gender'=>$row['Gender'],
                        'UserType' =>$this->isVip($row["VIPEndTime"]),
                        'VIPEndTime' =>$this->getOverDay($row["VIPEndTime"]),
                        'Status' =>$row["Status"],
                        'BanDeadline' =>$row["BanDeadline"],
                        'DateCreated' =>$row["DateCreated"],
                        'IPCreated' =>$row["IPCreated"],
                        'AvatarUrl' =>$row["AvatarUrl"],
                        'Synopsis' =>$row["Synopsis"],
                        'FollowedCount' =>$this->getFollowerCount($row["AuId"],2),
                        'FollowerCount' =>$this->getFollowerCount($row["AuId"],1),
                        'ZhongcaoCount' =>$this->ZhongcaoCount($row["AuId"]),
                        'Points' =>$row["Points"],
                        'Aglc' =>$row["Aglc"],
                        'Rank' =>$row["Rank"],
                        'guanzhu'=>$this->isGuanzhu($auid,$row["AuId"])
                    );
                }
                return $data;
            }else{
                $data = array(
                    'Auid' =>'-1',
                    'UserName' =>'该用户已注销',
                    'Email' =>'-1',
                    'Phone' =>'-1',
                    'Wxid' =>'-1',
                    'wxOpenId'=>'-1',
                    'Gender'=>'-1',
                    'UserType' =>'-1',
                    'VIPEndTime' =>'-1',
                    'Status' =>'-1',
                    'BanDeadline' =>'-1',
                    'DateCreated' =>'-1',
                    'IPCreated' =>'-1',
                    'AvatarUrl' =>'https://sz.oss.data.angeli.top/angeli/QQ%E6%88%AA%E5%9B%BE20190911202158.png',
                    'Synopsis' =>'该用户不存在或已被注销',
                    'FollowedCount' =>'-1',
                    'FollowerCount' =>'-1',
                    'ZhongcaoCount' =>'-1',
                    'Points' =>'-1',
                    'Aglc' =>'-1',
                    'Rank' =>'0',
                    'guanzhu'=>false
                );

                return $data;

                //========原来的是直接返回False
                //return false;
            }
        }
    }

    /*
     * 获取本周排行榜
     */
    public function getWeekTop($auid){

        $sdefaultDate = date("Y-m-d");
        $first=1;
        $w=date('w',strtotime($sdefaultDate));

        $beginThisweek=strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days');
        $endtemp=date('Y-m-d',$beginThisweek);
        $endThisweek=strtotime("$endtemp +7 days");
        $sql="SELECT *,count(*) as lll FROM angeli_favorite WHERE addTime>=$beginThisweek AND addTime<=$endThisweek AND `show`=0 GROUP BY AuthorId ORDER BY lll desc LIMIT 100 ";
        //echo $sql.'<br>';
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{

            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $index=$index+1;
                    if($row['AuthorId']==$auid){
                       $ddd=$index;
                       $dd=$row['lll'];
                    }
                    $d = array(
                        'api' =>$row["lll"],
                        'AuId' =>$this->getInfo($row['AuthorId']),
                        'isMe'=>$row['AuthorId']==$auid?'true':'false'
                    );
                    $data['data'][]=$d;
                }
                $data['api']=isset($ddd)?$ddd:'0';
                $data['count']=isset($dd)?$dd:'0';
                return $data;
            }else{
                return FALSE;
            }
        }


    }

    /*
   *   获取好友信息
   *   参数：查询关键字类型，查询关键字
   */
    public function getFInfo($auid,$keyword)
    {
        $sql="SELECT * from angeli_user WHERE AuId='$keyword'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{

            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $data = array(
                        'Auid' =>$row["AuId"],
                        'UserName' =>$row["UserName"],
                        'Email' =>$row["UserEmail"],
                        'Phone' =>"177******91",
                        'Wxid' =>"***************",
                        'wxOpenId'=>"***************",
                        'Gender'=>$row['Gender'],
                        'UserType' =>$this->isVip($row["VIPEndTime"]),
                        'VIPEndTime' =>$this->getOverDay($row["VIPEndTime"]),
                        'Status' =>$row["Status"],
                        'BanDeadline' =>$row["BanDeadline"],
                        'DateCreated' =>$row["DateCreated"],
                        'AvatarUrl' =>$row["AvatarUrl"],
                        'Synopsis' =>$row["Synopsis"],
                        'FollowedCount' =>$row["FollowedCount"],
                        'FollowerCount' =>$row["FollowerCount"],
                        'ZhongcaoCount' =>$row["ZhongcaoCount"],
                        'Aglc' =>$row["Aglc"],
                        'Rank' =>$row["Rank"],
                    );
                }
                return $data;
            }else{
                return FALSE;
            }
        }


    }

    /*
    *   获取用户列表到数组里
    *
    */
    public function getUserList()
    {
        $sql="SELECT * from angeli_user";
        $result = $this->mysqli->query($sql);
        if ($result->num_rows > 0) {
            $data =[];
            while($row = $result->fetch_assoc()) {
                $ddata = array(
                    'AuId' =>$row['AuId'],
                    'NickName' =>$row['UserName'],
                    'Mail' =>$row['UserEmail'],
                    'Phone' =>$row['Phone'],
                    'WeiXinUID' =>$row['WxUid'],
                    'UserType' =>$this->isVip($row["VIPEndTime"]),
                    'VIPEndTime' =>date("Y-d-m H:i:s",$row["VIPEndTime"]),
                    'Status' =>$row['Status'],
                    'BanDeadline' =>$row['BanDeadline'],
                    'DateCreated' =>$row['DateCreated'],
                    'IPCreated' =>$row['IPCreated'],
                    'LastLogonTime' =>$row['LastLogonTime'],
                    'LastLogonIP' =>$row['LastLogonIP'],
                    'AvatarUrl' =>$row['AvatarUrl'],
                    'Synopsis' =>$row['Synopsis'],
                    'FollowedCount' =>$row['FollowedCount'],
                    'FollowerCount' =>$row['FollowerCount'],
                    'ZhongcaoCount' =>$row['ZhongcaoCount'],
                    'Points' =>$row['Points'],
                    'Aglc' =>$row['Aglc'],
                    'Rank' =>$row['Rank'],
                );
                $data[]=$ddata;
            }
            $outmsg = array('code' =>'0','msg'=>'OK!'.$this->mysqli->error,'data'=>$data);
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        } else {
            $outmsg = array('code' =>'0','msg'=>'没有找到数据!'.$this->mysqli->error,'data'=>$data);
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }
    }


    /*
    *   登录账号
    *   登录wxid或者手机号；密码
    *
    */
    public function Login($uid,$passWord)
    {
        if($this ->isPhone($uid))
        {
            $sql = $this->mysqli->prepare("SELECT AuId,WxUid,Phone,Status,UserName,BanEndTime,BanDeadline,AvatarUrl,VIPEndTime from angeli_user WHERE Phone=? AND PassWord=?");
            $outmsg = array('code' =>'0','msg'=>'登录手机号或者密码不正确'.$this->mysqli->error,'data'=>'');
        }else{
            $sql = $this->mysqli->prepare("SELECT AuId,WxUid,Phone,Status,UserName,BanEndTime,BanDeadline,AvatarUrl,VIPEndTime from angeli_user WHERE WxUid=? AND PassWord=?");
            $outmsg = array('code' =>'0','msg'=>'登录微信或者密码不正确'.$this->mysqli->error,'data'=>'');
        }
        if(!$sql){
            $outmsg = array('code' =>'0','msg'=>'执行数据失败！','data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }
        $passWord=$this->passWordMD5($passWord);
        $sql->bind_param("ss",$uid,$passWord);
        $sql->bind_result($AuId,$WxUid,$Phone,$Status,$UserName,$BanEndTime,$BanDeadline,$AvatarUrl,$isVip);
        $sql->execute();
        $sql->store_result();
        if($sql->num_rows<1)
        {
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            while ($sql->fetch())
            {
                $d = array('Auid' =>$AuId,
                    'WxUid'=>$WxUid,
                    'Phone' => $Phone,
                    'Status' => $Status,
                    'BanEndTime' => $BanEndTime,
                    'BanDeadline' => $BanDeadline,
                    'AvatarUrl' => $AvatarUrl,
                    'NickName'=>$UserName,
                    'UserType' =>$this->isVip($isVip),
                    'VIPEndTime' =>date("Y-d-m H:i:s",$isVip)
                );
            }
            $this->setUserLogin($AuId);
            return $d;
        }
    }

    /**设置用户登录更新IP以及时间
     * @param $auid
     */
    public function setUserLogin($auid)
    {
        $loginIp=$_SERVER['REMOTE_ADDR'];
        $time=time();
        $sql="UPDATE angeli_user SET LastLogonTime='$time',LastLogonIP='$loginIp' WHERE AuId='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
    }

    //查询VIP时间戳
    public function qVipTime($auid)
    {
        $sql="SELECT * from angeli_user WHERE AuId='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($row=$this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $endtime=$row['VIPEndTime'];
                }
                return $endtime;
            }else{
                return FALSE;
            }
        }
    }



    /**
     * 延期VIP，主要是为了给下面的函数填坑
     * $auid:操作账号ID
     * $time:欲增加多少的Vip时间,时间戳
     * @return true/false
     */
    public function setLongVip($auid,$time)
    {
        $vipTime=$this->qVipTime($auid);
        $vip=date('Y-m-d H:i:s',$vipTime);//将VIP时间转换为时间格式
        $timeCha=$vipTime-time();  //VIP到期时间减去当前时间，获得时间差
        //如果时间差小于等于0则说明当前用户不是VIP，则需要将vip到期时间转为当前时间戳
        if($timeCha<=0){
            $time=strtotime($time);
            $sql="UPDATE angeli_user SET Type='VIP', VIPEndTime=$time WHERE AuId=$auid";
        }else{
            //时间差大于0，则说明这个用户是VIP，在原来的事件上，加上时间就是新的到期时间
            $time=strtotime($time,$vip);
            $sql="UPDATE angeli_user SET Type='VIP', VIPEndTime=$time WHERE AuId=$auid";
        }
        $result=$this->mysqli->query($sql);
        if(!$result){
            return 0;
        }else{
            if($row=$this->mysqli->affected_rows>0){

                return true;
            }else{
                return FALSE;
            }
        }


    }


    /*
    *   增加VIP
    *   UID类型，用户UID，到期时间(如果时间戳比现在时间少，则撤回会员，如果时间戳在此时间多，就是VIP)
    */
    public function setVIP($type,$uid,$endTime)
    {
        switch ($type) {
            case 'wxid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Type=?, VIPEndTime=? WHERE wxopenid=?");
                break;
            case 'phone':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Type=?, VIPEndTime=? WHERE Phone=?");
                break;
            case 'username':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Type=?, VIPEndTime=? WHERE UserName=?");
                break;
            case 'auid':
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Type=?, VIPEndTime=? WHERE AuId=?");
                break;
            default:
                return false;
                break;
        }

        $usertype='VIP';
        $sql->bind_param("sss",$usertype,$endTime,$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            return false;
        }else{
            return true;
        }
    }


    /*
    *   修改积分
    *   用户ID，修改类型（+为增加积分，-为减少积分），操作数量，改动说明
    */
    public function setPoints($uid,$type='+',$num,$note='无特别说明')
    {
        if($type=="-")
        {
            $sql = $this->mysqli->prepare("UPDATE angeli_user SET Points=Points-? WHERE AuId=?");
        }else{
            $sql = $this->mysqli->prepare("UPDATE angeli_user SET Points=Points+? WHERE AuId=?");
        }

        $sql->bind_param("ii",$num,$uid);
        $sql->execute();
        $time=time();
        if($sql->affected_rows<1)
        {
            $this->errorLog($this->mysqli->error);
            return false;
        }else{
            if($note=="登录赠送积分"){
                if(!$this->checkLogin($uid)){
                    $sql="INSERT INTO angeli_jifen(type,number,opentime,note,auid) VALUES ('$type','$num','$time','$note','$uid')";
                    $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                    if($this->mysqli->affected_rows<1){
                        //表示操作失败
                        $this->errorLog($this->mysqli->error);
                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return TRUE;
                }
            }else{
                $sql="INSERT INTO angeli_jifen(type,number,opentime,note,auid) VALUES ('$type','$num','$time','$note','$uid')";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    $this->errorLog($this->mysqli->error);
                    return false;
                }else{
                    return true;
                }
            }
        }
    }



    /*
    *   发送帖子
    *   用户ID，内容，标签，帖子类型，赏金数量，悬赏结束时间，图片id
    *
    */
    public function addPost($uid,$content,$tag,$postType='1',$postSum='0',$endTime='0',$picId)
    {
        $sql = $this->mysqli->prepare("INSERT INTO angeli_posts (AuthorId,Content,Tag,PsotDate,PostType,PostSum,RewardEndTime,PictureId) VALUES (?,?,?,?,?,?,?,?)");
        $time=time();
        $sql->bind_param("isssssis",$uid,$content,$tag,$time,$postType,$postSum,$endTime,$picId);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'操作失败！'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            $openid=$this->getpostpostid($uid,$content,$tag);
            $this->addUserLog($uid,'发布帖子',$openid,$this->systeminfo['phonebrand'],$this->systeminfo['phonesystem']);
            return TRUE;
        }
    }

    /**
     * @param $auid 用户id
     * @param $txt  帖子内容
     * @param $tag   标签
     * @return bool
     */
    public function getpostpostid($auid,$txt,$tag)
    {
        $sql="SELECT * FROM angeli_posts WHERE AuthorId=$auid AND Content='$txt' AND Tag=$tag";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            return false;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $id=$row['PostsId'];
                }
                return $id;
            }else{
                return FALSE;
            }
        }
    }

    /*
    *   获取帖子列表
    *   帖子类型，获取数目,页码，排序规则字段
    */
    public function getPosts($type="new",$count=20,$page,$tag="4",$auid='0'){
        $pageNum=($page-1)*$count;
        //return $sql;
        switch ($type){
            case 'new':
                $sql = "SELECT * FROM angeli_posts WHERE PostType='1' AND IsLock<>'2'   ORDER BY PsotDate  DESC  LIMIT $pageNum, $count";
                break;
            case 'hot':
                $sdefaultDate = date("Y-m-d");
                $first=1;
                $w=date('w',strtotime($sdefaultDate));
                $beginThisweek=strtotime("$sdefaultDate -".($w ? $w - $first : 6).' days');
                $endtemp=date('Y-m-d',$beginThisweek);
                $endThisweek=strtotime("$endtemp +7 days");
                $sql="SELECT PostsId,count(*) as lll FROM angeli_favorite WHERE addTime>=$beginThisweek AND addTime<=$endThisweek AND `show`=0 GROUP BY AuthorId ORDER BY lll desc LIMIT 100 ";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                if(!$result){
                    //表示操作失败
                    return FALSE;
                }else{

                    if($this->mysqli->affected_rows>0){
                        $data=[];
                        while($row = $result->fetch_assoc()) {
                            $postData=$this->getPostInfo($row['PostsId'],$auid);
                            if($postData['IsLock']!='2'){
                                array_push($data,$postData);
                            }

                        }
                        return $data;
                    }else{
                        return FALSE;
                    }
                }

                //$sql = "SELECT * FROM angeli_posts WHERE PostType='1' AND IsLock<>'2' AND   ORDER BY ZhongcaoCount  DESC  LIMIT $pageNum, $count";
                break;
            case 'classPost':
                $sql = "SELECT * FROM angeli_posts WHERE PostType='1' AND IsLock<>'2' AND Tag='$tag'  PsotDate  DESC  LIMIT $pageNum, $count";
                break;
            case 'guanzhu':
                $sql="SELECT angeli_posts.* FROM angeli_posts 
                      INNER JOIN angeli_guanzhu ON (angeli_posts.AuthorId=angeli_guanzhu.beiguanzhu) 
                      where angeli_guanzhu.guanzhu=$auid 
                      AND PostType='1' 
                      AND IsLock<>'2' 
                      ORDER BY PsotDate  
                      DESC  LIMIT $pageNum, $count";
                break;
            default:
                $sql = "SELECT * FROM angeli_posts WHERE PostType='1' AND IsLock<>'2'   ORDER BY PsotDate  DESC  LIMIT $pageNum, $count";
                break;
        }
        // echo $sql;
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $da = array(
                        'PostsId' =>$row["PostsId"],
                        'AuthorId' =>$row["AuthorId"],
                        'Content' =>$row["Content"],
                        'Tag' =>$this->getClassinfo($row["Tag"]),
                        'PsotDate' =>$this->uc_time_ago($row["PsotDate"]),
                        'ZhongcaoCount' =>$row["ZhongcaoCount"],
                        'ViewCount' =>$row["ViewCount"],
                        'PostType' =>$row["PostType"],
                        'PostSum' =>$row["PostSum"],
                        'RewardEndTime' =>$row["RewardEndTime"],
                        'PictureId' =>explode(',',$row["PictureId"]),
                        'AuthorInfo'=>$this->getInfo($row["AuthorId"]),
                        'Give'=>$this->checkGive($auid,$row["PostsId"])
                    );
                    $data[]=$da;

                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    //获取分类列表
    public function getClassList(){
        $sql = "SELECT * FROM angeli_class ORDER BY ClassOrder ASC";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $data= [];
                while($row = $result->fetch_assoc()) {
                    $da = array(
                        'ClassId' =>$row["ClassId"],
                        'ClassName' =>$row["ClassName"],
                        'ClassImage' =>$row["ClassImage"],
                        'ClassOrder' =>$row["ClassOrder"],
                        'ImageMax'=>$row["ClassMaxImage"]
                    );
                    $data[]=$da;
                }
                return $data;
            }else{
                return FALSE;
            }
        }
    }

    //获取帖子详情
    public function getPostInfo($postID,$uid='6666'){
        if(!is_numeric($postID)){
            $outmsg = array('code' =>'0','msg'=>'系统错误，帖子ID必须为数字','data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }
        $sql = "SELECT * FROM angeli_posts WHERE PostsId='$postID'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            $outmsg = array('code' =>'0','msg'=>'系统错误，Error：'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            if($this->mysqli->affected_rows>0){
                $classid='';
                while($row = $result->fetch_assoc()) {
                    $classid=$row["Tag"];
                    $data = array(
                        'PostsId' =>$row["PostsId"],
                        'AuthorId' =>$row["AuthorId"],
                        'Content' =>$row["Content"],
                        'Tag' =>$this->getClassinfo($row["Tag"]),
                        'PsotDate' =>$this->uc_time_ago($row["PsotDate"]),
                        'ZhongcaoCount' =>$row["ZhongcaoCount"],
                        'ViewCount' =>$row["ViewCount"],
                        'PostType' =>$row["PostType"],
                        'PostSum' =>$row["PostSum"],
                        'RewardEndTime' =>$row["RewardEndTime"],
                        'PictureId' =>explode(',',$row["PictureId"]),
                        'AuthorInfo'=>$this->getInfo($row["AuthorId"]),
                        'Give'=>$this->checkGive($uid,$row["PostsId"]),
                        'IsLock'=>$row['IsLock']
                    );
                }
                $sql="UPDATE angeli_posts SET ViewCount=ViewCount+1 WHERE PostsId='$postID'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                $sql="UPDATE angeli_class SET ViewCount=ViewCount+1 WHERE ClassId='$classid'";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                return $data;
            }else{
                return FALSE;
            }
        }
    }



    /*==============================================================================
    *   函数区域
    *
    *===============================================================================*/

    /*
     * 计算时间差
     */
    function getOverDay($vipTime){
        if(!is_numeric($vipTime)){
            $vipTime=0;
        }
        $dtime=time();
        $time=$vipTime-$dtime;
        return $time/86400;
    }


    /*
     * 生成订单号
     */
    function getOrderId(){
        $time=$this->getMillisecond();
        return 'LHSD'.$time.mt_rand(1000,9999);
    }
    /*
     * 取毫秒级时间戳
     */
    function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

    /* 查询今天评论赠送安个利个数
     * return 不能在赠送就返回真，否则返回假
     */
    public function checkplangeli($auid)
    {
        $starTime=strtotime(date("Y-m-d"));
        $endTime=$starTime+24*60*60;
        $sql = "SELECT count(*) as nunumber FROM angeli_jifen WHERE auid ='$auid' AND opentime>'$starTime' AND opentime<'$endTime' AND note='评论奖励积分'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $count=$row['nunumber'];
                }
                if($count>=5){
                    return TRUE;
                }else{
                    return FALSE;
                }
            }else{
                return FALSE;
            }
        }
    }

    /* 查询今天是否签到
     * return 已经签到返回真，否则false
     */
    public function checkLogin($auid)
    {
        $starTime=strtotime(date("Y-m-d"));
        $endTime=$starTime+24*60*60;
        $sql = "SELECT * FROM angeli_jifen WHERE auid ='$auid' AND opentime>'$starTime' AND opentime<'$endTime' AND note='登录赠送积分'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }

    /*
         *  根据回复ID查询评论内容
         *  $postid 文章ID
         *  @return  array
         *
         */
    public function huifuTotxt($ID){
        $sql="SELECT * from angeli_comments WHERE CommentsId='$ID'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return '';
        }else{
            if($row=$this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $auid=$row['CommentsContent'];
                }
                return $auid;
            }else{
                return '';
            }
        }
    }


    /*
     *  根据文章ID查询用户信息
     *  $postid 文章ID
     *  @return  array
     *
     */
    public function postGetUserinfo($postID){
        $sql="SELECT * from angeli_posts WHERE PostsId='$postID'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($row=$this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $auid=$row['AuthorId'];
                }
                return $auid;
            }else{
                return FALSE;
            }
        }
    }


    /*
    *   检查用户信息，这些字段都是唯一值
    *   用户名，手机号，邮箱，微信ID
    *
    */
    public function checkGive($auid,$postId){
        $sql = "SELECT * FROM angeli_favorite WHERE PostsId ='$postId' AND AuId='$auid' AND `show`=0";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }
    /*
     * 检查是已经打赏
     */
    public function checkDashang($auid,$postId){
        $sql = "SELECT * FROM angeli_dashang WHERE postId ='$postId' AND fromAuid='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }
    public function checkUserName($userName)
    {
        $sql="SELECT * from angeli_user WHERE UserName='$userName'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }
    public function checkPhone($Phone)
    {
        $sql="SELECT * from angeli_user WHERE Phone='$Phone'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }
    public function checkUnionid($unionid)
    {
        $sql="SELECT * from angeli_user WHERE WxUid='$unionid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }
    public function checkEmail($UserEmail)
    {
        $sql="SELECT * from angeli_user WHERE UserEmail='$UserEmail'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($this->mysqli->affected_rows>0){
                return FALSE;
            }else{
                return TRUE;
            }
        }
    }
    function getInfo($audi,$auid='0'){
        $sql="SELECT * from angeli_user WHERE AuId='$audi'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($row=$this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $outmsg = array(
                        'AuthorName' =>$row['UserName'],
                        'AuthorAvatarUrl'=>$row['AvatarUrl'],
                        'Auid'=>$row['AuId'],
                        'ms'=>$row['Synopsis'],
                        'VIPEndTime' =>$this->getOverDay($row["VIPEndTime"]),
                        'isGz'=>$this->isGuanzhu($auid,$row["AuId"])
                    );
                }
                return $outmsg;
            }else{
                $outmsg = array(
                    'AuthorName' =>'该用户已注销',
                    'AuthorAvatarUrl'=>'https://sz.oss.data.angeli.top/angeli/QQ%E6%88%AA%E5%9B%BE20190911202158.png',
                    'Auid'=>'-1',
                    'VIPEndTime' =>'-1'
                );
                return $outmsg;
            }
        }

    }

    //获取分类信息
    public function getClassinfo($classId){
        $sql="SELECT * from angeli_class WHERE ClassId='$classId'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($row=$this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $outmsg = array(
                        'ClassId' =>$row['ClassId'],
                        'ClassName'=>$row['ClassName'],
                        'ClassImage'=>$row['ClassImage'],
                        'ClassImageMax'=>$row['ClassMaxImage'],
                        'ClassOrder'=>$row['ClassOrder'],
                        'ViewCount'=>$row['ViewCount'],
                        'canyu'=>$this->getClassPostConut($classId)
                    );
                }
                return $outmsg;
            }else{
                return FALSE;
            }
        }
    }


    function getClassPostConut($classId){
        $sql="SELECT COUNT(*) as number FROM angeli_posts where Tag='$classId'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if($row=$this->mysqli->affected_rows>0){
            while($row = $result->fetch_assoc()) {
                return $row['number'];
            }

        }else{
            return FALSE;
        }
    }

    /*
     * 检查是否为VIP
     */
    public function vipIs($auid){
        $sql="SELECT * from angeli_user WHERE AuId='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        if(!$result){
            //表示操作失败
            return FALSE;
        }else{
            if($row=$this->mysqli->affected_rows>0){
                while($row = $result->fetch_assoc()) {
                    $dd=$this->getOverDay($row["VIPEndTime"]);
                }
                if($dd>0){
                    return true;
                }
            }else{
                return FALSE;
            }
        }
    }

    //写到错误日志
    function errorLog($str)
    {
        $path = "errorLog.txt";
        $handle = fopen($path,"a+");
        fwrite($handle,'['.date('Y-m-d H:i:s').']'.$str.PHP_EOL);
        fclose($handle);
    }

    function passWordMD5($passWord)
    {
        return md5(md5('BuTing'.$passWord.'angeli').'xfhl');
    }
    function isPhone($str)
    {
        $preg_phone='/^1[345789]\d{9}$/ims';
        if(preg_match($preg_phone,$str)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function isVip($VIPEndTime)
    {
        if($VIPEndTime>time()){
            return "true";
        }else{
            return "普通用户";
        }
    }
    function uc_time_ago($ptime) {
        date_default_timezone_set('PRC');
        //$ptime = strtotime($ptime);
        $etime = time() - $ptime;
        switch ($etime){
            case $etime <= 60:
                $msg = '刚刚';
                break;
            case $etime > 60 && $etime <= 60 * 60:
                $msg = floor($etime / 60) . ' 分钟前';
                break;
            case $etime > 60 * 60 && $etime <= 24 * 60 * 60:
                $msg = date('Ymd',$ptime)==date('Ymd',time()) ? '今天 '.date('H:i',$ptime) : '昨天 '.date('H:i',$ptime);
                break;
            case $etime > 24 * 60 * 60 && $etime <= 2 * 24 * 60 * 60:
                $msg = date('Ymd',$ptime)+1==date('Ymd',time()) ? '昨天 '.date('H:i',$ptime) : '前天 '.date('H:i',$ptime);
                break;
            case $etime > 2 * 24 * 60 * 60 && $etime <= 12 * 30 * 24 * 60 * 60:
                $msg = date('Y',$ptime)==date('Y',time()) ? date('m-d H:i',$ptime) : date('Y-m-d H:i',$ptime);
                break;
            default: $msg = date('Y-m-d H:i',$ptime);
        }
        return $msg;
    }
}
?>