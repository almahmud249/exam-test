<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <style>
                body {
                    margin: 0;
                    font-family: "Lato", sans-serif;
                }

                .sidebar {
                    margin: 0;
                    padding: 0;
                    width: 200px;
                    background-color: #f1f1f1;
                    position: fixed;
                    height: 100%;
                    overflow: auto;
                }

                .sidebar a {
                    display: block;
                    color: black;
                    padding: 16px;
                    text-decoration: none;
                }

                .sidebar a.active {
                    background-color: #04AA6D;
                    color: white;
                }

                .sidebar a:hover:not(.active) {
                    background-color: #555;
                    color: white;
                }

                div.content {
                    margin-left: 200px;
                    padding: 1px 16px;
                    height: 1000px;
                }

                @media screen and (max-width: 700px) {
                    .sidebar {
                        width: 100%;
                        height: auto;
                        position: relative;
                    }
                    .sidebar a {float: left;}
                    div.content {margin-left: 0;}
                }

                @media screen and (max-width: 400px) {
                    .sidebar a {
                        text-align: center;
                        float: none;
                    }
                }
            </style>
            <div class="sidebar">
                <a class="{{Request::segment(2) == 'dashboard' ? ' active' : ''}}" href="{{route('individual.dashboard')}}">Home</a>
                <a class="{{Request::segment(2) == 'create-deposit' ? ' active' : ''}}" href="{{route('individual.create.deposit')}}">Deposit</a>
                <a class="{{Request::segment(2) == 'create-withdraw' ? ' active' : ''}}" href="{{route('individual.create.withdraw')}}">Withdraw</a>
                <a class="{{Request::segment(2) == 'all-transaction' ? ' active' : ''}}" href="{{route('individual.all.transaction')}}">All Transactions</a>
                <a class="{{Request::segment(2) == 'all-deposit-transaction' ? ' active' : ''}}" href="{{route('individual.all.deposit.transaction')}}">All Deposit Transactions</a>
                <a class="{{Request::segment(2) == 'all-withdraw-transaction' ? ' active' : ''}}" href="{{route('individual.all.withdraw.transaction')}}">All Withdraw Transactions</a>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
