<?php
require_once('../config.php');
require_once('angeli.class.php');
session_start();
error_reporting(E_ALL^E_NOTICE);

//跨域解决方案

$allow_origin = array(
    'https://api.angeli.top',
    'http://www.angeli.top',
    'http://angeli.top'
);
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';  //跨域访问的时候才会存在此字段
if (in_array($origin, $allow_origin)) {
    header('Access-Control-Allow-Origin:' . $origin);
}

$post=new angeli($config);
if(empty($_GET['type'])){
    die('缺少请求类型！');
}
switch ($_GET['type']) {
    case 'addPost':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就想发帖？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $txt=textCheck($_REQUEST['txt']);
        if(checkText($txt)!==0){
            $outmsg = array('code' =>'2','msg'=>'有不恰当内容，请修改后再提交','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $data=$post->getJifen($_SESSION['Auid']);
        if($data['Points']<1){
            $outmsg = array('code' =>'2','msg'=>'安个利币不足，请充值或者做任务','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $add=$post->addPost($_SESSION['Auid'],$txt,$_POST['huati'],"1","0","0",$_POST['imageList']);
        if(!$add){
            die($add);
        }else{
            if($post->vipIs($_SESSION['Auid'])){
                $post->setPoints($_SESSION['Auid'],'-',1,"发布帖子");
            }else{
                $post->setPoints($_SESSION['Auid'],'-',2,"发布帖子");
            }
            $outmsg = array('code' =>'1','msg'=>'发帖成功！','data'=>$_POST['txt']);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        break;
    case 'outPostList':
        if(!$_SESSION['Auid'])
        {
            $auid=11;
        }
        if($_GET['postType']=="guanzhu"){
            if(!$_SESSION['Auid'])
            {
                $outmsg = array('code' =>'0','msg'=>'没有登录就不能查看这个','data'=>"");
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
            $auid=$_SESSION['Auid'];
            $postList=$post->getPosts(empty($_GET['postType'])?"new":$_GET['postType'],empty($_GET['count'])?15:$_GET['count'],empty($_GET['page'])?1:$_GET['page'],empty($_GET['classId'])?"5":$_GET['classId'],$auid);
        }else{
            $auid=$_SESSION['Auid'];
            $postList=$post->getPosts(empty($_GET['postType'])?"new":$_GET['postType'],empty($_GET['count'])?15:$_GET['count'],empty($_GET['page'])?1:$_GET['page'],empty($_GET['classId'])?"5":$_GET['classId'],$auid);
        }
        $outmsg = array('code' =>'1','msg'=>'获取成功！','data'=>$postList);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
    case 'outPostInfo':
        if(empty($_GET['id'])){
            $outmsg = array('code' =>'0','msg'=>'缺少必要的参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $postinfo=$post->getPostInfo($_GET['id'],$_SESSION['Auid']);
        if(!$postinfo){
            $outmsg = array('code' =>'0','msg'=>'获取失败吖！','data'=>"0 data");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $outmsg = array('code' =>'1','msg'=>'获取成功！','data'=>$postinfo);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
    case 'pl':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就想评论？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(empty($_POST['txt'])||empty($_POST['auid'])||empty($_POST['uid'])){
            die("缺少必要的参数！");
        }
        $out=$post->addComment($_POST['postid'],$_POST['txt'],$_POST['auid'],$_POST['uid']);
        if(!$out)
        {
            $outmsg = array('code' =>'0','msg'=>'评论失败！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'评论成功！','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getpl':
        if(empty($_GET['postid']))
        {
            die("缺少参数！");
        }
        $c=$_GET['count']?$_GET['count']:20;
        $p=$_GET['page']?$_GET['page']:1;
        if(!$out=$post->getCommentList($_GET['postid'],$c,$p)){
            $outmsg = array('code' =>'0','msg'=>'获取失败！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'dianzan':
        if(empty($_GET['postid']))
        {
            die("缺少参数！");
        }
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就想评论？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(!$out=$post->commentsGive($_GET['postid'])){
            $outmsg = array('code' =>'0','msg'=>'点赞失败！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'点赞成功！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'hfpl':
        if(empty($_POST['id']) || empty($_POST['txt']) || empty($_POST['uid'])){die("缺少参数！");}
        if(!$_SESSION['Auid']){$outmsg = array('code' =>'0','msg'=>'没有登录就想评论？','data'=>"");die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));}

        if(!$out=$post->addReply($_POST['id'],$_POST['txt'],$_POST['postid'],$_POST['auid'],$_POST['uid'],"1","123"))
        {
            $outmsg = array('code' =>'0','msg'=>'回复失败！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'回复成功！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'geiHfList':
        die(json_encode($post->getCommentList("1197")));
        break;
    case 'myPostList':
        if(empty($_GET['uid'])){die("缺少参数！");}
        if(!$_SESSION['Auid']){$outmsg = array('code' =>'0','msg'=>'没有登录获取列表？？','data'=>"");die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));}
        $count=$_GET['count']?$_GET['count']:'10';
        $page=$_GET['page']?$_GET['page']:'1';
        if(!$out=$post->getMyPosts($_GET['uid'],$count,$page)){
            $outmsg = array('code' =>'0','msg'=>'获取失败！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;

    case 'getClassList':
        if(!$outdata=$post->getClassList()){
            $outmsg = array('code' =>'0','msg'=>'获取失败！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
    case 'searchPosts':
        if(empty($_GET['keyword'])){
            $outmsg = array('code' =>'0','msg'=>'请输入搜索关键词！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就想点赞？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $auid=$_SESSION['Auid'];
        }
        if(!$outdata=$post->searchPosts('title',$_GET['keyword'],empty($_GET['page'])?'1':$_GET['page'],empty($_GET['count'])?'20':$_GET['count'],$auid)){
            $outmsg = array('code' =>'0','msg'=>'获取失败！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'Like':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就想点赞？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $auid=$_SESSION['Auid'];
        }
        if(empty($_GET['fuid'])){
            $outmsg = array('code' =>'0','msg'=>'必须输入帖子作者auid！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else if(empty($_GET['postid'])){
            $outmsg = array('code' =>'0','msg'=>'必须输入帖子id！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else if(empty($_GET['mode'])){
            $outmsg = array('code' =>'0','msg'=>'必须传递点赞模式','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            if($_GET['mode']=='add'){
                $out=$post->postGive($_GET['postid'],$auid,'add',$_GET['fuid']);
            }else{
                $out=$post->postGive($_GET['postid'],$auid,'del',$_GET['fuid']);
            }
            if(!$out){
                $outmsg = array('code' =>'0','msg'=>'操作失败','data'=>$outdata);
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }else{
                $outmsg = array('code' =>'1','msg'=>'操作成功','data'=>$outdata);
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
        }
        break;
    case 'getMyGive':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就想看我的收藏？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        $out=$post->getMyFavorite($auid,empty($_GET['page'])?1:$_GET['page'],empty($_GET['count'])?20:$_GET['count']);
        if(!$out){
            $outmsg = array('code' =>'0','msg'=>'没有任何收藏！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'checkText':
        if(empty($_GET['txt'])){
            $outmsg = array('code' =>'0','msg'=>'必须传入文字内容','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        var_dump(checkText($_GET['txt']));
        break;
    case 'weekTop':

        $auid=$_SESSION['Auid'];
        //$auid=6666;
        $out=$post->getWeekTop($auid);
        if(!$out){
            $outmsg = array('code' =>'0','msg'=>'获取失败！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'dashang':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就想看我的收藏？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(empty($_GET['postid'])||empty($_GET['toid'])||empty($_GET['number'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];

        $code=$post->setDashang($auid,$_GET['postid'],$_GET['toid'],$_GET['number']);
        if($code==0){
            $outmsg = array('code' =>'0','msg'=>'操作失败！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($code==1){
            $outmsg = array('code' =>'1','msg'=>'操作成功！','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($code==2){
            $outmsg = array('code' =>'0','msg'=>'你已经打赏过了，不能再打赏哦','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($code==3){
            $outmsg = array('code' =>'0','msg'=>'安个利币不足，先去充值吧','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getDashangList':

        $auid=$_SESSION['Auid'];
        if(empty($_GET['postId'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $data=$post->getDashangList($auid,$_GET['postId']);
        if(!$data){
            $cout['count']=0;
            $outmsg = array('code' =>'0','msg'=>'没有打赏','data'=>$data,'data'=>$cout);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getClassPostList':
        $auid=$_SESSION['Auid'];
        if(empty($_GET['classId'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        $data=$post->getClassPostList($_GET['classId'],empty($_GET['page'])?1:$_GET['page'],empty($_GET['count'])?20:$_GET['count'],$auid,$_GET['postType']);
        if(!$data){
            $cout['count']=0;
            $outmsg = array('code' =>'0','msg'=>'没有打赏','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getClassInfo':
        if(empty($_GET['classId'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $data=$post->getClassinfo($_GET['classId']);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'获取失败','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'jubao':
        if(empty($_GET['postId'])||empty($_GET['auid'])||empty($_GET['beijubao'])||empty($_GET['liyou'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $postId=$_GET['postId'];
        $data=$post->addJuBao($postId,$_GET['auid'],$_GET['beijubao'],$_GET['liyou']);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'举报失败','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'举报成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        break;
    case 'delPost':
        if(empty($_GET['postId'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>$out);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就想那啥？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];

        $data=$post->delPost($_GET['postId'],$auid);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'举报失败','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'举报成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        break;
    default:
        # code...

        break;
}

function checkText($txt){
    $html = file_get_contents('https://api.angeli.top/contentCensor.php?type=text&text='.$txt);
    $html=json_decode($html,true);
    $data=$html['result']['spam'];
    // array_push($data,$html['result']['review']);
    return $data;

}
function textCheck($txt){
    $html = file_get_contents('https://api.angeli.top/contentCensor.php?type=text&text='.$txt);
    $html=json_decode($html,true);
    if($html['result']['spam']==0){
        return $txt;
    }
    $html=$html['result']['review'];
    foreach ($html as $value) {
        $value['hit'];
        $txt=str_replace($value['hit']," ",$txt);
    }
    return $txt;
}





?>