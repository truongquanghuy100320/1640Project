@extends('layout')
@section('content')
    <style>
        .invalid-feedback {
            font-size: 16px; /* Điều chỉnh kích thước chữ tùy theo nhu cầu */
            font-weight: bold; /* Điều chỉnh độ đậm của chữ */
            color: red; /* Điều chỉnh màu sắc của chữ */
        }
    </style>
    <br>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-danger">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                {{--                                action="{{route('students.save-student')}}"--}}
                                <form class="forms-sample" method="post" action="{{route('staffs.save-staff')}}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @if ($errors->has('staffname'))
                                        <div class="form-group">
                                            <label for="exampleInputName1">Name</label>
                                            <input type="text" name="staffname" class="form-control is-invalid"
                                                   id="exampleInputName1" placeholder="Name">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('staffname') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputName1">Name</label>
                                            <input type="text" name="staffname" class="form-control"
                                                   id="exampleInputName1" placeholder="Name">
                                        </div>
                                    @endif

                                    @if ($errors->has('email'))
                                        <div class="form-group">
                                            <label for="exampleInputEmail6">Email address</label>
                                            <input name="email" type="email" class="form-control is-invalid"
                                                   id="exampleInputEmail6" placeholder="Email">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('email') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputEmail6">Email address</label>
                                            <input name="email" type="email" class="form-control" id="exampleInputEmail6" placeholder="Email">
                                        </div>
                                    @endif
                                    @if ($errors->has('password'))
                                        <div class="form-group">
                                            <label for="exampleInputEmail6">Password</label>
                                            <input name="password" type="password" class="form-control is-invalid"
                                                   id="exampleInputEmail6" placeholder="Password">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputEmail6">Password</label>
                                            <input name="password" type="password" class="form-control" id="exampleInputEmail6" placeholder="Password">
                                        </div>
                                    @endif


                                    @if ($errors->has('status'))
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Status</label>
                                            <select name="status" class="form-control is-invalid" id="exampleSelectGender">
                                                <option value="" selected>Chọn trạng thái phù hợp</option>
                                                <option value="0">Hide</option>
                                                <option value="1">Show</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('status') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Status</label>
                                            <select name="status" class="form-control" id="exampleSelectGender">
                                                <option value="" selected>Chọn trạng thái phù hợp</option>
                                                <option value="0">Hide</option>
                                                <option value="1">Show</option>
                                            </select>
                                        </div>
                                    @endif
                                    @if($errors->has('faculty_id'))
                                    <div class="form-group">
                                            <label for="exampleSelectGender">Faculties</label>
                                            <select name="faculty_id" class="form-control is-invalid" id="exampleSelectGender"
                                                    onchange="updateUserType(this)">
                                                <option value="" selected>Chọn khoa phù hợp</option>
                                                @foreach($faculty as $itemRole)
                                                    @if($itemRole->faculty_id != 1)
                                                        <option value="{{$itemRole->faculty_id}}"
                                                                data-name="{{$itemRole->MSFaculties}}">{{$itemRole->faculty_id}}
                                                            -- {{$itemRole->faculty_name}} -- Department code: {{$itemRole->MSFaculties}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('faculty_id') }}
                                        </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Faculties</label>
                                            <select name="faculty_id" class="form-control " id="exampleSelectGender"
                                                    onchange="updateUserType(this)">
                                                <option value="" selected>Chọn khoa phù hợp</option>
                                                @foreach($faculty as $itemRole)
                                                    @if($itemRole->faculty_id != 1)
                                                        <option value="{{$itemRole->faculty_id}}"
                                                                data-name="{{$itemRole->MSFaculties}}">{{$itemRole->faculty_id}}
                                                            -- {{$itemRole->faculty_name}} -- Department code:  {{$itemRole->MSFaculties}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if($errors->has('role_id'))
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Role</label>
                                            <select name="role_id" class="form-control is-invalid" id="exampleSelectGender"
                                                    onchange="updateUserType(this)">
                                                <option value="" selected>Chọn Role phù hợp</option>
                                                @foreach($role as $itemRole)
                                                    @if($itemRole->role_id != 1)
                                                        <option value="{{$itemRole->role_id}}"
                                                                >{{$itemRole->role_id}}
                                                            -- {{$itemRole->role_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('role_id') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Role</label>
                                            <select name="role_id" class="form-control " id="exampleSelectGender"
                                                    onchange="updateUserType(this)">
                                                <option value="" selected>Chọn Role phù hợp</option>
                                                @foreach($role as $itemRole)
                                                    @if($itemRole->role_id != 1)
                                                        <option value="{{$itemRole->role_id}}"
                                                                >{{$itemRole->role_id}}
                                                            -- {{$itemRole->role_name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if($errors->has('MSStaff'))
                                        <div class="form-group">
                                            <label for="exampleInputName1">MS Staff</label>
                                            <input type="text" name="MSStaff" class="form-control is-invalid" id="exampleInputName22" placeholder="MS Staff" value="MSStaff" readonly>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('MSStaff') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputName1">MS Staff</label>
                                            <input type="text" name="MSStaff" class="form-control " id="exampleInputName22"  placeholder="MS Staff" value="MSStaff" readonly>
                                        </div>
                                    @endif
                                    @if($errors->has('images'))
                                        <div class="form-group">
                                            <label>File upload</label>
                                            <input type="file" name="img[]" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input name="images" type="file" class="form-control file-upload-info is-invalid" readonly placeholder="Upload Image">
                                                <span class="input-group-append"></span>
                                            </div>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('images') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label>File upload</label>
                                            <input type="file" name="img[]" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input name="images" type="file" class="form-control file-upload-info i" readonly placeholder="Upload Image">
                                                <span class="input-group-append"></span>
                                            </div>
                                        </div>
                                    @endif

                                   @if($errors->has('phone'))
                                    <div class="form-group">
                                            <label for="exampleInputCity1">Phone</label>
                                            <input type="text" name="phone" class="form-control is-invalid"
                                                   id="exampleInputCity1"
                                                   placeholder="Phone">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('phone') }}
                                        </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputCity1">Phone</label>
                                            <input type="text" name="phone" class="form-control "
                                                   id="exampleInputCity1"
                                                   placeholder="Phone">
                                        </div>
                                    @endif
                                        <input type="submit" name="save_student" value="Create"
                                               class="btn btn-primary mr-2">
                                        <a href="{{route('students.list-student')}}" class="btn btn-light">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

