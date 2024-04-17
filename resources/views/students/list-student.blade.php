@extends('layout')
@section('content')

<br>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="template-demo">
                <a type="button" href="{{ route('students.add-student') }}" class="btn btn-info font-weight-bold"> +
                    Create student </a>
            </div>
            <br>
            <div class="table-responsive">
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                @if(session('sussec'))
                <div class="alert alert-danger">
                    {{ session('sussec') }}
                </div>
                @endif
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>MSV</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Faculty ID</th>
                            <th>Role ID</th>
                            <th>Created</th>
                            <th>updated</th>
                            <th>Action</th>
                            <th>Edit</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list_student as $item)
                        <tr>


                            <td>{{$item -> student_id}}</td>
                            <td>{{$item -> MSV}}</td>
                            <td>{{$item -> studentname}}</td>
                            <td>{{$item -> email_login	}}</td>
                            <td>{{$item -> password	}}</td>
                            <td>{{$item->studentphone }}</td>
                            <td>
                                @if($item->status == 1)
                                <label class="badge badge-success"> <span
                                        style="color: green; font-size: 14px">Show</span></label>
                                @elseif($item->status == 0)
                                <label class="badge badge-danger"> <span
                                        style="color: red;font-size: 14px;color: #1a202c"> Locked </span></label>

                                @endif
                            </td>

                            <td>
                                <img src="public/images/faces/student/{{$item->images }}">
                            </td>


                            <td>
                                @if($item -> faculty)
                                ID: {{$item->faculty->faculty_id}} , Name: {{$item->faculty->faculty_name}}
                                @else
                                Faculty information not available
                                @endif
                            </td>
                            <td>
                                @if($item -> role)
                                ID: {{$item->role->role_id}} , Name: {{$item->role->role_name}}
                                @else
                                Role information not available
                                @endif
                            </td>
                            <td>{{$item -> created_at}}</td>
                            <td>{{$item -> updated_at}}</td>
                            <td>
                                <div class="template-demo">
                                    @if($item->status == 0)
                                    <a href="{{ route('students.edit-status-show-student',['student_id' => $item->student_id]) }}"
                                        class="btn btn-outline-success btn-fw">Show</a>
                                    @else
                                    <a href="{{ route('students.edit-status-hide-student',['student_id' => $item->student_id]) }}"
                                        class="btn btn-outline-success btn-fw">Hide</a>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($item ->status == 1)
                                <a class="btn btn-dark btn-icon-text"
                                    href="{{ route('students.edit-student',['student_id' =>$item->student_id ]) }}">
                                    Edit
                                    <i class="mdi mdi-file-check btn-icon-append"></i>
                                </a>
                                <br />
                                <a href="{{ route('students.delete-student',['student_id' => $item->student_id]) }}"
                                    class="btn btn-outline-danger btn-fw"
                                    onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>

                                @else
                                <label class="badge badge-danger"> <span
                                        style="color: red;font-size: 14px;color: #1a202c"> Locked </span></label>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection