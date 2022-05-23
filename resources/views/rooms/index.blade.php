@extends('layout.table')

@section('title', 'لیست اتاق ها')

@section('content')
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">نام</th>
                <th scope="col">آماده است؟</th>
                <th scope="col">برنامه</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rooms as $room)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->equipped ? 'بله' : 'خیر' }}</td>
                    <td>
                        <a href="{{ route('room.show', $room->name) }}">
                            برنامه اتاق
                        </a>
                    </td>
                </tr>
            @empty
                <tr colspan=2>خالی است</tr>
            @endforelse
        </tbody>
    </table>
@endsection
