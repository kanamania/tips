<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use File as F;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function __construct(){}

    public function postFile(Request $request)
    {
        if (!$request->hasFile('f')) {
            return response()->json([
                'name' => 'Not found',
                'size' => 'NULL',
                'error' => 'No file'
            ]);
        }
        $file = $request->file('f');
        $filename = hash('MD5', time() . $file->getClientOriginalName());

        $upload = new File;
        $upload->name = $file->getClientOriginalName();
        $upload->hash = $filename;
        $upload->ext = $file->getClientOriginalExtension();
        $upload->size = $file->getClientSize();
        $upload->mime = $file->getClientMimeType();
        if ($file->move(public_path('/f'), $filename . '.' . $file->getClientOriginalExtension())) {
            if (auth()->check()) {
                $upload->created_by = Auth::id();
            }
            else {
                $upload->created_by = 1;
            }

            if ($upload->save()) {
                $success = new \stdClass();
                $success->name = substr($upload->name, 0, 26);
                $success->size = $upload->size;
                $success->url = action('Admin\FileController@getFile', $upload->id);
                $success->thumbnailUrl = action('Admin\FileController@getThumbnail', $upload->id);
                $success->deleteType = 'GET';
                $success->id = $upload->id;
                $success->deleteUrl = action('Admin\FileController@deleteFile', $upload->id);
                return response()->json([
                    'id' => $upload->id,
                    'name' => $success->name,
                    'thumb' => $success->thumbnailUrl,
                    'delete' => $success->deleteUrl
                ]);
            }

            return response()->json([
                'name' => 'NULL',
                'size' => 'NULL',
                'error' => 'Failed to save'
            ]);
        }
    }

    //Delete file
    public function deleteFile($id)
    {
        $file = File::find($id);
        if ($file) {
            $item = file_get_contents(public_path('f/'. $file->hashname));
            if ($item) {
                if (F::delete($item)) {
                    $f = $file;
                    $file->delete();
                    return response()->json(['name' => $f->name, 'status' => 'deleted']);
                }
            } else {
                return 'Not_Found';
            }
        } else {
            return 'Not_Found';
        }

    }

    //Get File
    public function getFile($id)
    {
        $file = File::find($id);
        if ($file) {
            try {
                $item = file_get_contents(public_path('f/'. $file->hashname));
                if ($item) {
                    $file_content = Image::make($item);
                    return $file_content->response();
                }

            } catch (\Exception $e){
                info($e->getMessage());
                $placeholder = file_get_contents(public_path('front/images/not_found.png'));
                return Image::make($placeholder)->response();
            }
        }
        $placeholder = file_get_contents(public_path('front/images/not_found.png'));
        return Image::make($placeholder)->response();
    }

    //Download File
    public function download($id, $useName=0)
    {
        $file = File::find($id);
        if ($file) {
            $name = $file->hashname;
            if($useName) {
                $useName = $file->name . '.' . $file->ext;
            }
            $item = file_get_contents(public_path('f/'. $name));
            if ($item) {
                return response()->download(public_path('f/'. $name), $useName, ['Content-Type: '.$file->mime, ]);
            }

            return 'Not_Found';
        }
        return 'Not_Found';
    }

    //Get File Thumbnail
    public function getThumbnail($id)
    {
        $width = \request('w') ?? 400;
        $height = \request('h');
        $file = File::find($id);
        if ($file) {
            try {
                $item = file_get_contents(public_path('f/' . $file->hashname));
                if ($item) {
                    $file_content = Image::make($item)->resize($width, $height, static function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    return $file_content->response();
                }
            } catch (\Exception $e){
                $placeholder = file_get_contents(public_path(''));
                return Image::make($placeholder)->response();
            }
        }
        $placeholder = file_get_contents(public_path('front/images/not_found.png'));
        return Image::make($placeholder)->response();
    }
}
