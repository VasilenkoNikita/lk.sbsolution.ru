<div class="admin-wrapper py-3">
    <div class="row">
        <div class="col-md-12">
            <h4 class="font-thin text-black mb-3">Новые сообщения</h4>
            <div class="row">
                <div class="w-100 table-responsive">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th width="150" class="text-left" data-column="id">
                                <div>
                                    ID
                                </div>
                            </th>

                            <th width="350" class="text-left" data-column="name">
                                <div>
                                    Тема
                                </div>
                            </th>
                            <th class="text-left" data-column="content">
                                <div>
                                    Сообщение
                                </div>
                            </th>
                            <th class="text-left" data-column="created-on">
                                <div>
                                    Дата
                                </div>
                            </th>
                            <th class="text-left">
                                <div>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($messages as $message)
                        <tr>
                            <td class="text-left " data-column="id" colspan="1">
                                <div style="width:150px">
                                    {{ $message->id }}
                                </div>
                            </td>

                            <td class="text-left " data-column="name" colspan="1">
                                <div style="width:350px">
                                    {{ $message->subject }}
                                </div>
                            </td>

                            <td class="text-left  text-truncate " data-column="content" colspan="1">
                                <div style="width:">
                                    {{ $message->content }}
                                </div>
                            </td>

                            <td class="text-left  text-truncate " data-column="created-on" colspan="1">
                                <div style="width:">
                                    {{ $message->created_on }}
                                </div>
                            </td>
                            <td class="text-left  text-truncate " colspan="1">
                            <div class="form-group">
                                <button type="button" class="btn btn-link" title="Новое сообщение" data-action="screen--base#targetModal" data-modal-title="Новое сообщение" data-modal-key="message" data-modal-async="" data-modal-params="[]" data-modal-action="{{ url()->current() }}/asyncNewMessage">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="1em" height="1em" viewBox="0 0 32 32" class="mr-2" role="img" fill="currentColor" componentname="orchid-icon">
                                        <path d="M28.025 4.97l-7.040 0v-2.727c0-1.266-1.032-2.265-2.298-2.265h-5.375c-1.267 0-2.297 0.999-2.297 2.265v2.727h-7.040c-0.552 0-1 0.448-1 1s0.448 1 1 1h1.375l2.32 23.122c0.097 1.082 1.019 1.931 2.098 1.931h12.462c1.079 0 2-0.849 2.096-1.921l2.322-23.133h1.375c0.552 0 1-0.448 1-1s-0.448-1-1-1zM13.015 2.243c0-0.163 0.133-0.297 0.297-0.297h5.374c0.164 0 0.298 0.133 0.298 0.297v2.727h-5.97zM22.337 29.913c-0.005 0.055-0.070 0.11-0.105 0.11h-12.463c-0.035 0-0.101-0.055-0.107-0.12l-2.301-22.933h17.279z"></path>
                                    </svg>
                                </button>

                            </div>
                            </td>

                            @endforeach
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
