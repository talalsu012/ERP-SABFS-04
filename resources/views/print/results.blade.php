<!DOCTYPE html>
<html>
<head>
    <title>Results for {{ $course->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6">
    <div class="mx-auto max-w-4xl">
        <h1 class="text-2xl font-bold mb-6">
            Results for {{ $course->name }}
        </h1>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">Student</th>
                    @foreach($course->subjects as $subject)
                        <th class="p-2 border">{{ $subject->name }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($students as $student)
                <tr>
                    <td class="p-2 border">{{ $student->name }}</td>
                    @foreach($course->subjects as $subject)
                        @php
                            $mark = $student->marks->firstWhere('subject_id', $subject->id);
                        @endphp
                        <td class="p-2 border text-center">
                            {{ $mark ? $mark->grade : '-' }}
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 text-right text-sm text-gray-500">
            Printed on {{ now()->format('Y-m-d H:i') }}
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>
</html>
