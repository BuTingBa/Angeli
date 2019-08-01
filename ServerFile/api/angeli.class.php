<?php

/**
 * 安个利数据读写类
 * 作者：不停(张原粽)
 * 版权：深圳市鑫峰互联有限公司
 * 最后更新时间：2019年6月27日16:50:08
 */
class angeli
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

    /**
     * @return mysqli
     */
    public function markNoRead($auid)
    {
        $sql="UPDATE angeli_favorite SET mark=1 WHERE AuthorId='$auid'";
        $result=$this->mysqli->query($sql) or die($this->mysqli->error);
        //echo $sql;
        if($this->mysqli->affected_rows<1){
            //表示操作失败
            return false;
        }else{
            return true;
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


    /**查询我的未读消息数量
     * @return array
     */
    public function getMyNoreadNumber($auid)
    {
        $sql = "SELECT * FROM angeli_comments WHERE AuthorId='$auid' AND mark=0";
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
        $sql = "SELECT * FROM angeli_msg WHERE ToId='$auid' AND MsgStatus=0";
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

        $count=$pinglun+$Give+$Msg+$Guanzhu;

        $data=array(
            'pinglun'=>$pinglun,
            'Give'=>$Give,
            'Msg'=>$Msg,
            'Guanzhu'=>$Guanzhu,
            'count'=>$count
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
                return 2;
            }else{
                $sql="INSERT INTO angeli_favorite(AuId,PostsId,addTime,AuthorId) VALUES ('$auid','$postId','$time','$AuthorId')";
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
            $sql="DELETE FROM angeli_favorite WHERE PostsId ='$postId' AND AuId='$auid'";
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
        $sql = "SELECT * FROM angeli_favorite WHERE AuId='$auid' ORDER BY addTime DESC LIMIT $pageNum, $count";
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
        $sql = "SELECT * FROM angeli_posts WHERE AuthorId='$uid' ORDER BY PsotDate DESC  LIMIT $pageNum, $count";

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

            return TRUE;
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



    /**
     * @return 评论列表
     */
    public function getCommentList($postid,$count=20,$page="1")
    {
        $pageNum=($page-1)*$count;
        $sql = "SELECT * FROM angeli_comments WHERE PostId='$postid' ORDER BY Zan DESC  LIMIT $pageNum, $count";
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
                        'Time' =>$this->uc_time_ago($row["CommentsTime"]),
                        'Give' =>$row["Zan"],
                        'userinfo'=>$this->getInfo($row["CommentsFromUid"]),
                        'replyList'=>$this->getReplyList($row["CommentsId"])
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
        $sql = $this->mysqli->prepare("INSERT INTO angeli_comments (PostId,CommentsContent,CommentsFromUid,CommentsTime,AuthorId) VALUES (?,?,?,?,?)");
        $time=time();
        $sql->bind_param("isiii",$PostId,$Content,$auid,$time,$AuthorId);
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
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }
        if(!$this->checkUserName($newUserName)){
            $outmsg = array('code' =>'0','msg'=>'该用户名已经被人抢注了哦','data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }
        $sql->bind_param("ss",$newUserName,$uid);
        $sql->execute();
        if($sql->affected_rows<1)
        {
            $outmsg = array('code' =>'0','msg'=>'修改用户名错误!'.$this->mysqli->error,'data'=>'');
            return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
        }else{
            return TRUE;
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
            if($AvatarUrl=="1" || $AvatarUrl=="" || $AvatarUrl==null || $AvatarUrl==" "){
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

    /*
    *   获取用户信息
    *   参数：查询关键字类型，查询关键字
    */
    public function getUserInfo($type,$keyword)
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
                        'Gender'=>$row['Gender'],
                        'UserType' =>$this->isVip($row["VIPEndTime"]),
                        'VIPEndTime' =>date("Y-d-m H:i:s",$row["VIPEndTime"]),
                        'Status' =>$row["Status"],
                        'BanDeadline' =>$row["BanDeadline"],
                        'DateCreated' =>$row["DateCreated"],
                        'IPCreated' =>$row["IPCreated"],
                        'AvatarUrl' =>$row["AvatarUrl"],
                        'Synopsis' =>$row["Synopsis"],
                        'FollowedCount' =>$row["FollowedCount"],
                        'FollowerCount' =>$row["FollowerCount"],
                        'ZhongcaoCount' =>$row["ZhongcaoCount"],
                        'Points' =>$row["Points"],
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
        $time=time();
        $loginIp=$_SERVER['REMOTE_ADDR'];
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
            $sql="UPDATE angeli_user SET LastLogonTime='$time',LastLogonIP='$loginIp' WHERE AuId='$AuId'";
            $result=$this->mysqli->query($sql) or die($this->mysqli->error);
            return $d;
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
                $sql = $this->mysqli->prepare("UPDATE angeli_user SET Type=?, VIPEndTime=? WHERE WxUid=?");
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
                $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
                return json_encode($outmsg,JSON_UNESCAPED_UNICODE);
                break;
        }

        $usertype=$this->isVip($endTime);
        $sql->bind_param("sss",$usertype,$endTime,$uid);
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
            return false;
        }else{
            if(!$this->checkLogin($uid)){
                $sql="INSERT INTO angeli_jifen(type,number,opentime,note,auid) VALUES ('$type','$num','$time','$note','$uid')";
                $result=$this->mysqli->query($sql) or die($this->mysqli->error);
                if($this->mysqli->affected_rows<1){
                    //表示操作失败
                    return false;
                }else{
                    return true;
                }
            }else{
                return TRUE;
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

            return TRUE;
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
                $sql = "SELECT * FROM angeli_posts WHERE PostType='1' AND IsLock<>'2'   ORDER BY ZhongcaoCount  DESC  LIMIT $pageNum, $count";
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
    public function getPostInfo($postID,$uid){
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
                while($row = $result->fetch_assoc()) {
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
                        'Give'=>$this->checkGive($uid,$row["PostsId"])
                    );
                }
                $sql="UPDATE angeli_posts SET ViewCount=ViewCount+1 WHERE PostsId='$postID'";
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
        $sql = "SELECT * FROM angeli_favorite WHERE PostsId ='$postId' AND AuId='$auid'";
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
    function getInfo($audi){
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
                        'Auid'=>$row['AuId']
                    );
                }
                return $outmsg;
            }else{
                return FALSE;
            }
        }

    }

    //获取分类信息
    function getClassinfo($classId){
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
                        'ClassOrder'=>$row['ClassOrder']
                    );
                }
                return $outmsg;
            }else{
                return FALSE;
            }
        }
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
            return "VIP";
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