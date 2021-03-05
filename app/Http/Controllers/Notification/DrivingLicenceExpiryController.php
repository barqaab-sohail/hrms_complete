<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\DatabaseNotification;
use App\user;
use DB;
use Notification;
use Illuminate\Support\Facades\Auth;

class DrivingLicenceExpiryController extends Controller
{
    

    // public function index(){
        
    // 	return view ('hr.drivingLicenceExpiry.list');
    // }

    // public function show($id){

    // 	$notification = auth()->user()
    //                         ->Notifications
    //                         ->where('id', $id)
    //                         ->first();
    //      $notification->markAsRead();
         
    //     //auth()->user()->notifications()->delete()->where('type', 'App\Notifications\LikedComment')->where('data->model', $comment->id)->delete();
    // 	return view ('hr.notification.showNotification',compact('notification'));

    // }
}
