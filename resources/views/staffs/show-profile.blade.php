@extends('layout')
@section('content')
<div class="col-md-9 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title" style="font-size: 35px">Profile Setting</h4>
            @php
            $staff_id = session('staff_id');

            $staff = App\Models\StaffModel::where('staff_id', $staff_id)->first();
            $imageFileName = $staff->images; // Lấy tên tệp ảnh từ cột images
            $imagePath = public_path('images/Faces/staff/' . $imageFileName); // Tạo đường dẫn tuyệt đối đến tệp ảnh

            @endphp

            <h5 class="mdi-message-alert">
                <style>
                .mdi-message-alert {
                    font-size: 20px;
                    color: red;
                }
                </style>
                <?php
                    $message = Session::get('message_update');
                    if ($message){
                        echo '<span class = "text-alert">'.$message.'</span>';
                        Session::put('message_update',null);
                    }
                    ?>
            </h5>

            <form role="form" action="{{URL::to('/update-profile/'. $staff -> satff_id)}}" enctype="multipart/form-data"
                method="post">
                {{csrf_field()}}
                <div class="position-center">
                    <div class="tab-pane fade active show" id="account-general">
                        <div class="card-body media align-items-center">
                            {{--                            <image src="{{URL::to('resources/images/Faces/'.$profile_edit-> images)}}"
                            alt class="d-block ui-w-80"></image>--}}
                            <image src="{{ asset('/public/images/faces/staff/' . $staff->images) }}" height="250"
                                width="250" alt class="d-block ui-w-80"></image>
                        </div>
                        <hr class="border-light m-0">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">Code Of Staff</label>
                                <input disabled="true" name="MSS" type="text" class="form-control mb-1"
                                    value="{{$staff -> MSStaff}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Staff Name</label>
                                <input disabled="true" name="staff_name" type="text" class="form-control mb-1"
                                    value="{{$staff -> staffname}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Role name</label>
                                <input disabled="true" name="role_name" type="text" class="form-control mb-1"
                                    value="{{$staff -> role->role_name}}">
                            </div>
                            @if($staff->role_id != 1 && $staff->role_id != 2)
                            <div class="form-group">
                                <label class="form-label">Faculty</label>
                                <input disabled="true" name="faculty" type="text" class="form-control mb-1"
                                    value="{{$staff -> faculty->faculty_name}}">
                            </div>
                            @endif
                            <div class="form-group">
                                <label class="form-label">Phone number</label>
                                <input disabled="true" name="phone" type="text" class="form-control"
                                    value="{{$staff->phone}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">E-mail</label>
                                <input disabled="true" name="satff_email" type="email" class="form-control mb-1"
                                    value="{{$staff->email}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <input disabled="true" name="pass" type="password" class="form-control mb-1"
                                    value="{{$staff->password}}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
