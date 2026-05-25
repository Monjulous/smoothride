<?php

use Illuminate\Support\Facades\Route; 

use App\Models\User;
use App\Notifications\NewMessageNotification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('setlocale/{locale}', function ($lang) {
	\Session::put('locale', $lang);
	return redirect()->back();
})->name('setlocale'); 


// Frontend Routes
Route::get('/', function () { return view('welcome');})->name('home');
Route::get('/dashboard',[App\Http\Controllers\Frontend\HomeController::class, 'homeindex'])->name('index');
Route::get('/userlogin',[App\Http\Controllers\Frontend\HomeController::class, 'userlogin'])->name('userlogin');
Route::get('/signup',[App\Http\Controllers\Frontend\HomeController::class, 'signup'])->name('signup');
Route::post('/addUser',[App\Http\Controllers\Frontend\HomeController::class, 'addUser'])->name('addUser');
Route::post('/logins',[App\Http\Controllers\Frontend\HomeController::class, 'logins'])->name('logins');
Route::get('/myprofile',[App\Http\Controllers\Frontend\ProfileController::class, 'myprofile'])->name('myprofile');
Route::get('/userlogout',[App\Http\Controllers\Frontend\ProfileController::class, 'userlogout'])->name('userlogout');
Route::get('/addAction',[App\Http\Controllers\Frontend\ProfileController::class, 'addAction'])->name('addAction');
Route::post('/Auction',[App\Http\Controllers\Frontend\ProfileController::class, 'Auction'])->name('Auction');
Route::post('/Sell',[App\Http\Controllers\Frontend\ProfileController::class, 'Sell'])->name('Sell');
Route::post('/addBid',[App\Http\Controllers\Frontend\ProfileController::class, 'addBid'])->name('addBid');



