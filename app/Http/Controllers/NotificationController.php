<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
// use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class NotificationController extends Controller
{
    public function GetNotifications()
    {
        try {
            $receiverId = 708;
            $userReceiver = User::where("id", $receiverId)->first();
            //dd($userReceiver);
            $senderId = 1;
            if ($userReceiver) {
                // Send Notification
                $firebaseMessaging = app('firebase.messaging');
                $fcmToken = "";
                //dd($fcmToken);

                if ($fcmToken) {
                    $notification = FirebaseNotification::create("admin" . ' ' . "elmliki", "hello you have email");
                    $cloudMessage = CloudMessage::withTarget('token', $fcmToken)
                        ->withNotification($notification)
                        ->withData(['id' => $senderId, "type" => "new_message"]);

                    try {
                        $firebaseMessaging->send($cloudMessage);
                    } catch (Exception $e) {
                        // Handle other potential errors    
                        // Log the error or take appropriate action
                        Log::error('Error sending FCM message: ' . $e->getMessage());
                    }
                } else {
                    Log::error('FCM token not found for user ID: ' . $receiverId);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

            // $firebase = (new Factory)
            //     ->withServiceAccount(base_path('notif-spabooking-firebase-adminsdk-fslch-fbfbc15dfe.json'))
            //     ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

            // $database = $firebase->createDatabase();
            // $data = $database->getReference('data');

            // return $data->getValue();  // Corrected here
