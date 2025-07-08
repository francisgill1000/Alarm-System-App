<?php

namespace App\Services;

use App\Http\Controllers\Alarm\Api\ApiAlarmControlController;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

use Illuminate\Http\Request;


class MqttService
{
    protected $mqtt;
    protected $clientId;
    protected $mqttDeviceClientId;

    public function __construct()
    {
        $host = env('MQTT_HOST');
        $port = env('MQTT_PORT', 1883);
        $this->clientId = 'laravel-client-' . uniqid(); //env('MQTT_CLIENT_ID', 'laravel-client-' . uniqid());
        $this->mqttDeviceClientId = env('MQTT_DEVICE_CLIENTID');

        Log::info("MQTT Initialied " . $this->clientId);

        $connectionSettings = new ConnectionSettings();

        $this->mqtt = new MqttClient($host, $port, $this->clientId);
        $this->mqtt->connect($connectionSettings, true);
    }

    /**
     * Publish a message to a topic.
     */
    public function publish($topic, $message, $serial_number)
    {
        $clientId = 'laravel-client-' . uniqid(); //env('MQTT_CLIENT_ID', 'laravel-client-' . uniqid());
        $host =  env('MQTT_HOST'); //env('MQTT_HOST', '165.22.222.17');
        $port = env('MQTT_PORT', 1883);

        $mqtt = new MqttClient($host, $port, $clientId);
        $mqtt->connect(new ConnectionSettings(), true);

        $mqtt->publish($topic, $message, 0, false);
        $mqtt->disconnect();
    }

    /**
     * Subscribe and listen for MQTT heartbeat and config messages.
     */
    public function subscribeAndListen()
    {
        while (true) {
            // echo "heartbeat";
            try {
                $this->mqtt->subscribe($this->mqttDeviceClientId . '/+/heartbeat', function ($topic, $message) {



                    echo "heartbeat\n";
                    try {

                        $serialNumber = $this->extractSerial($topic);

                        // Log::info($message);

                        echo "\n";

                        $logPath = base_path('../../mytime2cloud/mqtt-logs/' . date("Y-m-d") . '.log');
                        File::prepend($logPath, "[" . now() . "] " . $message . "\n");

                        Device::where("serial_number", $serialNumber)->update([
                            'status_id' => 1,
                            'last_live_datetime' => date("Y-m-d H:i:s"),
                        ]);

                        //get config details
                        /*
                        $json = json_decode($message, true);
                        if (isset($json['config'])) {

                            //Cache::forget("device_config_$serialNumber");

                            echo "Config is Available\n";
                            Cache::put("device_config_$serialNumber", $json['config'], now()->addMinutes(1));
                        }*/
                    } catch (\Throwable $e) {

                        echo "ERROR\n";
                        $logPath = base_path('../../mytime2cloud/mqtt-logs/' . date("Y-m-d") . '.log');
                        File::prepend($logPath, "[" . now() . "] ❌ MQTT heartbeat Exception: " . $e->getMessage() . "\n");

                        // Log::error("❌ MQTT heartbeat Exception: " . $e->getMessage());

                        // sleep(5); // Wait and retry
                        //$this->reconnect();
                    }
                });

                $this->mqtt->subscribe($this->mqttDeviceClientId . '/+/config', function ($topic, $message) {
                    $logPath = base_path('../../mytime2cloud/mqtt-logs/' . date("Y-m-d") . '.log');
                    try {

                        echo "All\n";

                        // Log::info($message);
                        echo "\n";
                        $serialNumber = $this->extractSerial($topic);

                        File::prepend($logPath, "[" . now() . "] Data :" . $message . "\n");

                        //echo $message;
                        $json = json_decode($message, true);

                        // if (isset($json['config'])) {
                        //     //Cache::forget("device_config_$serialNumber");


                        //     echo "Config is Available\n";

                        //     Cache::put("device_config_$serialNumber", $json['config'], now()->addMinutes(1));

                        //     $logPath = base_path('../../mytime2cloud/mqtt-logs/' . date("Y-m-d") . '.log');
                        //     File::prepend($logPath, "[" . now() . "] Config received from $serialNumber\n");
                        // }

                        if ($json) {
                            if ($json && isset($json['type']) && ($json['type'] == "alarm" || $json['type'] == "sensor")) {

                                echo "alarm or sensor info \n";

                                // Log::info($message);
                                echo "\n";
                                File::prepend($logPath, "[" . now() . "]  " . $json['type'] . " " . $message . " \n");
                                $data = $json;

                                // Create request object with data
                                $request = new Request($data);
                                // Call the method with your custom request
                                $controller = new ApiAlarmControlController();
                                $controller->LogDeviceStatus($request);
                            }
                        }
                    } catch (\Throwable $e) {

                        echo "ERROR\n";
                        $logPath = base_path('../../mytime2cloud/mqtt-logs/' . date("Y-m-d") . '.log');
                        File::prepend($logPath, "[" . now() . "] ❌ MQTT config Exception: " . $e->getMessage() . "\n");






                        // Log::error("❌ MQTT config Exception: " . $e->getMessage());

                        //sleep(5); // Wait and retry
                        //$this->reconnect();
                    }
                });


                $this->mqtt->loop(true); // Blocking loop
            } catch (\Throwable $e) {

                // echo "ERROR SERVICE\n";
                // $logPath = base_path('../../mytime2cloud/mqtt-logs/' . date("Y-m-d") . '.log');
                // File::prepend($logPath, "[" . now() . "] ❌ MQTT SERVICE Exception: " . $e->getMessage() . "\n");

                //Log::error("❌ MQTT SERVICE Exception: " . $e->getMessage());

                sleep(5); // Wait and retry
                $this->reconnect();
            }
        } //loop
    }

    protected function reconnect()
    {
        $host = env('MQTT_HOST', '165.22.222.17');
        $port = env('MQTT_PORT', 1883);
        $this->clientId = env('MQTT_CLIENT_ID', 'laravel-client-' . uniqid());

        $this->mqtt = new MqttClient($host, $port, $this->clientId);
        $this->mqtt->connect(new ConnectionSettings(), true);
    }


    /**
     * Extract serial number from MQTT topic.
     */
    protected function extractSerial($topic)
    {
        preg_match('#' . $this->mqttDeviceClientId . '/([^/]+)/#', $topic, $matches);
        return $matches[1] ?? null;
    }
}
