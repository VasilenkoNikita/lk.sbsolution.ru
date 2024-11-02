<div class="admin-wrapper py-3">
    <div class="row">
        <div class="col-md-12">
            @foreach ($enumerationcats as $enumcat)
                <h4 class="font-thin text-black mb-3">{{ $enumcat[0]->type }}</h4>

            <div class="row">
                <div class="w-100 table-responsive">
                    <table class="table ">
                        <thead>
                        <tr>
                            <th width="150" class="text-center" data-column="id">
                                <div>
                                    Позиция
                                </div>
                            </th>

                            <th width="350" class="text-left" data-column="name">
                                <div>
                                    Название
                                </div>
                            </th>
                            <th class="text-center" data-column="content">
                                <div>
                                    По умолчанию
                                </div>
                            </th>
                            <th class="text-center" data-column="created-on">
                                <div>
                                    Активность
                                </div>
                            </th>
                            <th class="text-left">
                                <div>
                                </div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($enumcat as $enum)
                        <tr>
                            <td class="text-text-center " data-column="id" colspan="1">
                                <div style="width:150px">
                                    {{ $enum->position }}
                                </div>
                            </td>

                            <td class="text-left " data-column="name" colspan="1">
                                <div style="width:350px">
                                    {{ $enum->name }}
                                </div>
                            </td>

                            <td class="text-center  text-truncate " data-column="content" colspan="1">
                                <div>
                                    @if ($enum->is_default === 1)
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" viewBox="0 0 32 32"><path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14 6.28 14 14-6.28 14.032-14 14.032zM22.386 10.146l-9.388 9.446-4.228-4.227c-0.39-0.39-1.024-0.39-1.415 0s-0.391 1.023 0 1.414l4.95 4.95c0.39 0.39 1.024 0.39 1.415 0 0.045-0.045 0.084-0.094 0.119-0.145l9.962-10.024c0.39-0.39 0.39-1.024 0-1.415s-1.024-0.39-1.415 0z"></path></svg>
                                    @else
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" viewBox="0 0 32 32"><path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14 6.28 14 14-6.28 14.032-14 14.032zM21.657 10.344c-0.39-0.39-1.023-0.39-1.414 0l-4.242 4.242-4.242-4.242c-0.39-0.39-1.024-0.39-1.415 0s-0.39 1.024 0 1.414l4.242 4.242-4.242 4.242c-0.39 0.39-0.39 1.024 0 1.414s1.024 0.39 1.415 0l4.242-4.242 4.242 4.242c0.39 0.39 1.023 0.39 1.414 0s0.39-1.024 0-1.414l-4.242-4.242 4.242-4.242c0.391-0.391 0.391-1.024 0-1.414z"></path></svg>
                                    @endif
                              </div>
                            </td>

                            <td class="text-center  text-truncate " data-column="created-on" colspan="1">
                                <div>
                                    @if ($enum->active === 1)
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" viewBox="0 0 32 32"><path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14 6.28 14 14-6.28 14.032-14 14.032zM22.386 10.146l-9.388 9.446-4.228-4.227c-0.39-0.39-1.024-0.39-1.415 0s-0.391 1.023 0 1.414l4.95 4.95c0.39 0.39 1.024 0.39 1.415 0 0.045-0.045 0.084-0.094 0.119-0.145l9.962-10.024c0.39-0.39 0.39-1.024 0-1.415s-1.024-0.39-1.415 0z"></path></svg>
                                    @else
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="red" viewBox="0 0 32 32"><path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16-7.163 16-16s-7.163-16-16-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14 6.28 14 14-6.28 14.032-14 14.032zM21.657 10.344c-0.39-0.39-1.023-0.39-1.414 0l-4.242 4.242-4.242-4.242c-0.39-0.39-1.024-0.39-1.415 0s-0.39 1.024 0 1.414l4.242 4.242-4.242 4.242c-0.39 0.39-0.39 1.024 0 1.414s1.024 0.39 1.415 0l4.242-4.242 4.242 4.242c0.39 0.39 1.023 0.39 1.414 0s0.39-1.024 0-1.414l-4.242-4.242 4.242-4.242c0.391-0.391 0.391-1.024 0-1.414z"></path></svg>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
