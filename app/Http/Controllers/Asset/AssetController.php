<?php

namespace App\Http\Controllers\Asset;

use DB;
use Storage;
use DataTables;
use App\Models\Asset\Asset;
use Illuminate\Http\Request;
use App\Models\Asset\AsClass;
use App\Models\Common\Client;
use App\Models\Common\Office;
use App\Models\Hr\HrEmployee;
use App\Models\Asset\AsSubClass;
use App\Models\Asset\AsOwnership;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Asset\AsDocumentation;
use App\Http\Requests\Asset\AssetStore;
use App\Http\Requests\Asset\ClassStore;
use App\Http\Requests\Asset\SubClassStore;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Requests\Asset\AssetSearchStore;

class AssetController extends Controller
{

    public function index(Request $request)
    {

        // authorize only user who add the Asset
        $assets = Asset::join('audits', 'audits.auditable_id', 'assets.id')->select('assets.*', 'audits.user_id', 'audits.auditable_id', 'audits.auditable_type')->where('auditable_type', 'App\Models\Asset\Asset')->where('user_id', Auth::user()->id)->with('asCurrentLocation', 'asCurrentAllocation', 'asDocumentation')->get();
        return view('asset.index', compact('assets'));
    }

    public function loadData(Request $request)
    {
        if ($request->ajax()) {

            $data = Asset::join('audits', 'audits.auditable_id', 'assets.id')->select('assets.*', 'audits.user_id', 'audits.auditable_id', 'audits.auditable_type')->where('auditable_type', 'App\Models\Asset\Asset')->where('user_id', Auth::user()->id)->distinct()->with('asCurrentLocation', 'asCurrentAllocation', 'asDocumentation')->get();

            if (Auth::user()->can('asset all record')) {
                $data = Asset::with('asCurrentLocation', 'asCurrentAllocation', 'asDocumentation')->get();
            }


            return DataTables::of($data)

                ->addColumn('ownership', function ($data) {

                    $clientId = $data->currentOwnership->id ?? null;
                    if ($clientId == null) {
                        return 'Not Entered';
                    } else if ($clientId == 20) {
                        return 'BARQAAB';
                    } else {
                        return 'Client';
                    }
                })
                ->addColumn('location', function ($data) {

                    $location = $data->asCurrentLocation->name ?? '';
                    $allocation = $data->asCurrentAllocation->full_name ?? '';
                    if ($location) {
                        return $location;
                    } elseif ($allocation) {
                        return $allocation . ' - ' . $data->asCurrentAllocation->designation ?? '';
                    } else {
                        return 'N/A';
                    }
                })
                // ->addColumn('bar_code', function ($data) {
                //     //$barCode ='<img src="data:image/png;base64,'.\DNS1D::getBarcodePNG($data->asset_code,'C39+',1,33,array(0,0,0),true).'" alt="barcode" />';


                //     $qrCode = '<img  src="data:image/png;base64,' . \DNS2D::getBarcodePNG($data->asset_code, 'QRCODE') . '" alt="barcode"   /><br><p style="color:black; font-weight: bold">' . $data->asset_code . '</p>';

                //     return $qrCode;
                // })
                ->addColumn('image', function ($data) {
                    if ($data->asDocumentation->extension != 'pdf') {
                        $image = '<img src="' . url(isset($data->asDocumentation->file_name) ? '/storage/' . $data->asDocumentation->path . $data->asDocumentation->file_name : 'Massets/images/document.png') . '" class="img-round picture-container picture-src"  id="ViewIMG' . $data->id . '" width=50>';
                    } else {
                        $image = '<img src="' . asset('Massets/images/document.png') . '" href="' . url(isset($data->asDocumentation->file_name) ? '/storage/' . $data->asDocumentation->path . $data->asDocumentation->file_name : 'Massets/images/document.png') . '" class="img-round picture-container picture-src"  id="ViewPDF' . $data->id . '" width=50>';
                    }


                    return $image;
                })

                ->addColumn('edit', function ($data) {

                    $button = '<a class="btn btn-success btn-sm" href="' . route('asset.edit', $data->id) . '"  title="Edit"><i class="fas fa-pencil-alt text-white "></i></a>';

                    return $button;
                })
                ->addColumn('delete', function ($data) {

                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $data->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteAsset">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['location', 'bar_code', 'image', 'edit', 'delete'])
                ->make(true);
        }
    }

    public function create()
    {
        session()->put('asset_id', '');
        $asClasses = AsClass::all();
        $asSubClasses = AsSubClass::all();

        return view('asset.create', compact('asClasses', 'asSubClasses'));
    }

    public function store(AssetStore $request)
    {

        $input = $request->all();

        $asset = '';
        DB::transaction(function () use ($input, $request, &$asset) {
            $today = \Carbon\Carbon::today();

            $asset = Asset::create($input);

            //add image
            $extension = request()->document->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $folderName = "asset/" .  $asset->id . "/";
            //store file
            $request->file('document')->storeAs('public/' . $folderName, $fileName);

            $file_path = storage_path('app/public/' . $folderName . $fileName);

            $attachment['description'] = 'image';
            $attachment['file_name'] = $fileName;
            $attachment['size'] = $request->file('document')->getSize();
            $attachment['path'] = $folderName;
            $attachment['extension'] = $extension;
            $attachment['asset_id'] = $asset->id;

            AsDocumentation::create($attachment);
        }); // end transcation
        return response()->json(['url' => route("asset.edit", $asset), 'message' => 'Data Successfully Saved']);
    }

    public function edit(Request $request, $id)
    {
        session()->put('asset_id', $id);
        $asClasses = AsClass::all();
        $asSubClasses = AsSubClass::all();
        $data = Asset::with('asClass')->find($id);
        //dd($data);

        if ($request->ajax()) {
            return view('asset.ajax', compact('asClasses', 'asSubClasses', 'data'));
        } else {
            return view('asset.edit', compact('asClasses', 'asSubClasses', 'data'));
        }
    }


    public function update(AssetStore $request, $id)
    {

        $input = $request->all();

        DB::transaction(function () use ($input, $request, $id) {
            $today = \Carbon\Carbon::today();

            Asset::findOrFail($id)->update($input);

            //Edit image
            if ($request->hasFile('document')) {

                $extension = request()->document->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $folderName = "asset/" . $id . "/";
                //store file
                $request->file('document')->storeAs('public/' . $folderName, $fileName);

                $file_path = storage_path('app/public/' . $folderName . $fileName);

                $attachment['description'] = 'image';
                $attachment['file_name'] = $fileName;
                $attachment['size'] = $request->file('document')->getSize();
                $attachment['path'] = $folderName;
                $attachment['extension'] = $extension;
                $attachment['asset_id'] = $id;

                $asDocumentation = AsDocumentation::where('asset_id', $id)->first();

                if ($asDocumentation) {
                    $oldDocumentPath =  $asDocumentation->path . $asDocumentation->file_name;
                    AsDocumentation::findOrFail($asDocumentation->id)->update($attachment);

                    if (File::exists(public_path('storage/' . $oldDocumentPath))) {
                        File::delete(public_path('storage/' . $oldDocumentPath));
                    }
                } else {
                    AsDocumentation::create($attachment);
                }
            }
        }); // end transcation

        return response()->json(['status' => 'OK', 'message' => "Data Successfully Updated"]);
    }




    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $asDocuments = AsDocumentation::where('asset_id', $id)->get();
            foreach ($asDocuments as $asDocument) {
                $path = public_path('storage/' . $asDocument->path . $asDocument->file_name);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            Asset::findOrFail($id)->delete();
        }); // end transcation

        return response()->json(['success' => 'data  delete successfully.']);
    }

