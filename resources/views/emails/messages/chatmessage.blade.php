<div class="chatmessage" data-chat-id="{{ $chat->id }}">

    <div class="chat-header">
        <div class="chat-subject">
            {{ $chat->subject_title }}
        </div>

        <div class="chat-name">
            {{ $chat->name }}   
        </div>

        <div class="participants">
            <div class="external">
                @foreach ($chat->profiles as $profile)
                    <div class="participant">{{ $profile->title }}</div>
                @endforeach     
            </div>
            <div class="internal">
                @foreach ($chat->users as $user)
                    <div class="participant">{{ $user->title }}</div>
                @endforeach     
            </div>  
        </div>
    </div>

    <div class="chat-description">
        {!! nl2br($chat->description) !!}
    </div>

    <div class="messages">
        @foreach ($chat->chatMessages()->where('sent', true)->orderBy('id', 'asc')->get() as $chatMessage)

            @if ($loop->last)
                <div class="new-message-delimiter">
                    Новое сообщение
                </div>
            @endif

            <div class="message {{ $chatMessage->from_type == 'App\Profile' ? 'profile' : 'user' }}">
                <div class="message-from">{{ $chatMessage->from->title }}</div>
                <div class="message-date">
                    {{ (new \Carbon\Carbon($chatMessage->created_at))->format('d.m.Y H:i') }}
                </div>
                <div class="message-content">{!! nl2br($chatMessage->message) !!}</div>
            </div>
        @endforeach
    </div>

    <div class="reply">
        Ответьте на это сообщение, чтобы написать в беседу.
    </div>

</div>