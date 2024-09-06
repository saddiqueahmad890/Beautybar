<?php

use App\Models\Notification;

use Carbon\Carbon;


use App\Mail\DynamicMail;
use Illuminate\Support\Facades\Mail;

if (!function_exists('sendDynamicEmail')) {
    function sendDynamicEmail($recipientName, $recipientEmail, $messageBody)
    {
        // Validate email address format
        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email address provided.");
        }

        Mail::to($recipientEmail)->send(new DynamicMail($recipientName, $messageBody));
    }
}

if (!function_exists('getDocNumber')) {
    function getDocNumber($id,$type='')
    {
        return $type . date('y') . date('m') . str_pad($id,  STR_PAD_LEFT);


    }
}


if (!function_exists('displayIndexValue')) {
    function displayIndexValue($index,$array)
    {
        $index = trim($index);
        if(is_numeric($index)) {
            if(array_key_exists($index,$array)) {
                return $array[$index];
            } else {
                return $index;
            }
        } else if(!empty($index)) {
            return $index;
        }
    }
}


if (!function_exists('calculateAge')) {
    function calculateAge($date_of_birth)
    {
        $dob = Carbon::parse($date_of_birth);
        $today = Carbon::now();

        $years = $today->diffInYears($date_of_birth);


        return [
            'years' => $years,

        ];
    }
}

if (!function_exists('sendNotification')) {
    function sendNotification($Id,$url,$msg,$apple=null)
    {
        $notification = new Notification([
            'notification_from' => auth()->id(),
            'notification_to' => 1,
            'text' => $msg,
            'url' => $url,
         ]);
        $notification->save();
    }
}

if (!function_exists('send_Notification')) {
    function send_Notification($url,$msg)
    {
        $notification = new Notification([
            'notification_from' => auth()->id(),
            'notification_to' => 1,
            'text' => $msg,
            'url' => $url,
         ]);
        $notification->save();
    }
}
