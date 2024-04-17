@extends('layout')
@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title" style="font-size: 40px"> Edit Faculty Information</h4>

        @foreach($edit as $edit_value)
            <form class="forms-sample" action="{{URL::to('/update-faculty/'.$edit_value->faculty_id)}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleInputCity1">Code of Faculty</label>
                    <input type="text" value="{{$edit_value->MSFaculties}}" name="faculty_ms" class="form-control" id="exampleInputCity1" placeholder="Location">
                </div>
                <div class="form-group">
                    <label for="exampleInputCity1">Name</label>
                    <input type="text" value="{{$edit_value->faculty_name}}" name="faculty_name" class="form-control" id="exampleInputCity1" placeholder="Location">
                </div>
                <button type="submit" name="edit_brand_product" class="btn btn-primary mr-2 div_left">Submit</button>
            </form>
            <a href="{{URL::to('/cancel-edit-faculty/')}}" onclick="return confirm('Are you cancel edit this faculty?')">
                <button class="btn btn-light div_right">Cancel</button>
            </a>
        @endforeach
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
    </style>
</div>
@endsection
