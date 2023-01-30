<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vela: المشرفين</title>
    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400&display=swap"
        rel="stylesheet"
    />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('dashboard//css/all.min.css')}}" />
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
<h1 class="heading" id="heading_admins">Admins</h1>
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
            @hasrole('Super Admin')
            <a
                href="{{route('admins.create')}}"
                class="btn btn-primary"
            id="add_admin"
            >+ Add Admin</a
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
            @endhasrole

            @hasrole('Super Admin Company')
            <a
                href="{{route('admins.create.admin')}}"
                class="btn btn-primary"
                id="add_admin"
            >+ Add Admin</a>
            @endhasrole

        </div>
        <div>
            <a href="{{route('tasks')}}" class="btn btn-primary" id="admins_show_tasks">Show Tasks</a>
        </div>
    </div>

    <div class="container py-5 h-100">
        <div
            class="row d-flex justify-content-center align-items-center h-100"
            style="row-gap: 20px; font-size: 14px"
        >

    @include('dashboard.errors.alert')

    @forelse($users as $key => $user)
        <div class="col col-md-9 col-lg-8 col-xl-6">
            <div class="card card-animation" style="border-radius: 15px">
                <div class="card-body p-4" style="overflow: auto;">
                    <div class="d-flex text-black">
                        <div class="flex-shrink-0">
                            <img
                                src="{{asset('storage/admins/'.$user->image)}}"
                                alt="Generic placeholder image"
                                class="img-fluid"
                                style="width: 180px; border-radius: 10px"
                            />
                        </div>
                        <div class="flex-grow-1 me-3" id="show_admin_picture">
                            <h5 class="mb-1">{{$user->full_name}}</h5>
                            <p class="mb-2 pb-1" style="color: #2b2a2a">{{$user->job}}</p>
                            <div
                                class="d-flex justify-content-start rounded-3 p-2 mb-2"
                                style="background-color: #efefef"
                            >
                                <div class="px-3">
                                    <p class="small text-muted mb-1" id="admin_card_phone">Phone Number</p>
                                    <p class="mb-0">{{$user->phone_NO}}</p>
                                </div>
                                <div>
                                    <p class="small text-muted mb-1" id="admin_card_comp_name">Company Name</p>
                                    <p class="mb-0">{{$user->company_name}}</p>
                                </div>
                                <div class="px-3">
                                    <p class="small text-muted mb-1" id="admin_card_comp_number">Company Number</p>
                                    <p class="mb-0">{{$user->company_NO}}</p>
                                </div>
                            </div>
                            <div class="d-flex pt-1" style="gap: 7px">
                                <a
                                    href="{{route('admins.show',['user'=>$user->id])}}"
                                    class="btn btn-outline-primary flex-grow-1"
                                    style="text-decoration: none"
                                    id="admin_card_show"
                                >
                                    Show
                                </a>
                                @hasrole('Super Admin Company')
                                @if($user->role_id == 4)
                                    <a
                                        href="{{route('admins.edit',['user'=>$user->id])}}"
                                        class="btn btn-outline-primary flex-grow-1"
                                        style="text-decoration: none"
                                        id="admin_card_edit"
                                    >
                                        Edit
                                    </a>
                                @else
                                    <a
                                        href="{{route('admins.edit.admin',['user'=>$user->id])}}"
                                        class="btn btn-outline-primary flex-grow-1"
                                        style="text-decoration: none"
                                        id="admin_card_edit"
                                    >
                                        Edit
                                    </a>
                                    <button
                                        type="button"
                                        class="btn btn-primary flex-grow-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{$user->id}}"
                                        id="admin_card_delete"
                                        onclick="changeModalLang()"
                                    >
                                        Delete
                                    </button>
                                @endif
                                @endhasrole


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

    {!! $users->links() !!}

        </div>
    </div>
</div>
<!-- DELETE MODAL -->
@foreach($users as $user)
    <div
        class="modal fade"
        id="deleteModal{{$user->id}}"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body"><span id="delete_modal_content">Are you sure you want to delete</span> <span class="text-vela">{{$user->full_name}}</span><span id="questionMark">?</span></div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        id="delete_modal_no">
                        No
                    </button>
                    <a class="btn btn-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('user-delete-form{{$user->id}}').submit();" id="delete_modal_yes">
                        Yes
                    </a>
                    <form id="user-delete-form{{$user->id}}" method="POST" action="{{ route('admins.destroy', ['user' => $user->id]) }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="locale" id="form_locale_delete">

                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dashboard/js/all.min.js')}}"></script>
<script src="{{asset('dashboard/js/tables-script.js')}}"></script>
</body>
</html>
