@extends('layout.table')

@section('title')
    لیست دروس
@endsection

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">شناسه</th>
                <th scope="col">کد نوع درس</th>
                <th scope="col">نام درس</th>
                <th scope="col">آماده است؟</th>
                <th scope="col">پیش نیاز</th>
                <th scope="col">هم نیاز</th>
                <th scope="col">برنامه</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($courses as $course)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $course->id }}</td>
                    <td>{{ $course->type->name }}</td>
                    <td>{{ $course->type->alias . '-' . $course->id }}</td>
                    <td>{{ $course->equipped ? 'بله' : 'خیر' }}</td>
                    <td>{{ $course->pre < 0 ? 'ندارد' : $course->pre }}</td>
                    <td>{{ empty($course->need) ? 'ندارد' : implode(',', $course->need) }}</td>
                    <td>
                        <a href="{{ route('course.show', [$course->id, $course->type->name]) }}">
                            برنامه درس
                        </a>
                    </td>
                </tr>
            @empty
                <tr colspan=2>خالی است</tr>
            @endforelse
        </tbody>
    </table>
    @if ($previous = $courses->previousPageUrl())
        <a class="btn btn-blue" href="{{ $previous }}">قبلی</a>
    @endif
    @if ($next = $courses->nextPageUrl())
        <a class="btn btn-blue" href="{{ $next }}">بعدی</a>
    @endif
@endsection
