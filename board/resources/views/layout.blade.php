<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel Guestbook</title>
        <!-- Latest compiled and minified CSS -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
                <!-- Optional theme -->
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
                <!-- Latest compiled and minified JavaScript -->
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    </head>

    <body>
        <div class="container">
            <nav class="navbar navbar-default">
                <ul>
                    @foreach($message as $key => $data)
                    <li>姓名:{{$data['name']}} 內容:{{$data['content']}} <a class="btn btn-success" href="{{url('/')}}/reMsg/{{$data['id']}}">回覆</a><a class="btn btn-success" href="{{url('/')}}/update/{{$data['id']}}">修改</a><button type="button" onclick="deletemsg('{{$data['id']}}')">刪除</button></li>
                    @if(isset($data['reMsg']))
                    @foreach($data['reMsg'] as $key => $reMsg)
                    回覆{{$key+1}} 姓名:{{$reMsg['name']}} 內容:{{$reMsg['content']}} <a class="btn btn-success" href="{{url('/')}}/update/{{$reMsg['id']}}">修改</a><button type="button" onclick="deletemsg('{{$reMsg['id']}}')">刪除</button><br>
                    @endforeach
                    @endif
                    @endforeach
                </ul>
            </nav>
        </div>

        @yield('content')
    </body>
</html>