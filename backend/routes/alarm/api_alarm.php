<?php

use App\Http\Controllers\Alarm\Api\ApiAlarmControlController;
use App\Http\Controllers\Alarm\DeviceSensorLogsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AnnouncementsCategoriesController;
use App\Models\Alarm\DeviceSensorLogs;
use Illuminate\Support\Facades\Route;

// announcement
//Route::apiResource('announcement', AnnouncementController::class);
// Route::get('announcement_list', [AnnouncementController::class, 'annoucement_list']);
// Route::get('announcement/search/{key}', [AnnouncementController::class, 'search']);
Route::get('alarm_device_status', [ApiAlarmControlController::class, 'LogDeviceStatus']);
//Route::get('announcement/employee/{id}', [AnnouncementController::class, 'getAnnouncement']);

Route::get('alarm_dashboard_get_temparature_latest', [DeviceSensorLogsController::class, 'getDeviceLatestTemperature']);