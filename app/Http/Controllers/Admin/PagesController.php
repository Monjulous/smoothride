<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\PrivacyPolicy;
use App\Models\Contact;
use App\Models\HomePage;
use App\Models\CMSdata;
use App\Models\Testimonial;
use App\Models\Setting;
use App\Models\service;


class PagesController extends Controller {
    function __construct() {

    }

    public function edit_privacy( $id ) {

        $data[ 'privacy' ] = PrivacyPolicy::where( 'id', $id )->first();
        return view( 'admin.privacy_policy.edit_privacy', $data );

    }

    public function update_privacy( Request $request, $id ) {

        $privacy = PrivacyPolicy::find( $id );

        $privacy->content = $request->input( 'content' );
        $privacy->update();
        return back()->with( 'success', 'Privacy Policy Updated successfully!' );

    }

    public function edit_terms( $id ) {

        $data[ 'terms' ] = PrivacyPolicy::where( 'id', $id )->first();
        $data[ 'terms1' ] = PrivacyPolicy::where('type','customer_term')->first();
        return view( 'admin.privacy_policy.edit_terms', $data );
    }

    public function update_terms( Request $request, $id ) {
        $terms = PrivacyPolicy::find( $id );

        $terms->content = $request->input( 'content' );
        $terms->update();
        return back()->with( 'success', 'Terms & Conditions Updated successfully!' );
    }

    public function contact() {
        $data[ 'contact' ] = Contact::get();
        return view( 'admin.privacy_policy.contact', $data );
    }

    public function delete_contact( $id ) {
        $delcontact = Contact::find( $id );
        $delcontact->delete();
        return back()->with( 'success', 'Contact Deleted Successfully' );
    }

    public function edit_home( $id ) {
        $data[ 'homedata' ] = HomePage::where( 'id', $id )->first();
        $data[ 'banner' ] = CMSdata::where( 'section', 'banner' )->first();
        $data[ 'info' ] = CMSdata::where( 'section', 'info' )->first();
        $data[ 'howitwork' ] = CMSdata::where( 'section', 'howitwork' )->first();
        $data[ 'whychooseus' ] = CMSdata::where( 'section', 'whychooseus' )->first();
        $data[ 'testimonial' ] = CMSdata::where( 'section', 'testimonial' )->first();
        $data[ 'calltoaction' ] = CMSdata::where( 'section', 'calltoaction' )->first();
        $data[ 'about' ] = CMSdata::where( 'section', 'about' )->first();
        $data[ 'service' ] = CMSdata::where( 'section', 'service' )->first();
        $data['setting']=Setting::first();
        return view( 'admin.homepage.edit_home', $data );
    }

   

    public function update_home( Request $request, $id ) {
        $uphome = HomePage::find( $id );

        $uphome->banner_title = $request->input( 'banner_title' );
        $uphome->banner_desc = $request->input( 'banner_desc' );
        $uphome->url1 = $request->input( 'url1' );
        $uphome->url2 = $request->input( 'url2' );
        $uphome->title = $request->input( 'title' );
        $uphome->desc1 = $request->input( 'desc1' );
        $uphome->desc2 = $request->input( 'desc2' );
        $uphome->desc3 = $request->input( 'desc3' );
        $uphome->desc4 = $request->input( 'desc4' );
        $uphome->footer_text = $request->input( 'footer_text' );
        $uphome->contact_text = $request->input( 'contact_text' );
        $uphome->contact_address = $request->input( 'contact_address' );
        $uphome->contact_phone = $request->input( 'contact_phone' );
        $uphome->contact_email = $request->input( 'contact_email' );
        $uphome->contact_fax = $request->input( 'contact_fax' );

        $uphome->update();
        return redirect()->back()->with( 'success', 'Data Updated Successfully' );

    }


