<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContributionModel;
use Illuminate\Support\Facades\DB;
use App\Models\StudentModel;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function content()
    {
        return view('homes.dashboard');
    }

    public function dashboard()
    {
        $contributionsStatus = $this->contributionsStatusChart();
        $contributionsPerFaculty = $this->contributionsPerFaculty();
        $registerCountsPerMonth = $this->registerCountsPerMonth();
        $registerCountsPerMonth2 = $this->registerCountsPerMonth2();
        $countStudents = $this->countStudents();
        $countUsers = $this->countUsers();
        $countContributors = $this->countContributors();
        $contributorsTopStudent = $this->contributorsTopStudent();
        // Trả về view 'homes.dashboard' với biến $contributionsPerFaculty và $registerCountsPerMonth
        return view('homes.dashboard', compact(
            'contributionsPerFaculty',
            'registerCountsPerMonth',
            'registerCountsPerMonth2',
            'contributionsStatus',
            'countStudents', 'countUsers','countContributors','contributorsTopStudent'
        )
        );
    }

    public function contributionsPerFaculty()
    {
        // Lấy số lượng đóng góp cho mỗi khoa
        $contributionsPerFaculty = ContributionModel::join('tbl_faculties', 'tbl_contributions.faculty_id', '=', 'tbl_faculties.faculty_id')
            ->select(
                'tbl_contributions.faculty_id',
                'tbl_faculties.faculty_name',
                DB::raw('count(*) as contribution_count'),
                DB::raw('SUM(tbl_contributions.condition_checkbox) as total_condition_checkbox'),
                DB::raw('SUM(tbl_contributions.status) as total_status')
            )
            ->groupBy('tbl_contributions.faculty_id', 'tbl_faculties.faculty_name')
            ->get();

        // Trả về biến $contributionsPerFaculty
        return $contributionsPerFaculty;
    }
    public function registerCountsPerMonth()
    {
        $registerCountsPerMonth = DB::table('tbl_students')
            ->select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(student_id) as register_count'))
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();

        return $registerCountsPerMonth;
    }

    public function registerCountsPerMonth2()
    {
        $registerCountsPerMonth2 = DB::table('tbl_users')
            ->select(DB::raw('YEAR(created_at) as year1, MONTH(created_at) as month1, COUNT(user_id) as register_count2'))
            ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
            ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
            ->get();

        return $registerCountsPerMonth2;
    }

    public function contributionsStatusChart()
    {
        // Đếm số lượng bản ghi với status = 1 (public) và status = 0 (private)
        $publicCount = ContributionModel::where('status', 1)->count();
        $privateCount = ContributionModel::where('status', 0)->count();

        // Tính tổng số lượng bản ghi
        $total = $publicCount + $privateCount;

        // Tính toán tỷ lệ phần trăm
        $publicPercentage = $total > 0 ? ($publicCount / $total) * 100 : 0;
        $privatePercentage = $total > 0 ? ($privateCount / $total) * 100 : 0;

        // Trả về dữ liệu tỷ lệ dưới dạng mảng
        return [
            'public' => $publicPercentage,
            'private' => $privatePercentage,
            'publicCount' => $publicCount, // Thêm số lượng bản ghi public
            'privateCount' => $privateCount // Thêm số lượng bản ghi private
        ];
    }


    public function countStudents()
    {
        $count = DB::table('tbl_students')->count();
        return $count;
    }

    public function countUsers()
    {
        $count = DB::table('tbl_users')->count();
        return $count;
    }
    public function countContributors()
    {
        $count = DB::table('tbl_contributions')->count();
        return $count;
    }
     public function contributorsTopStudent()
{
    // Truy vấn để xem ai có nhiều bài contribution_id có status = 1 để sắp xếp
    $topStudents = DB::table('tbl_contributions')
        ->select(
            'tbl_students.studentname',
            'tbl_students.email_login',
            'tbl_contributions.student_id',
            DB::raw('COUNT(*) AS total_contributions'),
            'tbl_faculties.faculty_name'
        )
        ->join('tbl_students', 'tbl_contributions.student_id', '=', 'tbl_students.student_id')
        ->join('tbl_faculties', 'tbl_contributions.faculty_id', '=', 'tbl_faculties.faculty_id')
        ->where('tbl_contributions.status', 1)
        ->groupBy('tbl_contributions.student_id', 'tbl_students.studentname', 'tbl_students.email_login', 'tbl_faculties.faculty_name')
        ->orderByDesc('total_contributions')
        ->get();

    // Trả về kết quả
    return [
        'top_students' => $topStudents,
    ];
}


}