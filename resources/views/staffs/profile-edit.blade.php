@extends('layout')
@section('content')
<div class="col-md-9 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title" style="font-size: 35px">Profile Setting</h4>

            @foreach($profile_edit as $key => $staff )
            <div class="position-center">
                <form role="form" action="{{URL::to('/update-profile/'. $staff -> staff_id)}}"
                    enctype="multipart/form-data" method="post">
                    {{csrf_field()}}
                    <div class="tab-pane fade active show" id="account-general">
                        <div class="card-body media align-items-center">
                            {{--                            <image src="{{URL::to('resources/images/Faces/'.$profile_edit-> images)}}"
                            alt class="d-block ui-w-80"></image>--}}
                            <image ssrc="{{ asset('/public/images/faces/staff/' . $staff->images) }}" height="250"
                                width="250" alt class="d-block ui-w-80"></image>
                            <div class="media-body ml-4">
                                <label class="btn btn-outline-primary">
                                    Upload new photo
                                    <input name="Avatar" type="file" class="account-settings-fileinput">
                                </label> &nbsp;
                                <div style="font-size: 15pd ">Allowed JPG, GIF or PNG. Max size of 800K</div>
                            </div>
                        </div>
                        <hr class="border-light m-0">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label"> Code Of Staff</label>
                                <input disabled="true" name="MSS" type="text" class="form-control mb-1"
                                    value="{{$staff -> MSStaff}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Staff Name</label>
                                <input name="staff_name" type="text" class="form-control mb-1"
                                    value="{{$staff -> staffname}}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone number</label>
                                <input name="phone" type="text" class="form-control" value="{{$staff->phone}}">
                            </div>

                            <div class="form-group">
                                <ul class="alert-danger">
                                    @foreach($errors -> all() as $errors)
                                    <li>{{$errors}}</li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>
                    </div>
                    <div class="div_left">
                        <style>
                        .div_left {

                            width: 85px;

                            float: left;

                            text-align: center;

                        }
                        </style>
                        <a href="">
                            <button type="submit" class="btn btn-primary mr-2">Update</button>
                        </a>
                    </div>
                </form>
            </div>

            <div class="div_right">
                <style>
                .div_right {

                    width: 85px;

                    float: left;

                    text-align: center;
                }
                </style>
                <a href="{{URL::to('/cancel-edit-profile/')}}"
                    onclick="return confirm('Are you cancel edit your profile?')">
                    <button class="btn btn-light">Cancel</button>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection