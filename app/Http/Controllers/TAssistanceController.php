<?php

namespace App\Http\Controllers;

use App\Events\DashboardEvent;
use App\Models\TAssistance;
use App\Models\TAssistanceResponses;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Log;
use Session;

use App\Notifications\NewNotification;

use App\Events\NotificationEvent;
use App\Events\TAssistanceRequest;

class TAssistanceController extends Controller
{
    //
    public function index()
    {
        return view('techassistance.index');
    }

    public function completeRequest(Request $request)
    {
        $request->validate([
            'performance_survey' => 'required',
        ]);

        // Log::channel('activity')->info('User ACcomplished', [
        //     'Performance Survey' => $request->performance_survey,
        // ]);

        if (TAssistance::findOrFail($request->ta_request_id)) {
            TAssistance::where('id', $request->ta_request_id)->update([
                'performance_survey' => $request->performance_survey,
                'status' => 1,
            ]);
        }
        broadcast(new DashboardEvent());
    }

    public function disregardTask($id)
    {
        if (TAssistance::findOrFail($id)) {
            TAssistance::where('id', $id)->update([
                'status' => 4,
            ]);
        }
        broadcast(new DashboardEvent());
        return response()->json('success');
    }

    public function takeAction(Request $request)
    {
        $curr_time = now()->format('Y-m-d H:i:s');
        $request->validate([
            'findings' => 'required',
            'recommendations' => 'required',
            'remarks' => 'required',
        ]);
        Log::channel('activity')->info('User submitted form', [
            'ta id' => $request->request_by,
        ]);

        $ta_count = TAssistance::count();
        $TAssistanceResponses = TAssistanceResponses::create([
            'ta_request_id' => $request->ta_request_id,
            'findings' => $request->findings,
            'recommendations' => $request->recommendations,
            'remarks' => $request->remarks,
            'performed_by' => auth('api')->user()->id,
        ]);

        if (TAssistance::findOrFail($request->ta_request_id)) {
            TAssistance::where('id', $request->ta_request_id)->update([
                'status' => 3,
            ]);
        }
        $message = 'Action Taken';

        $request_by = TAssistance::where('id', $request->ta_request_id)->value('request_by');
        $requestor = User::where('id', $request_by)->first();

        Log::channel('activity')->info('User submitted form', [
            'ta id' => $request->request_by,
        ]);

        $requestor->notify(new NewNotification(auth('api')->user(), $TAssistanceResponses, $message));
        broadcast(new NotificationEvent())->toOthers();
        broadcast(new TAssistanceRequest())->toOthers();
        broadcast(new DashboardEvent());
        Session::flash('success', 'Technical Assistance Updated');
        return response()->json('success');
    }

    public function getTechResponse($id)
    {
        $query = TAssistanceResponses::where('ta_request_id', $id)->with('t_assistance')->firstOrFail();
        // Log::channel('activity')->info('User submitted form', [
        //     'user_id' => $query,

        // ]);
        return response()->json($query);
    }
    public function getPendingRequests()
    {
        $user_id = auth('api')->user()->id;
        $user = auth('api')->user();
        $roleNames = $user->roles->pluck('name')->toArray();
        // Log::channel('activity')->info('User submitted form', [
        //     'user_id' => $roleNames,

        // ]);

        if (in_array('administrator', $roleNames) || in_array('misu', $roleNames)) {

            return response()->json(TAssistance::whereIn('status', [0, 2, 3])->with('request_by')->latest()->paginate(10));
        } else {
            return response()->json(TAssistance::where('request_by', $user_id)->whereIn('status', [0, 2, 3])->with('request_by')->latest()->paginate(10));
        }
    }
    public function getAccomplishedRequests()
    {
        $user_id = auth('api')->user()->id;
        $user = auth('api')->user();
        $roleNames = $user->roles->pluck('name')->toArray();
        if (in_array('administrator', $roleNames) || in_array('misu', $roleNames)) {
            return response()->json(TAssistance::where('status', 1)->with('request_by')->latest()->paginate(10));
        } else {
            return response()->json(TAssistance::where('request_by', $user_id)->where('status', 1)->with('request_by')->latest()->paginate(10));
        }
    }

    public function addRequest(Request $request)
    {
        $curr_time = now()->format('Y-m-d H:i:s');
        $request->validate([
            'request_type' => 'required',
            'description' => 'required',
        ]);
        if ($request->file) {
            $upload_path = public_path('requests');
            $extension = $request->file->getClientOriginalExtension();
            $file_name = time() . '.' . $extension;
            $request->file->move($upload_path, $file_name);

            $file = $file_name;
        } else {
            $file = null;
        }
        $ta_count = TAssistance::count();
        $techassistance = TAssistance::create([
            'request_by' => auth('api')->user()->id,
            'division_id' => auth('api')->user()->division_id,
            'request_id' => 'TA-2025-' . $ta_count + 1,
            'request_date' => $curr_time,
            'request_type' => $request->request_type,
            'description' => $request->description,
            'file_attachement' => $file,
        ]);
        $message = 'New Technical Assistance Request';
        $usersInDivision = User::where('division_id', 1)->where('id', '!=', auth('api')->id())->get();

        foreach ($usersInDivision as $user) {
            $user->notify(new NewNotification(auth('api')->user(), $techassistance, $message));
        }

        broadcast(new NotificationEvent())->toOthers();
        broadcast(new TAssistanceRequest())->toOthers();
        broadcast(new DashboardEvent());


        Session::flash('success', 'Technical Assistance requested');
        return response()->json('success');
    }

    public function countTAPending()
    {

        $userDiv = auth('api')->user()->division_id;

        if ($userDiv == '1') {
            return response()->json([
                'completed' => TAssistance::where('status', '1')->count(),
                'pending' => TAssistance::where('status', '0')->count(),
                'ongoing' => TAssistance::where('status', '3')->count(),
                'disregard' => TAssistance::where('status', '2')->count(),
            ]);
        }else{
           $user_id = auth('api')->user()->id;
           return response()->json([
                'completed' => TAssistance::where('request_by', $user_id)->where('status', '1')->count(),
                'pending' => TAssistance::where('request_by', $user_id)->where('status', '0')->count(),
                'ongoing' => TAssistance::where('request_by', $user_id)->where('status', '3')->count(),
                'disregard' => TAssistance::where('request_by', $user_id)->where('status', '4')->count(),
            ]);
        }
    }
}
