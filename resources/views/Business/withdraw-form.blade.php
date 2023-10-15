
@extends('business.dashboard')
@section('content')
    <form action="{{route('business.withdraw')}}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Enter amount</label>
            <input type="text" class="form-control" name="amount" id="exampleFormControlInput1" placeholder="0.00">
            <input type="hidden" class="form-control" name="transaction_type" value="withdraw" id="exampleFormControlInput1" placeholder="0.00">
            <span style="color: red">{{ session('error') }}</span>
        </div>
        <button type="submit" class="btn btn-primary">Primary</button>
    </form>

@endsection
