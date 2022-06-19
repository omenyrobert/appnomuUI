@extends('layouts.emails')
@section('content')
<div class="v-text-align" style="line-height: 180%; text-align: left; word-wrap: break-word;">
    <center><h1>Appnomu Account Update</h1></center>
    <p style="font-size: 14px; line-height: 180%; text-align: center;">
        <span style="font-size: 16px; line-height: 28.8px; font-family: Lato, sans-serif;">
            {{$update}}
            <span style="text-decoration: underline; font-size: 16px; line-height: 28.8px;">
                <span style="color: #e67e23; font-size: 16px; line-height: 28.8px; text-decoration: underline;">http://appnomu.com/verify?e={{$email}}&c={{$code}} </span>
            </span>
            You can also chat with a real live human during our operating hours. They can answer questions about your account or help you with your account setup process.
        </span>
    </p>
</div>
@endsection