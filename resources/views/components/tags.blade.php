@if (!$tags[0]['tags']->isEmpty())
<div class="py-2 d-block">
    <label class="form-label">Список тэгов</label>
            <h4>
            @foreach ($tags[0]['tags'] as $tag)
                <span class="badge badge-primary">{{ $tag->name }}</span>
            @endforeach
            </h4>
</div>
@endif
