<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Meters;
use App\Models\EstimatedReadings;
use App\Models\MeterReadings;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionController extends Controller
{
    // meter view function
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Meters::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" id="' . $row->id . '" onClick="displaymeterinfo(' . $row->id . ')" data-original-title="Show" class="btn btn-primary btn-sm displaymeterinfo">Reading Information</a>';
                    $btn .= ' <a href="javascript:void(0)" onClick="cal_annu_meter_reading(' . $row->id . ')" class="btn btn-primary btn-sm">CALC EST Reading</a>';
                    $btn .= ' <a href="javascript:void(0)" onClick="view_est_reading(' . $row->id . ')" class="btn btn-primary btn-sm">View EST Reading</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('meters');
    }
    /* meter store action function */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'MPXN' => 'required|unique:meters,mpxn',
                'Installation_Date' => 'required',
                'meter_type' => 'required',
                'estimated_annual_consumption' => 'min:2000|max:8000|integer',

            ],

        );
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        $data = Meters::create([
            'mpxn' => $request->MPXN,
            'installation_date' => $request->Installation_Date,
            'meter_type' => $request->meter_type,
            'estimated_annual_consumption' => $request->estimated_annual_consumption,
        ]);
        return response()->json(['success' => 'Meter saved successfully.']);
    }

    /* Store estimated reading */
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'meter_id' => 'required',
                'estimated_reading_date' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $result = MeterReadings::select("*")
            ->with('meters')
            ->where('meter_readings.meters_id', '=', $request->meter_id)
            ->latest()->first();
        if (!$result) {
            $validator->getMessageBag()->add('error', 'Please add Reading against this recode');
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        $datetime1 = strtotime($result->reading_date);
        $datetime2 = strtotime($request->estimated_reading_date);
        $calculate_seconds = $datetime2 - $datetime1; // Number of seconds between the two dates
        $time_interval = floor($calculate_seconds / (24 * 60 * 60)); // convert to days
        // dd($time_interval);
        if ($time_interval < 0) {
            $validator->getMessageBag()->add('error', 'Estimated Reading Date should be greater than Reading Date');
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }
        $estimated_daily_consumption = ($result->meters['estimated_annual_consumption']) / 365;
        $estimated_usage = $time_interval * $estimated_daily_consumption;
        $estimated_reading = $estimated_usage + $result->reading_value;

        // dd($estimated_reading); 
        $data = EstimatedReadings::create([
            'meters_id' => $request->meter_id,
            'estimated_reading' => $estimated_reading
        ]);
        return response()->json(['success' => 'Estimated Reading saved successfully.']);
    }
    public function show($id)
    {
        $data = MeterReadings::select("*")
            ->with('meters')
            ->where('meter_readings.meters_id', '=', $id)
            ->get();
        return view('meter_detail', compact('data'));
    }
    /* display estimate reading */
    public function view_est_reading($id)
    {
        $data = EstimatedReadings::select("*")
            ->with('meters')
            ->where('estimated_readings.meters_id', '=', $id)
            ->get();
        return view('estemate_reading', compact('data'));
    }
}
