<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ContributionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FacltiesController;
use App\Http\Controllers\CommentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('homes.content');
})->middleware('auth.staff');

Route::get('/home', function () {
    return view('homes.content');
})->middleware('auth.staff');

Route::get('/content', [HomeController::class, 'content']);
Route::get('/login', [LoginController::class, 'login'])->name('login.login');
//login
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware('auth.staff')->name('homes.dashboard');



Route::post('admin-dashboard', [LoginController::class, 'login1'])->name('login');

//logout
Route::get('/logout', [LoginController::class, 'logout']);

//user
Route::get ('/list-user', [UserController::class, 'List_user'])->middleware('role.admin')->name('Users.list-user');

//student
Route::get ('/list-student', [StudentController::class, 'List_student'])->middleware('role.admin')->name('students.list-student');

//staff
Route::get('/list-staff',[StaffController::class,'list_staff'])->middleware('role.admin')->name('staffs.list-staff');

//contribution
Route::get('/list-contribution',[ContributionController::class,'list_contribution'])->middleware('role.check')->name('contributions.list-contribution');
Route::get('/download-All-Contributions', [ContributionController::class,'download_All_Contributions'])->middleware('role.check')->name('contributions.download-All-Contributions');
Route::get('/download-contribution-by-id/{contribution_id}',[ContributionController::class,'download_contribution_by_id'])->middleware('role.check')->name('contributions.download-contribution-by-id');
//role
Route::get('/list-role',[RoleController::class,'list_role'])->middleware('role.admin')->name('roles.list-role');

//edit user-role-Marketing-Coordinator
Route::get('/edit-marketing-coordinator/{user_id}', [UserController::class, 'edit_Marketing_Coordinator'])->middleware('role.admin')->name('users.edit-marketing-coordinator');
// edit user-role_admin
Route::get('/edit-admin/{user_id}',[UserController::class,'edit_admin'])->middleware('role.admin')->name('users.edit-admin');
//edit user-role-user

Route::get('/edit-user/{user_id}',[UserController::class,'edit_user'])->middleware('role.admin')->name('users.edit-user');

//edit status user
Route::get('/edit-status-hide/{user_id}',[UserController::class,'edit_status_hide'])->middleware('role.admin')->name('user.edit-status-hide');
Route::get('/edit-status-show/{user_id}',[UserController::class,'edit_status_show'])->middleware('role.admin')->name('user.edit-status-show');

//create user
Route::get('/add-user',[UserController::class,'user_add'])->middleware('role.admin')->name('Users.add-user');
Route::get('/delete-user/{user_id}',[UserController::class,'delete_user'])->middleware('role.admin')->name('users.delete-user');

//create student
Route::get('/add-student',[StudentController::class,'user_student'])->middleware('role.admin')->name('students.add-student');
Route::post('/save-student',[StudentController::class,'save_student'])->middleware('role.admin')->name('students.save-student');
Route::get('/edit-status-show-student/{student_id}',[StudentController::class,'edit_status_show_student'])->middleware('role.admin')->name('students.edit-status-show-student');
Route::get('/edit-status-hide-student/{student_id}',[StudentController::class,'edit_status_hide_student'])->middleware('role.admin')->name('students.edit-status-hide-student');
Route::get('/edit-student/{student_id}',[StudentController::class,'edit_student'])->middleware('role.admin')->name('students.edit-student');
Route::post('/update-student/{student_id}',[StudentController::class,'update_student'])->middleware('role.admin')->name('students.update-student');
Route::get('/delete-student/{student_id}',[StudentController::class,'delete_student'])->middleware('role.admin')->name('students.delete-student');
// create stafff
Route::get('/create-staff',[StaffController::class,'create_staff'])->middleware('role.admin')->name('staffs.create-staff');
Route::post('/save-staff',[StaffController::class,'save_staff'])->middleware('role.admin')->name('staffs.save-staff');
Route::get('/edit-staff/{staff_id}',[StaffController::class,'edit_staff'])->middleware('role.admin')->name('staffs.edit-staff');
Route::post('/update-staff/{staff_id}',[StaffController::class,'update_staff'])->middleware('role.admin')->name('staffs.update-staff');
Route::get('/edit-status-show-staff/{staff_id}',[StaffController::class,'edit_status_show_staff'])->middleware('role.admin')->name('staffs.edit-status-show-staff');
Route::get('/edit-status-hide-staff/{staff_id}',[StaffController::class,'edit_status_hide_staff'])->middleware('role.admin')->name('staffs.edit-status-hide-staff');
Route::get('/delete-staff/{staff_id}',[StaffController::class,'delete_staff'])->middleware('role.admin')->name('staffs.delete-staff');