    public function store_banner(Request $request){

        // echo"<pre>";print_r($request->all());die;
        //  $validated = $request->validate([
        //         'title'       => 'required|string|max:255',
        //         'subtitle'    => 'nullable|string|max:255',
        //         'description' => 'nullable|string',
        //         'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        //     ]);
    

        //     if($files=$request->file('image')){  
        //         $name=str_replace(' ','-',$files->getClientOriginalName());  
        //         $files->move('public/image/cmsdata/',$name);  
        //         $imagePath ='public/image/cmsdata/'.$name;
        
        //         }
            
        //         $data=new CMSdata ();
        //         $data->title =$request->title;
        //         $data->sub_title =$request->sub_title;
        //         $data->desc =$request->desc;
        //         $data->image =$imagePath;
        //         $data->save();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
          //  'image'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $id=$request->id;
    
        $data = CMSdata::findOrFail($id);

      $existingImages = $request->input('old_card_image', []); 
        $newImages = $request->file('image', []); 

        $uploadedImagePaths = [];

        if (!empty($newImages)) {
            foreach ($newImages as $key => $image) {
                if ($image->isValid()) {
                    // Replace spaces with underscores in filename
                    $name = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
                    $image->move(public_path('image/cmsdata'), $name);

                    $uploadedImagePaths[] = 'public/image/cmsdata/' . $name;

                    // Delete the old image if exists at the same index
                    if (isset($existingImages[$key]) && file_exists(public_path($existingImages[$key]))) {
                        unlink(public_path($existingImages[$key]));
                    }
                }
            }
        }


        $imagePath = implode(',', $uploadedImagePaths);


    
        $data->title = $request->title;
        $data->sub_title = $request->sub_title;
        $data->desc =$request->desc;
        $data->image =$imagePath;
        $data->save();
           
            return back()->with('success', 'Banner Updated successfully.');
      
    }

   

    public function store_info_section(Request $request){

        // echo"<pre>";print_r($request->all());die;
        $validated = $request->validate([
               'title'       => 'required',
               'card_title'    => 'required',
               'card_desc' => 'required',
               'card_image' => 'required',
           ]);
   

               $id=$request->id;
           
            //    $data=new CMSdata ();
               $data = CMSdata::findOrFail($id);

               $existingImages = $request->input('old_card_image', []);
               $newImagePaths = [];
           
               if ($request->hasFile('card_image')) {
                foreach ($request->file('card_image') as $image) {
                    $name = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('image/cmsdata'), $name);
        
                    $newImagePaths[] = 'public/image/cmsdata/' . $name;
                }
            }
        
             $allImages = array_merge($existingImages, $newImagePaths);

               $data->section ='info';
               $data->title =$request->title;
               $data->card_title =$request->card_title ?  implode(',',$request->card_title) : Null;
               $data->card_desc =$request->card_desc ?  implode(',',$request->card_desc) : Null;
               $data->card_img = implode(',', $allImages);

               $data->save();
          
