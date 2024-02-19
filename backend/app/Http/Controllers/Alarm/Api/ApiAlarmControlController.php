<?php

namespace App\Http\Controllers\Alarm\Api;

use App\Console\Commands\SendWhatsappNotification;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WhatsappController;
use App\Mail\DbBackupMail;
use App\Mail\EmailContentDefault;
use App\Mail\ReportNotificationMail;
use App\Models\Alarm\DeviceSensorLogs;
use Illuminate\Http\Request;
use App\Models\Community\AttendanceLog;
use App\Models\Company;
use App\Models\Device;
use App\Models\ReportNotification;
use App\Models\ReportNotificationLogs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
                    $smoke_alarm = $request->smokeStatus;
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
                $logs["smoke_alarm"] = $smoke_alarm == 1 ? 0 : 1;
                $logs["water_leakage"] = $water_leakage;
                $logs["power_failure"] = $power_failure;
                $logs["door_status"] = $door_status; //== 1 ? 0 : 1;

                // $logs["smoke_alarm"] = $smoke_alarm == 'true' ? 1 : 0;
                // $logs["water_leakage"] = $water_leakage == 'true' ? 1 : 0;
                // $logs["power_failure"] = $power_failure == 'true' ? 1 : 0;
                // $logs["door_status"] = $door_status == 'true' ? 0 : 1;

                $logs["log_time"] = $log_time;
                DeviceSensorLogs::create($logs);

                $deviceModel = Device::where("serial_number", $device_serial_number);

                $row = [];

                if ($temparature >= $max_temparature) {
                    $row["temparature_alarm_status"] = 1;
                    $row["temparature_alarm_start_datetime"] = $log_time;
                }
                if ($humidity >= $max_humidity) {
                    $row["humidity_alarm_status"] = 1;
                    $row["humidity_alarm_start_datetime"] = $log_time;
                }


                // if ($fire_alarm == 1) {
                //     $row["fire_alarm_status"] = 1;
                //     $row["fire_alarm_start_datetime"] = $log_time;
                // }
                if ($smoke_alarm == 1) {
                    $row["smoke_alarm_status"] = 1;
                    $row["smoke_alarm_start_datetime"] = $log_time;

                    $message[] =  $this->SendWhatsappNotification("Smoke Detection",   $deviceModel->first()->name, $deviceModel->first()->company_id);
                }

                if ($water_leakage == 1) {
                    $row["water_alarm_status"] = 1;
                    $row["water_alarm_start_datetime"] = $log_time;
                    $message[] = $this->SendWhatsappNotification("Water Leakage ",  $deviceModel->first()->name, $deviceModel->first()->company_id);
                }
                if ($power_failure == 1) {
                    $row["power_alarm_status"] = 1;
                    $row["power_alarm_start_datetime"] = $log_time;
                    $message[] = $this->SendWhatsappNotification("Power OFF", $deviceModel->first()->name, $deviceModel->first()->company_id);
                }
                if ($door_status == 1) {
                    $row["door_open_status"] = 1;
                    $row["door_open_start_datetime"] = $log_time;
                    $message[] =  $this->SendWhatsappNotification("Door Open",  $deviceModel->first()->name, $deviceModel->first()->company_id);
                }


                //return [$logs, $row];

                // Device::where("serial_number", $device_serial_number)
                //     ->update($row);
                $deviceModel->clone()->update($row);


                return $this->response('Successfully Updated', null, true);
            }
        } catch (\Exception $e) {
            Storage::append("logs/alarm_error/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()) . ' \n' . $e->getMessage());

            return  $e->getMessage();
        }

        return $this->response('Data error', null, false);
    }

    public function SendWhatsappNotification($issue, $room_name, $company_id)
    {


        $reports = ReportNotification::where("company_id", $company_id)->get();

        foreach ($reports as $model) {
            $id = $model["id"];

            $script_name = "ReportNotificationCrons";

            $date = date("Y-m-d H:i:s");

            // try {

            $model = ReportNotification::with(["managers", "company.company_mail_content"])->where("id", $id)->first();


            if (in_array("Email", $model->mediums)) {



                foreach ($model->managers as $key => $value) {
                    $body_content1 = "📊 *{$issue} Notification <br/>";

                    $body_content1 = " Hello, {$value->name} <br/>";
                    $body_content1 .= " Company:  {$model->company->name}<br/>";
                    $body_content1 .= "This is Notifing you about {$issue} status <br/>";
                    $body_content1 .= " Date:  $date<br/>";
                    $body_content1 .= "Room Name: *{$room_name}<br/><br/><br/><br/>";

                    $body_content1 .= "*Xtreme Guard*<br/>";

                    $data = [
                        'subject' => "{$issue} Notification - {$date}",
                        'body' => $body_content1,
                    ];



                    $body_content1 = new EmailContentDefault($data);

                    if ($value->email != '') {
                        Mail::to($value->email)
                            ->send($body_content1);


                        $data = ["company_id" => $value->company_id, "branch_id" => $value->branch_id, "notification_id" => $value->notification_id, "notification_manager_id" => $value->id, "email" => $value->email];



                        ReportNotificationLogs::create($data);
                    }
                }
            } else {
                echo "[" . $date . "] Cron: $script_name. No emails are configured";
            }

            //wahtsapp with attachments
            if (in_array("Whatsapp", $model->mediums)) {

                foreach ($model->managers as $key => $manager) {

                    if ($manager->whatsapp_number != '') {


                        $body_content1 = "📊 *{$issue} Notification* 📊\n\n";

                        $body_content1 = "*Hello, {$manager->name}*\n\n";
                        $body_content1 .= "*Company:  {$model->company->name}*\n\n";
                        $body_content1 .= "This is Notifing you about {$issue} status \n\n";
                        $body_content1 .= "*Date:* $date\n\n";
                        $body_content1 .= "Room Name: *{$room_name}*\n\n";

                        $body_content1 .= "*Xtreme Guard*\n";




                        if (count($model->company->company_whatsapp_content))
                            $body_content1 .= $model->company->company_whatsapp_content[0]->content;

                        (new WhatsappController())->sendWhatsappNotification($model->company, $body_content1, $manager->whatsapp_number, []);

                        $data = [
                            "company_id" => $model->company->id,
                            "branch_id" => $manager->branch_id,
                            "notification_id" => $manager->notification_id,
                            "notification_manager_id" => $manager->id,
                            "whatsapp_number" => $manager->whatsapp_number
                        ];

                        ReportNotificationLogs::create($data);
                    }
                }
            }
            // } catch (\Exception $e) {
            // }


            // $device = Device::with(["company"])->where("serial_number", $device_serial_number)->first();
            // $message = "";

            // $date = date("d-M-Y");









            // return (new SendWhatsappNotification())->sendWhatsappNotification($company, $message, $number, $attachments = []);
        }
    }
    // public function LogDeviceStatus_old(Request $request)
    // {

    //     try {
    //         Storage::append("logs/alarm/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()));

    //         $temparature = 0;
    //         $humidity = 0;
    //         $fire_alarm = 0;
    //         $water_leakage = 0;
    //         $power_failure = 0;
    //         $door_status = 0;
    //         $log_time = date('Y-m-d H:i:s');

    //         $max_temparature = 30;
    //         $max_humidity = 50;

    //         try {

    //             $json = file_get_contents(Storage::path('alarm_rules.json'));
    //             $json_data = json_decode($json, true);

    //             $max_temparature = $json_data['max_temparature'];
    //             $max_humidity =  $json_data['max_humidity'];
    //         } catch (\Exception $e) {
    //         }

    //         $device_serial_number = $request->serial_number;
    //         if ($request->filled("temperature")) {
    //             $temparature = $request->temperature;
    //         }
    //         if ($request->filled("humidity")) {
    //             $humidity = $request->humidity;
    //         }
    //         if ($request->filled("fire_alarm")) {
    //             $fire_alarm = $request->fire_alarm;
    //         }
    //         if ($request->filled("water_leakage")) {
    //             $water_leakage = $request->water_leakage;
    //         }
    //         if ($request->filled("power_failure")) {
    //             $power_failure = $request->power_failure;
    //         }
    //         if ($request->filled("door_status")) {
    //             $door_status = $request->door_status;
    //         }


    //         if ($device_serial_number != '') {

    //             $logs["serial_number"] = $device_serial_number;
    //             $logs["temparature"] = $temparature;
    //             $logs["humidity"] = $humidity;
    //             $logs["fire_alarm"] = $fire_alarm;
    //             $logs["water_leakage"] = $water_leakage;
    //             $logs["power_failure"] = $power_failure;
    //             $logs["door_status"] = $door_status;

    //             $logs["log_time"] = $log_time;
    //             DeviceSensorLogs::create($logs);


    //             $row = [];

    //             if ($temparature >= $max_temparature) {
    //                 $row["temparature_alarm_status"] = 1;
    //                 $row["temparature_alarm_start_datetime"] = $log_time;
    //             }
    //             if ($humidity >= $max_humidity) {
    //                 $row["humidity_alarm_status"] = 1;
    //                 $row["humidity_alarm_start_datetime"] = $log_time;
    //             }


    //             if ($fire_alarm == 1) {
    //                 $row["fire_alarm_status"] = 1;
    //                 $row["fire_alarm_start_datetime"] = $log_time;
    //             }

    //             if ($water_leakage == 1) {
    //                 $row["water_alarm_status"] = 1;
    //                 $row["water_alarm_start_datetime"] = $log_time;
    //             }
    //             if ($power_failure == 1) {
    //                 $row["power_alarm_status"] = 1;
    //                 $row["power_alarm_start_datetime"] = $log_time;
    //             }
    //             if ($door_status == 1) {
    //                 $row["door_open_status"] = 1;
    //                 $row["door_open_start_datetime"] = $log_time;
    //             }


    //             //return [$logs, $row];

    //             Device::where("serial_number", $device_serial_number)
    //                 ->update($row);
    //             return $this->response('Successfully Updated', null, true);
    //         }
    //     } catch (\Exception $e) {
    //         Storage::append("logs/alarm_error/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()) . ' \n' . $e->getMessage());
    //     }

    //     return $this->response('Data error', null, false);
    // }
}
