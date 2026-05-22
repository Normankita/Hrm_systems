<?php
namespace App\Http\Interfaces;

interface AttendanceInterface
{
    public static function createAttendanceRecord($data);

    public static function getEmployeeAttendanceRecords($employeeId, $date = null);

    public static function getDayBasedAttendanceRecords($date = null);

    public function updateAttendanceRecord($attendanceId, $data);

    public static function getDayBasedAbsentees($date = null);
}
