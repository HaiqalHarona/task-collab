<x-mail::message>
    # You've been invited!

    You've been invited to collaborate on a project. Click the button below to accept.

    <x-mail::button :url="$signedUrl">
        Accept Invitation
    </x-mail::button>

    This link expires in 7 days.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>