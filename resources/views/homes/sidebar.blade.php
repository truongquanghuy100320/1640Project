<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="user-profile">
        @php
        $staff_id = session('staff_id');

        $staff = App\Models\StaffModel::where('staff_id', $staff_id)->first();
        $imageFileName = $staff->images; // Lấy tên tệp ảnh từ cột images
        $imagePath = public_path('public/images/faces/staff/' . $imageFileName); // Tạo đường dẫn tuyệt đối đến tệp ảnh

        @endphp

        @if($staff)
        <div class="user-image">
            <img src="{{ asset('public/images/faces/staff/' . $staff->images) }}">

        </div>
        <div class="user-name"> StaffID:
            {{ $staff->staff_id }}

        </div>
        <div class="user-name">
            {{ $staff->email }}
        </div>
        <div class="user-designation">
            {{ $staff->staffname }}
        </div>
        <div class="user-designation">
            @if($staff->faculty_id)
            Faculty ID: {{ $staff->faculty_id }}
            @else
            Faculty ID: 0
            @endif
            @if($staff->faculty)
            ({{ $staff->faculty->faculty_name }})
            @endif
        </div>
        @endif

    </div>

    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{URL::to('/dashboard')}}">
                <i class="icon-box menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @if(auth()->check() && auth()->user()->role_id == 1)
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="icon-disc menu-icon"></i>
                <span class="menu-title">Admin Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('Users.list-user') }}">Users Management</a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('students.list-student')}}">Students
                            Management</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('staffs.list-staff')}}">Staffs
                            Management</a></li>
                    <li class="nav-item"> <a class="nav-link"
                            href="{{route('contributions.list-contribution')}}">Contribution Management</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{route('roles.list-role')}}">Roles Management</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif
        @if(auth()->check() && (auth()->user()->role_id == 1 || auth()->user()->role_id == 2))
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="icon-disc menu-icon"></i>
                <span class="menu-title">Marketing Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link"
                            href="{{route('contributions.list-contribution')}}">Contribution Management</a></li>
        </li>
    </ul>
    </div>
    </li>
    @endif



    @if($staff->role_id == 1 || $staff->role_id == 3)
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic-2" aria-expanded="false" aria-controls="ui-basic-2">
            <i class="icon-head menu-icon"></i>
            <span class="menu-title"> Contributions </span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic-2">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('/all-list-contribution')}}"> List
                        Contribution </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('/chat/'.$staff->staff_id)}}"> Chart with
                        of student</a></li>
            </ul>
        </div>
    </li>
    @endif








    @if($staff->role_id == 1 || $staff->role_id == 2)
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#faculty-menu" aria-expanded="false"
            aria-controls="faculty-menu">
            <i class="icon-head menu-icon"></i>
            <span class="menu-title"> Faculty </span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="faculty-menu">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('/list-faculty')}}"> List Faculty</a></li>
            </ul>
        </div>
    </li>
    @endif

    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#profile-settings" aria-expanded="false"
            aria-controls="profile-settings">
            <i class="icon-head menu-icon"></i>
            <span class="menu-title"> Profile setting </span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="profile-settings">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('/show-profile/'.$staff->staff_id)}}">
                        Profile</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('/profile-edit/'.$staff->staff_id)}}">
                        Edit information </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{URL::to('/profile-login-edit/'.$staff->staff_id)}}">
                        Edit login information </a></li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{URL::to('/logout')}}">
            <i class="icon-book menu-icon"></i>
            <span class="menu-title">Logout</span>
        </a>
    </li>
    </ul>
</nav>