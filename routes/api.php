<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apicontroller;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/user', function () {
    return \App\Models\User::all(); // This pulls a real user from your DB
});
Route::post('/register',[apicontroller::class, 'register']);
Route::post('/sendTestSms',[apicontroller::class, 'sendTestSms']);
Route::post('/otpsend',[apicontroller::class, 'otpsend']);
Route::post('/login',[apicontroller::class, 'login']);
Route::post('/forgotpassword',[apicontroller::class, 'forgotpassword']);
Route::post('/otpsendforforgotpassword',[apicontroller::class, 'otpsendforforgotpassword']);
Route::post('/getprofile',[apicontroller::class, 'getprofile']);
Route::post('/updateprofile',[apicontroller::class, 'updateprofile']);
Route::post('/changepassword',[apicontroller::class, 'changepassword']);
Route::post('/addride',[apicontroller::class, 'addride']);
Route::post('/getallride',[apicontroller::class, 'getallride']);
Route::post('/verify_id',[apicontroller::class, 'verify_id']);
Route::post('/verify_details',[apicontroller::class, 'verify_details']);
Route::post('/saveapi',[apicontroller::class, 'saveapi']);
Route::get('/pages',[apicontroller::class, 'pages']);
Route::post('/addvehicle',[apicontroller::class, 'addvehicle']);
Route::post('/updateVehicle',[apicontroller::class, 'updateVehicle']);
Route::post('/sentverifyotp',[apicontroller::class, 'sentverifyotp']);
Route::post('/recent_history',[apicontroller::class, 'recent_history']);
Route::post('/getsearch_history',[apicontroller::class, 'getsearch_history']);
Route::post('/cancel_ride',[apicontroller::class, 'cancel_ride']);
Route::post('/update_ride',[apicontroller::class, 'update_ride']);
Route::post('/booking',[apicontroller::class, 'booking']);
Route::post('/vehicle_brand',[apicontroller::class, 'vehicle_brand']);
Route::post('/delete_vehicle',[apicontroller::class, 'delete_vehicle']);
Route::post('/vehicle_list',[apicontroller::class, 'vehicle_list']);
Route::post('/Delete_applybooking',[apicontroller::class, 'Delete_applybooking']);
Route::post('/Accept_ride',[apicontroller::class, 'Accept_ride']);
Route::post('/postal_address',[apicontroller::class, 'postal_address']);
Route::post('/postal_addresslist',[apicontroller::class, 'postal_addresslist']);
Route::post('/postal_addresslist',[apicontroller::class, 'postal_addresslist']);
Route::post('/delete_booking',[apicontroller::class, 'delete_booking']);
Route::post('/sendsms',[apicontroller::class, 'sendsms']);
Route::post('/updateprofileimg',[apicontroller::class, 'updateprofileimg']);
Route::post('/loginotp',[apicontroller::class, 'loginotp']);
Route::post('/history',[apicontroller::class, 'history']);
Route::post('/test',[apicontroller::class, 'test']);
Route::post('/complete_ride',[apicontroller::class, 'complete_ride']);
Route::post('/send_rating_reviews',[apicontroller::class, 'send_rating_reviews']);
Route::post('/rating_reviews_list',[apicontroller::class, 'rating_reviews_list']);
Route::post('/send_notification',[apicontroller::class, 'send_notification']);
Route::post('/delete_history',[apicontroller::class, 'delete_history']);
Route::post('/Faq',[apicontroller::class, 'Faq']);
Route::post('/logout',[apicontroller::class, 'logout']);
Route::post('/send_notification1',[apicontroller::class, 'send_notification1']);
Route::post('/verify_status',[apicontroller::class, 'verify_status']);
Route::post('/get_distance',[apicontroller::class, 'get_distance']);
Route::post('/push_notification',[apicontroller::class, 'push_notification']);
Route::post('/delete_user',[apicontroller::class, 'delete_user']);
Route::post('/reminder_notification',[apicontroller::class, 'reminder_notification']);
Route::post('/alert_notification',[apicontroller::class, 'alert_notification']);
Route::post('/duplicate_ride',[apicontroller::class, 'duplicate_ride']);
Route::post('/newserach',[apicontroller::class, 'newserach']);
Route::post('/filter_program',[apicontroller::class, 'filter_program']);
Route::post('/get_single_ridedata',[apicontroller::class, 'get_single_ridedata']);
Route::post('/send_meessage_email',[apicontroller::class, 'send_meessage_email']);
Route::get('/cron_jobs',[apicontroller::class, 'cron_jobs']);
// Route::get('/democron',[apicontroller::class, 'democron']);
Route::get('/check_expiredate',[apicontroller::class, 'check_expiredate']);
Route::post('/sendDataToRealtimeDatabase',[apicontroller::class, 'sendDataToRealtimeDatabase']);
Route::post('/offer_notifications_status',[apicontroller::class, 'offer_notifications_status']);
Route::post('/offer_submit',[apicontroller::class, 'offer_submit']);
Route::post('/payment_history',[apicontroller::class, 'payment_history']);
Route::post('/push_ride',[apicontroller::class, 'push_ride']);
// Route::post('/delete_offered_ride',[apicontroller::class, 'delete_offered_ride']);
// Route::post('/delete_offered_ride_driver_end',[apicontroller::class, 'delete_offered_ride_driver_end']);
Route::post('/delete_offered_ride_driver_end',[apicontroller::class, 'delete_offered_ride_driver_end']);
Route::post('/delete_offered_ride_user_end',[apicontroller::class, 'delete_offered_ride_user_end']);
Route::post('/paypal',[apicontroller::class, 'paypal']);
Route::get('/pay',[apicontroller::class, 'pay']);
Route::post('/save_payment_into',[apicontroller::class, 'save_payment_into']);
Route::post('/cancel_push_ride',[apicontroller::class, 'cancel_push_ride']);
Route::post('/complete_offer_ride',[apicontroller::class, 'complete_offer_ride']);
Route::post('/start_ride',[apicontroller::class, 'start_ride']);

Route::post('/test_otp',[apicontroller::class, 'testotp']);
Route::get('/access_keys',[apicontroller::class, 'access_keys']);
Route::post('/notify_search_ride',[apicontroller::class, 'notify_search_ride']);
Route::post('/req_to_offer_ride',[apicontroller::class, 'req_to_offer_ride']);
Route::post('/user_documents',[apicontroller::class, 'user_documents']);



Route::post('/payment/initiate', [apicontroller::class, 'initiate']);
Route::post('/payment/callback', [apicontroller::class, 'callback']);
Route::post('/payment/status/', [apicontroller::class, 'status']);


Route::post('/phonepe/payment', [apicontroller::class, 'payment'])->name('phonepe.payment');

Route::post('/phonepe/success', [apicontroller::class, 'success'])->name('phonepe.success');

Route::post('/applyCoupon', [apicontroller::class, 'applyCoupon'])->name('applyCoupon');


Route::post('/sendnotification', [apicontroller::class, 'sendnotification'])->name('sendnotification');


?>





