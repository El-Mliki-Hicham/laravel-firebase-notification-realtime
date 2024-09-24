<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/GetNotifications", [NotificationController::class, 'GetNotifications']);
// test if this token is exsist
Route::get("/send-fcm-test", function () {
    $firebaseMessaging = app('firebase.messaging');
    $fcmToken  = "faqwinCDTICwUnSOlzDnb7:APA91bEQT1QYfLD88CvMSTKrfDFv2ipnVSNIGk-zfXd1aqSbxTjXUZfraD0rW2kCZ11rHBpil9PIRU3gZ2l0V0k7w4BlbeyqD_cNwiF225AE_HVEFh1xlRqFr_TY8C0hqavNLb1XMuEx";
    $message = CloudMessage::withTarget('token', $fcmToken)
        ->withNotification(Notification::create('Test Notification', 'This is a test message from Firebase!'));

    try {
        $firebaseMessaging->send($message);
        return response()->json(['status' => 'Notification sent successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
