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
            <h2 style="padding-bottom: 15px;" id="p2p_conversation">Conversation</h2>
            <div class="col-md-12">
                <div class="note-wrapper">
                    @foreach($conversation->messages as $message)
                        <div class="note-40 {{$message->user_id == $conversation->SenderConversation->id ? 'admin-note' : ''}}">
                            <small class="text-muted">
                                {{$message->user_id == $conversation->SenderConversation->id ?
                                    $conversation->SenderConversation->full_name :
                                     $conversation->ReceiverConversation->full_name}}
                            </small>
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
