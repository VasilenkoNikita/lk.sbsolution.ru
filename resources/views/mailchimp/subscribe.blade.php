

<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>


    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center mb-4">
                <h2>Подписка на рассылку</h2>
            </div>
                <x-jet-validation-errors class="mb-4" />
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-info mb-2">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('news-letter') }}">
                        @csrf

                        <div>
                            <x-jet-label for="email" value="{{ __('Email') }}" />
                            <x-jet-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autofocus />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-jet-button class="ml-4">
                             Подписаться
                            </x-jet-button>
                        </div>
                    </form>

                </div>
        </div>
    </div>
    </x-jet-authentication-card>
</x-guest-layout>
