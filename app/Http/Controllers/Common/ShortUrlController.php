<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;

use App\Models\Common\ShortUrl;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShortUrlController extends Controller
{
    public function create()
    {

        return view('common.shorter_url.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if URL already exists
        $existing = ShortUrl::where('original_url', $request->url)->first();

        if ($existing) {
            return response()->json([
                'short_url' => url('/' . $existing->short_code)
            ]);
        }

        $shortUrl = ShortUrl::create([
            'original_url' => $request->url,
        ]);

        return response()->json([
            'short_url' => url('/' . $shortUrl->short_code)
        ]);
    }

    public function redirect($code)
    {
        $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();

        // Increment click count
        $shortUrl->increment('click_count');

        return redirect()->away($shortUrl->original_url);
    }
}
