<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Car_brand;
use App\Models\Car_model;
use App\Models\Imported_brand;

class VehicleBrandModelController extends Controller
{
    // Update these methods in your VehicleBrandModelController.php

// VehicleBrandModelController.php

public function VehicleBrandModeladd()
{
    $data['brand'] = Imported_brand::latest()->get()->toArray();  
    return view('admin.VehicleBrandModel.index', $data);
}

public function VehicleModeladd()
{
    $data['brand'] = Imported_brand::latest()->paginate(10);  
    $data['model'] = Imported_brand::latest()->paginate(10);  
    return view('admin.VehicleBrandModel.index1', $data);
}

    public function addcarmoel(Request $request)
    {
        $modelArr = [
            'model_name' => $request->model_name,
            'brand_id'   => $request->vehiclebrand,
            'created_at' => now() // Use now() helper for cleaner code
        ];

        $res = Car_model::create($modelArr);
        
        if ($res) {
            return back()->with('success', 'Successfully Added');
        } else {
            return back()->with('error', 'Something went wrong, please try again');
        }
    }

    public function addcarbrand(Request $request)
    {
        $arr = [
            'brand_name' => $request->brand_name,
            'created_at' => now()
        ];

        $res = Car_brand::create($arr);

        if ($res) {
            return back()->with('success', 'Successfully Added');
        } else {
            return back()->with('error', 'Something went wrong, please try again');
        }
    }
}