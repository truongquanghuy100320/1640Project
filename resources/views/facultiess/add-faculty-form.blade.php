@extends('layout')
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Basic form elements</h4>
            <p class="card-description">
                Basic form elements
            </p>
            <form class="forms-sample" action="{{URL::to('/save-faculty')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleInputCity1">Code of faculty</label>
                    <input type="text" name="faculty_code" class="form-control" id="exampleInputCity1" placeholder="Code of faculty">
                </div>
                <div class="form-group">
                    <label for="exampleInputCity1">Name of faculty</label>
                    <input type="text" name="faculty_name" class="form-control" id="exampleInputCity1" placeholder="Name of faculty">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Status</label>
                    <select class="form-control form-control-lg font-select" name="status">
                        <option style="font-size: 15px" value="0"> Hide </option>
                        <option style="font-size: 15px" value="1" > Display </option>
                    </select>
                </div>
                    <button type="submit" name="save" class="btn btn-primary mr-2 div_left">Submit</button>
            </form>
            <a href="{{URL::to('/cancel-edit-faculty/')}}" onclick="return confirm('Are you cancel add new faculty?')">
            <button class="btn btn-light div_right">Cancel</button>
            </a>
        </div>
        <style>
            .div_right{

                width: 85px;

                float: left;

                text-align: center;

            }
            .div_left{

                width: 85px;

                float: left;

                text-align: center;

            }
            .font-select{
                width: 200px;
                height: 40px;
                font-size: 15px;
            }

        </style>
    </div>

@endsection
