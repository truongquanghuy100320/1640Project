@extends('layout')
@section('content')
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
                <div class="col-md-12">
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Password</th>
                            <th>Status</th>
                            <th>User Type</th>
                            <th>Image</th>
                            <th>Role ID</th>
                            <th>Created</th>
                            <th>updated</th>
                            <th>Show/Hide</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list_user as $item)
                        <tr>


                            <td>{{$item -> user_id}}</td>
                            <td>{{$item -> name}}</td>
                            <td>{{$item -> email_login}}</td>
                            <td>{{$item -> phone_number	}}</td>
                            <td>{{ $item->password }}</td>
                            <td>
                                @if($item->status_user == 1)
                                <label class="badge badge-success"> <span
                                        style="color: green; font-size: 14px">Show</span></label>
                                @elseif($item->status_user == 0)
                                <label class="badge badge-danger"> <span
                                        style="color: red;font-size: 14px;color: #1a202c"> Locked </span></label>

                                @endif
                            </td>

                            <td>{{$item -> user_type}}</td>
                            <td>
                                <img src="resources/images/Faces/{{$item->images }}">
                            </td>


                            <td>
                                @if($item ->role)
                                ID: {{$item ->role->role_id}}, Name: {{$item->role->role_name}}
                                @else
                                Role information not available
                                @endif
                            </td>
                            <td>{{$item -> created_at}}</td>
                            <td>{{$item -> updated_at}}</td>

                            @if($item->role_id != 1)
                            <td>
                                <div class="template-demo">
                                    @if($item->status_user == 0)
                                    <a href="{{ route('user.edit-status-show',['user_id' => $item->user_id]) }}"
                                        class="btn btn-outline-success btn-fw">Show</a>
                                    @else
                                    <a href="{{ route('user.edit-status-hide',['user_id' => $item->user_id]) }}"
                                        class="btn btn-outline-success btn-fw">Hide</a>
                                    @endif
                                </div>
                            </td>
                            @endif
                            <td>
                                <a href="{{ route('users.delete-user', ['user_id' => $item->user_id]) }}"
                                    class="btn btn-outline-danger btn-fw"
                                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
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
