<?php
require_once('../config.php');
require_once('angeli.class.php');
//require_once('sendsms.class.php');
session_start();
error_reporting(E_ALL^E_NOTICE);
$allow_origin = array(
    'https://api.angeli.top',
    'http://www.angeli.top',
    'http://angeli.top'
);
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';  //跨域访问的时候才会存在此字段
if (in_array($origin, $allow_origin)) {
    header('Access-Control-Allow-Origin:' . $origin);
}
$user=new angeli($config);
if(empty($_GET['type']))
{
    die("请输出请求类型参数！");
}

switch ($_GET['type']){
    case 'getUserInfo':
        $auid=$_SESSION['Auid'];
        $info=$user->getUserInfo('auid',$_GET['auid'],$auid);
        if($info){
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>$info);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>'没有找到该用户','data'=>$info);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'search':
        if(empty($_GET['keyword'])){
            $outmsg = array('code' =>'0','msg'=>'请输入搜索关键词！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $userinfo=$user->searchUser('name',$_GET['keyword']);
        if(!$userinfo){
            $outmsg = array('code' =>'0','msg'=>'没有搜索到用户','data'=>$info);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>$userinfo);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getMyNoRead':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        //$auid=6666;
        $app=$user->getMyNoreadNumber($auid);
        $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$app);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));

        break;
    case 'getMyZan':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(!$app=$user->getMyZan($auid)){
            $outmsg = array('code' =>'0','msg'=>'查询失败！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$app);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'mark':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(empty($_GET['class'])){
            $outmsg = array('code' =>'0','msg'=>'没有指定标记类型','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $type=$_GET['class'];
        if(isset($_GET['msgId'])){
            $msgId=$_GET['msgId'];
        }
        if(!$app=$user->markNoRead($auid,$type,$msgId)){
            $outmsg = array('code' =>'0','msg'=>'标记失败！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$app);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        break;
    case 'getMypl':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        //$auid=6666;
        if(!$app=$user->getMyPinglun($auid)){
            $outmsg = array('code' =>'0','msg'=>'查询失败！','data'=>$outdata);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$app);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'cxjf':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        $data=$user->getJifen($auid);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'查询失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getMyMsgList':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        //$auid=6666;

        $data=$user->getMyMsgList($auid,empty($_GET['page'])?'1':$_GET['page'],empty($_GET['count'])?'50':$_GET['count']);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'查询失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        break;
    case 'getMyMsg':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        //$auid=6666;
        if(empty($_GET['msgid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $msgId=$_GET['msgid'];
        $data=$user->getMyMsg($msgId,$auid,empty($_GET['page'])?'1':$_GET['page'],empty($_GET['count'])?'50':$_GET['count']);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'查询失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getMynotReadGZ':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        $data=$user->getMyGZMsg($auid);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'发送失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        break;
    case 'upmsg':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(empty($_POST['msg'])||empty($_POST['toid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $data=$user->addMsg($auid,$_POST['toid'],$_POST['msg']);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'发送失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK！','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'gzORungz':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(empty($_GET['uid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $data=$user->gzORungz($auid,$_GET['uid']);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'操作失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>$data,'data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getMyhaoyou':

        $auid=$_SESSION['Auid'];
        $data=$user->getMyhaoyou($auid);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'操作失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'isvip':
        if(empty($_GET['auid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少auid参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($user->vipIs($_GET['auid'])){
            $outmsg = array('code' =>'1','msg'=>'你是VIP用户','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>'你不是VIP用户','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getSysConfig':
        if(empty($_GET['configName'])){
            $outmsg = array('code' =>'0','msg'=>'缺少auid参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($data=$user->getSystemConfig($_GET['configName'])){
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>'获取失败','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;

    case 'getSystemMsg':

        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        $data=$user->getSystemMsg($auid);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'操作失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getMyTG':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        $data=$user->getTuiGuang($auid);
        if(!$data){
            $outmsg = array('code' =>'0','msg'=>'操作失败！','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'1','msg'=>'获取成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'setName':
        //$auid=6666;
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];

        if(empty($_GET['newName'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $vip=$user->vipIs($auid);
        $data=$user->getUserConfig($auid);
        if($vip){
            $count=12-$data['upname'];

        }else{
            $count=2-$data['upname'];
        }
        if($count<1){
            $count=0;
            $outmsg = array('code' =>'0','msg'=>'你的修改次数已经用完','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }

        $data=$user->setUserName('auid',$auid,$_GET['newName']);
        if($data=='0'){
            $outmsg = array('code' =>'1','msg'=>'修改成功','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>$data,'data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getNameCount':
        if(empty($_GET['auid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少auid参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $vip=$user->vipIs($_GET['auid']);
        $data=$user->getUserConfig($_GET['auid']);
        if($vip){
            $count=12-$data['upname'];

        }else{
            $count=2-$data['upname'];
        }
        if($count<1){
            $count=0;
        }

        $outmsg = array('code' =>'1','msg'=>$count,'data'=>$count);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
    case 'setSex':
        //$auid=6666;
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(empty($_GET['sex'])){
            $outmsg = array('code' =>'0','msg'=>'缺少auid参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $data=$user->setSex($auid,$_GET['sex']);
        if($data){
            $outmsg = array('code' =>'1','msg'=>'修改成功','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>'修改失败','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'setms':
        //$auid=6666;
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(empty($_GET['ms'])){
            $outmsg = array('code' =>'0','msg'=>'缺少auid参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $data=$user->setms($auid,$_GET['ms']);
        if($data){
            $outmsg = array('code' =>'1','msg'=>'修改成功','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>'修改失败','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getDayHot':

        $auid=$_SESSION['Auid']?$_SESSION['Auid']:'0';
        $data=$user->getDayHot($auid);
        if($data){
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>'获取失败','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'getisvip':

        //$auid=6666;
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];

        $data=$user->getUserConfig($auid);
        if($data['First_vip']=='0'){
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>'1');
        }else{
            $outmsg = array('code' =>'1','msg'=>'OK','data'=>'10');
        }
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
    case 'tixian':
        if(!$_SESSION['Auid'])
        {
            $outmsg = array('code' =>'0','msg'=>'没有登录就操作？','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $auid=$_SESSION['Auid'];
        if(empty($_GET['fee'])||empty($_GET['openid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $fee=floatval($_GET['fee']);
        if($user->vipIs($auid))
        {
            if($fee<80){
                $outmsg = array('code' =>'0','msg'=>'最低提现金额不得的低于80','data'=>"");
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
        }else{
            if($fee<100){
                $outmsg = array('code' =>'0','msg'=>'最低提现金额不得的低于100','data'=>"");
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
        }
        $openid=$_GET['openid'];
        $data=$user->addTixian($auid,$fee,$openid);
        if($data==3){
            $outmsg = array('code' =>'0','msg'=>'剩余积分不足以提现','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($data==1){
            $outmsg = array('code' =>'0','msg'=>'系统错误','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($data==0){
            $outmsg = array('code' =>'1','msg'=>'已提交后台等待审核','data'=>"");
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'test':
        var_dump($user->setUserConfig(6666,'First_vip','1'));
        break;
    default:
        $outmsg = array('code' =>'0','msg'=>'非法请求','data'=>'');
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
}












?>