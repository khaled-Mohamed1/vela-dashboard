{{-- Message --}}
@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible text-left" role="alert">
        <button type="button" class="close" data-dismiss="alert">
        </button>
        <strong> !</strong> {{ session('success') }}
    </div>
@endif

@if (Session::has('warning'))
    <div class="alert alert-warning alert-dismissible text-left" role="alert">
        <button type="button" class="close" data-dismiss="alert">
        </button>
        <strong> !</strong> {{ session('warning') }}
    </div>
@endif


@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible text-left" role="alert">
        <button type="button" class="close" data-dismiss="alert">
        </button>
        <strong> !</strong> {{ session('error') }}
    </div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script type="text/javascript">
    $("document").ready(function() {
        setTimeout(() => {
            $("div .alert").remove();
        }, 5000);
    });
</script>
