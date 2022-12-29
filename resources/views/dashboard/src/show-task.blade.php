<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vela: صفحة المهمة</title>
    <!-- Google Fonts Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400&display=swap"
        rel="stylesheet"
    />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/css/bootstrap.rtl.min.css')}}" />
    <link rel="stylesheet" href="{{asset('dashboard/css/forms-style.css')}}" />
    <style>
        small {
            color: #a4a4a4;
        }
    </style>
</head>
<body>
<div class="table-container section-style">
    <h3 class="mb-4 pb-2" style="border-bottom: 1px solid #ddd" id="show_task_heading">
        Task Inforamtions
    </h3>
    @include('dashboard.errors.alert')

    <div style="padding-right: 20px">
        <div class="row">
            <div class="col-sm-6">
                <small id="add_task_title">Title</small>
                <p>{{$task->title}}</p>
            </div>
            <div class="col-sm-6">
                <small id="show_task_status">Task Status</small>

                <p>
                    @if($task->status == 'closed')
                        <span class="text-success">{{$task->status}}</span>
                    @else
                        <span class="text-warning">{{$task->status}}</span>
                    @endif
                </p>

            </div>
            <div class="col-sm-6">
                <small id="add_task_start_date">Start Date</small>
                <p>{{$task->start_date}}</p>
            </div>
            <div class="col-sm-6">
                <small id="add_task_end_date">End Date</small>
                <p>{{$task->end_date}}</p>
            </div>
            <div class="col-sm-12 pb-2" style="border-bottom: 1px solid #eee">
                <small id="add_task_desc">Description</small>
                <p>{{$task->description}}</p>
            </div>
            <div class="col-sm-12 mt-4">
                <h5 id="show_task_employees">Employees:</h5>
            </div>
            <div class="col-sm-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col" id="admin_profile_name">Name</th>
                        <th scope="col" id="admin_profile_phone">Phone Number</th>
                        <th scope="col" id="show_task_status">Task Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($task->userTask as $user)
                        <tr>
                            <th scope="row">1</th>
                            <td>{{$user->UserTask->full_name}}</td>
                            <td>{{$user->UserTask->phone_NO}}</td>
                            <td>
                                @if($user->status == '1')
                                    <span class="text-success" id="show_task_completed">Task completed</span>
                                @else
                                    <span class="text-danger" id="show_task_working">Still working</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" id="add_task_no_emp">No Employees</td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
            <div class="col-sm-12" style="display: flex;justify-content: space-around;">
                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#exampleModal"
                    id="show_task_notes"
                >
                    Notes
                </button>
                @if($task->status == 'closed')
                    <button class="btn btn-primary" disabled id="show_task_close">Close Task</button>
                @else
                    <button class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#closedModal"
                            id="show_task_close"
                            onclick="changeModalLang()"
                        {{ $task->userTask->every('status', 1) ? '' : 'disabled' }}>Close Task</button>
                @endif

                <button class="btn btn-secondary" onclick="history.back()" id="show_task_back">Back</button>
            </div>
        </div>
    </div>
</div>
<!-- NOTE MODAL -->
<div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="task-add-form{{$task->id}}" method="POST" action="{{ route('tasks.addNote', ['task' => $task->id]) }}">
                @csrf
                <input type="hidden" name="locale" id="form_locale">

                <div class="modal-header" style="display: flex; justify-content: space-between;">
                <h1 class="modal-title fs-5" id="exampleModalLabel" id="show_task_notes">Notes</h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                    style="margin: 0;"
                ></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <label for="show_task_note_area" class="form-label" id="show_task_add_note">Add Note:</label>
                        <textarea
                            name="note"
                            cols="20"
                            rows="2"
                            class="form-control @error('note') is-invalid @enderror"
                            placeholder="add note"
                            id="show_task_note_area"
                        ></textarea>
                        @error('note')
                        <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="col-md-12">

                        <div class="note-wrapper">
                            @foreach($task->notes as $note)
                                <div class="note-40 {{$note->admin_id == NULL ? '': 'admin-note'}}">
                                    <small class="text-muted">{{$note->admin_id == NULL ? $note->UserTask->full_name : $note->AdminTask->full_name}}</small>
                                    <div class="note">{{$note->note}}</div>
                                    <small class="text-muted">{{$note->created_at->format('m:d h:i')}}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: space-around">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                    id="form_add_close">
                    Close
                </button>
                <a class="btn btn-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('task-add-form{{$task->id}}').submit();" id="form_add_btn">
                    Add
                </a>

            </div>
            </form>

        </div>
    </div>
</div>


<div
    class="modal fade"
    id="closedModal"
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
            <div class="modal-body"><span id="close_modal_content">Are you sure you want to close</span> <span class="text-vela">{{$task->title}}</span><span id="questionMark">?</span></div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    لا
                </button>
                <a class="btn btn-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('task-closed-form{{$task->id}}').submit();">
                    حذف
                </a>
                <form id="task-closed-form{{$task->id}}" method="POST" action="{{ route('tasks.closed', ['task' => $task->id]) }}">
                    @csrf
                    <input type="text" name="locale" id="form_locale">

                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dashboard/js/forms-lang.js')}}"></script>
</body>
</html>
