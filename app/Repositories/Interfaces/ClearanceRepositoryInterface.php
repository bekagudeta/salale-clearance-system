<?php

namespace App\Repositories\Interfaces;

interface ClearanceRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function findByStudent($studentId);
    public function create(array $data);
    public function updateStatus($id, $status);
    public function getPendingByDepartment($departmentId);
    public function getStatsByStudent($studentId);
}