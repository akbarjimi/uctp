@extends('layout.table')

@section('title', 'لیست اساتید')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">نام</th>
                <th scope="col">برنامه</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lectures as $lecture)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $lecture->name }}</td>
                    <td>
                        <a href="{{ route('lecture.show', $lecture->id) }}">
                            برنامه
                        </a>
                    </td>
                </tr>
            @empty
                <tr colspan=2>خالی است</tr>
            @endforelse
        </tbody>
    </table>
@endsection