// test mail

Route::get('/test-mail',[ContributionController::class,'test_mail'])->middleware('role.check')->name('contributions.test-mail');


// My

Route::get('/profile-edit/{profile_id}',[StaffController::class,'profile_edit'])->middleware('check.profile');
Route::post('/update-profile/{profile_id}',[StaffController::class,'update_profile']);

Route::get('/cancel-edit-profile/',[StaffController::class,'cancel_edit_profile'])->middleware('check.profile');
Route::get('/show-profile/{profile_id}',[StaffController::class,'show_profile'])->middleware('check.profile');

Route::get('/profile-login-edit/{profile_id}',[StaffController::class,'profile_login_edit'])->middleware('check.profile');
Route::post('/update-profile-login/{profile_id}',[StaffController::class,'update_profile_login'])->middleware('check.profile');


//contribution
Route::get('/all-list-contribution',[ContributionController::class,'all_list_contribution'])->middleware('check.staff')->name('contributions.all-list-contribution');

Route::get('/approve_contribution/{contribution_id}',[ContributionController::class,'approve_contribution'])->middleware('check.staff');
Route::get('/not-approve_contribution/{contribution_id}',[ContributionController::class,'notApprove_contribution'])->middleware('check.staff');
Route::get('/pending_contribution/{contribution_id}',[ContributionController::class,'pending_contribution'])->middleware('check.staff');

Route::get('/show-contribution/{contribution_id}',[ContributionController::class,'show_contribution'])->middleware('check.staff');

Route::get('/edit_contribution/{contribution_id}',[ContributionController::class, 'edit_contribution'])->middleware('check.staff');
Route::post('/update_contribution/{contribution_id}',[ContributionController::class,'update_contribution'])->middleware('check.staff');
Route::get('/cancel-edit/',[ContributionController::class,'cancel_edit'])->middleware('check.staff');

Route::get('/downloard/{contribution_id}',[ContributionController::class,'downloard_contribution'])->middleware('check.staff');

//pand
//Route::get('/delete_contribution/{contribution_id}',[ContributionController::class, 'delete_contribution'])->middleware('check.staff')->name('contributions.all-list-contribution');
Route::get('/remove/{contribution_id}',[ContributionController::class,'remove']);
Route::get('/chat/{id}',[CommentController::class,'chatbox']);
Route::post('/chat/message',[CommentController::class,'messageReceived']);
Route::get('/chatjoin',[CommentController::class,'chatjoin']);


Route::post('/send_message/', [CommentController::class, 'send_message']);

//Route::get('/sender/{id}',[CommentController::class,'sender']);
Route::get('/student-mess/{id}', [CommentController::class, 'student_mess'])->name('student_mess');




//faulty
Route::get('/list-faculty',[FacltiesController::class,'list_faculty']);

Route::get('/add-faculty',[FacltiesController::class,'add_faculty'])->middleware('role.check')->name('facultiess.list-faculty');
Route::post('/save-faculty',[FacltiesController::class,'save_faculty'])->middleware('role.check');

Route::post('/update-faculty/{faculty_id}',[FacltiesController::class,'update_faculty'])->middleware('role.check')->name('facultiess.list-faculty');
Route::get('/edit/{faculty_id}',[FacltiesController::class,'edit'])->middleware('role.check');
Route::get('/cancel-edit-faculty/',[FacltiesController::class,'cancel_edit_faculty'])->middleware('check.staff');


Route::get('/edit-status-show-faclties/{faculty_id}',[FacltiesController::class,'edit_status_show_faclties'])->middleware('role.check');
Route::get('/edit-status-hide-faclties/{faculty_id}',[FacltiesController::class,'edit_status_hide_faclties'])->middleware('role.check');

Route::get('/delete-faculties/{faculty_id}',[FacltiesController::class,'delete_faculties'])->middleware('role.check')->name('facultiess.list-faculty');