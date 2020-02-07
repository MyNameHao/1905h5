<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    public function regview(){
        return view('index.reg');
    }
    public function reg(){
        $data=$_POST;
        if($data['sex']!='1' && $data['sex']!='2'){
            echo 'sex内容有误';
        }
        $url='http://api.1905.com/api/user/reg';
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $info=curl_exec($ch);
        curl_close($ch);
        $arr=json_decode($info,true);
        if($arr['errorcode']=='0000'){
            return redirect('/user/loginview');
        }else{
            echo "<script>window.history.back();alert('".$arr['errmsg']."');</script>";
        }
    }
    public function loginview(){
        return view('index.login');
    }
    public function login(){
        $data=$_POST;
       $client=new Client();
        $url='http://api.1905.com/api/user/login';
        $aaa=$client->request('post',$url,[
            'form_params'=>$data
        ]);
        $str_json=$aaa->getBody();
        $arr=json_decode($str_json,true);
        if($arr['errorcode']=='0000'){
            Cookie::queue('token',$arr['token'],600);
            Cookie::queue('id',$arr['id'],600);
            return redirect('/');
        }else{
            echo "<script>window.history.back();alert('".$arr['errmsg']."');</script>";
        }


    }
    public function personal(){
        echo urlencode('http://sh.lizhijun.fun');exit;
        $aaa=$this->auth();
        if($aaa=='pass'){
            echo '欢迎来到个人中心';
        }else{
            header('Refresh:2;url=/user/loginview');        //两秒跳转
            echo "请先登录, 页面跳转中";die;
        }
    }
    public function auth(){
        $token=Cookie::get('token');
        $id=Cookie::get('id');
        if(empty($token)||empty($id)){
           return 'failure';
        }
        $url='http://passport.1905.com/user/auth';
        $data=json_encode(['token'=>$token,'id'=>$id]);
        $client=new Client();
        $aaa=$client->request('post',$url,[
            'body'=>$data
        ]);
       $retu= json_decode($aaa->getBody(),true);
        if($retu['errorcode']=='0000'){
            return 'pass';
        }else{
            return 'failure';
        }
    }
}
