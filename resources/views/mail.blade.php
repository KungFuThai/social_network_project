<div style="width: 500px;
        margin: 0 auto;
        padding: 15px;
        text-align: center;">
    <h3>
        <span>Đây là tin nhắn xác nhận việc đổi mật khẩu của bạn đến từ {{ config('app.name') }}</span>
    </h3>
    <h2>
        <span>Mã của bạn là:</span>
    </h2>
    <br>
    <span
            style="background-color: #00bcd4;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 10px;"
    >
        <span>{{$forgetPassword->token}}</span>
    </span>
</div>