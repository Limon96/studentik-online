<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Панель управления">

    <title>@yield('title') {{ config('app.name', 'Laravel') }}</title>

    <!-- Vendor css -->
    <link href="{{ asset('manager/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/Ionicons/css/ionicons.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/datatables/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- Shamcey CSS -->
    <link href="{{ asset('manager/css/shamcey.css') }}" rel="stylesheet">
    <link href="{{ asset('manager/css/manager.css') }}" rel="stylesheet">
    @yield('styles')
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-12">
            <label class="form-control-label" for="textarea-logs">Logs [{{ microtime(true) * 10000 }}]</label>
            <textarea class="form-control" name="textarea-logs" id="textarea-logs" cols="30" rows="10"></textarea>
        </div>
    </div>
</div>


<script src="{{ asset('manager/lib/jquery/jquery.js') }}"></script>
<script src="{{ asset('manager/lib/popper.js/popper.js') }}"></script>
<script src="{{ asset('manager/lib/bootstrap/bootstrap.js') }}"></script>
<script src="{{ asset('manager/lib/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{ asset('manager/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js') }}"></script>
<script src="{{ asset('manager/lib/moment/moment.js') }}"></script>
<script src="{{ asset('manager/lib/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('manager/lib/Flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('manager/lib/flot-spline/jquery.flot.spline.js') }}"></script>

<script src="{{ asset('manager/js/shamcey.js') }}"></script>
<script src="{{ asset('manager/js/dashboard.js') }}"></script>

<script src="https://cdn.socket.io/3.1.3/socket.io.min.js"
        integrity="sha384-cPwlPLvBTa3sKAgddT6krw0cJat7egBga3DJepJyrLl4Q9/5WLra3rrnMcyTyOnh"
        crossorigin="anonymous"></script>

<script>
    var ts = {{ floor(microtime(true) * 100) }};


    function setLog(str) {
        $('#textarea-logs').text(
            $('#textarea-logs').text() + "\n" + str
        );

        console.log(str);
    }

    function connectSocket(){
        try {
            const socket = io('localhost:3000', {ts: ts});

            socket.on("connect", () => {
                socket.sendBuffer = [];

                console.log('connect');
                socket.emit("start", {'ts': ts});
            });

            socket.on("history", (data) => {
                ts = data.ts;

                if (data.data.length) {
                    for (var datum of data.data) {
                        setLog('[' + datum['history_id'] + '] ts = ' + datum['ts'] + '; code = ' +  datum['code']);
                    }
                }
            });

        } catch (e) {
            console.log('Error connection to socket', e);

            setTimeout(function () {
                connectSocket();
            }, 5000);
        }
    }

    connectSocket();
</script>

</body>
</html>
