<?php

namespace Conv\App\Controllers;

use App\Http\Controllers\Controller;
use Conv\Domain\PortsIn\ConvServiceInterface;
use Conv\App\Requests\ConvRequest;


class ConvController extends Controller
{
    public function words(ConvRequest $request, ConvServiceInterface $convServiceInterface)
    {
        return response()->json([
            'words'=>$convServiceInterface->getWords($request->number)
        ]);
    }

}
