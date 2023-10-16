
@extends('individual.dashboard')
@section('content')
    <form action="{{route('individual.deposit')}}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Enter amount</label>
            <input type="text" class="form-control" name="amount" id="exampleFormControlInput1" placeholder="0.00">
            <input type="hidden" class="form-control" name="transaction_type" value="deposit" id="exampleFormControlInput1" placeholder="0.00">
            {{ session('error') }}
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@endsection
