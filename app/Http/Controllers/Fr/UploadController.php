<?php

namespace App\Http\Controllers\Fr;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function froala(Request $request)
    {
        if($request->input('tipo') == 'quiz')
        {
            $caminho = 'quiz/'.$request->input('quiz_id').'/froala';
        }
        elseif($request->input('tipo') == 'avaliacao')
        {
            $caminho = 'avaliacao/froala/'.$request->input('user_id');
        }
        elseif($request->input('tipo') == 'indica_avaliacao')
        {
            $caminho = 'indica/froala/avaliacao/'.$request->input('user_id');
        }
        else
        {
            $caminho = 'froala/'.Auth::user()->id;
        }

        $fileName = 'fRo'.uniqid().'.webp';
        $img = Image::make($request->file('file'));
        $img->resize(500, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('webp', 90);
        $resource = $img->stream()->detach();
        $caminhoStorage = config('app.frStorage').$caminho.'/'.$fileName;
        Storage::disk()->put($caminhoStorage, $resource);

    	$response = new \StdClass;
    	$response->link = config('app.cdn').'/storage/'.$caminho.'/'.$fileName;
        return response()->json(($response));
    }
}
