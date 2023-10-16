@extends('Business.dashboard')
<style>
    .common_table table tr.hr{
        border-bottom: 1px solid ;
    }
</style>
@section('content')
    <div>
        <div class="common_table">

            <table style="width: 100%" class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th class="text-left">type</th>
                    <th class="text-left">amount</th>
                    <th class="text-left">date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_transactions as $key=>$transaction)
                    <tr>
                        <th scope="row">{{$key+1}}</th>
                        <td>{{$transaction->transaction_type}}</td>
                        <td>{{$transaction->amount}}</td>
                        <td>{{$transaction->date}}</td>
                    </tr>
                @endforeach
                <tr class="hr"></tr>
                <tr>
                    <td class="text-center"><strong>Current Balance</strong></td>
                    <td></td>
                    <td  class="text-left">{{Auth()->user()->balance}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
