@extends('layouts.mail')
@section('content')
@if($order)
    <p style="font-family: 'PT Sans','Helvetica Neue',Arial,sans-serif; font-size: 14px; width:95%; padding: 5px;">Здравствуйте, {{ $order->customer->login ?? 'Unknown' }}!</p>

    <p style="font-family: 'PT Sans','Helvetica Neue',Arial,sans-serif; font-size: 14px; width:95%; padding: 5px;">Вы добавили новый заказ №{{ $order->getId() }} <a href="{{ route('order.show', $order->getSlug()) }}">"{{ $order->title }}"</a>, но пока не выбрали исполнителя для него</p>

    <p style="font-weight: bold; font-family: 'PT Sans','Helvetica Neue',Arial,sans-serif; font-size: 14px; width:95%; padding: 5px;">Пришло время это сделать!</p>

    <p style="font-family: 'PT Sans','Helvetica Neue',Arial,sans-serif; font-size: 14px; width:95%; padding: 5px;">Выберите исполнителя из тех экспертов, которые предложили свои услуги:</p>

    @foreach($order->offers as $offer)
    <p style="font-family: 'PT Sans','Helvetica Neue',Arial,sans-serif; font-size: 14px; width:95%; padding: 5px;">Эксперт <a href="{{ $offer->customer ? route('user.show', $offer->customer->getSlug()) : '#' }}">{{ $offer->customer->login ?? 'Unknown' }}</a> сделал(а) предложениe со ставкой <span style="font-weight: bold">{{ $offer->bet }}р.</span></p>
    @endforeach

    <a href="{{ route('order.show', $order->getSlug()) }}" style="display: inline-block;
    margin: 0;
    background: #1CB7AD;
    color: #ffffff;
    font-family: 'PT Sans','Helvetica Neue',Arial,sans-serif;
    font-size: 18px;
    font-weight: bold;
    line-height: 120%;
    text-decoration: none;
    text-transform: none;
    padding: 10px 25px;
    border: none;
    border-radius: 32px;">Перейти к заказу</a>
@endif
@endsection
