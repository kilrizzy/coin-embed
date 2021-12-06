@component('mail.template')
    # New User Registered

    **Email:** xxx

    **Name:** xxx

    <a href="#">
        View User
    </a>

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
