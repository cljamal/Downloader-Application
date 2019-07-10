<?php

namespace App\Http\Controllers;

use App\FileHandlersModel;
use App\Jobs\DownloadFileJob;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileHandlerController extends Controller
{
    /**
     * Main used storage disk
     * @var string
     */
    protected $storage_disk = 'public';

    /**
     * Index page view interface
     * @return View
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
     * @return JsonResponse|string
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
     * @return FileHandlersModel[]
     */
    public function getFilesList()
    {
        return FileHandlersModel::all();
    }

    /**
     * Getting single file from storage
     *
     * @param FileHandlersModel $file
     * @return string|BinaryFileResponse
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
     * @return JsonResponse
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
                $downloading = $this->getFileCurl( $file->file_url );
            } catch (\Exception $e) {
                $file->update([
                    'file_status' => 'error',
                    'file_error_info' => $e->getMessage()
                ]);
                return response()->json('File not found');
            }

            try{
                Storage::disk( $file->file_storage )->put( $file_name, $downloading );
                $file->update(['file_status' => 'complete']);
            }catch (\Exception $e){
                $file->update([
                    'file_status' => 'error',
                    'file_error_info' => $e->getMessage()
                ]);
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
            // Images
            'bmp',
            'svg',
            'jpg',
            'jpeg',
            'png',
            'gif',
            'webp',

            //Archives
            'zip',
            'rar',
            'iso',

            // Documents
            'pdf',
            'doc',
            'docx',
            'xls',
            'xlsx',
        ];
        $file = explode('.', $file_name);
        $mime = array_pop($file);

        if (in_array($mime, $acceptedMimes))
            return true;

        return false;
    }

    /**
     * Get file by curl
     * @param $url
     * @return bool|string
     */
    public function getFileCurl( $url ){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, 'Chrome/51.0.2704.103');
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;
    }
}
