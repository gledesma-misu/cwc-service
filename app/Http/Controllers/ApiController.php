<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    //
    public function getAllNotifications()
    {
        // Because the User model has a notifications() method, we can use notifications on the user object.
        return response()->json(auth('api')->user()->notifications);
    }
    public function getUnreadNotifications()
    {
        return response()->json(auth('api')->user()->unreadNotifications);
    }
    public function markNotificationAsRead()
    {
        $id = \Request::get('unread');
        if ($id != 0) {
            auth('api')->user()->notifications->where('id', $id)->markAsRead();
        } else {
            auth('api')->user()->notifications->markAsRead();
        }
        return response()->json('success');
    }
    public function clearAllNotifications()
    {
        auth('api')->user()->notifications()->delete();
        return response()->json('success');
    }
    public function getAllDivisions()
    {
        return response()->json(Division::get());
    }
    public function getAllRoles()
    {
        return response()->json(Role::get());
    }
    public function getAllPermissions()
    {
        return response()->json(Permission::where('deleted', 0)->get());
    }
}
