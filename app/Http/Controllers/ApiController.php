<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Role;
use App\Models\Permission;
use App\Models\TAssistance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function getLineChartData($year)
    {
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];
        $numeric_months = [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12'
        ];

        $ta_request = [];
        $ta_request_completed = [];
        $own_completed = [];

        foreach ($numeric_months as $nm) {
            $ta = TAssistance::whereMonth('created_at', '=', $nm)->whereYear('created_at', '=', $year)->get();
            array_push($ta_request, $ta->count());
        }

        foreach ($numeric_months as $nm) {
            $ta_completed = TAssistance::where('request_by', auth('api')->user()->id)->where('status', '1')->whereMonth('created_at', '=', $nm)->whereYear('created_at', '=', $year)->get();
            array_push($ta_request_completed, $ta_completed->count());
        }

        return response()->json([
            'year' => $year,
            'months' => $months,
            'ta_request' => $ta_request,
            'ta_request_completed' => $ta_request_completed,
            'own_completed' => $own_completed
        ]);
    }
    public function getRequestType()
    {
        $numeric_request_type = [
            'Hardware',
            'Software',
            'Network',
            'Other'
        ];
        $numeric_request_type_count = [
            '1',
            '2',
            '3',
            '4'
        ];
        $ta_request_count_completed = [];
        $ta_request_count_pending = [];
        $ta_request_count_ongoing = [];
        $ta_request_count_disregard = [];
        // $ta_count = TAssistance::count();
        // array_push($ta_request_count, $ta_count);

        foreach ($numeric_request_type_count as $nmrt) {
            $ta = TAssistance::where('request_type', $nmrt)->where('status', '1')->get();
            array_push($ta_request_count_completed, $ta->count());
        }
        foreach ($numeric_request_type_count as $nmrt) {
            $ta = TAssistance::where('request_type', $nmrt)->where('status', '0')->get();
            array_push($ta_request_count_pending, $ta->count());
        }
        foreach ($numeric_request_type_count as $nmrt) {
            $ta = TAssistance::where('request_type', $nmrt)->where('status', '3')->get();
            array_push($ta_request_count_ongoing, $ta->count());
        }
        foreach ($numeric_request_type_count as $nmrt) {
            $ta = TAssistance::where('request_type', $nmrt)->where('status', '4')->get();
            array_push($ta_request_count_disregard, $ta->count());
        }


        return response()->json([
            'ta_request_count_completed' => $ta_request_count_completed,
            'ta_request_count_pending' => $ta_request_count_pending,
            'ta_request_count_ongoing' => $ta_request_count_ongoing,
            'ta_request_count_disregard' => $ta_request_count_disregard,
            'numeric_request_type' => $numeric_request_type
        ]);
    }
    }
