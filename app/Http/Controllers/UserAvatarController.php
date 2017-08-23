<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades;
use Intervention\Image\Facades\Image;

class UserAvatarController extends Controller
{
    public function store()
    {
        $this->validate(request(), [
            'avatar' => 'required|file'
        ]);
        // $base64_str = substr(request('avatar'), strpos(request('avatar'), ",")+1);
        // $image = Image::make($base64_str);
        
        // Log::info(__METHOD__, [get_class_methods(get_class($image))]);
        // $fileData = (string) $image->stream('jpg');
        
        // $hash = Str::random(40) . '.jpg';

        // Storage::put('file.jpg', $fileData, 'public');
        // $file = Storage::put("public/avatars/$hash", $fileData, 'public');

        //        Log::info(__METHOD__, [$file]);
        //        $file = request()->file('avatar')->store('avatars', 'public');
                //dd(request()->file('avatar'));
        $file = request()->file('avatar')->store('avatars', 'public');

        auth()->user()->update([
            'avatar_path' => $file
        ]);
        
        return response(asset(auth()->user()->avatar_path));
    }
}
