<div style="position: relative;">
    <div class="brush" style="position: absolute;left: 89%;top: -21px;padding-bottom: 0px;display:none;">
        {!! $modal !!}
    </div>
    <div id="client{{ $client->id }}" @empty(!$color) class="colorReplace" style="background:{{ $color }};" @endempty>
        @empty(!$badge)
            <sup class="text-black"
                 role="button"
                 data-html="true"
                 data-controller="fields--popover"
                 data-action="click->fields--popover#trigger"
                 data-container="body"
                 data-toggle="popover"
                 tabindex="0"
                 data-trigger="focus"
                 data-placement="auto"
                 data-delay-show="300"
                 data-delay-hide="200"
                 data-content="{{ $infotext }}"
                 data-original-title="Банковские выписки"
                 title="">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="1.5em" height="1.5em" viewBox="0 0 32 32" role="img" fill="red" componentname="orchid-icon">
                    <path d="M15 21.063v-15.063c0-0.563 0.438-1 1-1s1 0.438 1 1v15.063h-2zM15 23.031h2v1.875h-2v-1.875zM0 16c0-8.844 7.156-16 16-16s16 7.156 16 16-7.156 16-16 16-16-7.156-16-16zM30.031 16c0-7.719-6.313-14-14.031-14s-14 6.281-14 14 6.281 14 14 14 14.031-6.281 14.031-14z"></path>
                </svg>
            </sup>
        @endempty

        <a data-turbolinks="true" class="btn btn-link" href="{{ url()->current() }}/{{ $client->id }}/edit" target="{{ $target }}">
            {{ $client->organization }}
        </a>
    </div>
</div>
