<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\StatsRepository;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    protected $statsRepository;

    public function __construct(StatsRepository $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    
    public function getCourseStats()
    {
        $stats = $this->statsRepository->getCourseStats();
        return response()->json(['stats' => $stats]);
    }

   
    public function getCategoryStats()
    {
        $stats = $this->statsRepository->getCategoryStats();
        return response()->json(['stats' => $stats]);
    }

    public function getTagStats()
    {
        $stats = $this->statsRepository->getTagStats();
        return response()->json(['stats' => $stats]);
    }

  
    public function getEnrollmentStats()
    {
        $stats = $this->statsRepository->getEnrollmentStats();
        return response()->json(['stats' => $stats]);
    }
}