    public function getSubClasses($id)
    {

        $as_sub_classes = DB::table("as_sub_classes")
            ->where("as_class_id", $id)
            ->pluck("name", "id");

        return response()->json($as_sub_classes);
    }

    public function asCode($asSubClass)
    {

        $asSubClass = AsSubClass::where('id', $asSubClass)->first();

        $count = 1;
        // $code = $code.'0'; //200
        $asCode =  $asSubClass->as_class_id . '-' . $asSubClass->id . '-';
        // $asCode = $asCode.$count;

        while (Asset::where('asset_code', $asCode . $count)->count() > 0) {
            $count++;
        }
        $asCode = $asCode . $count;

        return response()->json(['assetCode' => $asCode]);
    }

    public function storeClass(ClassStore $request)
    {
        $newClass = preg_replace('/[^A-Za-z0-9\- ]/', '', $request->name);
        $class = AsClass::where('name', $newClass)->first();

        if ($class == null) {

            DB::transaction(function () use ($request, $newClass) {

                AsClass::create(['name' => $newClass]);
            }); // end transcation   

            $classes = DB::table("as_classes")->orderBy('id')
                ->pluck("id", "name");

            return response()->json(['classes' => $classes, 'message' => "$newClass Successfully Entered"]);
        } else {

            return response()->json(['classes' => '', 'message' => "$newClass is already entered"]);
        }
    }

