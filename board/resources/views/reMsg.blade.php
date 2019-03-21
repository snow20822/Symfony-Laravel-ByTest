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
    <!-- Bootstrap 樣板... -->
    <div class="container">
            <nav class="navbar navbar-default">
                回復對象&內容 <br>
                姓名:{{$message['name']}} 內容:{{$message['content']}} 
            </nav>
        </div>
    <div class="panel-body">
    
        <!-- 新留言的表單 -->
        <form action="{{url('/')}}/reMsgPost" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            
            <!-- 姓名內容 -->
            <div class="form-group">
                <label for="message-name" class="col-sm-3 control-label">請輸入姓名</label>

                <div class="col-sm-6">
                    <input type="text" name="name" id="name" class="form-control" value="">
                </div>
            </div>

            <!-- 留言內容 -->
            <div class="form-group">
                <label for="message-name" class="col-sm-3 control-label">請輸入留言內容</label>

                <div class="col-sm-6">
                    <input type="text" name="content" id="content" class="form-control" value="">
                </div>
            </div>
            <input type="hidden" name="reId" value="{{$message['id']}}">

            <!-- 增加留言按鈕-->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i> 留言
                    </button>
                </div>
            </div>
        </form>
    </div>
    </body>
</html>