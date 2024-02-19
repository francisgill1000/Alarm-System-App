<?php

namespace App\Http\Controllers\Alarm\Api;

use App\Http\Controllers\Controller;
use App\Models\Alarm\DeviceSensorLogs;
use Illuminate\Http\Request;
use App\Models\Community\AttendanceLog;
use App\Models\Company;
use App\Models\Device;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ApiAlarmControlController extends Controller
{
    public function LogDeviceStatus(Request $request)
    {



        try {
            Storage::append("logs/alarm/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()));

            $temparature = 0;
            $humidity = 0;
            $fire_alarm = 0;
            $water_leakage = 0;
            $power_failure = 0;
            $door_status = 0;
            $log_time = date('Y-m-d H:i:s');

            $max_temparature = 30;
            $max_humidity = 50;

            try {

                $json = file_get_contents(Storage::path('alarm_rules.json'));
                $json_data = json_decode($json, true);

                $max_temparature = $json_data['max_temparature'];
                $max_humidity =  $json_data['max_humidity'];
            } catch (\Exception $e) {
            }

            $device_serial_number = $request->serialNumber;


            if ($device_serial_number != '') {


                if ($request->filled("temperature")) {
                    $temparature = $request->temperature;
                }
                if ($request->filled("humidity")) {
                    $humidity = $request->humidity;
                }
                if ($request->filled("smokeStatus")) {
                    $fire_alarm = $request->smokeStatus;
                }
                if ($request->filled("waterLeakage")) {
                    $water_leakage = $request->waterLeakage;
                }
                if ($request->filled("acPowerFailure")) {
                    $power_failure = $request->acPowerFailure;
                }
                if ($request->filled("doorOpen")) {
                    $door_status = $request->doorOpen;
                }


                $logs["serial_number"] = $device_serial_number;
                $logs["temparature"] = $temparature;
                $logs["humidity"] = $humidity;
                $logs["fire_alarm"] = $fire_alarm == "true" ? 1 : 0;
                $logs["water_leakage"] = $water_leakage == "true" ? 1 : 0;
                $logs["power_failure"] = $power_failure == "true" ? 1 : 0;
                $logs["door_status"] = $door_status == "false" ? 1 : 0;

                $logs["log_time"] = $log_time;
                DeviceSensorLogs::create($logs);


                $row = [];

                if ($temparature >= $max_temparature) {
                    $row["temparature_alarm_status"] = 1;
                    $row["temparature_alarm_start_datetime"] = $log_time;
                }
                if ($humidity >= $max_humidity) {
                    $row["humidity_alarm_status"] = 1;
                    $row["humidity_alarm_start_datetime"] = $log_time;
                }


                if ($fire_alarm == 1) {
                    $row["fire_alarm_status"] = 1;
                    $row["fire_alarm_start_datetime"] = $log_time;
                }

                if ($water_leakage == 1) {
                    $row["water_alarm_status"] = 1;
                    $row["water_alarm_start_datetime"] = $log_time;
                }
                if ($power_failure == 1) {
                    $row["power_alarm_status"] = 1;
                    $row["power_alarm_start_datetime"] = $log_time;
                }
                if ($door_status == 1) {
                    $row["door_open_status"] = 1;
                    $row["door_open_start_datetime"] = $log_time;
                }


                //return [$logs, $row];

                Device::where("serial_number", $device_serial_number)
                    ->update($row);
                return $this->response('Successfully Updated', null, true);
            }
        } catch (\Exception $e) {
            Storage::append("logs/alarm_error/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()) . ' \n' . $e->getMessage());
        }

        return $this->response('Data error', null, false);
    }
    public function LogDeviceStatus_old(Request $request)
    {

        try {
            Storage::append("logs/alarm/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()));

            $temparature = 0;
            $humidity = 0;
            $fire_alarm = 0;
            $water_leakage = 0;
            $power_failure = 0;
            $door_status = 0;
            $log_time = date('Y-m-d H:i:s');

            $max_temparature = 30;
            $max_humidity = 50;

            try {

                $json = file_get_contents(Storage::path('alarm_rules.json'));
                $json_data = json_decode($json, true);

                $max_temparature = $json_data['max_temparature'];
                $max_humidity =  $json_data['max_humidity'];
            } catch (\Exception $e) {
            }

            $device_serial_number = $request->serial_number;
            if ($request->filled("temperature")) {
                $temparature = $request->temperature;
            }
            if ($request->filled("humidity")) {
                $humidity = $request->humidity;
            }
            if ($request->filled("fire_alarm")) {
                $fire_alarm = $request->fire_alarm;
            }
            if ($request->filled("water_leakage")) {
                $water_leakage = $request->water_leakage;
            }
            if ($request->filled("power_failure")) {
                $power_failure = $request->power_failure;
            }
            if ($request->filled("door_status")) {
                $door_status = $request->door_status;
            }


            if ($device_serial_number != '') {

                $logs["serial_number"] = $device_serial_number;
                $logs["temparature"] = $temparature;
                $logs["humidity"] = $humidity;
                $logs["fire_alarm"] = $fire_alarm;
                $logs["water_leakage"] = $water_leakage;
                $logs["power_failure"] = $power_failure;
                $logs["door_status"] = $door_status;

                $logs["log_time"] = $log_time;
                DeviceSensorLogs::create($logs);


                $row = [];

                if ($temparature >= $max_temparature) {
                    $row["temparature_alarm_status"] = 1;
                    $row["temparature_alarm_start_datetime"] = $log_time;
                }
                if ($humidity >= $max_humidity) {
                    $row["humidity_alarm_status"] = 1;
                    $row["humidity_alarm_start_datetime"] = $log_time;
                }


                if ($fire_alarm == 1) {
                    $row["fire_alarm_status"] = 1;
                    $row["fire_alarm_start_datetime"] = $log_time;
                }

                if ($water_leakage == 1) {
                    $row["water_alarm_status"] = 1;
                    $row["water_alarm_start_datetime"] = $log_time;
                }
                if ($power_failure == 1) {
                    $row["power_alarm_status"] = 1;
                    $row["power_alarm_start_datetime"] = $log_time;
                }
                if ($door_status == 1) {
                    $row["door_open_status"] = 1;
                    $row["door_open_start_datetime"] = $log_time;
                }


                //return [$logs, $row];

                Device::where("serial_number", $device_serial_number)
                    ->update($row);
                return $this->response('Successfully Updated', null, true);
            }
        } catch (\Exception $e) {
            Storage::append("logs/alarm_error/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()) . ' \n' . $e->getMessage());
        }

        return $this->response('Data error', null, false);
    }
}
