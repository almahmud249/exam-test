@extends('Business.dashboard')
@section('content')
    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">type</th>
                <th scope="col">amount</th>
                <th scope="col">date</th>
                {{--                <th scope="col">balance</th>--}}
            </tr>
            </thead>
            <tbody>
            @foreach($all_transactions as $key=>$transaction)
                <tr>
                    <th scope="row">1</th>
                    <td>{{$transaction->transaction_type}}</td>
                    <td>{{$transaction->amount}}</td>
                    <td>{{$transaction->date}}</td>
                    {{--                    <td>{{auth()->user()->balance}}</td>--}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
