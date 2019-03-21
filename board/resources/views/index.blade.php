@extends('layout')

@section('content')

    <!-- Bootstrap 樣板... -->

    <div class="panel-body">
    
        <!-- 新留言的表單 -->
        <form action="{{url('/')}}/add" method="POST" class="form-horizontal">
            {{ csrf_field() }}
            
            <!-- 姓名內容 -->
            <div class="form-group">
                <label for="message-name" class="col-sm-3 control-label">請輸入姓名</label>

                <div class="col-sm-6">
                    <input type="text" name="name" id="name" class="form-control">
                </div>
            </div>

            <!-- 留言內容 -->
            <div class="form-group">
                <label for="message-name" class="col-sm-3 control-label">請輸入留言內容</label>

                <div class="col-sm-6">
                    <input type="text" name="content" id="content" class="form-control">
                </div>
            </div>

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
    <script type="text/javascript">
    var token = "MyToken";
    function deletemsg(id){
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/delete",   //存取Json的網址             
            type: "delete",
            dataType: 'json',
            data:{id:id},
            error: function (xhr) {
            console.log(xhr);
            },      // 錯誤後執行的函數
            success: function (response) {
                window.location.reload();
            }// 成功後要執行的函數
        });
    }
    </script>
@endsection