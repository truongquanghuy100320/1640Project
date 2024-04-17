@extends('layout')
@section('content')
<?php
// Sắp xếp mảng $sendMessage theo created_at tăng dần
$sortedMessages = $sendMessage->sortBy('created_at');
?>

<div class="container">
    <ul class="list-unstyled" id="messages" style="height: 700px; overflow-y: auto;">
        @foreach($sortedMessages as $key => $item)
        <li class="{{ $item->sender_type == 'student' ? 'incoming_msg' : 'outgoing_msg' }}">
            @if($item->sender_type == 'student')

            <div class="incoming_msg_img">
                <img src="{{ asset('/public/images/faces/student/' . $item->student_image) }}" alt="sunil"
                    style="width: 50px; height: 50px; border-radius: 50%;">
            </div>

            <div class="received_msg">
                <h5>{{$item->studentname}} - {{$item->MSV}}</h5>
                <div id="messageStudent" class="received_withd_msg student-message">
                    <p>{{$item->content}}</p>
                    <span class="time_date"> {{$item->created_at}}</span>
                </div>
            </div>
            @elseif($item->sender_type == 'staff')

            <div class="sent_msg staff-message">
                <div class="incoming_msg_img">
                    <img src="{{ asset('/public/images/faces/staff/' . $item->staff_image) }}" alt="sunil">
                </div>
                <div class="message-content">
                    <h5>{{$item->staffname}} - {{$item->faculty_name}}</h5>
                    <div id="messsageStaff">
                        <p>{{$item->content}}</p>
                        <span class="time_date"> {{$item->created_at}}</span>
                    </div>
                </div>
            </div>

            @endif
        </li>
        @endforeach
    </ul>






    {{-- Khu gửi tin --}}
    <form action="{{ URL::to('/send_message/') }}" id="sent" method="post">


        <input type="hidden" name="student_id" value="{{ $item->student_id }}">
        <input type="hidden" name="staff_id" value="{{ $item->staff_id }}">
        <!-- Input hidden cho faculty_id -->
        <input type="hidden" name="faculty_id" value="{{ $item->faculty_id }}">
        {{csrf_field()}}
        <div class="type_msg">
            <div class="input_msg_write">
                <input name="message" id="mess" type="text" class="write_msg" placeholder="Type a message"
                    style="font-family: Arial, sans-serif; font-size: 16px; color: #333; border: 1px solid #ccc;" />
                <button id="send" class="msg_send_btn" type="submit" style="background-color: #007bff; color: #fff;">
                    <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                </button>
                <script>
                // Đặt biến global để lưu trạng thái của trường input
                var isInputEmpty = true;

                // Kiểm tra trạng thái của trường input và ẩn/hiển thị nút gửi tương ứng
                function toggleSendButton() {
                    var sendButton = document.getElementById('send');
                    if (isInputEmpty) {
                        sendButton.style.display = 'none'; // Ẩn nút gửi nếu trường input trống
                    } else {
                        sendButton.style.display = 'block'; // Hiển thị nút gửi nếu có nội dung trong trường input
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    var messInput = document.getElementById('mess');
                    // Kiểm tra nếu trường input đã được điền sẵn khi trang được tải
                    if (messInput.value.trim() !== '') {
                        isInputEmpty = false;
                    }
                    toggleSendButton(); // Ẩn/hiển thị nút gửi ban đầu

                    // Thêm sự kiện lắng nghe vào trường input để cập nhật trạng thái của nút gửi khi người dùng nhập liệu
                    messInput.addEventListener('input', function() {
                        isInputEmpty = this.value.trim() === ''; // Cập nhật trạng thái của trường input
                        toggleSendButton(); // Ẩn/hiển thị nút gửi dựa trên trạng thái của trường input
                    });
                });
                </script>


            </div>
        </div>

    </form>
</div>

<style>
/* Add margin between consecutive messages */
.list-unstyled .incoming_msg+.outgoing_msg,
.list-unstyled .outgoing_msg+.incoming_msg {
    margin-top: 10px;
    /* Adjust this value as needed */
}

.staff-message .incoming_msg_img {
    margin-right: 10px;
    /* Adjust as needed */
}

.staff-message .message-content {
    display: inline-block;
    max-width: calc(100% - 60px);
    /* Adjust as needed */
    vertical-align: top;
}


.list-unstyled {
    /* Set a fixed height for the container */
    height: 400px;
    /* Enable vertical scrolling */
    overflow-y: auto;
}

.container {
    border: 2px solid #4CAF50;
    /* Màu và độ rộng đường viền */
    border-radius: 10px;
    /* Làm tròn góc */
    padding: 20px;
    /* Khoảng cách giữa nội dung và đường viền */
    width: 80%;
    /* Độ rộng của container */
    margin: 0 auto;
    /* Canh giữa container */
    margin-top: 20px;
    /* Khoảng cách từ container đến phần trên cùng */
}

.write_msg:focus {
    border: 2px solid #007bff;
    /* Thay đổi màu sắc và độ rộng của đường viền */
    outline: none;
    /* Loại bỏ đường viền nổi */
}

/* Định dạng phần input */
.input_msg_write {
    display: flex;
    align-items: center;
}

.write_msg {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 20px;
    outline: none;
}

/* Thiết kế nút gửi */
.msg_send_btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 50%;
    padding: 10px;
    margin-left: 10px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.msg_send_btn:hover {
    background-color: #0056b3;
}

.student-message {
    float: left;
    clear: both;
    margin-right: 50%;
}

.staff-message {
    float: right;
    clear: both;
    margin-left: 50%;
}

.container {
    max-width: 1170px;
    margin: auto;
}

.msg_history {
    display: flex;
    flex-direction: column-reverse;
    height: 400px;
    overflow-y: auto;
}

.message {
    margin-bottom: 20px;
}

.message h5 {
    color: #888;
    margin-bottom: 10px;
}

.incoming_msg,
.outgoing_msg {
    overflow: hidden;
    margin-bottom: 15px;
}

.incoming_msg_img img,
.outgoing_msg_img img {
    width: 40px;
    border-radius: 50%;
}

.received_withd_msg p,
.sent_msg p {
    background: #f3f3f3;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
    color: #000;
    margin: 5px 0;
    display: inline-block;
    max-width: 80%;
    word-wrap: break-word;
}

.time_date {
    font-size: 12px;
    color: #888;
}

.type_msg {
    margin-top: 20px;
}

.input_msg_write {
    position: relative;
    display: flex;
    justify-content: space-between;
}

.msg_send_btn {
    width: 40px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.msg_send_btn i {
    font-size: 18px;
}
</style>

@endsection
