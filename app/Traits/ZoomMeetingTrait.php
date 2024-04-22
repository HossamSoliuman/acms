<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log as FacadesLog;
use Log;

/**
 * trait ZoomMeetingTrait
 */
trait ZoomMeetingTrait
{
    public $client;
    public $jwt;
    public $headers;
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function __construct()
    {
        $this->client = new Client();
        $this->jwt = $this->generateZoomToken();
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->jwt,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    public function generateZoomToken()
    {
        $key = "bW5me4ZGSTG2imKhBtNi8Q";
        $secret = "f1GQ3fjDBwuxDshCyponuVrbyEIR3cxz";
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];

        return \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
    }

    private function retrieveZoomUrl()
    {
        return env('ZOOM_API_URL');
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        try {
            $date = new \DateTime($dateTime);
            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            FacadesLog::error('ZoomJWT->toZoomTimeFormat : ' . $e->getMessage());
            return '';
        }
    }

    public function create($data)
    {
        $data['topic'] = 'ACMS system meeting';
        $data['duration'] = 30;
        $path = '/users/me/meetings'; // Fixed the URL path
        $url = $this->retrieveZoomUrl();

        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'topic'      => $data['topic'],
                'type'       => self::MEETING_TYPE_SCHEDULE,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration'   => $data['duration'],
                'agenda'     => (!empty($data['agenda'])) ? $data['agenda'] : null,
                'timezone'   => 'Africa/Cairo',
                'settings'   => [
                    'host_video'        =>  true,
                    'participant_video' => true,
                    'waiting_room'      => true,
                ],
            ]),
        ];

        try {
            $response =  $this->client->post($url . $path, $body);
            return [
                'success' => $response->getStatusCode() === 201,
                'data'    => json_decode($response->getBody(), true),
            ];
        } catch (\Exception $e) {
            // Log the error
            FacadesLog::error('ZoomJWT->create : ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(), // Return the error message
            ];
        }
    }
}
