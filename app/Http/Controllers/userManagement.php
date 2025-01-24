<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class userManagement extends Controller
{
    public function index(){
        return view('user-management');
    }
    public function getUserData(){
        $data = User::all();
        return DataTables::of($data)->make(mDataSupport: true);
    }
}
