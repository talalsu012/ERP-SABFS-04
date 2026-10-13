@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Marks for {{ $subject->name }} ({{ $subject->code }})</h1>
    <h3>Batch: {{ $batch->name }} ({{ $batch->code }})</h3>

    <div class="card mt-4">
        <div class="card-header">
            <h3>Student Marks</h3>
        </div>
        <div class="card-body">
            @if($subject->marks->count() > 0)
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>University No</th>
                            <th>Student Name</th>
                            <th>Mark</th>
                            <th>Grade</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subject->marks as $mark)
                        <tr>
                            <td>{{ $mark->university_no }}</td>
                            <td>{{ $mark->student->name ?? 'N/A' }}</td>
                            <td>{{ $mark->mark }}</td>
                            <td>{{ $mark->grade }}</td>
                            <td>{{ $mark->point }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">No marks recorded for this subject</div>
            @endif
        </div>
    </div>

    <a href="{{ route('batch-reports.show', $batch->code) }}" class="btn btn-secondary mt-3">
        Back to Batch Details
    </a>
</div>
@endsection
