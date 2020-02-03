@php
    $cnt = count($devices);
    $i=1;
@endphp

@foreach ($devices as $device)
    <a href="{{backpack_url('device')}}/{{$device->id}}/show">{{$device->name}}</a>@if($i++ < $cnt), @endif
@endforeach
