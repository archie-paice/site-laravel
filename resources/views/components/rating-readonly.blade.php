<div class="rating">
    @for($i = 0; $i < 5; $i++)
        <div class="mask mask-star" @if($rating == $i+1) aria-current="true" @endif aria-label="{{$i+1}} star"></div>
    @endfor
</div>
