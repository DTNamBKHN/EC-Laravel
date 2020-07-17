<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\support\facades\Redirect;
use App\Social; //sử dụng model Social
use Laravel\Socialite\Facades\Socialite; //sử dụng Socialite
use App\Login; //sử dụng model Login
use Illuminate\Support\Facades\Validator;
use App\Rules\Captcha;

session_start();

class AdminController extends Controller
{
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }
        else{
            return Redirect::to('admin')->send();
        }
    }
    public function login_google(){
        return Socialite::driver('google')->redirect();
   }
    public function callback_google(){
        $users = Socialite::driver('google')->user();
        // return $users->id;
        $authUser = $this->findOrCreateUser($users,'google');
        $account_name = Login::where('admin_id',$authUser->user)->first();
        Session::put('admin_name',$account_name->admin_name);
        Session::put('admin_id',$account_name->admin_id);
        return redirect('/dashboard')->with('message', 'Đăng nhập Admin thành công');


    }
    public function findOrCreateUser($users,$provider){
        $authUser = Social::where('provider_user_id', $users->id)->first();
        if($authUser){

            return $authUser;
        }

        $nam = new Social([
            'provider_user_id' => $users->id,
            'provider' => strtoupper($provider)
        ]);

        $orang = Login::where('admin_email',$users->email)->first();

            if(!$orang){
                $orang = Login::create([
                    'admin_name' => $users->name,
                    'admin_email' => $users->email,
                    'admin_password' => '',

                    'admin_phone' => '',
                    'admin_status' => 1
                ]);
            }
        $nam->login()->associate($orang);
        $nam->save();

        $account_name = Login::where('admin_id',$nam->user)->first();
        Session::put('admin_name',$account_name->admin_name);
        Session::put('admin_id',$account_name->admin_id);
        return redirect('/dashboard')->with('message', 'Đăng nhập Admin thành công');


    }

    public function login_facebook(){
        return Socialite::driver('facebook')->redirect();//neu chua dang nhap thi tra ve trang danh nhap cua face, nguoc lai thi goi ham callback
    }

    public function callback_facebook(){
        $provider = Socialite::driver('facebook')->user();//pthuc cua facebook
        $account = Social::where('provider','facebook')->where('provider_user_id',$provider->getId())->first();//get id cua truong admin_id trong tbl_admin
        if($account){//TH da dang nhap
            //login in vao trang quan tri
            $account_name = Login::where('admin_id',$account->user)->first();//tbl_social.user=tbl_admin.admin_id
            Session::put('admin_name',$account_name->admin_name);
            Session::put('admin_id',$account_name->admin_id);
            return redirect('/dashboard')->with('message', 'Đăng nhập Admin thành công');
        }else{//TH chua dang nhap
            $nam = new Social([
                'provider_user_id' => $provider->getId(),
                'provider' => 'facebook'
            ]);

            $orang = Login::where('admin_email',$provider->getEmail())->first();

            if(!$orang){
                $orang = Login::create([
                    'admin_name' => $provider->getName(),
                    'admin_email' => $provider->getEmail(),
                    'admin_password' => '',
                    'admin_phone' => '',
                ]);
            }
            $nam->login()->associate($orang);
            $nam->save();

            $account_name = Login::where('admin_id',$account->user)->first();

            Session::put('admin_name',$account_name->admin_name);
            Session::put('admin_id',$account_name->admin_id);
            return redirect('/dashboard')->with('message', 'Đăng nhập Admin thành công');
        }
    }


    public function index(){
        return view('admin_login');
    }
    public function show_dashboard(){
        $this->AuthLogin();
        return view('admin.dashboard');
    }
    public function dashboard(Request $request){
        // $data = $request->all();
        $data = $request->validate([
            //validation laravel
           'admin_email' => 'required',
           'admin_password' => 'required',
           'g-recaptcha-response' => new Captcha(), 		//dòng kiểm tra Captcha
        ]);

        $admin_email = $data['admin_email'];
        $admin_password = md5($data['admin_password']);
        $login = Login::where('admin_email',$admin_email)->where('admin_password',$admin_password)->first();
        $login_count = $login->count();
        if($login_count){
            Session::put('admin_name',$login->admin_name);
            Session::put('admin_id',$login->admin_id);
            return Redirect::to('/dashboard');
        }else{
            Session::put('message','Mật khẩu hoặc tài khoản bị sai. Làm ơn nhập lại');
            return Redirect::to('/admin');
        }
        // $admin_email = $req;uest->admin_email;
        // $admin_password = md5($request->admin_password);

        // $result = DB::table('tbl_admin')->where('admin_email', $admin_email)->
        // where('admin_password', $admin_password)->first();
        // if ($result) {
        //     Session::put('admin_name', $result->admin_name);
        //     Session::put('admin_id', $result->admin_id);
        //     return Redirect::to('/dashboard');
        // }else{
        //     Session::put('message', 'Mật khẩu hoặc tài khoản bị sai. Làm ơn nhập lại!');
        //     return Redirect::to('/admin');
        // }
    }
    public function logout(){
        $this->AuthLogin();
        Session::put('admin_name', null);
        Session::put('admin_id', null);
        return Redirect::to('/admin');
    }
}
