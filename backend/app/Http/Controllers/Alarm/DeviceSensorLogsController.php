<?php

namespace App\Http\Controllers\Alarm;

use App\Http\Controllers\Controller;
use App\Models\Alarm\DeviceSensorLogs as AlarmDeviceSensorLogs;
use App\Models\DeviceSensorLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as Logger;

class DeviceSensorLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeviceSensorLogs  $deviceSensorLogs
     * @return \Illuminate\Http\Response
     */
    public function show(AlarmDeviceSensorLogs $deviceSensorLogs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeviceSensorLogs  $deviceSensorLogs
     * @return \Illuminate\Http\Response
     */
    public function edit(AlarmDeviceSensorLogs $deviceSensorLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeviceSensorLogs  $deviceSensorLogs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AlarmDeviceSensorLogs $deviceSensorLogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeviceSensorLogs  $deviceSensorLogs
     * @return \Illuminate\Http\Response
     */
    public function destroy(AlarmDeviceSensorLogs $deviceSensorLogs)
    {
        //
    }

    public function UpdateCompanyIds()
    {
        $date = date("Y-m-d H:i:s");

        $model = AlarmDeviceSensorLogs::query();
        $model->distinct('serial_number');
        $model->where("company_id", 0);

        $model->whereHas('device', function ($query) {
            $query->where('company_id', '!=', 0);
        });

        $model->take(100);
        $model->with("device:device_id,company_id,location,device_type");
        $rows = $model->get(["serial_number"]);

        if (count($rows) == 0) {
            return "[" . $date . "] Cron: UpdateCompanyIds. No new record found while updating company ids for device.\n";
        }

        $i = 0;

        foreach ($rows as $arr) {

            try {
                $i++;
                AlarmDeviceSensorLogs::where("serial_number", $arr["serial_number"])->update([
                    "company_id" => $arr["device"]["company_id"] ?? 0,

                ]);
            } catch (\Throwable $th) {
                Logger::channel("custom")->error('Cron: UpdateCompanyIds. Error Details: ' . $th);

                $data = [
                    'title' => 'Quick action required',
                    'body' => $th,
                ];

                return "[" . $date . "] Cron: UpdateCompanyIds. Error occured while updating company ids.\n";
            }
        }

        return "[" . $date . "] Cron: UpdateCompanyIds. $i Logs has been merged with Company IDS.\n"; //."Details: " . json_encode($result) . ".\n";

    }
}