Route::group(['middleware' => 'language'], function () {

	// Admin Routes
	Route::prefix('admin')->group(function () {

		Route::get('/login', 					[App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
		Route::post('/login', 					[App\Http\Controllers\Auth\LoginController::class, 'login_go'])->name('login_go');
		Route::get('/logout', 					[App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
		
		Route::get('forget-password', 			[App\Http\Controllers\Auth\ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
		Route::post('forget-password', 			[App\Http\Controllers\Auth\ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');

		Route::get('reset-password/{token}', 	[App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
		Route::post('reset-password', 			[App\Http\Controllers\Auth\ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');

		// Admin Authenticated Routes
		Route::group(['middleware' => ['auth']], function () {

			Route::get('/dashboard', 			[App\Http\Controllers\Admin\DashboardController::class, 'dashboard'])->name('dashboard');

			// Profile
			Route::get('/admin_profile', 				[App\Http\Controllers\Admin\UserController::class, 'profile'])->name('admin_profile');
			Route::post('/profile/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'profile_update'])->name('profile.update');

			// User
			Route::prefix('users')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\UserController::class, 'status_update'])->name('users.status_update');
			});

			// Role
			Route::prefix('roles')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\RoleController::class, 'index'])->name('roles.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\RoleController::class, 'create'])->name('roles.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\RoleController::class, 'store'])->name('roles.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\RoleController::class, 'destroy'])->name('roles.destroy');
			});

			// Permission
			Route::prefix('permissions')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\PermissionController::class, 'create'])->name('permissions.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\PermissionController::class, 'store'])->name('permissions.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\PermissionController::class, 'edit'])->name('permissions.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('permissions.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\PermissionController::class, 'destroy'])->name('permissions.destroy');
			});

			// Currency
			Route::prefix('currencies')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CurrencyController::class, 'index'])->name('currencies.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CurrencyController::class, 'create'])->name('currencies.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\CurrencyController::class, 'store'])->name('currencies.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CurrencyController::class, 'edit'])->name('currencies.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CurrencyController::class, 'update'])->name('currencies.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CurrencyController::class, 'destroy'])->name('currencies.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CurrencyController::class, 'status_update'])->name('currencies.status_update');
			});

			// Setting
			Route::prefix('setting')->group(function () {
				Route::get('/file-manager/index', 			 [App\Http\Controllers\Admin\FileManagerController::class, 'index'])->name('filemanager.index');
				Route::get('/website-setting/edit', 		 [App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('website-setting.edit');
				Route::post('/website-setting/update/{id}',  [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('website-setting.update');
			});

			// CMS category
			Route::prefix('cmscategories')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CMSCategoryController::class, 'index'])->name('cmscategories.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CMSCategoryController::class, 'create'])->name('cmscategories.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\CMSCategoryController::class, 'store'])->name('cmscategories.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CMSCategoryController::class, 'edit'])->name('cmscategories.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CMSCategoryController::class, 'update'])->name('cmscategories.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CMSCategoryController::class, 'destroy'])->name('cmscategories.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CMSCategoryController::class, 'status_update'])->name('cmscategories.status_update');
			});

			// CMS Pages
			Route::prefix('cmspages')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\CMSPageController::class, 'index'])->name('cmspages.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\CMSPageController::class, 'create'])->name('cmspages.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\CMSPageController::class, 'store'])->name('cmspages.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\CMSPageController::class, 'edit'])->name('cmspages.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\CMSPageController::class, 'update'])->name('cmspages.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\CMSPageController::class, 'destroy'])->name('cmspages.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\CMSPageController::class, 'status_update'])->name('cmspages.status_update');
			});

			// Testimonials
			Route::prefix('testimonials')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\TestimonialController::class, 'index'])->name('testimonials.index');
				Route::get('/create', 			[App\Http\Controllers\TestimonialController::class, 'create'])->name('testimonials.create');
				Route::post('/store', 			[App\Http\Controllers\TestimonialController::class, 'store'])->name('testimonials.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\TestimonialController::class, 'edit'])->name('testimonials.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\TestimonialController::class, 'update'])->name('testimonials.update');
				Route::post('/destroy', 		[App\Http\Controllers\TestimonialController::class, 'destroy'])->name('testimonials.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\TestimonialController::class, 'status_update'])->name('testimonials.status_update');
			});


			// Offers
			Route::prefix('offer')->group(function () {
				Route::get('/index', 			[App\Http\Controllers\Admin\OfferController::class, 'index'])->name('offer.index');
				Route::get('/create', 			[App\Http\Controllers\Admin\OfferController::class, 'create'])->name('offer.create');
				Route::post('/store', 			[App\Http\Controllers\Admin\OfferController::class, 'store'])->name('offer.store');
				Route::get('/edit/{id}', 		[App\Http\Controllers\Admin\OfferController::class, 'edit'])->name('offer.edit');
				Route::post('/update/{id}', 	[App\Http\Controllers\Admin\OfferController::class, 'update'])->name('offer.update');
				Route::post('/destroy', 		[App\Http\Controllers\Admin\OfferController::class, 'destroy'])->name('offer.destroy');
				Route::get('/status_update', 	[App\Http\Controllers\Admin\OfferController::class, 'status_update'])->name('offer.status_update');
			});


				// routes/web.php or routes/admin.php
				Route::prefix('notifications')->group(function () {

					Route::get('/index', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
					Route::get('/create', [App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create');
					Route::post('/store', [App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');
					Route::get('/edit/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
				    Route::post('/update/{id}', 	[App\Http\Controllers\Admin\NotificationController::class, 'update'])->name('notifications.update');

					Route::delete('notifications/{id}', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');
					Route::post('/send', [App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('notifications.send');
				
				});

							

		});
	});

});

 // **********************************************************************************************
	Route::get('page',[App\Http\Controllers\Frontend\HomeController::class, 'pagename'])->name('page');
	Route::get('vehicle',[App\Http\Controllers\Admin\VehicleController::class, 'Vehiclelist'])->name('Vehicle.index');
	Route::post('user_status',[App\Http\Controllers\Admin\UserController::class, 'user_status'])->name('admin.user_status');
	Route::get('ratingReviews',[App\Http\Controllers\Admin\RatingReviewsController::class, 'ratingReviews'])->name('ratingReviews');
	Route::get('verify_id',[App\Http\Controllers\Admin\Verify_idController::class, 'verify_id'])->name('verify_id');
	Route::get('Ride',[App\Http\Controllers\Admin\RideController::class, 'rideList'])->name('Ride');
	Route::post('/update-ride-admin-status', [App\Http\Controllers\Admin\RideController::class, 'update_ride_admin_status'])->name('update_ride_status');
	Route::get('Booking',[App\Http\Controllers\Admin\RideController::class, 'Booking'])->name('Booking');
	Route::get('cancel_booking',[App\Http\Controllers\Admin\RideController::class, 'cancel_booking'])->name('cancel_booking');
	Route::get('Tickets',[App\Http\Controllers\Admin\TicketsController::class, 'Tickets'])->name('Tickets');
	Route::get('tiket_statusupdate/{id?}',[App\Http\Controllers\Admin\TicketsController::class, 'tiket_statusupdate'])->name('tiket_statusupdate');
	Route::post('ticket_status',[App\Http\Controllers\Admin\TicketsController::class, 'ticket_status'])->name('ticket_status');
	Route::get('ticketchat/{id}',[App\Http\Controllers\Admin\TicketsController::class, 'ticketchat'])->name('ticketchat');
	Route::get('viewapplyedusers/{id}',[App\Http\Controllers\Admin\RideController::class, 'viewapplyedusers'])->name('viewapplyedusers');

	Route::get('VehicleBrandModeladd',[App\Http\Controllers\Admin\VehicleBrandModelController::class, 'VehicleBrandModeladd'])->name('VehicleBrandModeladd');
	Route::get('VehicleModeladd',[App\Http\Controllers\Admin\VehicleBrandModelController::class, 'VehicleModeladd'])->name('VehicleModeladd');
	Route::post('addcarmoel',[App\Http\Controllers\Admin\VehicleBrandModelController::class, 'addcarmoel'])->name('addcarmoel');
	Route::post('addcarbrand',[App\Http\Controllers\Admin\VehicleBrandModelController::class, 'addcarbrand'])->name('addcarbrand');
	Route::get('user_info/{id}',[App\Http\Controllers\Admin\UserController::class, 'user_info'])->name('user_info');
	Route::post('update-status',[App\Http\Controllers\Admin\UserController::class, 'updateuserstatus'])->name('admin.updateuserstatus');
	Route::get('users-request',[App\Http\Controllers\Admin\UserController::class, 'PendingRequest'])->name('PendingRequest');
	Route::get('document-verify-request',[App\Http\Controllers\Admin\UserController::class, 'doucmentVerfiyRequest'])->name('DocVerifyRequest');
	Route::get('completeRequest',[App\Http\Controllers\Admin\UserController::class, 'completeRequest'])->name('completeRequest');
	Route::get('driver',[App\Http\Controllers\Admin\UserController::class, 'driver'])->name('driver');
	Route::get('single_ride/{id}',[App\Http\Controllers\Admin\RideController::class, 'single_ride'])->name('single_ride');
	Route::get('faq',[App\Http\Controllers\Admin\RideController::class, 'faq'])->name('faq');
	Route::get('addfaq',[App\Http\Controllers\Admin\RideController::class, 'addfaq'])->name('addfaq');
	Route::post('editfaq',[App\Http\Controllers\Admin\RideController::class, 'editfaq'])->name('editfaq');
	Route::post('submit_faq',[App\Http\Controllers\Admin\RideController::class, 'submit_faq'])->name('submit_faq');
	Route::post('delete_Faq',[App\Http\Controllers\Admin\RideController::class, 'delete_Faq'])->name('delete_Faq');
	Route::get('chat/{id}',[App\Http\Controllers\Admin\UserController::class, 'chat'])->name('chat');
	Route::post('chatimg',[App\Http\Controllers\Admin\UserController::class, 'chatimg'])->name('chatimg');
	Route::post('sendnotification',[App\Http\Controllers\Admin\UserController::class, 'sendnotification'])->name('sendnotification');
	Route::post('update_Verify_doc',[App\Http\Controllers\Admin\UserController::class, 'update_Verify_doc'])->name('admin.update_Verify_doc');
	Route::post('update_date',[App\Http\Controllers\Admin\UserController::class, 'update_date'])->name('update_date');
	Route::post('clear_all',[App\Http\Controllers\Admin\UserController::class, 'clear_all'])->name('clear_all');
	Route::post('delete_userr',[App\Http\Controllers\Admin\UserController::class, 'delete_userr'])->name('admin.delete_userr');
	Route::post('update-document',[App\Http\Controllers\Admin\UserController::class, 'update_document'])->name('admin.update-document');

	// Pages Admin

	Route::get('edit_privacy/{id}',[App\Http\Controllers\Admin\PagesController::class, 'edit_privacy'])->name('edit_privacy');
	Route::post('update_privacy/{id}',[App\Http\Controllers\Admin\PagesController::class, 'update_privacy'])->name('update_privacy');
	Route::get('edit_terms/{id}',[App\Http\Controllers\Admin\PagesController::class, 'edit_terms'])->name('edit_terms');
	Route::post('update_terms/{id}',[App\Http\Controllers\Admin\PagesController::class, 'update_terms'])->name('update_terms');

	// Route::get('save_contact',[App\Http\Controllers\Admin\PagesController::class, 'contact_form'])->name('contact_form');
	// Route::post('save_contact',[App\Http\Controllers\Admin\PagesController::class, 'save_contact'])->name('save_contact');
	Route::get('contact',[App\Http\Controllers\Admin\PagesController::class, 'contact'])->name('contact');
	Route::get('delete_contact/{id}',[App\Http\Controllers\Admin\PagesController::class, 'delete_contact'])->name('delete_contact');

	Route::get('edit_home/{id}',[App\Http\Controllers\Admin\PagesController::class, 'edit_home'])->name('edit_home');
	Route::post('update_home/{id}',[App\Http\Controllers\Admin\PagesController::class, 'update_home'])->name('update_home');

	// CMS Data

	Route::post('store_banner/',[App\Http\Controllers\Admin\PagesController::class, 'store_banner'])->name('store_banner');
	Route::post('store_info_section/',[App\Http\Controllers\Admin\PagesController::class, 'store_info_section'])->name('store_info_section');
	Route::post('store_howitwork_section/',[App\Http\Controllers\Admin\PagesController::class, 'store_howitwork_section'])->name('store_howitwork_section');
	Route::post('store_whychooseus_section/',[App\Http\Controllers\Admin\PagesController::class, 'store_whychooseus_section'])->name('store_whychooseus_section');
	Route::post('store_testimonial_section/',[App\Http\Controllers\Admin\PagesController::class, 'store_testimonial_section'])->name('store_testimonial_section');
	Route::post('store_calltoaction_section/',[App\Http\Controllers\Admin\PagesController::class, 'store_calltoaction_section'])->name('store_calltoaction_section');
	Route::post('testimonial_store/',[App\Http\Controllers\Admin\PagesController::class, 'testimonial_store'])->name('testimonial.store');
	Route::post('testimonial_update/',[App\Http\Controllers\Admin\PagesController::class, 'testimonial_update'])->name('testimonial.update');
	Route::post('testimonial_delete/',[App\Http\Controllers\Admin\PagesController::class, 'testimonial_delete'])->name('testimonial_delete');
	Route::get('testimonial/',[App\Http\Controllers\Admin\PagesController::class, 'testimonial'])->name('testimonial');
	Route::post('update_setting/',[App\Http\Controllers\Admin\PagesController::class, 'update_setting'])->name('update_setting');
	Route::post('store_about_us/',[App\Http\Controllers\Admin\PagesController::class, 'store_about_us'])->name('store_about_us');
	Route::post('store_service_heading/',[App\Http\Controllers\Admin\PagesController::class, 'store_service_heading'])->name('store_service_heading');
	
	
	Route::get('service/',[App\Http\Controllers\Admin\PagesController::class, 'service'])->name('service');
	Route::post('service_store/',[App\Http\Controllers\Admin\PagesController::class, 'service_store'])->name('service.store');
	Route::post('service_update/',[App\Http\Controllers\Admin\PagesController::class, 'service_update'])->name('service.update');
	Route::post('service_delete/',[App\Http\Controllers\Admin\PagesController::class, 'service_delete'])->name('service_delete');





	// Pages Frontend

	Route::get('/',[App\Http\Controllers\Frontend\HomeController::class, 'homeindex'])->name('homeindex');
	Route::get('termscondition',[App\Http\Controllers\Frontend\HomeController::class, 'termscondition'])->name('termscondition');
	Route::get('about',[App\Http\Controllers\Frontend\HomeController::class, 'about'])->name('about');
	Route::get('services',[App\Http\Controllers\Frontend\HomeController::class, 'services'])->name('services');

	Route::get('privacy_policy',[App\Http\Controllers\Frontend\HomeController::class, 'privacy_policy'])->name('privacy_policy');
	Route::get('contact_us',[App\Http\Controllers\Frontend\HomeController::class, 'contact_us'])->name('contact_us');
	Route::post('contact_us',[App\Http\Controllers\Frontend\HomeController::class, 'store_contact'])->name('store_contact');

	Route::get('chats',[App\Http\Controllers\Admin\UserController::class, 'chats'])->name('chats');







Route::get('/test-notify', function () {
	$user = User::find(1);
    $notification = new NewMessageNotification();
    info($notification->toArray($user)); // check data payload

    $user->notify($notification);
    return 'Notification sent';
});

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

Route::get('/inspect/{table}/{id?}', function ($table, $id = null) {
    // 1. Validate table exists
    if (!Schema::hasTable($table)) {
        return response()->json(['error' => "Table '{$table}' not found."], 404);
    }

    // 2. If an ID is provided, find ONLY that record
    if ($id !== null) {
        $data = DB::table($table)->where('id', $id)->first();
        
        if (!$data) {
            return response()->json(['error' => "ID {$id} not found in table '{$table}'"], 404);
        }
        
        return response()->json($data);
    }

    // 3. Otherwise, return the whole table
    return response()->json(DB::table($table)->get());
})->where('id', '[0-9]+'); // This ensures the ID must be a number