           return back()->with('success', 'Data added successfully!');
     
   }


   public function store_howitwork_section(Request $request){

    //  $validated = $request->validate([
    //        'title'       => 'required',
    //        'card_title'    => 'required',
    //        'card_desc' => 'required',
    //        'card_image' => 'required',
    //    ]);

    //    $imagePaths = [];
    //    if ($request->hasFile('card_image')) {
    //        foreach ($request->file('card_image') as $image) {
    //            $name = time() . '_' . $image->getClientOriginalName(); 
    //            $image->move('public/image/cmsdata/',$name);  
    //            $imagePaths[] = 'public/image/cmsdata/' . $name;
    //        }
    //    }
       
    //        $data=new CMSdata ();
    //        $data->section ='howitwork';
    //        $data->title =$request->title;
    //        $data->card_title =$request->card_title ?  implode(',',$request->card_title) : Null;
    //        $data->card_desc =$request->card_desc ?  implode(',',$request->card_desc) : Null;
    //        $data->card_img = implode(',', $imagePaths);      

    //        $data->save();
    $id=$request->id;
    $validated = $request->validate([
        'title'       => 'required|string',
        'card_title'  => 'required|array',
        'card_desc'   => 'required|array',
        'card_image.*'=> 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = CMSdata::findOrFail($id);

    $existingImages = $request->input('old_card_image', []);
    $newImagePaths = [];

    if ($request->hasFile('card_image')) {
        foreach ($request->file('card_image') as $image) {
            $name = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('image/cmsdata'), $name);

            $newImagePaths[] = 'public/image/cmsdata/' . $name;
        }
    }

     $allImages = array_merge($existingImages, $newImagePaths);

    $data->title = $request->title;
    $data->card_title = implode(',', $request->card_title);
    $data->card_desc = implode(',', $request->card_desc);
    $data->card_img = implode(',', $allImages);
    $data->save();
      
       return back()->with('success', 'Data Updated successfully!');
 
    }

    public function store_whychooseus_section(Request $request){

        // $validated = $request->validate([
        //       'title'       => 'required',
        //       'card_title'    => 'required',
        //       'card_desc' => 'required',
        //       'card_image' => 'required',
        //   ]);
   
        //   $imagePaths = [];
        //   if ($request->hasFile('card_image')) {
        //       foreach ($request->file('card_image') as $image) {
        //           $name = time() . '_' . $image->getClientOriginalName(); 
        //           $image->move('public/image/cmsdata/',$name);  
        //           $imagePaths[] = 'public/image/cmsdata/' . $name;
        //       }
        //   }
          
        //       $data=new CMSdata ();
        //       $data->section ='whychooseus';
        //       $data->title =$request->title;
        //       $data->card_title =$request->card_title ?  implode(',',$request->card_title) : Null;
        //       $data->card_desc =$request->card_desc ?  implode(',',$request->card_desc) : Null;
        //       $data->card_img = implode(',', $imagePaths);      
   
        //       $data->save();

        $id=$request->id;
        $validated = $request->validate([
            'title'       => 'required|string',
            'card_title'  => 'required|array',
            'card_desc'   => 'required|array',
            'card_image.*'=> 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $data = CMSdata::findOrFail($id);
    
        $existingImages = $request->input('old_card_image', []);
        $newImagePaths = [];
    
        if ($request->hasFile('card_image')) {
            foreach ($request->file('card_image') as $image) {
                $name = time() . '_' . $image->getClientOriginalName();
                
                $image->move(public_path('image/cmsdata'), $name);
                $newImagePaths[] = 'public/image/cmsdata/' . $name;
            }
        }
    
         $allImages = array_merge($existingImages, $newImagePaths);
    
        $data->title = $request->title;
        $data->sub_title = $request->sub_title;
        $data->card_title = implode(',', $request->card_title);
        $data->card_desc = implode(',', $request->card_desc);
        $data->card_img = implode(',', $allImages);
        $data->save();
          
         
          return back()->with('success', 'Data Updated successfully!');
    
       }

       public function store_testimonial_section(Request $request){

        $validated = $request->validate([
               'title'       => 'required',
               'sub_title'    => 'required',
               
           ]);
   

           
            //    $data=new CMSdata ();
               $id=$request->id;
               $data = CMSdata::findOrFail($id);
               $data->section ='testimonial';
               $data->title =$request->title;
               $data->sub_title =$request->sub_title;
               $data->save();
          
           return back()->with('success', 'Data added successfully!');
     
   }
            public function store_calltoaction_section(Request $request){

                $validated = $request->validate([
                    'title'       => 'required',
                    'sub_title'    => 'required',
                    
                ]);
        
     
                
                    // $data=new CMSdata ();
                    $id=$request->id;
                    $data = CMSdata::findOrFail($id);
                    $data->section ='calltoaction';
                    $data->title =$request->title;
                    $data->sub_title =$request->sub_title;
                    $data->save();
                
                return back()->with('success', 'Data added successfully!');
            
            }


            public function testimonial() {
                $data[ 'testimonial' ] = Testimonial::get();
                return view( 'admin.homepage.testimonial', $data );
            }
            public function testimonial_store(Request $request)
            {
                // Validate input
                $validated = $request->validate([
                    'name'      => 'required|string|max:255',
                    'designation' => 'nullable|string|max:255',
                    'message'   => 'required|string',
                    'photo'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $imagePath = null;

               if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                    $image = $request->file('photo');
                    $name = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images/testimonials'), $name);
                    
                    $imagePath = 'images/testimonials/' . $name;
                }

                // Save testimonial (assuming you have a Testimonial model)
                $testimonial = new Testimonial();
                $testimonial->name = $request->name;
                $testimonial->location = $request->location;
                $testimonial->message = $request->message;
                $testimonial->photo = $imagePath;
                $testimonial->save();

                return redirect()->route('testimonial')->with('success', 'Testimonial added successfully!');
            }


            public function testimonial_update(Request $request){

               
                $id=$request->id;
            
                $testimonial = Testimonial::findOrFail($id);
        
                $existingImage = $request->input('old_photo', '');
            
                $imagePath = $existingImage; 
            
                 if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                    $image = $request->file('photo');
                    $name = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('images/testimonials'), $name);
                    $imagePath = 'images/testimonials/' . $name;
            
                    if ($existingImage && file_exists(public_path($existingImage))) {
                        unlink(public_path($existingImage));
                    }
                }
            
                $testimonial->name = $request->name;
                $testimonial->location = $request->location;
                $testimonial->message = $request->message;
                $testimonial->photo = $imagePath;
                $testimonial->save();
                   
                    return back()->with('success', 'Testimonial Updated successfully.');
              
            }


            public function testimonial_delete( Request $request ) {

                $res = Testimonial::where( 'id', $request->id )->delete();
                if ( $res ) {
                    return back()->with( 'success', 'Deleted Successfully' );
        
                } else {
                    return back()->with( 'success', 'Something else wrong please try again ' );
                }
            }
        
            public function update_setting(Request $request)
                    {
                       $id = $request->id;

                        // Default to old images
                        $website_logo_dark = $request->old_website_logo_dark;
                        $website_logo_light = $request->old_website_logo_light;
                        $website_logo_small = $request->old_website_logo_small;
                        $website_favicon = $request->old_website_favicon;

                     
                                // Handle new uploads
                        if ($request->hasFile('website_logo_light') && $request->file('website_logo_light')->isValid()) {
                                $image = $request->file('website_logo_light');
                                $originalName = $image->getClientOriginalName();
                                $name = time() . '_' . str_replace(' ', '_', $originalName); 
                                $image->move('public/assets/admin/img/', $name);
                                $website_logo_light = 'assets/admin/img/' . $name;

                                if ($request->old_website_logo_light && file_exists(public_path($request->old_website_logo_light))) {
                                    unlink(public_path($request->old_website_logo_light));
                                }
                            }

                            if ($request->hasFile('website_logo_small') && $request->file('website_logo_small')->isValid()) {
                                $image = $request->file('website_logo_small');
                                $originalName = $image->getClientOriginalName();
                                $name = time() . '_' . str_replace(' ', '_', $originalName); // Remove spaces
                                $image->move('public/assets/admin/img/', $name);
                                $website_logo_small = 'assets/admin/img/' . $name;

                                if ($request->old_website_logo_small && file_exists(public_path($request->old_website_logo_small))) {
                                    unlink(public_path($request->old_website_logo_small));
                                }
                            }

                        if ($request->hasFile('website_logo_dark') && $request->file('website_logo_dark')->isValid()) {
                            $image = $request->file('website_logo_dark');
                            $originalName = $image->getClientOriginalName();
                            $name = time() . '_' . str_replace(' ', '_', $originalName); // Remove spaces
                            $image->move('public/assets/admin/img/', $name);
                            $website_logo_dark = 'assets/admin/img/' . $name;

                            if ($request->old_website_logo_dark && file_exists(public_path($request->old_website_logo_dark))) {
                                unlink(public_path($request->old_website_logo_dark));
                            }
                        }

                        if ($request->hasFile('website_favicon') && $request->file('website_favicon')->isValid()) {
                            $image = $request->file('website_favicon');
                            $originalName = $image->getClientOriginalName();
                            $name = time() . '_' . str_replace(' ', '_', $originalName); // Remove spaces
                            $image->move('public/assets/admin/img/', $name);
                            $website_favicon = 'assets/admin/img/' . $name;

                            if ($request->old_website_favicon && file_exists(public_path($request->old_website_favicon))) {
                                unlink(public_path($request->old_website_favicon));
                            }
                        }
                            
                      


                    // Save updated settings
                    $data = Setting::findOrFail($id);
                    $data->website_title = $request->website_title;
                    $data->meta_title = $request->meta_title;
                    $data->meta_description = $request->meta_description;
                    $data->address = $request->address;
                    $data->address2 = $request->address2;
                    $data->phone = $request->phone;
                    $data->whatsapp = $request->whatsapp;
                    $data->business_hours = $request->business_hours;
                    $data->email = $request->email;
                    $data->facebook = $request->facebook;
                    $data->instagram = $request->instagram;
                    $data->twitter = $request->twitter;
                    $data->linkedin = $request->linkedin;
                    $data->google_key = $request->google_key;

                    // Corrected: use updated image paths
                    $data->website_logo_small = $website_logo_small;
                    $data->website_logo_dark = $website_logo_dark;
                    $data->website_logo_light = $website_logo_light;
                    $data->website_favicon = $website_favicon;

                    $data->playstore = $request->playstore;
                    $data->appstore = $request->appstore;


                    $data->save();

                    return back()->with('success', 'Data Updated Successfully.');
                }

        
        
                public function store_about_us(Request $request){

                    $validated = $request->validate([
                           'title'       => 'required',
                           'sub_title'    => 'required',
                       ]);
               
            
                           $id=$request->id;
                       
                        //    $data=new CMSdata ();
                    $data = CMSdata::findOrFail($id);
                    $data->section ='about';
                    $data->title =$request->title;
                    $data->sub_title =$request->sub_title;
                    $data->save();
                      
                       return back()->with('success', 'Data added successfully!');
                 
               }
           
               
               public function store_service_heading(Request $request){

                $validated = $request->validate([
                       'title'       => 'required',
                       'sub_title'    => 'required',
                   ]);
           
        
                       $id=$request->id;
                   
                    //    $data=new CMSdata ();
                $data = CMSdata::findOrFail($id);
                $data->section ='service';
                $data->title =$request->title;
                $data->sub_title =$request->sub_title;
                $data->save();
                  
                   return back()->with('success', 'Data added successfully!');
             
           }

            //    Services



            public function service() {
                $data[ 'service' ] = service::get();
                return view( 'admin.homepage.service', $data );
            }
            public function service_store(Request $request)
            {
                // Validate input
                $validated = $request->validate([
                    'title'      => 'required|string|max:255',
                    'description' => 'required',
                ]);

                 $testimonial = new service();
                $testimonial->title = $request->title;
                $testimonial->description = $request->description;
                $testimonial->save();

                return redirect()->back()->with('success', 'Service added successfully!');
            }


            public function service_update(Request $request){

               
                $id=$request->id;
            
                $testimonial = service::findOrFail($id);
        
              
                $testimonial->title = $request->title;
                $testimonial->description = $request->description;
                $testimonial->save();
                   
                    return back()->with('success', 'Service Updated successfully.');
              
            }


            public function service_delete( Request $request ) {

                $res = service::where( 'id', $request->id )->delete();
                if ( $res ) {
                    return back()->with( 'success', 'Deleted Successfully' );
        
                } else {
                    return back()->with( 'success', 'Something else wrong please try again ' );
                }
            }
        
    }
