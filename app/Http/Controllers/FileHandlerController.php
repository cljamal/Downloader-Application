<?php

namespace App\Http\Controllers;

use App\FileHandlersModel;
use App\Jobs\DownloadFileJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHandlerController extends Controller
{
    /**
     * Main used storage disk
     * @var string
     */
    protected $storage_disk = 'public';

    /**
     * Index page view interface
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadPage()
    {
        return view('download-page');
    }

    /**
     * Entry point of the app
     *
     * @param Request $request
     * @param null $url
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function downloadLink( Request $request, $url = null )
    {
        if ( !empty( $url ) )
            $request->merge(['url' => $url]);

        if ($request->has('url') && $request->filled('url')) {
            $url = request()->input('url');

            $file = FileHandlersModel::create([
                'file_name' => basename( $url ),
                'file_storage' => $this->storage_disk,
                'file_url' => $url,
                'file_status' => 'pending',
            ]);
            DownloadFileJob::dispatch( $file );

            return 'File downloading';
        }
        return response()->json('Url is empty');
    }

    /**
     * API method to get all files list
     *
     * @return FileHandlersModel[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getFilesList()
    {
        return FileHandlersModel::all();
    }

    /**
     * Getting single file from storage
     *
     * @param FileHandlersModel $file
     * @return string|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getFile( FileHandlersModel $file ){

        $file_location = Storage::disk($file->file_storage)->getAdapter()->getPathPrefix();

        if ( is_file( $file_location . $file->file_name ) )
            return response()->download( $file_location . $file->file_name, $file->original_file_name );

        return 'File not found';
    }

    /**
     * Main download handler
     * @param FileHandlersModel $file
     * @return \Illuminate\Http\JsonResponse
     */
    public function downloadFile( FileHandlersModel $file ){
        $file_name = basename( $file->file_url );
        $file->update(['original_file_name' => $file_name ]);

        $isValidFile = $this->checkForMimes($file_name);
        $file_location = Storage::disk( $file->file_storage )->getAdapter()->getPathPrefix();

        if ($isValidFile) {

            if ( is_file( $file_location . $file->file_name ) )
                $file_name = Str::random(5) . '-' . $file_name;

            $file->update(['file_name' => $file_name ]);

            try {
                $downloading = file_get_contents( $file->file_url );
            } catch (\Exception $e) {
                $file->update(['file_status' => 'error']);
                return response()->json('File not found');
            }

            try{
                Storage::disk( $file->file_storage )->put( $file_name, $downloading );
                $file->update(['file_status' => 'complete']);
            }catch (\Exception $e){
                $file->update(['file_status' => 'error']);
            }

            return response()->json('File downloading');
        }

        $file->update(['file_status' => 'not_valid' ]);
        return response()->json('File not found');
    }

    /**
     * Checking file to accepted file type
     * @param $file_name
     * @return bool
     */
    private function checkForMimes($file_name)
    {
        $acceptedMimes = [
            'bmp',
            'svg',
            'png',
            'gif',
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
            'iso',
        ];
        $file = explode('.', $file_name);
        $mime = array_pop($file);

        if (in_array($mime, $acceptedMimes))
            return true;

        return false;
    }

}
