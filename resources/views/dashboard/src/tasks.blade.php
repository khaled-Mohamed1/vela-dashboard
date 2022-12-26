<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vela: المهام</title>
    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400&display=swap"
        rel="stylesheet"
    />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('dashboard/css/all.min.css')}}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/css/bootstrap.rtl.min.css')}}" />
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{asset('dashboard/css/tables-style.css')}}" />
</head>
<body>
<div class="loading-screen">
    <div
        class="spinner-border text-primary"
        style="width: 3rem; height: 3rem; color: var(--main-color) !important"
        role="status"
    >
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<h1 class="heading" id="tasks_heading">Tasks</h1>


<div class="table-container section-style">
    <div
        style="
          display: flex;
          align-items: center;
          margin-bottom: 10px;
          gap: 20px;
        "
    >
        <a href="{{route('tasks.create')}}" class="btn btn-primary" id="tasks_add_task">+ Add Task</a>
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
    <div class="container py-5 h-100">
        <div
            class="row d-flex justify-content-center align-items-center h-100"
            style="row-gap: 20px; font-size: 14px"
        >

            @include('dashboard.errors.alert')

            <!-- REPEAT THIS DIV U IDIOT -->
            @forelse($tasks as $task)

                <div class="col col-md-6 col-lg-5 col-xl-4">
                    <div class="card card-animation" style="border-radius: 15px">
                        <div class="card-body p-4" style="overflow: auto;">
                            <div class="d-flex text-black">
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1">{{$task->title}}</h5>
                                    <p class="mb-2 pb-1" style="color: #2b2a2a">{{$task->description}}</p>
                                    <div
                                        class="d-flex justify-content-start rounded-3 p-2 mb-2"
                                        style="background-color: #efefef"
                                    >
                                        <div>
                                            <p class="small text-muted mb-1" id="tasks_start_date">Start Date</p>
                                            <p class="mb-0">{{$task->start_date}}</p>
                                        </div>
                                        <div class="px-3">
                                            <p class="small text-muted mb-1" id="tasks_end_date">End Date</p>
                                            <p class="mb-0">{{$task->end_date}}</p>
                                        </div>
                                        <div class="px-3">
                                            <p class="small text-muted mb-1" id="tasks_status">Status</p>
                                            <p class="mb-0">
                                                @if($task->status == 'closed')
                                                    <span class="text-success">{{$task->status}}</span>
                                                @else
                                                    <span class="text-warning">{{$task->status}}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex pt-1" style="gap: 7px;">
                                        <a
                                            href="{{route('tasks.show',['task' => $task->id])}}"
                                            class="btn btn-outline-primary flex-grow-1"
                                            style="text-decoration: none"
                                            id="tasks_show"
                                        >
                                            Show
                                        </a>
                                        @if($task->status == 'closed')
                                        @else
                                            <a
                                                href="{{route('tasks.edit',['task' => $task->id])}}"
                                                class="btn btn-outline-primary flex-grow-1"
                                                style="text-decoration: none"
                                                id="tasks_edit"
                                            >
                                                Edit
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-primary flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{$task->id}}"
                                                id="tasks_delete"
                                                onclick="changeModalLang()"
                                            >
                                                Delete
                                            </button>
                                        @endif


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

        </div>
    </div>
</div>
<!-- DELETE MODAL -->
@foreach($tasks as $task)
    <div
        class="modal fade"
        id="deleteModal{{$task->id}}"
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
                <div class="modal-body"><span id="tasks_delete_modal_content">Are you sure you want to delete</span> <span class="text-vela">{{$task->title}}</span><span id="questionMark">?</span></div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        id="delete_modal_no">
                        No
                    </button>
                    <a class="btn btn-danger" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('task-delete-form{{$task->id}}').submit();" id="delete_modal_yes">
                        Yes
                    </a>
                    <form id="task-delete-form{{$task->id}}" method="POST" action="{{ route('tasks.destroy', ['task' => $task->id]) }}">
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
