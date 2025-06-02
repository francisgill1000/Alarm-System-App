<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class MqttService
{
    protected $mqtt;
    protected $clientId;
    protected $mqttDeviceClientId;

    public function __construct()
    {
        $host = env('MQTT_HOST', 'broker.hivemq.com');
        $port = env('MQTT_PORT', 1883);
        $this->clientId = env('MQTT_CLIENT_ID', 'laravel-client-' . uniqid());
        $this->mqttDeviceClientId = env('MQTT_DEVICE_CLIENTID');

        Log::info($this->clientId);

        $connectionSettings = new ConnectionSettings();

        $this->mqtt = new MqttClient($host, $port, $this->clientId);
        $this->mqtt->connect($connectionSettings, true);
    }

    /**
     * Publish a message to a topic.
     */
    public function publish($topic, $message, $serial_number)
    {
        $clientId = env('MQTT_CLIENT_ID', 'laravel-client-' . uniqid());
        $host = env('MQTT_HOST', 'broker.hivemq.com');
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
            try {
                $this->mqtt->subscribe($this->mqttDeviceClientId . '/+/heartbeat', function ($topic, $message) {
                    $serialNumber = $this->extractSerial($topic);

                    $logPath = base_path('../../mytime2cloud/arduino-sdk/' . date("Y-m-d") . '.log');
                    File::prepend($logPath, "[" . now() . "] Received heartbeat from device: $serialNumber\n");

                    Device::where("serial_number", $serialNumber)->update([
                        'status_id' => 1,
                        'last_live_datetime' => date("Y-m-d H:i:s"),
                    ]);

                    //get config details

                    $json = json_decode($message, true);
                    if (isset($json['config'])) {
                        Cache::put("device_config_$serialNumber", $json['config'], now()->addMinutes(5));
                    }
                });

                $this->mqtt->subscribe($this->mqttDeviceClientId . '/+/config', function ($topic, $message) {
                    $serialNumber = $this->extractSerial($topic);


                    $json = json_decode($message, true);

                    if (isset($json['config'])) {


                        Cache::put("device_config_$serialNumber", $json['config'], now()->addMinutes(2));

                        $logPath = base_path('../../mytime2cloud/arduino-sdk/' . date("Y-m-d") . '.log');
                        File::prepend($logPath, "[" . now() . "] Config received from $serialNumber\n");
                    }
                });

                $this->mqtt->loop(true); // Blocking loop
            } catch (\Throwable $e) {
                $logPath = base_path('../../mytime2cloud/arduino-sdk/' . date("Y-m-d") . '.log');
                File::prepend($logPath, "[" . now() . "] ❌ MQTT Exception: " . $e->getMessage() . "\n");

                Log::error("❌ MQTT Exception: " . $e->getMessage());

                sleep(5); // Wait and retry
                $this->reconnect();
            }
        }
    }

    protected function reconnect()
    {
        $host = env('MQTT_HOST', 'broker.hivemq.com');
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
