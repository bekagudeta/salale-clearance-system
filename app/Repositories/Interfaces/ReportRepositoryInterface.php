<?php

namespace App\Repositories\Interfaces;

interface ReportRepositoryInterface
{
    public function getClearedStudentsByMonth($month, $year);
    public function getRejectedRequests($fromDate, $toDate);
    public function getDepartmentDelays();
    public function getGraduationStatistics($year);
    public function getFacultyBasedReports($faculty);
}