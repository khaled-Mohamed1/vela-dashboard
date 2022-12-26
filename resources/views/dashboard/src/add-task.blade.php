<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vela: المهام اضافة</title>
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
</head>
<body>
<div class="table-container section-style">
    <form
        method="POST"
        action="{{route('tasks.store')}}"
        class="row"
        style="row-gap: 20px"
        onsubmit="(e)=>e.preventDefault()"
    >
        @csrf

        <input type="hidden" name="locale" id="form_locale">


        <div class="col-md-12">
            <label for="add_task_title_input" class="form-label" id="add_task_title">Title</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="task title" id="add_task_title_input"/>
            @error('title')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="start" class="form-label" id="add_task_start_date">Start Date</label>
            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="start" />
            @error('start_date')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="end" class="form-label" id="add_task_end_date">End Date</label>
            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" id="end" />
            @error('end_date')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="col-md-12">
            <label for="add_task_desc_holder" class="form-label" id="add_task_desc">Description</label>
            <textarea
                name="description"
                cols="20"
                rows="5"
                class="form-control @error('description') is-invalid @enderror"
                placeholder="task description"
                id="add_task_desc_holder"
            ></textarea>

            @error('description')
            <span class="text-danger">{{$message}}</span>
            @enderror</div>
        <div class="col-md-12">
                <span id="add_task_add_users">Add Users:</span>
{{--            <input type="text" name="user_id" class="form-control mb-2 mt-2" placeholder="بحث عن مستخدمين" />--}}
            <div class="users-wrapper">
                @forelse($users  as $key => $user)
                    <label>
                        <input type="checkbox" name="user_id[]" class="form-check-input @error('user_id') is-invalid @enderror" value="{{$user->id}}"/>
                        {{$user->full_name}}
                    </label>
                @empty
                    <label id="add_task_no_emp">No Employees</label>
                @endforelse


            </div>
            @error('user_id')
            <span class="text-danger">{{$message}}</span>
            @enderror

        </div>
        <div class="col-md-12"></div>
        <div style="display: flex; justify-content: space-around">
            <button class="btn btn-primary" id="add_task_confirm">Confirm</button>
            <button
                type="button"
                onclick="history.back()"
                class="btn btn-secondary"
                id="admin_profile_back"
            >
                Back
            </button>
        </div>
    </form>
</div>
<script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dashboard/js/forms-lang.js')}}"></script>
</body>
</html>
