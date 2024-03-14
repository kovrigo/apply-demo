<div class="chat" data-chat-id="{{ $chat->id }}">

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

    <div class="reply">
        Ответьте на это сообщение, чтобы написать в беседу.
    </div>    

</div>