<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatalAntreanRequest;
use App\Services\AntreanService;

class AntreanController extends Controller
{
    public function __construct(private AntreanService $antreanService) {}

    public function batalkanOlehPasien(BatalAntreanRequest $request)
    {
        $antrean = $this->antreanService->batalkanOlehPasien($request->token);

        return response()->json([
            'success' => true,
            'message' => 'Antrean berhasil dibatalkan',
            'data' => ['nomor_antrean' => $antrean->nomor_antrean],
        ]);
    }

    public function batalkanOlehAdmin(int $id)
    {
        $antrean = $this->antreanService->batalkanOlehAdmin($id);

        return response()->json([
            'success' => true,
            'message' => 'Antrean berhasil dibatalkan oleh petugas',
            'data' => ['nomor_antrean' => $antrean->nomor_antrean],
        ]);
    }
}