    public function storeSubClass(SubClassStore $request)
    {
        $newSubClass = preg_replace('/[^A-Za-z0-9\- ]/', '', $request->name);
        $subClass = AsSubClass::where('as_class_id', $request->as_class_id)->where('name', $newSubClass)->first();

        if ($subClass == null) {

            DB::transaction(function () use ($request, $newSubClass) {

                AsSubClass::create(['name' => $newSubClass, 'as_class_id' => $request->as_class_id]);
            }); // end transcation   

            $subClasses = DB::table("as_sub_classes")->where('as_class_id', $request->as_class_id)->orderBy('id')
                ->pluck("id", "name");

            return response()->json(['subClasses' => $subClasses, 'message' => "$newSubClass Successfully Entered"]);
        } else {

            return response()->json(['subClasses' => '', 'message' => "$newSubClass is already entered"]);
        }
    }

    public function search()
    {
        $offices = Office::select('id', 'name')->get();
        $classes = AsClass::select('id', 'name')->get();
        $employees = HrEmployee::select('id', 'first_name', 'last_name', 'employee_no')->with('employeeCurrentDesignation')->get();
        $clients = AsOwnership::pluck('client_id')->unique();
        $owners = Client::whereIn('id', $clients)->get();
        return view('asset.search.search', compact('offices', 'classes', 'employees', 'owners'));
    }

