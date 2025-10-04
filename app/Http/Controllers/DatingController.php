<?php

namespace App\Http\Controllers;

use DateTime;

class DatingController extends Controller
{
    private DateTime $startDate;
    private ?\DateInterval $totalDuration = null;

    public function __construct()
    {
        $this->startDate = new DateTime('03-10-2025');
    }

    public function getDuration()
    {
        $currentDate = new DateTime();
        $startLoveDate = $this->startDate;

        $this->totalDuration = $startLoveDate->diff($currentDate);

        return response()->json([
            'status' => 'success',
            'message' => 'Dating duration retrieved successfully',
            'timestamp' => now(),
            'data' => [
                'start_date' => $startLoveDate->format('d-m-Y'),
                'years' => $this->totalDuration->y,
                'months' => $this->totalDuration->m,
                'days' => $this->totalDuration->d,
            ],
        ]);
    }
}
