<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Meters;
use App\Models\MeterReadings;
use App\Models\EstimatedReadings;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ReadingActionController extends Controller
{
    // meter view function
    public function index(Request $request)
    {
        $Meters_data = Meters::get();
        if ($request->ajax()) {
            $data = MeterReadings::select("*")
                ->with('meters')
                ->get();
            return Datatables::of($data)
                ->make(true);
        }

        return view('meter_reading', compact('Meters_data'));
    }

    /* Fetch meter info according to MPXN */

    public function edit($id)
    {
        $Meters = Meters::find($id);
        return response()->json($Meters);
    }

    /* meter store action function */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MPXN' => 'required',
            'reading_value' => 'required|integer',
            'reading_date' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $result = EstimatedReadings::select("*")
            ->where('estimated_readings.meters_id', '=', $request->MPXN)
            ->latest()->first();
        if ($result) {
            $acceptable_range = $result->estimated_reading * 0.25; // 25% range
            if (($request->reading_value - $result->estimated_reading) > $acceptable_range) {
                // Reading is outside acceptable range, show an error message 
                $validator->getMessageBag()->add('error', 'Reading is outside acceptable range');
                return response()->json([
                    'error' => $validator->errors()->all()
                ]);
            } else {
                // Reading is within acceptable range, proceed with adding to the database

                // ...
            }
        }
        MeterReadings::create([
            'meters_id' => $request->MPXN,
            'reading_value' => $request->reading_value,
            'reading_date' => $request->reading_date,
        ]);

        return response()->json(['success' => 'Meter Reading saved successfully.']);
    }
}
