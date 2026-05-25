<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\offer;

class OfferController extends Controller
{
    public function index()
    {
        $offers = offer::latest()->paginate(10);
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:coupon,informative',
            'coupon_code' => 'nullable|unique:offers,coupon_code',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|in:percentage,fixed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'offer_image' => 'nullable',
            ]);

            $data = $request->only([
                'type',
                'coupon_code',
                'discount',
                'discount_type',
                'start_date',
                'end_date',
            ]);

            // Set default active status
            $data['is_active'] = $request->has('is_active') ? 1 : 0;

            // Handle image upload
            if ($request->hasFile('offer_image')) {
                $image = $request->file('offer_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/offers'), $imageName);
                $data['image'] = 'public/uploads/offers/' . $imageName;
            }

           
            offer::create($data);

            return redirect()->route('offer.index')->with('success', 'Offer created successfully.');
        }


    public function edit($id)
    {

         $offer=offer::find($id);
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'type' => 'required|in:coupon,informative',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|in:percentage,fixed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'offer_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

   
     $offer = offer::findOrFail($id);
    if ($request->has('remove_image') && $request->remove_image == 1) {
        if ($offer->image && file_exists(public_path($offer->image))) {
            unlink(public_path($offer->image));
        }
        $offer->image = null;
    }

    // Upload new image
    if ($request->hasFile('offer_image')) {
        // Delete old image
        if ($offer->image && file_exists(public_path($offer->image))) {
            unlink(public_path($offer->image));
        }

        $image = $request->file('offer_image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $image->move(public_path('uploads/offers'), $imageName);
        $offer->image = 'public/uploads/offers/' . $imageName;
    }

   
    $offer->type = $request->type;
    $offer->coupon_code = $request->coupon_code;
    $offer->discount = $request->discount;
    $offer->discount_type = $request->discount_type;
    $offer->start_date = $request->start_date;
    $offer->end_date = $request->end_date;
    $offer->is_active = $request->has('is_active') ? 1 : 0;

    $offer->save();

    return redirect()->route('offer.index')->with('success', 'Offer updated successfully.');
}


    public function destroy(offer $offer)
    {
        $offer->delete();
        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted.');
    }
}
