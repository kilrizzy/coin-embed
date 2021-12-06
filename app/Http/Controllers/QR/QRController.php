<?php

namespace App\Http\Controllers\QR;

use App\Http\Controllers\Controller;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use Auth;

class QRController extends Controller
{

    public function show(Request $request, $key)
    {
        if($key == 'encoded'){
            $key = urldecode($request->get('data'));
        }
        $options = new QROptions([
            'version'    => QRCode::VERSION_AUTO,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L,
            'addQuietZone' => false,
            'quietzoneSize' => 0,
        ]);
        $qrcode = new QRCode($options);
        $qrData = $qrcode->render($key);
        $image = \Image::make($qrData);
        return $image->response();
    }

}