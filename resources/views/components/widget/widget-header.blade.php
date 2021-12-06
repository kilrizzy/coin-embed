<div class="p-4 flex bg-gray-100 rounded-t">
    <div class="mr-2 self-center"><img src="{{ $displayProductImageURL }}" class="h-8 w-8 rounded"/></div>
    <div class="font-bold flex-1 self-center text-gray-600">{{ $displayProductName }}</div>
    <div class="text-black font-bold self-center">${{ number_format($displayAmount,2) }}</div>
</div>
