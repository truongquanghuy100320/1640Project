@extends('layout')
@section('content')
<style>
.mdi-message-alert {
    font-size: 25px;
    color: red;
}

.btn-add {
    font-size: 20px;
}
</style>
<div class="card">
    <div class="card-body">
        <h4 class="card-title" style="font-size: 40px">List Faculty</h4>
        <h6 class="mdi-message-alert">
            <?php
                $message = Session::get('message_cancel_f');
                if ($message){
                    echo '<span class = "text-alert">'.$message.'</span>';
                    Session::put('message_cancel_f',null);
                }
                ?>
        </h6>
        <div class="card-body">
            <a type="button" href="{{URL::to('/add-faculty')}}" class="btn btn-info font-weight-bold btn-add">ADD NEW
                FACULTY</a>
        </div>
        <?php
             $message = Session::get('message');
             if($message){
                 echo '<span class="text-alert">'.$message.'</span>';
                 Session::put('message',null);
             }
             ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>falcuty id</th>
                        <th>Code of falcuty</th>
                        <th>falcuty name</th>
                        <th>status</th>
                        <th>created at</th>
                        <th>updated at</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list_faculty as $key => $all_faculties)
                    <tr>
                        <td>{{$all_faculties->faculty_id}}</td>
                        <td>{{$all_faculties->MSFaculties}}</td>
                        <td>{{$all_faculties->faculty_name}}</td>

                        <td>
                            <?php
                            if($all_faculties->status==1){
                                ?>
                            <div class="template-demo">
                                <a href="{{URL::to('/edit-status-hide-faclties/'.$all_faculties->faculty_id)}}"
                                    class="btn btn-outline-success btn-fw">Show</a>
                            </div>
                            <?php
                            }else{
                                ?>

                            <div class="template-demo">
                                <a href="{{URL::to('/edit-status-show-faclties/'.$all_faculties->faculty_id)}}"
                                    class="btn btn-outline-danger btn-fw">Hide</a>
                            </div>
                            <?php
                            }
                                ?>
                        </td>


                        <td>{{$all_faculties->created_at}}</td>
                        <td>{{$all_faculties->updated_at}}</td>
                        <td>
                            <a href="{{URL::to('/edit/'.$all_faculties->faculty_id)}}">
                                <button type="button" class="btn btn-dark btn-icon-text">
                                    Edit
                                    <i class="mdi mdi-playlist-edit btn-icon-append"></i>
                                </button>
                            </a>

                            <a onclick="return confirm('Are you sure delete?')"
                                href="{{URL::to('/delete-faculties/'.$all_faculties->faculty_id)}}"
                                class="active stuling-edit" ui-toggle-class="">
                                <button type="button" class="btn btn-danger btn-icon-text">
                                    Remove
                                    <i class="mdi mdi-delete btn-icon-append"></i>
                                </button>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection