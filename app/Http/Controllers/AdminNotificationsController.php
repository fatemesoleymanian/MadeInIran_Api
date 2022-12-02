<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationsController extends Controller
{

    public function countUnreadNotifications()
    {
        $admin = Admin::query()->first();
        return $admin->unreadNotifications->count();
    }


    public function readNotifications()
    {
        $notifications = Notification::where('read_at','!=',null)->orderByDesc('created_at')->get();
        return response()->json([
            'notifications' => $notifications
        ], 201);
    }

     public function unreadNotifications()
    {
        $notifications = Notification::where('read_at',null)->orderByDesc('created_at')->get();
        return response()->json([
            'notifications' => $notifications
        ], 201);
    }


    public function markAllAsReadNotification()
    {
        $admin = Admin::query()->first();
        return $admin->unreadNotifications->markAsRead();
    }
}
