@extends('layout')
@section('content')
    <div class="col-md-9 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title" style="font-size: 35px">Profile Setting</h4>

                <h5 class="mdi-message-alert">
                    <style>
                        .mdi-message-alert{
                            font-size: 20px;
                            color: red;
                        }
                    </style>
                    <?php
                    $error1 = Session::get('error1');
                    if ($error1){
                        echo '<span class = "text-alert">'.$error1.'</span>';
                        Session::put('error1',null);
                    }
                    ?>
                    <?php
                    $error2 = Session::get('error2');
                    if ($error2){
                        echo '<span class = "text-alert">'.$error1.'</span>';
                        Session::put('error2',null);
                    }
                    ?>
                    <?php
                    $error3 = Session::get('error3');
                    if ($error1){
                        echo '<span class = "text-alert">'.$error3.'</span>';
                        Session::put('error3',null);
                    }
                    ?>
                </h5>

                @foreach($profile_login_edit as $key => $staff )
                    <div class="position-center">
                        <form role="form" action="{{URL::to('/update-profile-login/'. $staff -> staff_id)}}" enctype="multipart/form-data" method="post">
                            {{csrf_field()}}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input disabled="true" name = "mail" type="text" class="form-control mb-1" value="{{$staff -> email}}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Old Password</label>
                                        <input name="old_pass" type="password" class="form-control" placeholder="Old Password" >
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New Password</label>
                                        <input name="new_pass" type="password" class="form-control" placeholder="New Password" value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Confirm New Password</label>
                                        <input name="con_new_pass" type="password" class="form-control" placeholder="Confirm New Password" value="">
                                    </div>
                                    <div class="form-group">
                                        <ul class="alert-danger">
                                            @foreach($errors -> all() as $errors)
                                                <li>{{$errors}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            <div class="div_left">
                                <style>
                                    .div_left{

                                        width: 85px;

                                        float: left;

                                        text-align: center;

                                    }
                                </style>
                                <a href="">
                                    <button type="submit" name="" class="btn btn-primary mr-2">Update</button>
                                </a>
                            </div>
                        </form>

                    </div>

                    <div class="div_right">
                        <style>
                            .div_right{

                                width: 85px;

                                float: left;

                                text-align: center;
                            }
                        </style>
                        <a href="{{URL::to('/cancel-edit-profile/')}}" onclick="return confirm('Are you cancel edit your password?')">
                            <button class="btn btn-light">Cancel</button>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
