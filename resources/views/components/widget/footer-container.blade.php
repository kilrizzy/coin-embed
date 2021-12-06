<div class="px-4 py-2 bg-gray-100 text-gray-400 text-xs text-center rounded-b flex">
    @if(isset($actionLeft))<div class="self-center flex">{!! $actionLeft !!}</div>@endif
    <div class="flex-1 w-full self-center flex justify-center text-center">{!! $slot !!}</div>
    @if(isset($actionRight))<div class="self-center flex">{!! $actionRight !!}</div>@endif
</div>
