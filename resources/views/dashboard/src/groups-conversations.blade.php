<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vela: المحادثات الجماعية</title>
    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400&display=swap"
        rel="stylesheet"
    />
    <!-- Font Awesome -->
{{--    <link rel="stylesheet" href="{{asset('dashboard//css/all.min.css')}}" />--}}
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/css/bootstrap.rtl.min.css')}}" />
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{asset('dashboard/css/tables-style.css')}}" />

    <style>
        .form-check-input:checked {
            background-color: red;
            border-color: pink;
        }

        .form-switch .form-check-input {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='red'/%3e%3c/svg%3e");
        }
        .form-switch .form-check-input:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='pink'/%3e%3c/svg%3e");
        }

    </style>

</head>
<body>
<div class="loading-screen">
    <div
        class="spinner-border text-primary"
        style="width: 3rem; height: 3rem; color: var(--main-color) !important"
        role="status"
    >
        <span class="visually-hidden">اننظر...</span>
    </div>
</div>
<h1 class="heading" id="groups_heading">Groups Chats</h1>
<div class="table-container section-style">

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div
            style="
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 20px;
          "
        >
            <div class="input-group" style="width: 300px; height: 38px">
            <span class="input-group-text" id="search_icon">
              <button style="border: none">
                <i class="fa-solid fa-magnifying-glass"></i>
              </button>
            </span>
                <input
                    type="text"
                    class="form-control"
                    placeholder="Search"
                    aria-label="Username"
                    aria-describedby="basic-addon1"
                    id="admins_search"
                />
            </div>

        </div>
    </div>

    <div class="container py-5 h-100">
        <div
            class="row d-flex justify-content-center align-items-center h-100"
            style="row-gap: 20px; font-size: 14px"
        >

            @include('dashboard.errors.alert')

            @forelse($conversations as $key => $conversation)
                <div class="col-md-6 col-lg-5 col-xl-4">
                    <div class="card card-animation" style="border-radius: 15px">
                        <div class="card-body p-4" style="overflow: auto;">
                            <div class="d-flex text-black">
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{$conversation->image}}"
                                        alt="Generic placeholder image"
                                        class="img-fluid"
                                        style="width: 150px; border-radius: 10px"
                                    />
                                </div>
                                <div class="flex-grow-1 me-3 d-flex" style="flex-direction: column; justify-content: space-between" id="show_admin_picture">
                                    <div>
                                        <h5 class="mb-2">{{$conversation->name}}</h5>
                                        <div
                                            class="d-flex justify-content-start rounded-3 p-2 mb-2"
                                            style="background-color: #efefef"
                                        >
                                            <div class="px-3">
                                                <p class="small text-muted mb-1" id="groups_users_count">Users Count</p>
                                                <p class="mb-0">{{$conversation->usersConversation->count()}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex pt-1" style="gap: 7px">
                                        <a
                                            href="{{route('admins.groups_conversation.show',['conversation' => $conversation->id])}}"
                                            class="btn btn-primary flex-grow-1"
                                            style="margin-top: auto; font-size: 14px;"
                                            id="groups_show_conversation"
                                        >
                                            Show users and conversation
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty

                <div class="col col-md-9 col-lg-8 col-xl-6">
                    <div class="card card-animation" style="border-radius: 15px">
                        <div class="card-body p-4">
                            <div class="d-flex text-black">
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1" id="admins_no_data">No Data</h5>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforelse

{{--            {!! $users->links() !!}--}}

        </div>
    </div>
</div>


<script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dashboard/js/all.min.js')}}"></script>
<script src="{{asset('dashboard/js/tables-script.js')}}"></script>
</body>
</html>
