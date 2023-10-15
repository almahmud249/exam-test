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
                <a class="active" href="{{route('business.dashboard')}}">Home</a>
                <a href="{{route('business.all.transaction')}}">All Transactions</a>
                <a href="{{route('business.all.deposit.transaction')}}">All Deposit Transactions</a>
                <a href="{{route('business.all.withdraw.transaction')}}">All Withdraw Transactions</a>
                <a href="{{route('business.create.deposit')}}">Deposit</a>
                <a href="{{route('business.create.withdraw')}}">Withdraw</a>
            </div>
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
