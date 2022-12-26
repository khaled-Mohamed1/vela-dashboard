<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>الأنابيب</title>
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
    <img
        src="
        {{asset('storage/admins/'. $user->image)}}"
        alt="elliot"
        width="150px"
        height="150px"
        style="object-fit: cover; border-radius: 50%; margin-bottom: 30px"
    />
    <div style="padding-right: 20px">
        <div class="row">
            <div class="col-sm-6">
                <small id="admin_profile_name">Name:</small>
                <p>{{$user->full_name}}</p>
                <small id="admin_profile_phone">Phone Number:</small>
                <p>{{$user->phone_NO}}</p>
                <small id="admin_profile_job">Job:</small>
                <p>{{$user->job}}</p>
            </div>
            <div class="col-sm-6">
                <small id="admin_profile_email">Email:</small>
                <p>{{$user->email}}</p>
                <small id="admin_profile_comp_number">Company Number:</small>
                <p>{{$user->company_NO}}</p>
                <small id="admin_profile_comp_name">Company Name:</small>
                <p>{{$user->company_name}}</p>
            </div>
            <div class="col-sm-12 mt-3">
                <button onclick="history.back()" class="btn btn-secondary" style="display: block; margin-right: auto; width: 80px;" id="admin_profile_back">Back</button>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('dashboard/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dashboard/js/forms-lang.js')}}"></script>
</body>
</html>