    public function result(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            // Use subqueries to get the latest location and ownership records
            $latestLocationSubquery = DB::table('as_locations')
                ->select('asset_id', DB::raw('MAX(date) as max_date'))
                ->groupBy('asset_id');

            $latestOwnershipSubquery = DB::table('as_ownerships')
                ->select('asset_id', DB::raw('MAX(date) as max_date'))
                ->groupBy('asset_id');

            $assets = Asset::query()
                ->with([
                    'asOwnership' => function ($query) use ($latestOwnershipSubquery) {
                        $query->joinSub($latestOwnershipSubquery, 'latest_ownership', function ($join) {
                            $join->on('as_ownerships.asset_id', '=', 'latest_ownership.asset_id')
                                ->on('as_ownerships.date', '=', 'latest_ownership.max_date');
                        });
                    },
                    'asPurchase',
                    'asCurrentAllocation.employeeCurrentDesignation',
                    'asCurrentLocation',
                    'asPicture'
                ])
                ->when(isset($data['hr_employee_id']) && !empty($data['hr_employee_id']), function ($query) use ($data, $latestLocationSubquery) {
                    $query->whereHas('asLocations', function ($q) use ($data, $latestLocationSubquery) {
                        $q->joinSub($latestLocationSubquery, 'latest_location', function ($join) {
                            $join->on('as_locations.asset_id', '=', 'latest_location.asset_id')
                                ->on('as_locations.date', '=', 'latest_location.max_date');
                        })
                            ->where('hr_employee_id', $data['hr_employee_id']);
                    });
                })
                ->when($request->has('as_sub_class_id') && !empty($data['as_sub_class_id']), function ($query) use ($data) {
                    return $query->where('as_sub_class_id', $data['as_sub_class_id']);
                })
                ->when($request->has('office_id') && !empty($data['office_id']), function ($query) use ($data) {
                    return $query->where('office_id', $data['office_id']);
                })
                ->when($request->has('client_id') && !empty($data['client_id']), function ($query) use ($data) {
                    return $query->where('client_id', $data['client_id']);
                })
                ->select('assets.*');

            return DataTables::of($assets)
                ->addColumn('ownership', function ($asset) {
                    return $asset->asOwnership->name ?? '';
                })
                ->addColumn('purchase_condition', function ($asset) {
                    return ($asset->asPurchase->as_purchase_condition_id ?? null) == 1 ? 'New' : (isset($asset->asPurchase->as_purchase_condition_id) ? 'Used' : '');
                })
                ->addColumn('purchase_date', function ($asset) {
                    return $asset->asPurchase?->purchase_date ? \Carbon\Carbon::parse($asset->asPurchase?->purchase_date)->format('M d, Y') : '';
                })
                ->addColumn('purchase_cost', function ($asset) {
                    return $asset->asPurchase?->purchase_cost ? number_format($asset->asPurchase->purchase_cost, 2) : '';
                })
                ->addColumn('allocation_location', function ($asset) {
                    $location = '';
                    if (isset($asset->asCurrentAllocation->first_name)) {
                        $location = $asset->asCurrentAllocation?->employee_no . '-' . $asset->asCurrentAllocation?->full_name . '-' . ($asset->asCurrentAllocation?->employeeCurrentDesignation?->name ?? '');
                        $location .= '<br>';
                    }
                    $location .= $asset->asCurrentLocation->name ?? '';
                    return $location;
                })
                ->addColumn('image', function ($asset) {
                    $imagePath = public_path('storage/' . ($asset->asPicture->path ?? '') . ($asset->asPicture->file_name ?? ''));
                    $imageUrl = asset('storage/' . ($asset->asPicture->path ?? '') . ($asset->asPicture->file_name ?? ''));
                    $defaultImage = asset('Massets/images/asset1.png');

                    // Prepare base64 for PDF (only when needed)
                    $base64Image = '';
                    if (file_exists($imagePath) && $asset->asPicture) {
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $base64Image = 'data:' . mime_content_type($imagePath) . ';base64,' . $imageData;
                    } else {
                        $defaultPath = public_path('Massets/images/asset1.png');
                        if (file_exists($defaultPath)) {
                            $imageData = base64_encode(file_get_contents($defaultPath));
                            $base64Image = 'data:' . mime_content_type($defaultPath) . ';base64,' . $imageData;
                        }
                    }

                    // Use a tiny placeholder initially for faster loading
                    $placeholder = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjRjBGMEYwIi8+CjxwYXRoIGQ9Ik0yNSAxNkMyNi42NTY5IDE2IDI4IDE0LjY1NjkgMjggMTNDMjggMTEuMzQzMSAyNi42NTY5IDEwIDI1IDEwQzIzLjM0MzEgMTAgMjIgMTEuMzQzMSAyMiAxM0MyMiAxNC42NTY5IDIzLjM0MzEgMTYgMjUgMTZaTTI2IDM0SDE0VjMySDI0VjE5SDE2VjE3SDI2VjM0WiIgZmlsbD0iIzk5OTk5OSIvPgo8L3N2Zz4K';

                    if (file_exists($imagePath) && $asset->asPicture) {
                        return '<img class="img-fluid profile-pic ViewIMG export-image lazy-load"
                             src="' . $placeholder . '"
                             data-src="' . $imageUrl . '"
                             data-base64="' . $base64Image . '"
                             alt="Asset Image"
                             style="width: 50px; height: 50px; object-fit: cover;" />';
                    } else {
                        return '<img class="img-fluid profile-pic ViewIMG export-image lazy-load"
                             src="' . $placeholder . '"
                             data-src="' . $defaultImage . '"
                             data-base64="' . $base64Image . '"
                             alt="Default Image"
                             style="width: 50px; height: 50px; object-fit: cover;" />';
                    }
                })
                ->addColumn('action', function ($asset) {
                    return '<a href="' . route('asset.edit', $asset->id) . '" class="btn btn-success btn-sm" title="Edit"><i class="fas fa-pencil-alt text-white"></i></a>';
                })
                ->rawColumns(['allocation_location', 'image', 'action'])
                ->make(true);
        }

        // For non-AJAX requests, return the search view
        $offices = Office::select('id', 'name')->get();
        $classes = AsClass::select('id', 'name')->get();
        $employees = HrEmployee::select('id', 'first_name', 'last_name', 'employee_no')->with('employeeCurrentDesignation')->get();
        $clients = AsOwnership::pluck('client_id')->unique();
        $owners = Client::whereIn('id', $clients)->get();

        return view('asset.search.search', compact('offices', 'classes', 'employees', 'owners'));
    }

    public function verification($assetCode)
    {
        // Following function restrict maximum 5 request in 1 minute
        $executed = RateLimiter::attempt(
            'send-message:',
            $perMinute = 5,
            function () {
                // Send message...
            }
        );

        if (!$executed) {
            return 'Too many Request sent!, Please retry after some time';
        }




        $data = Asset::with('asPicture', 'asLocation', 'asPurchase', 'currentAllocation')->where('asset_code', $assetCode)->first();
        if ($data) {
            //return response()->json($data);
            return view('asset.verification.show', compact('data'));
        } else {
            return view('asset.verification.show', compact('data'));
        }
    }
}
