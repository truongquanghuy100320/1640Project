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

                                <form class="forms-sample" method="post" action="{{ route('students.update-student', ['student_id' => $edit->student_id]) }}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @if ($errors->has('studentname'))
                                        <div class="form-group">
                                            <label for="exampleInputName1">Name</label>
                                            <input type="text" name="studentname" class="form-control is-invalid"
                                                   id="exampleInputName1" placeholder="Name" value="{{$edit ?$edit->studentname : '' }}">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('studentname') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputName1">Name</label>
                                            <input type="text" name="studentname" class="form-control "
                                                   id="exampleInputName1" placeholder="Name" value="{{ $edit ? $edit->studentname : '' }}">

                                        </div>
                                    @endif

                                    @if ($errors->has('email_login'))
                                        <div class="form-group">
                                            <label for="exampleInputEmail6">Email address</label>
                                            <input name="email_login" type="email" class="form-control is-invalid"
                                                   id="exampleInputEmail6" placeholder="Email" value="{{$edit ? $edit->email_login : ''}}">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('email_login') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputEmail6">Email address</label>
                                            <input name="email_login" type="email" class="form-control" id="exampleInputEmail6" placeholder="Email" value="{{$edit ? $edit->email_login : ''}}">
                                        </div>
                                    @endif
                                    @if ($errors->has('password'))
                                        <div class="form-group">
                                            <label for="exampleInputEmail6">Password</label>
                                            <input name="password" type="password" class="form-control is-invalid"
                                                   id="exampleInputEmail6" placeholder="Password" value="{{$edit ? $edit->password : ''}}">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('password') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputEmail6">Password</label>
                                            <input name="password" type="password" class="form-control" id="exampleInputEmail6" placeholder="Password" value="{{$edit ? $edit->password : ''}}">
                                        </div>
                                    @endif
                                    @if ($errors->has('status'))
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Status</label>
                                            <select name="status" class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }}" id="exampleSelectGender">
                                                <option value="" {{ $edit ? ($edit->status == '' ? 'selected' : '') : 'selected' }}>Chọn trạng thái phù hợp</option>
                                                <option value="0" {{ $edit && $edit->status == '0' ? 'selected' : '' }}>Hide</option>
                                                <option value="1" {{ $edit && $edit->status == '1' ? 'selected' : '' }}>Show</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('status') }}
                                            </div>
                                        </div>

                                    @else
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Status</label>
                                            <select name="status" class="form-control " id="exampleSelectGender">
                                                <option value="" {{ $edit ? ($edit->status == '' ? 'selected' : '') : 'selected' }}>Chọn trạng thái phù hợp</option>
                                                <option value="0" {{ $edit && $edit->status == '0' ? 'selected' : '' }}>Hide</option>
                                                <option value="1" {{ $edit && $edit->status == '1' ? 'selected' : '' }}>Show</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('status') }}
                                            </div>
                                        </div>

                                    @endif
                                    @if($errors->has('faculty_id'))
                                        <div class="form-group">
                                            <label for="exampleSelectGender">Faculties</label>
                                            <select name="faculty_id" class="form-control{{ $errors->has('faculty_id') ? ' is-invalid' : '' }}" id="exampleSelectGender" onchange="updateUserType(this)">
                                                <option value="" selected>Chọn khoa phù hợp</option>
                                                @foreach($faculty as $itemRole)
                                                    @if($itemRole->faculty_id != 1)
                                                        <option value="{{ $itemRole->faculty_id }}" {{ $edit && $edit->faculty_id == $itemRole->faculty_id ? 'selected' : '' }} data-name="{{ $itemRole->MSFaculties }}">
                                                            {{ $itemRole->faculty_id }} -- {{ $itemRole->faculty_name }} -- Department code: {{$itemRole->MSFaculties}}
                                                        </option>
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
                                                        <option value="{{ $itemRole->faculty_id }}" {{ $edit && $edit->faculty_id == $itemRole->faculty_id ? 'selected' : '' }} data-name="{{ $itemRole->MSFaculties }}">
                                                            {{ $itemRole->faculty_id }} -- {{ $itemRole->faculty_name }} -- Department code: {{$itemRole->MSFaculties}}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    @if($errors->has('MSV'))
                                        <div class="form-group">
                                            <label for="exampleInputName1">Name</label>
                                            <input type="text" name="MSV" class="form-control is-invalid" id="exampleInputName22" placeholder="MSV"
                                                   value="{{$edit ? $edit->MSV : '' }}" readonly>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('MSV') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputName1">Name</label>
                                            <input type="text" name="MSV" class="form-control " id="exampleInputName22" placeholder="MSV" value="{{$edit ? $edit->MSV : '' }} " readonly

                                            >
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
                                            <br>
                                            <img src="{{ asset('public/images/faces/student/' . $edit->images) }}" height="100" width="100">
                                            <div class="invalid-feedback">
                                                {{ $errors->first('images') }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label>File upload</label>
                                            <input type="file" name="img[]" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input name="images" type="file" class="form-control file-upload-info " onchange="previewImage(event)" readonly placeholder="Upload Image"  >

                                                <span class="input-group-append"></span>


                                            </div>
                                            <br>
                                            <img src="{{ asset('public/images/faces/student/' . $edit->images) }}" height="100" width="100" id="preview">
                                        </div>
                                    @endif

                                   @if($errors->has('studentphone'))
                                    <div class="form-group">
                                            <label for="exampleInputCity1">Phone</label>
                                            <input type="text" name="studentphone" class="form-control is-invalid"
                                                   id="exampleInputCity1"
                                                   placeholder="Phone" value="{{$edit ? $edit -> studentphone : ''}}">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('studentphone') }}
                                        </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="exampleInputCity1">Phone</label>
                                            <input type="text" name="studentphone" class="form-control "
                                                   id="exampleInputCity1"
                                                   placeholder="Phone" value="{{$edit ? $edit -> studentphone : ''}}">
                                        </div>
                                    @endif
                                        <input type="submit" name="update_student" value="Update"
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

