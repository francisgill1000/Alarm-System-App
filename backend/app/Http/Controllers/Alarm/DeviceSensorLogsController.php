<?php

namespace App\Http\Controllers\Alarm;

use App\Http\Controllers\Controller;
use App\Models\Alarm\DeviceSensorLogs as AlarmDeviceSensorLogs;
use App\Models\Device;
use App\Models\DeviceSensorLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as Logger;
use Ramsey\Uuid\Type\Integer;

class DeviceSensorLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }
    public function getDeviceTodayHourlyTemperature(Request $request)
    {

        $date = date('Y-m-d');


        ///----------Hourly Device Logs 

        $HouryData = $this->getTemparatureHourlyData($request->company_id, $request->device_serial_number, $date);


        //last alarm 
        $Device = Device::where("company_id", $request->company_id)
            ->where("serial_number", $request->device_serial_number)->get();
        if (isset($Device[0]))
            $fire_alarm_start_datetime = $Device[0]->fire_alarm_start_datetime;


        return [

            "houry_data" => $HouryData
        ];
    }
    public function getDeviceTodayHourlyHumidity(Request $request)
    {

        $date = date('Y-m-d');


        ///----------Hourly Device Logs 

        $HouryData = $this->getHumidityHourlyData($request->company_id, $request->device_serial_number, $date);


        //last alarm 
        $Device = Device::where("company_id", $request->company_id)
            ->where("serial_number", $request->device_serial_number)->get();
        if (isset($Device[0]))
            $fire_alarm_start_datetime = $Device[0]->fire_alarm_start_datetime;


        return [

            "houry_data" => $HouryData
        ];
    }
    public function getDeviceLatestTemperature(Request $request)
    {
        $temperature_latest = '--';
        $temperature_date_time = '--';
        $temperature_min = '--';
        $temperature_max = '--';
        $temperature_min_date_time = '--';
        $temperature_max_date_time = '--';
        $fire_alarm_start_datetime = '--';


        $humidity_latest = '--';
        $humidity_date_time = '--';
        $humidity_min = '--';
        $humidity_max = '--';
        $humidity_min_date_time = '--';
        $humidity_max_date_time = '--';



        $date = date('Y-m-d');


        $model = AlarmDeviceSensorLogs::where("company_id", $request->company_id)
            ->where("serial_number", $request->device_serial_number)
            ->orderBy("log_time", "DESC")
            ->first();


        if ($model) {
            $temperature_latest = $model->temparature;
            $temperature_date_time = $model->log_time;

            $humidity_latest = $model->humidity;
            $humidity_date_time = $model->log_time;
        }

        //----
        $model =   AlarmDeviceSensorLogs::where("company_id", $request->company_id)
            ->where("serial_number", $request->device_serial_number)
            ->whereDate("log_time", $date);
        $temperature  = $model->clone()->where(
            'temparature',
            '=',
            AlarmDeviceSensorLogs::where("company_id", $request->company_id)
                ->where("serial_number", $request->device_serial_number)
                ->where("temparature", "!=", "0.0")

                ->whereDate("log_time", $date)->min('temparature')
        )->first();
        if ($temperature) {
            $temperature_min = $temperature->temparature;
            $temperature_min_date_time = $temperature->log_time;
        }
        //----------------
        $temperature =  $temperature  = $model->clone()->where(
            'temparature',
            '=',
            AlarmDeviceSensorLogs::where("company_id", $request->company_id)
                ->where("serial_number", $request->device_serial_number)
                ->where("temparature", "!=", "0.0")
                ->whereDate("log_time", $date)->max('temparature')
        )->first();
        if ($temperature) {
            $temperature_max = $temperature->temparature;
            $temperature_max_date_time = $temperature->log_time;
        }

        //-------Humidity 
        //----
        $model =   AlarmDeviceSensorLogs::where("company_id", $request->company_id)
            ->where("serial_number", $request->device_serial_number)
            ->whereDate("log_time", $date);
        $humidity  = $model->clone()->where(
            'humidity',
            '=',
            AlarmDeviceSensorLogs::where("company_id", $request->company_id)
                ->where("serial_number", $request->device_serial_number)
                ->where("humidity", "!=", "0.0")

                ->whereDate("log_time", $date)->min('humidity')
        )->first();
        if ($temperature) {
            $humidity_min = $humidity->humidity;
            $humidity_min_date_time = $humidity->log_time;
        }
        //----------------
        $humidity =  $humidity  = $model->clone()->where(
            'humidity',
            '=',
            AlarmDeviceSensorLogs::where("company_id", $request->company_id)
                ->where("serial_number", $request->device_serial_number)
                ->where("humidity", "!=", "0.0")
                ->whereDate("log_time", $date)->max('humidity')
        )->first();
        if ($humidity) {
            $humidity_max = $humidity->humidity;
            $humidity_max_date_time = $humidity->log_time;
        }

        return [
            "temperature_latest" =>   $temperature_latest,
            "temperature_date_time" => $temperature_date_time,
            "temperature_min" => $temperature_min,
            "temperature_max" => $temperature_max,
            "temperature_min_date_time" => $temperature_min_date_time,
            "temperature_max_date_time" => $temperature_max_date_time,
            "fire_alarm_start_datetime" => $fire_alarm_start_datetime,


            "humidity_latest" =>   $humidity_latest,
            "humidity_date_time" => $humidity_date_time,
            "humidity_min" => $humidity_min,
            "humidity_max" => $humidity_max,
            "humidity_min_date_time" => $humidity_min_date_time,
            "humidity_max_date_time" => $humidity_max_date_time,

        ];
    }
    public function getHumidityHourlyData($company_id, $device_serial_number, $date)
    {
        $finalarray = [];

        for ($i = 0; $i < 24; $i++) {

            $j = $i;

            $j = $i <= 9 ? "0" . $i : $i;

            $date = date('Y-m-d'); //, strtotime(date('Y-m-d') . '-' . $i . ' days'));
            $model = AlarmDeviceSensorLogs::where('company_id', $company_id)
                ->where("serial_number", $device_serial_number)

                ->where('log_time', '>=', $date . ' ' . $j . ':00:00')
                ->where('log_time', '<=', $date  . ' ' . $j . ':59:59')
                ->avg("humidity");

            $finalarray[] = [
                "date" => $date,
                "hour" => $i,
                "count" => $model == null ? 0 : round($model, 2),

            ];
        }


        return  $finalarray;
    }
    public function getTemparatureHourlyData($company_id, $device_serial_number, $date)
    {
        $finalarray = [];

        for ($i = 0; $i < 24; $i++) {

            $j = $i;

            $j = $i <= 9 ? "0" . $i : $i;

            $date = date('Y-m-d'); //, strtotime(date('Y-m-d') . '-' . $i . ' days'));
            $model = AlarmDeviceSensorLogs::where('company_id', $company_id)
                ->where("serial_number", $device_serial_number)

                ->where('log_time', '>=', $date . ' ' . $j . ':00:00')
                ->where('log_time', '<=', $date  . ' ' . $j . ':59:59')
                ->avg("temparature");

            $finalarray[] = [
                "date" => $date,
                "hour" => $i,
                "count" => $model == null ? 0 : round($model, 2),

            ];
        }


        return  $finalarray;
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
}
