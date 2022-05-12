<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class LogController extends Controller
{
    public function storeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ip' => ['required', 'string'],
            'previous_url' => ['required', 'string'],
            'current_url' => ['required', 'string'],
            'access_date' => ['required', 'string'],
            'page_access' => ['required', 'string'],
            'user_agent' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            $validator->validate();
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Data tidak valid',
            ], 'Bad Request', 400);
        }
        DB::beginTransaction();
        try {
            SystemLog::create([
                'id' => Uuid::uuid4(),
                'ip' => $request->ip,
                'previous_url' => $request->previous_url,
                'current_url' => $request->current_url,
                'access_date' => $request->access_date,
                'page_access' => $request->page_access,
                'user_agent' => $request->user_agent,
            ]);
            DB::commit();
            return ResponseFormatter::success(
                'Data Log Berhasil Disimpan'
            );
        } catch (Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'success' => false,
                'message' => $e,
            ], 'Internal Server Error', 500);
        }
    }
}
