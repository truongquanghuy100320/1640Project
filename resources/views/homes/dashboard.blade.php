@extends('layout')
@section('content')
@php
$staff_id = session('staff_id');

$staff = App\Models\StaffModel::where('staff_id', $staff_id)->first();


@endphp
<div class="content-wrapper">
    <div class="row">
        <div class="col-sm-12 mb-4 mb-xl-0">
            @if($staff)
            <h4 class="font-weight-bold text-dark">Hi, welcome {{ $staff->staffname }} back!</h4>
            @endif
            <p class="font-weight-normal mb-2 text-muted" id="currentDate"></p>

            <script>
            // Lấy ngày hiện tại
            var currentDate = new Date();

            // Chuyển định dạng ngày tháng sang dạng "Tháng Ngày, Năm"
            var options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            var formattedDate = currentDate.toLocaleDateString('en-US', options);

            // Hiển thị ngày hiện tại trong phần tử có id là "currentDate"
            document.getElementById('currentDate').textContent = formattedDate;
            </script>
        </div>
    </div>
    <div class="dashboard-container">
        <div class="dashboard">

            <div class="dashboard-item">
                <h4 style="color: #006400;">Total Student</h4>
                <p id="totalStudents">
                    <span style="color: #1E90FF;">Total number of students:</span>
                    <span style="color: #000000;"><?php echo $countStudents; ?></span>
                </p>
            </div>


            <div class="dashboard-item">
                <h4 style="color: #006400;">Total Users</h4>
                <p id="totalUsers">
                    <span style="color: #1E90FF;">Total number of Users:</span>
                    <span style="color: #000000;"><?php echo $countUsers; ?></span>
                </p>
            </div>
            <div class="dashboard-item">
                <h4 style="color: #006400;">Total Contributions</h4>
                <p id="totalContributions">
                    <span style="color: #1E90FF;">Total number of Contributions:</span>
                    <span style="color: #000000;"><?php echo $countContributors; ?></span>
                </p>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-3 flex-column d-flex grid-margin stretch-card">
            <div class="row flex-grow">


                <div class="col-sm-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Registered Accounts student Month</h4>
                            <br />
                            <canvas id="registerCounts"
                                style="width: 100%; height: 300px; position: relative; z-index: 1;"></canvas>
                            <br />

                        </div>
                    </div>
                </div>


            </div>

        </div>
        <div class="col-xl-3 flex-column d-flex grid-margin stretch-card">
            <div class="row flex-grow">


                <div class="col-sm-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Registered Accounts User Month</h4>
                            <br />
                            <canvas id="registerCounts2"
                                style="width: 100%; height: 300px; position: relative; z-index: 1;"></canvas>
                            <br />

                        </div>
                    </div>
                </div>


            </div>

        </div>
        <div class="col-xl-3 flex-column d-flex grid-margin stretch-card">
            <div class="row flex-grow">
                <div class="col-sm-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">The chart shows the public and private contributions</h4>
                            <br />
                            <div id="myPieChart" class="pie-chart"></div>

                            <script>
                            // Lấy dữ liệu từ PHP và chuyển thành một đối tượng JavaScript
                            const rawData = <?= json_encode($contributionsStatus) ?>;
                            console.log(rawData); // Kiểm tra console để kiểm tra dữ liệu

                            // Sample data
                            var data = [{
                                    label: "Public",
                                    value: rawData.public
                                },
                                {
                                    label: "Private",
                                    value: rawData.private
                                }
                            ];

                            // Biểu đồ tròn
                            var chart = document.getElementById('myPieChart');
                            var width = chart.offsetWidth;
                            var height = chart.offsetHeight;
                            var radius = Math.min(width, height) / 2;

                            var color = d3.scaleOrdinal()
                                .domain(data.map(function(d) {
                                    return d.label;
                                }))
                                .range(["#ff5733", "#33ff57"]); // Màu cho Public và Private

                            var svg = d3.select("#myPieChart")
                                .append("svg")
                                .attr("width", width)
                                .attr("height", height)
                                .append("g")
                                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

                            var arc = d3.arc()
                                .outerRadius(radius - 10)
                                .innerRadius(0);

                            var pie = d3.pie()
                                .sort(null)
                                .value(function(d) {
                                    return d.value;
                                });

                            var g = svg.selectAll(".arc")
                                .data(pie(data))
                                .enter().append("g")
                                .attr("class", "arc");

                            g.append("path")
                                .attr("d", arc)
                                .style("fill", function(d) {
                                    return color(d.data.label);
                                });

                            // Thêm sự kiện click cho các phần tử trong biểu đồ
                            // Thêm văn bản vào cung của biểu đồ
                            g.append("text")
                                .attr("transform", function(d) {
                                    // Tính toán vị trí của văn bản để nó nằm giữa các cung
                                    var pos = arc.centroid(d);
                                    // Trả về vị trí của văn bản dựa trên vị trí tính toán và bán kính của cung
                                    return "translate(" + pos + ")";
                                })
                                .attr("dy", "0.35em")
                                .style("text-anchor", "middle")
                                .text(function(d) {
                                    var label = d.data.label;
                                    var count = label === "Public" ? rawData.publicCount : rawData.privateCount;
                                    return label + ": " + count;
                                })
                                .attr("class", "data-text");
                            </script>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Number of contributions per department</h4>
                        <div class="table-responsive mt-3">
                            <table class="table table-header-bg">
                                <thead>
                                    <tr>
                                        <th>Faculty Name</th>
                                        <th>Contribution Count</th>
                                        <th>Warehouse has been accepted</th>
                                        <th>Total approved contributions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contributionsPerFaculty as $contribution)
                                    <tr>
                                        <td>{{ $contribution->faculty->faculty_name }}</td>
                                        <td>{{ $contribution->contribution_count }}</td>
                                        <td>
                                            @if($contribution->total_condition_checkbox == 1)
                                            {{ $contribution->total_condition_checkbox }}
                                            @else
                                            0
                                            @endif
                                        </td>
                                        <td>
                                            @if($contribution->total_status == 1)
                                            {{ $contribution->total_status }}
                                            @else
                                            0
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="top-submission-contribution">
                    <div class="contribution-header">
                        <h2 class="header-title">Top Submission Contribution</h2>
                    </div>
                    <div class="contribution-list">
                        @if(isset($contributorsTopStudent['top_students']) &&
                        count($contributorsTopStudent['top_students']) > 0)
                        @foreach($contributorsTopStudent['top_students'] as $student)
                        <div class="contribution-item">
                            <h3>{{ $student->studentname }}</h3>
                            <p>Email: {{ $student->email_login }}</p>
                            <p>Total contributions: {{ $student->total_contributions }}</p>
                            <p>Faculty: {{ $student->faculty_name }}</p>
                        </div>
                        @endforeach
                        @else
                        <p>No top submissions found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="contribution-count">
            <h2 class="contribution-count-header">Number of Contributions Per Year</h2>
            <div class="contribution-count-list">
                <!-- Các mục số lượng đóng góp sẽ được thêm vào đây -->
            </div>
        </div>
    </div>
    <br />
    @endsection

    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

    <script>
    let chartDrawn1 = false;

    function drawChart1() {
        try {
            const registerCountsData = JSON.parse('<?= json_encode($registerCountsPerMonth) ?>');
            const canvas = document.getElementById('registerCounts');
            const ctx = canvas.getContext('2d');

            const chartHeight = canvas.height;
            const chartWidth = canvas.width;
            const barSpacing = 20;

            const maxCount = Math.max(...registerCountsData.map(item => item.register_count));
            const scaleFactor = chartHeight / maxCount;

            ctx.clearRect(0, 0, chartWidth, chartHeight);

            registerCountsData.forEach((item, index) => {
                const barHeight = item.register_count * scaleFactor;
                const x = index * (barSpacing + 70);
                const y = chartHeight - barHeight;

                ctx.fillStyle = '#3e95cd'; // Màu xanh
                ctx.fillRect(x, y, 50, barHeight);

                ctx.strokeStyle = '#2969b0'; // Màu xanh đậm
                ctx.strokeRect(x, y, 50, barHeight);

                ctx.fillStyle = '#000';
                ctx.textAlign = 'center';
                ctx.fillText(`${item.register_count} Students`, x + 25, y + 20);
                ctx.fillText(`${item.month}/${item.year}`, x + 25, chartHeight - 5);
            });

            // Thêm hiệu ứng khi vẽ biểu đồ
            anime({
                targets: '#registerCounts canvas',
                translateY: [100, 0],
                opacity: [0, 1],
                delay: anime.stagger(100),
                easing: 'easeOutExpo'
            });

            chartDrawn1 = true;
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    window.addEventListener('DOMContentLoaded', function() {
        drawChart1();
    });

    window.addEventListener('scroll', function(event) {
        if (chartDrawn1) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
    </script>

    <script>
    let chartDrawn2 = false;

    function drawChart2() {
        try {
            const registerCountsData2 = JSON.parse('<?= json_encode($registerCountsPerMonth2) ?>');
            const canvas2 = document.getElementById('registerCounts2');
            const ctx2 = canvas2.getContext('2d');

            const chartHeight2 = canvas2.height;
            const chartWidth2 = canvas2.width;
            const barSpacing2 = 20;

            const maxCount2 = Math.max(...registerCountsData2.map(item => item.register_count2));
            const scaleFactor2 = chartHeight2 / maxCount2;

            ctx2.clearRect(0, 0, chartWidth2, chartHeight2);

            registerCountsData2.forEach((item, index) => {
                const barHeight2 = item.register_count2 * scaleFactor2;
                const x2 = index * (barSpacing2 + 70);
                const y2 = chartHeight2 - barHeight2;

                ctx2.fillStyle = '#3e95cd'; // Màu xanh
                ctx2.fillRect(x2, y2, 50, barHeight2);

                ctx2.strokeStyle = '#2969b0'; // Màu xanh đậm
                ctx2.strokeRect(x2, y2, 50, barHeight2);

                ctx2.fillStyle = '#000';
                ctx2.textAlign = 'center';
                ctx2.fillText(`${item.register_count2} Users`, x2 + 25, y2 + 20);
                ctx2.fillText(`${item.month1}/${item.year1}`, x2 + 25, chartHeight2 - 5);
            });

            // Thêm hiệu ứng khi vẽ biểu đồ
            anime({
                targets: '#registerCounts2 canvas',
                translateY: [100, 0],
                opacity: [0, 1],
                delay: anime.stagger(100),
                easing: 'easeOutExpo'
            });

            chartDrawn2 = true;
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    window.addEventListener('DOMContentLoaded', function() {
        drawChart2();
    });

    window.addEventListener('scroll', function(event) {
        if (chartDrawn2) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
    </script>

    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>