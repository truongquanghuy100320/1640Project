@extends('layout')
@section('content')
<div class="list_view">
    <h5 class="mdi-message-alert">
        <?php
            $message_error1 = Session::get('error1');
            if ($message_error1){
                echo '<span class = "text-alert">'.$message_error1.'</span>';
                Session::put('error1',null);
            }
            ?>
        <?php
            $message_delete= Session::get('message_delete');
            if ($message_delete){
                echo '<span class = "text-alert">'.$message_delete.'</span>';
                Session::put('message_delete',null);
            }
            ?>
        <?php
            $message = Session::get('message');
            if ($message){
                echo '<span class = "text-alert">'.$message.'</span>';
                Session::put('message',null);
            }
            ?>
        <?php
            $message_update = Session::get('message_update');
            if ($message_update){
                echo '<span class = "text-alert">'.$message_update.'</span>';
                Session::put('message_update',null);
            }
            ?>
        <?php
            $message_cancel = Session::get('message_cancel');
            if ($message_cancel){
                echo '<span class = "text-alert">'.$message_cancel.'</span>';
                Session::put('message_cancel',null);
            }
            ?>
    </h5>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title" style="font-size: 35px">LIST CONTRIBUTION</h4>
            <div class="card table-responsive ">
                <table class="table-bordered  table-fix">
                    <thead>
                        <tr style="text-align: center; height: 70px">
                            <th>ID</th>
                            <th>Content</th>
                            <th>File Word</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Staff ID</th>
                            <th>Student ID</th>
                            <th>Facturity ID</th>
                            <th>Academic year</th>
                            <th>Create at</th>
                            <th>Update at</th>
                            <th>Downloaded</th>
                            <th>Download date</th>
                            <th>Star date</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                            <th>ACtive</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($all_list_contribution as $all_list_contribution )
                        <tr style="text-align: center; align-items: center;">
                            <td style="width: 40px">{{ $all_list_contribution->contribution_id }}</td>
                            <td class="content">{{ $all_list_contribution->content }}</td>
                            <td>
                                @if ($all_list_contribution->word_document)
                                <div>
                                    <a href="{{ $all_list_contribution->file_path}}"
                                        download>{{ $all_list_contribution->file_name }}</a>
                                </div>
                                @else
                                <div>
                                    No file
                                </div>
                                @endif
                            </td>
                            <td class="content">{{ $all_list_contribution->title }}</td>
                            <td>
                                <image src="resources/images/Faces/{{($all_list_contribution->image_url)}}"
                                    style="height: 120px; width: 120px"></image>
                            </td>
                            <td>{{ $all_list_contribution->student_id }}</td>
                            <td>{{ $all_list_contribution->staff_id }}</td>
                            <td>{{ $all_list_contribution->faculty_id }}</td>
                            <td>{{ $all_list_contribution->academic_years_id}}</td>
                            <td>{{ $all_list_contribution->created_at }}</td>
                            <td>{{ $all_list_contribution->updated_at }}</td>
                            <td>{{ $all_list_contribution->downloaded }}</td>
                            <td>{{ $all_list_contribution->download_date }}</td>
                            <td>{{ $all_list_contribution->start_day }}</td>
                            <td>{{ $all_list_contribution->expiration_date }}</td>

                            <td style="padding-left: 20px">
                                <div class="input-group-prepend" style="">
                                    <?php
                                                $tsatus=$all_list_contribution->status;
                                        if ($all_list_contribution->status == 2 ){
                                            ?>
                                    <button style="width: 110px" class="btn btn-sm btn-inverse-success dropdown-toggle"
                                        type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                        value="">Aprove </button>
                                    <?php
                                        }elseif ($all_list_contribution->status == 1){
                                            ?>
                                    <button class="btn btn-sm btn-inverse-danger dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value=""> Not
                                        Aprove </button>
                                    <?php
                                        }else{
                                            ?>
                                    <button class="btn btn-sm btn-inverse-warning dropdown-toggle" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="">
                                        Pending </button>
                                    <?php
                                        }
                                            ?>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="{{URL::to('/approve_contribution/'.$all_list_contribution -> contribution_id)}}">Approve</a>
                                        <a class="dropdown-item"
                                            href="{{URL::to('/not-approve_contribution/'.$all_list_contribution -> contribution_id)}}">Not
                                            Approve</a>
                                        <div role="separator" class="dropdown-divider"></div>
                                        <a class="dropdown-item"
                                            href="{{URL::to('/pending_contribution/'.$all_list_contribution -> contribution_id)}}">Pending</a>
                                    </div>
                                </div>

                            </td>
                            <td>
                                <div style="padding-right: 25px">
                                    <a
                                        href="{{URL::to('/show-contribution/'.$all_list_contribution -> contribution_id)}}">
                                        <button type="button" class="btn btn-primary btn-icon-text">
                                            <i class="mdi mdi-file-check btn-icon-prepend"></i>
                                            Views
                                        </button>
                                    </a>
                                </div>

                                <div style="margin-top: 5px;">
                                    <a href="{{URL::to('/remove/'.$all_list_contribution -> contribution_id)}}">
                                        <button type="button" class="btn btn-danger btn-icon-text">
                                            Remove
                                            <i class="mdi mdi-delete btn-icon-append"></i>
                                        </button>
                                    </a>

                                    <a href="{{URL::to('/downloard/'.$all_list_contribution -> contribution_id)}}">
                                        <button type="button" class="btn btn-info btn-icon-text">
                                            Download
                                            <i class="mdi mdi-download btn-icon-append"></i>
                                        </button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <style>
            .table-fix {
                width: 2200px;

            }

            .content {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;

                padding-right: 10px;
                padding-left: 10px;

                min-height: 150px;
                max-height: 150px;

                min-width: 150px;
                max-width: 150px;

                align-items: center;
            }

            .mdi-message-alert {
                font-size: 25px;
                color: red;
            }
            </style>
        </div>
    </div>
</div>
@endsection