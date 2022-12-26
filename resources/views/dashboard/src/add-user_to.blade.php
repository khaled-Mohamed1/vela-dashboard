<!DOCTYPE html>
<html lang="ar" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Vela: الموظفين اضافة</title>
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
        <div style="border-bottom: 1px solid #ddd; margin-bottom: 50px; padding-bottom: 10px">
            <h4 id="add_emp_heading">Add Employee</h4>
        </div>
        <form
            class="row"
            style="row-gap: 20px"
            method="post"
            action="{{route('users.storeTo')}}"
            onsubmit="(e)=>e.preventDefault()"
            enctype="multipart/form-data">
            @csrf

            <div class="col-md-4">
                <label for="full_name" class="form-label" id="form_full_name">Full Name <span style="color:red;">*</span></label>
                <input value="{{old('full_name')}}" type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" id="full_name" />

                @error('full_name')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label" id="form_add_email" >Email <span style="color:red;">*</span></label>
                <input value="{{old('email')}}" type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" />
                @error('email')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            {{-- execution_request --}}
            <div class="col-sm-4">
                <label id="add_user_to_comp">Add to Company <span style="color:red;">*</span></label>
                <select name="user_id" style="height: 45px;" class="form-control form-control-user @error('user_id') is-invalid @enderror">
                    <option selected value="">Choose...</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->full_name}}</option>
                    @endforeach
                </select>

                @error('user_id')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="password" class="form-label" id="form_add_password">Password <span style="color:red;">*</span></label>
                <input value="{{old('password')}}" type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" />
                @error('password')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="phone_NO" class="form-label" id="form_add_phone">Phone Number <span style="color:red;">*</span></label>
                <input value="{{old('phone_NO')}}" type="text" name="phone_NO" class="form-control @error('phone_NO') is-invalid @enderror" id="phone_NO" />
                @error('phone_NO')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="job" class="form-label" id="form_add_job">Job <span style="color:red;">*</span></label>
                <input value="{{old('job')}}" type="text" name="job" class="form-control @error('job') is-invalid @enderror" id="job" />
                @error('job')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="col-md-12">
                <label for="formFile" class="form-label" id="form_add_pp">Profile Picture <span style="color:red;">*</span></label>
                <input class="form-control @error('image') is-invalid @enderror" name="image" type="file" id="image" />
                @error('image')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div style="display: flex; justify-content: space-around">
                <button class="btn btn-primary" id="form_add_btn">Add</button>
                <button
                    type="button"
                    onclick="history.back()"
                    class="btn btn-secondary"
                    id="form_add_close"
                >
                    Close
                </button>
            </div>
        </form>
    </div>
    <script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('dashboard/js/forms-lang.js')}}"></script>
  </body>
</html>
