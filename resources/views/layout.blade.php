<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Regal Admin</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{asset('public/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/feather/feather.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/base/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <link rel="stylesheet" href="{{asset('public/vendors/flag-icon-css/css/flag-icon.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/jquery-bar-rating/fontawesome-stars-o.css')}}">
    <link rel="stylesheet" href="{{asset('public/vendors/jquery-bar-rating/fontawesome-stars.css')}}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('public/images/favicon.png ')}}" />
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
    <style>
    .pie-chart {
        width: 280px;
        /* Kích thước của hình tròn */
        height: 280px;
        /* Kích thước của hình tròn */
        border-radius: 50%;
        /* Tạo hình tròn */
        background-color: #fff;
        /* Màu nền của hình tròn */
        position: relative;
        /* Để tạo vị trí tương đối cho văn bản */
    }

    .textLayer {
        position: absolute;
        /* Đặt văn bản trong hình tròn */
        top: 50%;
        /* Đặt văn bản ở giữa theo chiều dọc */
        left: 50%;
        /* Đặt văn bản ở giữa theo chiều ngang */
        transform: translate(-50%, -50%);
        /* Dịch chuyển văn bản để nằm chính giữa */
        text-align: center;
        /* Căn giữa văn bản */
        display: none;
        /* Ẩn văn bản ban đầu */
    }
    </style>
    <style>
    /* Định dạng CSS */
    .contribution-count {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .contribution-count-header {
        text-align: center;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    .contribution-count-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .contribution-count-item {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 20px;
        font-family: Arial, sans-serif;
        font-size: 16px;
        color: #666;
    }

    .contribution-count-item h3 {
        color: #333;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .contribution-count-item p {
        margin-bottom: 5px;
    }

    @media only screen and (max-width: 767px) {
        .contribution-count-list {
            grid-template-columns: repeat(auto-fit, minmax(100%, 1fr));
        }

        .contribution-count-item {
            width: 100%;
        }
    }
    </style>
    <style>
    .top-submission-contribution {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
    }

    .header-title {
        color: #007bff;
        /* Màu xanh */
        cursor: pointer;
        /* Biểu tượng của chuột khi di chuột qua */
        transition: color 0.3s;
        /* Hiệu ứng chuyển đổi màu */
    }

    .header-title:hover {
        color: #0056b3;
        /* Màu xanh đậm khi hover */
    }

    .contribution-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .contribution-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .contribution-item {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 20px;
    }

    .contribution-item:hover {
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        /* Đổ bóng khi hover */
    }

    .contribution-item h3 {
        color: #333;
        margin-bottom: 10px;
    }

    .contribution-item p {
        color: #666;
    }
    </style>

    <style>
    .top-submission-contribution {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 8px;
    }

    .contribution-header {
        text-align: center;
        margin-bottom: 20px;
        font-family: Arial, sans-serif;
        /* Đặt font chữ */
        font-size: 24px;
        /* Đặt kích thước chữ */
        font-weight: bold;
        /* Đặt độ đậm */
        color: #333;
        /* Đặt màu chữ */
    }

    .contribution-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .contribution-item {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 20px;
        font-family: Arial, sans-serif;
        /* Đặt font chữ */
        font-size: 16px;
        /* Đặt kích thước chữ */
        color: #666;
        /* Đặt màu chữ */
    }

    .contribution-item h3 {
        color: #333;
        /* Đặt màu chữ */
        font-size: 20px;
        /* Đặt kích thước chữ */
        font-weight: bold;
        /* Đặt độ đậm */
        margin-bottom: 10px;
    }

    .contribution-item p {
        margin-bottom: 5px;
    }

    @media only screen and (max-width: 767px) {
        .contribution-list {
            grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
            /* Thay đổi cột grid thành một cột duy nhất */
        }

        .contribution-item {
            width: 100%;
            /* Đặt chiều rộng của mỗi phần tử thành 100% */
        }

        .contribution-item h3 {
            font-size: 18px;
            /* Giảm kích thước chữ cho tiêu đề */
        }
    }
    </style>
    <style>
    .dashboard-container {
        max-width: 800px;
        /* Đặt chiều rộng tối đa của khung */
        margin: 50px auto;
        /* Căn giữa khung */
    }

    .dashboard {
        display: flex;
        flex-wrap: wrap;
        /* Cho phép các mục trong dashboard xuống dòng khi không đủ không gian */
        justify-content: space-between;
    }

    .dashboard-item {
        flex: 1;
        padding: 20px;
        border: 1px solid #ccc;
        text-align: center;
        font-size: 18px;
        background-color: #f9f9f9;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-bottom: 20px;
        /* Khoảng cách dưới giữa các ô */
        box-sizing: border-box;
        /* Đảm bảo padding và border không làm thay đổi kích thước của ô */
    }

    /* Hover effect */
    .dashboard-item:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 767px) {
        .dashboard-item {
            flex-basis: calc(50% - 20px);
            /* Hiển thị 2 ô mỗi hàng trên thiết bị di động */
        }
    }
    </style>

</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        @include('homes.nav')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            @include('homes.sidebar')
            <!-- partial -->
            <div class="main-panel">
                @yield('content')
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                @include('homes.footer')

                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <!-- base:js -->
    <script src="{{asset('public/vendors/base/vendor.bundle.base.js')}}"></script>
    <script src="{{asset('public/vendors/chart.js/chart.min.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{asset('public/js/off-canvas.js')}}"></script>
    <script src="{{asset('public/js/d3.v7.min.js')}}"></script>
    <script src="{{asset('public/js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('public/js/template.js')}}"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->

    <script src="{{asset('public/vendors/jquery-bar-rating/jquery.barrating.min.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- Custom js for this page-->
    <script src="{{asset('public/js/dashboard.js')}}"></script>
    <!-- End custom js for this page-->
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>

    <script>
    function updateUserType(select) {
        var selectedRoleName = select.options[select.selectedIndex].text.split('--')[1].trim();
        document.getElementById('exampleInputEmail3').value = selectedRoleName;
    }
    </script>
    <script>
    function updateUserType(select) {
        var selectedOption = select.options[select.selectedIndex];
        var facultyName = selectedOption.getAttribute('data-name'); // Lấy tên khoa từ attribute data-name
        var randomNumber = Math.floor(100000 + Math.random() * 900000);
        var MSV = facultyName.substring(0, 3) + randomNumber;
        document.getElementById("exampleInputName22").value = MSV;
    }
    </script>

    <script>
    $(document).ready(function() {
        // Bắt sự kiện click trên hình ảnh nhỏ
        $('.student-image').click(function() {
            // Lấy đường dẫn hình ảnh lớn từ thuộc tính src của hình ảnh nhỏ
            var imgSrc = $(this).attr('src');

            // Hiển thị modal hoặc overlay
            $('#largeImageModal').show();

            // Hiển thị hình ảnh lớn trong modal hoặc overlay
            $('#largeImage').attr('src', imgSrc);
        });

        // Bắt sự kiện click để đóng modal hoặc overlay
        $('.close').click(function() {
            $('#largeImageModal').hide();
        });
    });
    </script>


    <script>
    document.getElementById('downloadLink').addEventListener('click', function(event) {
        // Xác định thư mục mặc định để lưu file
        var defaultSavePath = '/path/to/default/folder/';

        // Xác định tên tệp
        var fileName = 'all_contributions_' + new Date().toISOString().slice(0, 19).replace(/[-:]/g, '') +
            '.zip';

        // Thiết lập thư mục lưu file
        this.setAttribute('download', fileName);
        this.href = this.href + '?download=true&defaultSavePath=' + encodeURIComponent(defaultSavePath);
    });
    </script>

    <script>
    document.getElementById('downloadLink2').addEventListener('click', function(event) {
        // Xác định thư mục mặc định để lưu file
        var defaultSavePath = '/path/to/default/folder/';

        // Xác định tên tệp
        var fileName = 'contribution_' + new Date().toISOString().slice(0, 19).replace(/[-:]/g, '') +
            '.zip';

        // Thiết lập thư mục lưu file
        this.setAttribute('download', fileName);
        this.href = this.href + '?download=true&defaultSavePath=' + encodeURIComponent(defaultSavePath);
    });
    </script>






</body>






</html>