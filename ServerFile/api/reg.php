<?php
require_once('../config.php');
require_once('angeli.class.php');
require_once('sendsms.class.php');
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
if(isset($_REQUEST['token'])){
    session_id($_REQUEST['token']);
    session_start();
}
switch ($_GET['type']) {
    case 'reg':
        if(empty($_GET['username']))
        {
            $outmsg = array('code' =>'0','msg'=>'请传入用户名','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else if(empty($_GET['gender'])){
            $outmsg = array('code' =>'0','msg'=>'请传入性别','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else if(empty($_GET['phone'])){
            $outmsg = array('code' =>'0','msg'=>'请传入手机号码','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($_SESSION['phone']!==$_GET['phone']){
            $outmsg = array('code' =>'0','msg'=>'手机号码不一致啊！','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $email=time()."@angeli.top";
        $nickname = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $_GET['username']);
        $fankui=$user -> addUser($nickname,"angeli",$_GET['gender'],$_GET['phone'],"","",$email,"1",$_SERVER['REMOTE_ADDR'],"");
        $data=json_decode($fankui,true);
        if($data['code']=="1"){
            $userinfo=$user->getUserInfo("phone",$_GET['phone']);
            if($userinfo){
                $outmsg = array('code' =>'1','msg'=>'登录成功！','data'=>$userinfo);
                $_SESSION['Auid']=$userinfo['Auid'];
                $user->addUserLog($_SESSION['Auid'],'注册',$_SESSION['Auid'],$user->systeminfo['phonebrand'],$user->systeminfo['phonesystem']);
                $_SESSION['UserName']=$userinfo['UserName'];
                unset($_SESSION['code']);
                setcookie("Auid",$userinfo['Auid'],time()+3600*24*30,'/');
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }else{
                $outmsg = array('code' =>'0','msg'=>'登录失败！','data'=> $fankui);
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
        }else{
            die($fankui);
        }
        break;
    case 'getuser':
        die($user -> getUserList());
        break;
    case 'login':
        $data=$user -> Login($_POST['phone'],$_POST['password']);
        $userinfo=$user->getUserInfo("auid",$data['Auid']);
        $outmsg = array('code' =>'1','msg'=>'登录成功！','data'=>$userinfo,'token'=>session_id());
        $_SESSION['Auid']=$data['Auid'];
        $_SESSION['UserName']=$data['NickName'];
        //setcookie("Auid",$data['Auid'],time()+3600*24*30,'/');
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
    case 'set':
        var_dump($user->setPassWord("phone",$_POST['phone'],$_POST['password']));
        break;
    case 'wxlogin':
        if(isset($_REQUEST['token'])){
            session_id($_REQUEST['token']);
            session_start();
        }else{
            session_start();
        }
        if(empty($_POST['code'])){
            $outmsg = array('code' =>'0','msg'=>'请传入用户code','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if(isset($_POST['tuijianId'])&&$_POST['tuijianId']!='0'){
            $_SESSION['tjrId']=$_POST['tuijianId'];
        }
        $wxuserinfo=$user->getWxID($_POST['code']);
        $_SESSION['wxuid']=$wxuserinfo['openid'];
        $_SESSION['sessionKey']=$wxuserinfo['session_key'];
        setcookie("wxid",$wxuserinfo['openid'],time()+3600*24,'/');
        setcookie("sekey",$wxuserinfo['session_key'],time()+3600*24,'/');
        $userinfo=$user->getUserInfo("openid",$wxuserinfo['openid']);
        if($userinfo['Auid']!='-1')
        {
            $jifen=false;
            $_SESSION['Auid']=$userinfo['Auid'];
            $_SESSION['UserName']=$userinfo['UserName'];
            $_SESSION['openid']=$userinfo['wxOpenId'];
            $user->addUserLog($userinfo['Auid'],'小程序登录',$userinfo['Auid'],$user->systeminfo['phonebrand'],$user->systeminfo['phonesystem']);
            if(!$user->checkLogin($userinfo['Auid'])){
                if($user->vipIs($userinfo['Auid'])){
                    $jifen=$user->setPoints($userinfo['Auid'],"+",2,'登录赠送积分');
                    $user->setRank($userinfo['Auid'],2);
                    $j=2;
                }else{
                    $jifen=$user->setPoints($userinfo['Auid'],"+",1,'登录赠送积分');
                    $user->setRank($userinfo['Auid'],1);
                    $j=1;
                }
            }
            if($jifen){
                $outmsg = array('code' =>'2','msg'=>'签到成功，赠送'.$j.'个安个利币','data'=>$userinfo,'token'=>session_id());
            }else{
                $outmsg = array('code' =>'1','msg'=>'ok','data'=>$userinfo,'tuijianren'=>$_SESSION['tjrId'],'token'=>session_id());
            }
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $outmsg = array('code' =>'0','msg'=>'没有查询到该用户','data'=>$wxuserinfo);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
    case 'wxreg':
        if(isset($_POST['tuijianId'])&&$_POST['tuijianId']!='0'){
            $_SESSION['tjrId']=$_POST['tuijianId'];
        }
        if(empty($_POST['unionid']) || $_POST['unionid']=="undefined"){
            $outmsg = array('code' =>'0','msg'=>'请传入用户unionid','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else if (empty($_POST['username'])) {
            $outmsg = array('code' =>'0','msg'=>'请传入用户昵称','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $phone=$_POST['phone'];
        $email=time()."@angeli.top";
        $fankui=$user -> addUser($_POST['username'],"angeli",$_POST['gender'],$_POST['phone'],$_POST['openid'],$_POST['unionid'],$email,"1",$_SERVER['REMOTE_ADDR'],$_POST['avatarUrl']);
        $data=json_decode($fankui,true);
        if($data['code']=='1'){
            $userinfo=$user->getUserInfo('phone',$phone);
            if($_POST['tuijianId'] && is_numeric($_POST['tuijianId'])){
                $user->setTuiGuang($_POST['tuijianId'],$userinfo['Auid']);
            }

            $outmsg = array('code' =>'1','msg'=>'注册成功吖！','data'=>$userinfo,'TG'=>$_POST['tuijianId']);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else {
            die($fankui);
        }
        # code...
        break;
    case 'getCode':

        if(isset($_REQUEST['token'])){
            session_id($_REQUEST['token']);
            session_start();
        }else{
            session_start();
        }

        $phone=$_POST['phone'];
        $code=mt_rand(1000,9999);
        $data=sendSms("reg",$phone,$code);
        if($data['Code']=="OK"){
            $_SESSION['code']=$code;
            $_SESSION['phone']=$phone;
            $_SESSION['codeTime']=time();
            $outmsg = array('code' =>'1','msg'=>'发送验证码成功！','data'=>$code,'token'=>session_id());
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            $outmsg = array('code' =>'0','msg'=>'发送短信验证码失败！','data'=>$data);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'codeLogin':


        if(empty($_POST['phone'])||empty($_POST['code'])){
            $outmsg = array('code' =>'0','msg'=>'请输入验证码和手机号','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($_SESSION['phone']!==$_POST['phone']){
            $outmsg = array('code' =>'0','msg'=>'两次手机号码不一致','data'=>$_SESSION['phone'].'缓存|传递'.$_POST['phone'].'|id:'.session_id());
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        if($_SESSION['code']==$_POST['code']){
            $codeTime=$_SESSION['codeTime'];
            if(time()-$codeTime>300){
                $outmsg = array('code' =>'0','msg'=>'验证码已经过期','data'=>'');
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }else{
                $userinfo=$user->getUserInfo("phone",$_POST['phone']);
                if($userinfo){
                    $outmsg = array('code' =>'1','msg'=>'登录成功！','data'=>$userinfo,'token'=>session_id());
                    $_SESSION['Auid']=$userinfo['Auid'];
                    $_SESSION['UserName']=$userinfo['UserName'];
                    unset($_SESSION['code']);
                    setcookie("Auid",$userinfo['Auid'],time()+3600*24*30,'/');
                    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
                }else{
                    $outmsg = array('code' =>'2','msg'=>'未注册用户','data'=>$_SESSION['phone']);
                    die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
                }
            }
        }else{
            $outmsg = array('code' =>'0','msg'=>'验证码不正确，登录失败','data'=>$_POST['code'].'|'.$_SESSION['code']);
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        break;
    case 'checkName':
        if(empty($_GET['name'])){
            $outmsg = array('code' =>'0','msg'=>'请输入名字','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            if($user->checkUserName($_GET['name'])){
                $outmsg = array('code' =>'1','msg'=>'这个名字可以用','data'=>'');
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }else{
                $outmsg = array('code' =>'0','msg'=>'这个名字不可以用','data'=>'');
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
        }
        break;
    case 'checkPhone':
        if(empty($_GET['phone'])){
            $outmsg = array('code' =>'0','msg'=>'请输入手机号','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }else{
            if($user->checkPhone($_GET['name'])){
                $outmsg = array('code' =>'1','msg'=>'这个手机号可以用','data'=>'');
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }else{
                $outmsg = array('code' =>'0','msg'=>'这个手机号不可以用','data'=>'');
                die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
            }
        }
        break;
    case 'appwxlogin':
        if(isset($_REQUEST['token'])){
            session_id($_REQUEST['token']);
            session_start();
        }else{
            session_start();
        }
        if(empty($_POST['unionid'])){
            $outmsg = array('code' =>'0','msg'=>'缺少参数unionid','data'=>'');
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $userinfo=$user->getUserInfo("unionid",$_POST['unionid']);

        if($userinfo['Auid']!='-1')
        {
            $jifen=false;
            $_SESSION['Auid']=$userinfo['Auid'];
            $_SESSION['UserName']=$userinfo['UserName'];
            $_SESSION['openid']=$userinfo['wxOpenId'];
            //setcookie("Auid",$userinfo['Auid'],time()+3600*24,'/');
            if(!$user->checkLogin($userinfo['Auid'])){
                if($user->vipIs($userinfo['Auid'])){
                    $jifen=$user->setPoints($userinfo['Auid'],"+",2,'登录赠送积分');
                    $user->setRank($userinfo['Auid'],2);
                    $j=2;
                }else{
                    $jifen=$user->setPoints($userinfo['Auid'],"+",1,'登录赠送积分');
                    $user->setRank($userinfo['Auid'],1);
                    $j=1;
                }
            }
            if($jifen){
                $outmsg = array('code' =>'2','msg'=>'签到成功，赠送'.$j.'个安个利币','data'=>$userinfo,'token'=>session_id());
            }else{
                $outmsg = array('code' =>'1','msg'=>'登录成功','data'=>$userinfo,'tuijianren'=>$_SESSION['tjrId'],'token'=>session_id());
            }
            die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        }
        $outmsg = array('code' =>'0','msg'=>'没有查询到该用户','data'=>$userinfo);
        die(json_encode($outmsg,JSON_UNESCAPED_UNICODE));
        break;
    case 'test':
            
            break;    
    default:
        # code...
        break;
}