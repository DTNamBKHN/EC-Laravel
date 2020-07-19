<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Coupon;
class CouponController extends Controller
{
    public function insert_coupon(){
        return view('admin.coupon.insert_coupon');
    }
    public function insert_coupon_code(Request $request){
        $data = $request->all();
    }
}
