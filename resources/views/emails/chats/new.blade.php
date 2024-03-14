@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        {{--
        @component('mail::header', ['url' => $chat->tenant->career_website_url])
            {{ $chat->tenant->name }}
        @endcomponent
        --}}
    @endslot

    {{-- Body --}}
    @component('emails.chats.chat', ['chat' => $chat])
    @endcomponent

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        {{--
        @component('mail::footer')
        	Ответьте на это сообщение, чтобы написать в беседу.
        @endcomponent
        --}}
    @endslot
@endcomponent
