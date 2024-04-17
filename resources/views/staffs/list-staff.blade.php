@extends('layout')
@section('content')
<br>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Student</h4>
            <div class="template-demo">
                <a type="button" href="{{ route('staffs.create-staff') }}" class="btn btn-info font-weight-bold"> +
                    Create staff </a>
            </div>
            <br />
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
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>MSStaff</th>
                            <th>Status</th>
                            <th>images</th>
                            <th>Faculty ID</th>
                            <th>Role ID</th>
                            <th>Created</th>
                            <th>updated</th>
                            <th>Edit</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list_staff as $item)
                        <tr>


                            <td>{{$item -> staff_id}}</td>
                            <td>{{$item -> 	staffname}}</td>
                            <td>{{$item -> email	}}</td>
                            <td>{{$item->phone }}</td>
                            <td>{{$item->MSStaff }}</td>
                            <td>
                                @if($item->status == 1)
                                <span style="color: green;">Show</span>
                                {{--                                @elseif($item->status == 2)--}}
                                {{--                                    <span style="color: red;"> Locked </span>--}}
                                @else
                                <span style="color: blue;">Locked</span>
                                @endif
                            </td>

                            <td>
                                <img src="public/images/faces/staff/{{$item->images }}"
                                    style="width: 100px; height: 100px;">
                            </td>


                            <td>
                                @if($item->faculty)
                                ID: {{ $item->faculty->faculty_id }}, Name: {{ $item->faculty->faculty_name }}
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
                                @if($item->role_id == 1)
                                @else
                                @if($item ->status == 1)
                                <a class="btn btn-dark btn-icon-text"
                                    href="{{ route('staffs.edit-staff',['staff_id' =>$item->staff_id ]) }}">
                                    Edit
                                    <i class="mdi mdi-file-check btn-icon-append"></i>
                                </a>
                                @else
                                <label class="badge badge-danger"> <span
                                        style="color: red;font-size: 14px;color: #1a202c"> Locked </span></label>
                                @endif
                                @endif
                            </td>
                            <td>
                                <div class="template-demo">
                                    @if($item->role_id == 1)

                                    @else
                                    @if($item->status == 0)
                                    <a href="{{ route('staffs.edit-status-show-staff',['staff_id' => $item->staff_id]) }}"
                                        class="btn btn-outline-success btn-fw">Show</a>
                                    @else
                                    <a href="{{ route('staffs.edit-status-hide-staff',['staff_id' => $item->staff_id]) }}"
                                        class="btn btn-outline-success btn-fw">Hide</a>
                                    @endif
                                    <a href="{{ route('staffs.delete-staff',['staff_id' => $item->staff_id]) }}"
                                        class="btn btn-outline-danger btn-fw"
                                        onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>

                                    @endif


                                </div>

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