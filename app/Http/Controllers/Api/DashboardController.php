<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function viewSystemSummary(Request $request): JsonResponse
    {
        $summary = $this->dashboardService->getSystemSummary();

        return $this->successResponse(
            $summary,
            'Dashboard statistics retrieved successfully'
        );
    }
}
