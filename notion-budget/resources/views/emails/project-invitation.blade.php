<x-mail::message>

    <div style="text-align: center; padding: 10px 0 24px;">
        <h1 style="font-size: 26px; font-weight: 700; margin: 0 0 8px;">You're Invited! 🎉</h1>
        <p style="color: #6b7280; font-size: 15px; margin: 0;">You have a new project invitation waiting for you.</p>
    </div>

    ---

    Hi there,

    You've been invited to join **{{ $projectName }}**. We're excited to have you on board — can't wait to see what we
    build together!

    Here's what happens next:

    - ✅ Click **Accept Invitation** below
    - 🔐 Create or log in to your account
    - 🚀 Get instant access to **{{ $projectName }}**

    <x-mail::button :url="$signedUrl" color="success">
        Accept Invitation
    </x-mail::button>

    <x-mail::panel>
        ⏳ **This invitation expires in 7 days.** If you weren't expecting this, you can safely ignore the email — no
        action is needed.
    </x-mail::panel>

    Thanks,<br>
    **The {{ config('app.name') }} Team**

    <x-slot:subcopy>
        If the button above doesn't work, copy and paste this URL into your browser:
        [{{ $signedUrl }}]({{ $signedUrl }})
    </x-slot:subcopy>

</x-mail::message>