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
use DateTime;
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

            $temparature = -1;
            $humidity = -1;

            $smoke_alarm = -1;
            $water_leakage = -1;
            $power_failure = -1;
            $door_status = -1;
            $fire_alarm = 0;
            $log_time = date('Y-m-d H:i:s');

            $max_temparature = '';
            $max_humidity = '';

            // try {

            //     $json = file_get_contents(Storage::path('alarm_rules.json'));
            //     $json_data = json_decode($json, true);

            //     $max_temparature = $json_data['max_temparature'];
            //     $max_humidity =  $json_data['max_humidity'];
            // } catch (\Exception $e) {
            // }

            $device_serial_number = $request->serialNumber;


            if ($device_serial_number != '') {



                if ($request->filled("temperature")) {
                    $temparature = $request->temperature == 'NaN' ? 0 : $request->temperature;
                    //$temparature = (float) $request->temperature;

                }
                if ($request->filled("humidity")) {
                    $humidity = $request->humidity == 'NaN' ? 0 : $request->humidity;
                }
                if ($request->filled("fire_alarm")) {
                    $fire_alarm = $request->fire_alarm;
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

                if ($temparature == "NaN") {
                    $temparature = 0;
                }
                if ($humidity == "NaN") {
                    $humidity = 0;
                }


                $logs["serial_number"] = $device_serial_number;
                $logs["temparature"] = $temparature;
                $logs["fire_alarm"] = $fire_alarm;
                $logs["humidity"] = $humidity;
                $logs["smoke_alarm"] = $smoke_alarm; //== 1 ? 0 : 1;

                $logs["water_leakage"] = $water_leakage;
                $logs["power_failure"] = $power_failure;
                $logs["door_status"] = $door_status; //== 1 ? 0 : 1;



                $logs["log_time"] = $log_time;
                try {
                    DeviceSensorLogs::create($logs);
                } catch (\Exception $e) {
                }
                $deviceModel = Device::where("serial_number", $device_serial_number);

                $deviceObj = $deviceModel->clone()->get();
                //update live status 
                $deviceModel->clone()->update(["status_id" => 1, "last_live_datetime" => date("Y-m-d H:i:s")]);
                if (count($deviceObj) == 0) {
                    return $this->response('Device Information is not available', null, false);
                }
                $name = $deviceModel->clone()->first()->name;
                $deviceObj = $deviceObj[0];

                if ($deviceObj['temperature_threshold'] > 0) {

                    if ($temparature >= $deviceObj['temperature_threshold']) {

                        $ignore15Minutes = false;


                        if ($deviceObj['temparature_alarm_status'] == 0) {
                            $row = [];
                            $row["temparature_alarm_status"] = 1;
                            $row["temparature_alarm_start_datetime"] = $log_time;
                            $row["temparature_alarm_end_datetime"] = null;
                            $deviceModel->clone()->update($row);
                            $ignore15Minutes = true;
                        }

                        $message[] =  $this->SendWhatsappNotification($name . " - Threshold Temperature Alarm is  ON",   $name, $deviceModel->clone()->first(), $log_time, $deviceObj['temparature_alarm_status'], ["temparature" => $temparature, "max_temparature" => $deviceObj['temperature_threshold']], $ignore15Minutes);
                    } else {



                        if ($deviceObj['temparature_alarm_status'] == 1) {
                            $ignore15Minutes = true;
                            $message[] =  $this->SendWhatsappNotification($name . " - Threshold Temperature Alarm is  OFF",   $name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes, ["temparature" => $temparature, "max_temparature" => $deviceObj['temperature_threshold']]);
                            $row = [];
                            $row["temparature_alarm_status"] = 0;

                            $row["temparature_alarm_end_datetime"] = $log_time;
                            $deviceModel->clone()->update($row);
                        }
                    }
                }

                $row = [];




                // if ($humidity >= $max_humidity) {
                //     $row["humidity_alarm_status"] = 1;
                //     $row["humidity_alarm_start_datetime"] = $log_time;
                // }



                $row["smoke_alarm_status"] = $smoke_alarm;
                $row["water_alarm_status"] = $water_leakage;
                $row["power_alarm_status"] = $power_failure;
                $row["door_open_status"] = $door_status;



                //smoke_alarm
                if ($smoke_alarm == 1) {
                    $ignore15Minutes = false;

                    if ($deviceObj['smoke_alarm_status'] == 0) {
                        $row = [];
                        $row["smoke_alarm_status"] = $smoke_alarm;
                        $row["smoke_alarm_start_datetime"] = $log_time;
                        $row["smoke_alarm_end_datetime"] = null;
                        $deviceModel->clone()->update($row);
                    }
                    $message[] =  $this->SendWhatsappNotification($name . " - Smoke Alarm is ON",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                } else if ($smoke_alarm == 0) {



                    if ($deviceObj['smoke_alarm_status'] == 1) {
                        $row = [];
                        $row["smoke_alarm_status"] = $smoke_alarm;
                        $row["smoke_alarm_end_datetime"] = $log_time;

                        $ignore15Minutes = true;
                        $deviceModel->clone()->where("smoke_alarm_status", 1)->update($row);
                        $message[] =  $this->SendWhatsappNotification($name . " - Smoke Alarm is OFF",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                    }
                }
                //fire_alarm_status
                if ($fire_alarm == 1) {

                    $ignore15Minutes = false;



                    if ($deviceObj['fire_alarm_status'] == 0) {
                        $ignore15Minutes = true;
                        $row = [];
                        $row["fire_alarm_status"] = $fire_alarm;
                        $row["fire_alarm_start_datetime"] = $log_time;
                        $row["fire_alarm_end_datetime"] = null;
                    }
                    $deviceModel->clone()->update($row);
                    $message[] =  $this->SendWhatsappNotification($name . " - Fire Alarm is ON",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                } else if ($fire_alarm == 0) {

                    if ($deviceObj['fire_alarm_status'] == 1) {
                        $row = [];
                        $row["fire_alarm_status"] = $fire_alarm;
                        $row["fire_alarm_end_datetime"] = $log_time;
                        $ignore15Minutes = true;


                        $deviceModel->clone()->update($row);
                        $message[] =  $this->SendWhatsappNotification($name . " - Fire Alarm is off",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time,  $ignore15Minutes);
                    }
                }
                //water_leakage
                if ($water_leakage == 1) {
                    $ignore15Minutes = false;


                    if ($deviceObj['water_alarm_status'] == 0) {
                        $ignore15Minutes = true;
                        $row = [];
                        $row["water_alarm_status"] = $water_leakage;
                        $row["water_alarm_start_datetime"] = $log_time;
                        $row["water_alarm_end_datetime"] = null;

                        $deviceModel->clone()->update($row);
                    }
                    $message[] = $this->SendWhatsappNotification($name . " - Water Leakage Alarm is ON",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                } else if ($water_leakage == 0) {

                    if ($deviceObj['water_alarm_status'] == 1) {
                        $row = [];
                        $row["water_alarm_status"] = $water_leakage;
                        $row["water_alarm_end_datetime"] = $log_time;


                        $ignore15Minutes = true;

                        $deviceModel->clone()->update($row);
                        $message[] =  $this->SendWhatsappNotification($name . " - Water Leakage Alarm is off",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                    }
                }
                //power_failure
                if ($power_failure == 1) {
                    $ignore15Minutes = false;

                    if ($deviceObj['power_alarm_status'] == 0) {
                        $row = [];
                        $row["power_alarm_status"] = $power_failure;
                        $row["power_alarm_start_datetime"] = $log_time;
                        $row["power_alarm_end_datetime"] = null;
                        $ignore15Minutes = true;
                        $deviceModel->clone()->update($row);
                    }

                    $message[] = $this->SendWhatsappNotification($name . " - Power Alarm is ON",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                } else if ($power_failure == 0) {

                    if ($deviceObj['power_alarm_status'] == 1) {
                        $row = [];
                        $row["power_alarm_status"] = $power_failure;
                        $row["power_alarm_end_datetime"] = $log_time;

                        $ignore15Minutes = true;

                        $deviceModel->clone()->update($row);
                        $message[] =  $this->SendWhatsappNotification($name . " - Power Alarm is OFF",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                    }
                    // $deviceModel->clone()->where("power_alarm_status", 1)->update($row);
                }
                //door_status
                if ($door_status == 1) {
                    $ignore15Minutes = false;

                    if ($deviceObj['door_open_status'] == 0) {
                        $row = [];
                        $row["door_open_status"] = $door_status;
                        $row["door_open_start_datetime"] = $log_time;
                        $row["door_open_end_datetime"] = null;
                        $ignore15Minutes = true;
                        $deviceModel->clone()->update($row);
                    }

                    $message[] =  $this->SendWhatsappNotification($name . " - Door Open Alarm is ON",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                } else  if ($door_status == 0) {

                    if ($deviceObj['door_open_status'] == 1) {
                        $ignore15Minutes = true;
                        $row = [];
                        $row["door_open_status"] = $door_status;
                        $row["door_open_end_datetime"] = $log_time;

                        $deviceModel->clone()->update($row);


                        $message[] =  $this->SendWhatsappNotification($name . " - Door Open  Alarm is off",   $deviceModel->clone()->first()->name, $deviceModel->clone()->first(), $log_time, $ignore15Minutes);
                    }
                }


                //return [$logs, $row];

                // Device::where("serial_number", $device_serial_number)
                //     ->update($row);



                return $this->response('Successfully Updated', null, true);
            }
        } catch (\Exception $e) {
            Storage::append("logs/alarm_error/api-requests-device-" . date('Y-m-d') . ".txt", date("Y-m-d H:i:s") .  " : "    . json_encode($request->all()) . ' \n' . $e->getMessage());

            return  $e->getMessage();
        }

        return $this->response('Data error', null, false);
    }

    public function SendWhatsappNotification($issue, $room_name, $model1, $date,  $ignore15Minutes, $tempArray = [])
    {
        $company_id = $model1->company_id;
        $branch_id = $model1->branch_id;

        //$reports = ReportNotification::where("company_id", $model1->company_id)->where("branch_id", $model1->branch_id)->get();

        $reports = ReportNotification::with(["managers.branch",  "company.company_mail_content"])


            ->with("managers", function ($query) use ($company_id, $branch_id) {
                $query->where("company_id", $company_id);
                $query->where("branch_id", $branch_id);
            })->get();

        foreach ($reports as $model) {
            $id = $model["id"];

            $script_name = "ReportNotificationCrons";

            // $date = date("Y-m-d H:i:s");

            // try {


            // $model = ReportNotification::with(["managers.branch",  "company.company_mail_content"])->where("id", $id)


            //     ->with("managers", function ($query) use ($company_id, $branch_id) {
            //         $query->where("company_id", $company_id);
            //         $query->where("branch_id", $branch_id);
            //     })

            //     ->first();

            if ($model)
                if (in_array("Email", $model->mediums)) {



                    foreach ($model->managers as $key => $value) {
                        $minutesDifference = 1000;

                        //wait 5 minutes to send notification
                        $notificationSentLogs = ReportNotificationLogs::where("notification_id", $value->notification_id)
                            ->where("notification_manager_id", $value->id)
                            ->where("email", $value->email)
                            ->where("subject", $issue)

                            ->orderBy("created_at", "DESC")->first();

                        if ($notificationSentLogs) {
                            $datetime1 = new DateTime(date("Y-m-d H:i"));
                            $datetime2 = new DateTime($notificationSentLogs["created_at"]);

                            $interval = $datetime1->diff($datetime2);
                            $minutesDifference =  $interval->i + ($interval->h * 60) + ($interval->days * 1440);
                        }


                        if ($minutesDifference >=   15 || $ignore15Minutes) { // 




                            $branch_name = $value->branch->branch_name;

                            $body_content1 = "📊 *{$issue} Notification <br/>";

                            $body_content1 = " Hello, {$value->name} <br/>";
                            $body_content1 .= " Company:  {$model->company->name}<br/>";
                            $body_content1 .= "This is Notifing you about {$issue} status <br/>";

                            if (isset($tempArray["temparature"])) {
                                $body_content1 .= "Temperature:  {$tempArray["temparature"]}<br/>";
                            }
                            if (isset($tempArray["max_temparature"])) {
                                $body_content1 .= "Threshold:  {$tempArray["max_temparature"]}<br/>";
                            }

                            $body_content1 .= "Date:  $date<br/>";
                            $body_content1 .= "Room Name: {$room_name}<br/>";
                            $body_content1 .= "Branch: {$branch_name}<br/><br/><br/><br/>";
                            $body_content1 .= "*Xtreme Guard*<br/>";

                            $data = [
                                'subject' => "{$issue} Notification - {$date}",
                                'body' => $body_content1,
                            ];


                            $body_content1 = new EmailContentDefault($data);

                            if ($value->email != '') {
                                Mail::to($value->email)
                                    ->send($body_content1);


                                $data = ["company_id" => $value->company_id, "branch_id" => $value->branch_id, "notification_id" => $value->notification_id, "notification_manager_id" => $value->id, "email" => $value->email, "subject" => $issue];



                                ReportNotificationLogs::create($data);
                            }
                        }
                    }
                } else {
                    echo "[" . $date . "] Cron: $script_name. No emails are configured";
                }

            //wahtsapp with attachments
            if (in_array("Whatsapp", $model->mediums)) {

                foreach ($model->managers as $key => $manager) {
                    $minutesDifference = 1000; //minutes
                    //wait 5 minutes to send notification
                    $notificationSentLogs = ReportNotificationLogs::where("notification_id", $manager->notification_id)
                        ->where("notification_manager_id", $manager->id)
                        ->where("subject", $issue)
                        ->where("whatsapp_number", $manager->whatsapp_number)
                        ->orderBy("created_at", "DESC")->first();
                    $minutesDifference = 1000; //minutes
                    if ($notificationSentLogs) {
                        $datetime1 = new DateTime(date("Y-m-d H:i"));
                        $datetime2 = new DateTime($notificationSentLogs["created_at"]);
                        $interval = $datetime1->diff($datetime2);
                        $minutesDifference =  $interval->i + ($interval->h * 60) + ($interval->days * 1440);
                    }



                    if ($minutesDifference >=   15) { // 

                        if ($manager->whatsapp_number != '') {

                            $branch_name = $manager->branch->branch_name;

                            $body_content1 = "📊 *{$issue}* Notification  📊\n\n";

                            $body_content1 .= "Hello, *{$manager->name}*\n\n";
                            $body_content1 .= "Company:  {$model->company->name}\n\n";
                            $body_content1 .= "This is Notifing you about *{$issue}* status \n\n";
                            if (isset($tempArray["temparature"])) {
                                $body_content1 .= "Temperature:  {$tempArray["temparature"]}°C\n\n";
                            }
                            if (isset($tempArray["max_temparature"])) {
                                $body_content1 .= "*Threshold:  {$tempArray["max_temparature"]}°C*\n\n";
                            }
                            $body_content1 .= "Date:  $date\n";
                            $body_content1 .= "Room Name:  {$room_name}\n";

                            $body_content1 .= "Branch:  {$branch_name}\n";
                            $body_content1 .= "*Xtreme Guard*\n";




                            if (count($model->company->company_whatsapp_content))
                                $body_content1 .= $model->company->company_whatsapp_content[0]->content;

                            (new WhatsappController())->sendWhatsappNotification($model->company, $body_content1, $manager->whatsapp_number, []);

                            $data = [
                                "company_id" => $model->company->id,
                                "branch_id" => $manager->branch_id,
                                "notification_id" => $manager->notification_id,
                                "notification_manager_id" => $manager->id,
                                "whatsapp_number" => $manager->whatsapp_number,
                                "subject" => $issue
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
