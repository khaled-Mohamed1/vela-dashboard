<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Vela: المحادثات الفردية</title>
    <!-- Google Fonts Cairo -->
    <link rel="reconnect" href="https://fonts.googleapis.com" />
    <link rel="reconnect" href="https://fonts.gstatic.com" crossorigin />
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
</head>
<body>
<div class="loading-screen">
    <div
        class="spinner-border text-primary"
        style="width: 3rem; height: 3rem; color: var(--main-color) !important"
        role="status"
    >
        <span class="visually-hidden">انتظر...</span>
    </div>
</div>
<h1 class="heading" id="p2pchats_heading">Peer to peer chats</h1>
<div class="table-container section-style">
    <div
        style="
          display: flex;
          align-items: center;
          margin-bottom: 10px;
          gap: 20px;">
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
            @forelse($conversations as $key => $conversation)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card card-animation" style="border-radius: 15px">
                        <div class="card-body p-4" style="overflow: auto;">
                            <div class="d-flex" style="justify-content: space-between;">
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{$conversation->SenderConversation->image}}"
                                        alt="Generic placeholder image"
                                        class="img-fluid"
                                        style="width: 70px; border-radius: 10px; object-fit: cover; display: block; margin: auto"
                                    />
                                    <p style="text-align: center; font-weight: bold; margin-top: 15px; width: 100px" class="text-success">{{$conversation->SenderConversation->full_name}}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <img
                                        src="{{$conversation->ReceiverConversation->image}}"
                                        alt="Generic placeholder image"
                                        class="img-fluid"
                                        style="width: 70px; border-radius: 10px; object-fit: cover; display: block; margin: auto"
                                    />
                                    <p style="text-align: center; font-weight: bold; margin-top: 15px; width: 100px" class="text-primary">{{$conversation->ReceiverConversation->full_name}}</p>
                                </div>
                            </div>
                            <a href="{{route('admins.conversation.show',['conversation'=>$conversation->id])}}" class="btn btn-primary" style="display: block; margin: auto" id="p2pchats_showconv">Show Conversation</a>
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

