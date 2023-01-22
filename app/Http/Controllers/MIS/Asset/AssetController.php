<?php

namespace App\Http\Controllers\MIS\Asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset\Asset;

class AssetController extends Controller
{
    public function index()
    {
        $data = Asset::with('companyAsset', 'asPicture', 'asCurrentAllocation', 'asCurrentLocation')->get();

        $defaultPicture = asset('Massets/images/default.png');
        foreach ($data as $asset) {
            if ($asset->asPicture) {
                $picture = asset('storage/' . $asset->asPicture->path . $asset->asPicture->file_name);
            } else {
                $picture = $defaultPicture;
            }
            $assets[] =  array(
                "id" => $asset->id,
                "asset_code" => $asset->asset_code,
                "name" => $asset->description,
                "allocation" => $asset->asCurrentAllocation->full_name ?? '',
                "location" => $asset->asCurrentLocation->name ?? '',
                "picture" => $picture,

            );
        }
        return response()->json($assets);
    }
}
