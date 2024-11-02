 <fieldset class="mb-3" data-async="">
            <div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
                <div class="form-group">
                    <label for="field-reportreport-name-930502adce1d730455d2dc3c37cff51b8206a3a3" class="form-label">Название для цвета</label>
                    <div data-controller="fields--input" data-fields--input-mask="">
                        <input class="form-control"
                               name="color[name]"
                               title="Название для цвет"
                               id="field-reportreport-name-930502adce1d730455d2dc3c37cff51b8206a3a3">
                    </div>
                    <small class="form-text text-muted">Укажите название для отчета</small>
                </div>


                <div class="form-group">
                    <label for="field-color-076cd4effcf54de937f035a0280d12b1b9612cec" class="form-label">
                        Цвет
                    </label>

                    <div data-controller="fields--input" data-fields--input-mask="">
                        <input class="form-control"
                               name="color[color]"
                               type="color"
                               title="Цвет"
                               value="#563d7c"
                               id="field-color-076cd4effcf54de937f035a0280d12b1b9612cec">
                    </div>
                    <small class="form-text text-muted">Выберите цвет</small>
                </div>
            </div>
        </fieldset>
        {{ csrf_field() }}

@push('scripts')
    <script>
        $(document).ready(function () {
            $("#btnnewcolor").click(function() {
                $('#screen-modal-ModalColors').modal('hide');
            });

            @if(Session::get('colorCreated'))
            $('#screen-modal-ModalColors').modal('show');
            @endif
        });
    </script>
@endpush
