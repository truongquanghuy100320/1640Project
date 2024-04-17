@extends('layout')
@section('content')
<br>
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Contribution</h4>
            <div class="template-demo">
                <a id="downloadLink" type="button" href="{{ route('contributions.download-All-Contributions') }}"
                    class="btn btn-info font-weight-bold" download> Download All Contribution </a>
            </div>

            <div class="table-responsive">
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                @if(session('sussec'))
                <div class="alert alert-danger">
                    {{ session('sussec') }}
                </div>
                @endif
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Content</th>
                            <th>File word</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>downloaded</th>
                            <th>download_date</th>
                            <th>StaffID</th>
                            <th>StudentID</th>
                            <th>Faculty ID</th>
                            <th>Academic Years Id</th>
                            <th>Start day</th>
                            <th>Expiration Date</th>
                            <th>ZIP</th>
                            <th>Created</th>
                            <th>updated</th>
                            <th>Download</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list_contribution as $item)
                        @if($item->status == 1)
                        <tr>


                            <td>{{$item -> contribution_id }}</td>
                            <td>{{$item -> content}}</td>

                            <td>
                                @if ($item->word_document && pathinfo($item->file_path_docx,
                                PATHINFO_EXTENSION) === 'docx')
                                <div>
                                    <a href="{{ $item->file_path_docx }}">{{ $item->file_name_docx }}</a>
                                </div>
                                @else
                                <div>
                                    No docx file
                                </div>
                                @endif
                            </td>



                            <td>{{$item -> title	}}</td>
                            <td>
                                <img src="resources/images/Faces/{{$item->image_url }}">
                            </td>
                            <td>
                                @if($item->status == 1)
                                <span style="color: green;">Show</span>
                                @elseif($item->status == 2)
                                <span style="color: red;"> Locked </span>
                                @else
                                <span style="color: blue;">Other</span>
                                @endif
                            </td>

                            <td>
                                @if($item -> downloaded == 1)
                                <span style="color: green;">Downloaded successfully</span>
                                @elseif($item -> downloaded == 2)
                                <span style="color: green;">Download failed</span>
                                @else
                                <span style="color: blue;">not downloaded yet</span>
                                @endif
                            </td>
                            <td>{{$item -> download_date}}</td>
                            <td>
                                @if($item -> staff)
                                ID: {{$item -> staff ->staff_id}} <br> Name: {{$item -> staff ->staffname}}
                                @else
                                Staff information not available
                                @endif
                            </td>
                            <td>
                                @if($item -> student)
                                ID: {{$item ->student ->student_id}} <br>
                                Name: {{$item ->student ->studentname}}
                                @else
                                Student information not available
                                @endif
                            </td>



                            <td>
                                @if($item -> faculty)
                                ID: {{$item ->faculty ->faculty_id}} <br> Name: {{$item ->faculty ->faculty_name}}
                                @else
                                Faculty information not available
                                @endif

                            </td>
                            <td>
                                @if($item -> AcademicYear)
                                ID: {{$item ->AcademicYear ->academic_years_id}} <br> Academic year:
                                {{$item ->AcademicYear ->academic_year}}
                                @else
                                AcademicYear information not available
                                @endif

                            </td>

                            <td>{{$item -> start_day}}</td>
                            <td>{{$item -> expiration_date}}</td>


                            <td>
                                @if ($item->word_document && pathinfo($item->file_path_zip,
                                PATHINFO_EXTENSION) === 'zip')
                                <div>
                                    <a href="{{ $item->file_path_zip }}">{{ $item->file_name_zip }}</a>
                                </div>
                                @else
                                <div>
                                    No zip file
                                </div>
                                @endif
                            </td>

                            <td>{{$item -> created_at}}</td>
                            <td>{{$item -> updated_at}}</td>
                            <td>
                                <a class="btn btn-dark btn-icon-text" id="downloadLink2"
                                    href="{{ route('contributions.download-contribution-by-id',['contribution_id' =>$item->contribution_id ]) }}"
                                    type="button" download>
                                    Download
                                    <i class="mdi mdi-file-check btn-icon-append" download></i>
                                </a>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection