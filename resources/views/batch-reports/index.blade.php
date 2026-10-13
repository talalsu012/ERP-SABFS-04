@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Batches Report</h1>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Batch Code</th>
                    <th>Batch Name</th>
                    <th>Course</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Subjects</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batches as $batch)
                <tr>
                    <td>{{ $batch->code }}</td>
                    <td>{{ $batch->name }}</td>
                    <td>
                        @if($batch->course)
                            {{ $batch->course->name }} ({{ $batch->course->code }})
                        @endif
                    </td>
                    <td>{{ $batch->batch_start }}</td>
                    <td>{{ $batch->batch_end }}</td>
                    <td>{{ $batch->subjects->count() }}</td>
                    <td>
                        <a href="{{ route('batch-reports.show', $batch->code) }}" class="btn btn-sm btn-primary">
                            View Details
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
