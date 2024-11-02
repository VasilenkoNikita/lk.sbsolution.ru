<div style="position: relative;">
    <div class="brush" style="position: absolute;left: 89%;top: -21px;padding-bottom: 0px;display:none;">
        {!! $modal !!}
    </div>
    <div id="client{{ $client->id }}" @empty(!$color) class="colorReplace" style="background:{{ $color }};" @endempty>
            {{ $client->organization }}
    </div>
</div>
