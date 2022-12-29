<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vela: عرض المحادثة</title>
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
        .note-wrapper{
            height: 510px;
        }
    </style>
</head>
<body>
<div class="table-container section-style">
    <div style="padding-right: 20px">
        <div class="row">
            <h4 style="padding-bottom: 5px;" id="groups_users">Users</h4>
            <div class="col-md-12" style="margin-bottom: 50px; padding: 0 15px">
                <div class="input-group mb-2" style="width: 300px; height: 38px">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search"
                        aria-label="Username"
                        aria-describedby="basic-addon1"
                        id="groups_users_search"
                    />
                </div>
                <div class="groups-users">
                    @foreach($conversation->usersConversation as $user)
                    <div>
                        <img src="{{$user->UserParticipant->image}}" alt="pp" width="40px" style="object-fit: cover; border-radius: 50%">
                        <Strong>{{$user->UserParticipant->full_name}}</Strong>
                    </div>
                    @endforeach
                </div>
            </div>
            <h4 style="padding-bottom: 5px;" id="p2p_conversation">Conversation</h4>
            <div class="col-md-12">
                <div class="note-wrapper">
                    @foreach($conversation->messages as $message)
                    <div class="note-40 {{$message->user_id == $conversation->admin_id ? 'admin-note' : ''}}">
                        <small class="text-muted">{{$message->MessageUser->full_name}}</small>
                        <div class="note">{{$message->message}}</div>
                    </div>
                    @endforeach
                </div>
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