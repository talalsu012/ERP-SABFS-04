@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Batch: {{ $batch->name }} ({{ $batch->code }})</h1>

    <div class="card mb-4">
        <div class="card-header">
            <h3>Batch Information</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Course:</strong>
                        @if($batch->course)
                            {{ $batch->course->name }} ({{ $batch->course->code }})
                        @else
                            No course assigned
                        @endif
                    </p>
                    <p><strong>Start Date:</strong> {{ $batch->batch_start }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>End Date:</strong> {{ $batch->batch_end }}</p>
                    <p><strong>Status:</strong> {{ $batch->status }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Course Subjects</h3>
                </div>
                <div class="card-body">
                    @if($batch->course && $batch->course->subjects->count() > 0)
                        <ul class="list-group">
                            @foreach($batch->course->subjects as $subject)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $subject->name }} ({{ $subject->code }})
                                <a href="{{ route('batch-reports.subject-marks', [$batch->code, $subject->code]) }}"
                                   class="btn btn-sm btn-info">
                                    View Marks
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info">No subjects found for this course</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Batch Subjects</h3>
                </div>
                <div class="card-body">
                    @if($batch->subjects->count() > 0)
                        <ul class="list-group">
                            @foreach($batch->subjects as $subject)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $subject->name }} ({{ $subject->code }})
                                <a href="{{ route('batch-reports.subject-marks', [$batch->code, $subject->code]) }}"
                                   class="btn btn-sm btn-info">
                                    View Marks
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-info">No additional subjects assigned to this batch</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Students ({{ $batch->students->count() }})</h3>
        </div>
        <div class="card-body">
            @if($batch->students->count() > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>University No</th>
                            <th>Student Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batch->students as $student)
                        <tr>
                            <td>{{ $student->university_no }}</td>
                            <td>{{ $student->name }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-primary">View Profile</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">No students enrolled in this batch</div>
            @endif
        </div>
    </div>
</div>
@endsection
