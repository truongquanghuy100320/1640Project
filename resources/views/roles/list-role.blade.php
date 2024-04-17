@extends('layout')
@section('content')
<style>
.card-header {
    background-color: #ff2590;
    /* Màu nền */
    border-radius: 10px;
    /* Bo tròn ô */
    padding: 8px 10px;
    /* Khoảng cách bên trong */
    color: #fff;
    /* Màu chữ */
    font-weight: bold;
    /* Đậm chữ */
    cursor: pointer;
    /* Con trỏ chuột khi di chuột vào */


}

.card-header .btn-link {
    display: inline-block;
    /* Hiển thị nút dưới dạng hộp chữ nhật */
    font-size: 16px;
    /* Kích thước chữ */
    color: #ffffff;
    /* Màu chữ */
    background-color: #ff2590;
    /* Màu nền của hộp chữ nhật */
    padding: 8px 12px;
    /* Khoảng cách bên trong */
    border-radius: 10px;
    /* Bo tròn các góc của hộp chữ nhật */
    text-decoration: none;
    /* Loại bỏ gạch chân mặc định */
}

.custom-header {
    background-color: #ff2590;
    /* Màu nền */
    border-radius: 10px 10px 0 0;
    /* Bo tròn các góc của phần header */
    padding: 8px 10px;
    /* Khoảng cách bên trong */
    color: #fff;
    /* Màu chữ */
    font-weight: bold;
    /* Đậm chữ */
    cursor: pointer;
    /* Con trỏ chuột khi di chuột vào */
}


.outer-rectangle {
    width: auto;
    /* Độ rộng mong muốn */
    height: auto;
    /* Chiều cao mong muốn */
    background-color: #ff2590;
    /* Màu nền */
    border-radius: 10px;
    /* Bo tròn các góc */
    padding: 8px 12px;
    /* Khoảng cách bên trong */
    color: #fff;
    /* Màu chữ */
    font-weight: bold;
    /* Đậm chữ */
    cursor: pointer;
    /* Con trỏ chuột khi di chuột vào */
    display: inline-block;
    /* Hiển thị inline với kích thước của nội dung */
    margin: 0;
    /* Loại bỏ margin */
    border: 2px solid #ff2590;
    /* Viền màu y */
}



.card-header:hover {
    background-color: #d60062;
    /* Màu nền khi hover */
}

.card-body {
    padding: 10px;
    /* Khoảng cách bên trong */
}

/* Định dạng danh sách */
.nav-link {
    color: #3a9014;
    /* Màu chữ */
    font-weight: bold;
    /* Đậm chữ */
}

/* Định dạng hover trên danh sách */
.nav-link:hover {
    color: #007bff;
    /* Màu chữ khi hover */
}
</style>
<br>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Student</h4>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Role ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>updated</th>
                            <!-- <th>Action</th> -->

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list_role as $item)
                        <tr>


                            <td>{{$item -> role_id}}</td>
                            <td>{{$item -> 	role_name}}</td>
                            <td>
                                @if($item->status == 1)
                                <span style="color: red;">Administrators</span>
                                @elseif($item->status == 2)
                                <span style="color: green;"> Marketing Management </span>
                                @elseif($item->status == 3)
                                <span style="color: deeppink;"> Marketing Coordinator </span>
                                @elseif($item->status == 4)
                                <span style="color: deeppink;"> Student </span>

                                @else
                                <span style="color: #b23e35;"> User </span>
                                @endif
                            </td>

                            <td>{{$item -> created_at}}</td>
                            <td>{{$item -> updated_at}}</td>
                            <!-- <td>
                                <div class="outer-rectangle">
                                    <div class="accordion" id="accordion{{ $item->role_id }}">
                                        <div class="card">
                                            <div class="card-header custom-header" id="heading{{ $item->role_id }}">
                                                <h2 class="mb-0">
                                                    <a class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $item->role_id }}" aria-expanded="true" aria-controls="collapse{{ $item->role_id }}">
                                                        List of job positions
                                                    </a>
                                                </h2>
                                            </div>

                                            <div id="collapse{{ $item->role_id }}" class="collapse" aria-labelledby="heading{{ $item->role_id }}" data-parent="#accordion{{ $item->role_id }}">
                                                <div class="card-body">
                                                    <ul class="nav flex-column sub-menu">
                                                        <li class="nav-item"> <a class="nav-link" href="{{URL::to('/edit-marketing-coordinator'.$item->role_id)}}">Marketing Coordinator</a></li>
                                                        <li class="nav-item"> <a class="nav-link" href="#">Administrators</a></li>
                                                        <li class="nav-item"> <a class="nav-link" href="#">User</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td> -->


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection