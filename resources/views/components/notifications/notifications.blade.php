@if (isset($errors) && $errors->any())
    @foreach($errors->getMessages() as $errorKey => $errors)
        @foreach($errors as $error)
            <notification theme="error" title="{{ ucwords($errorKey) }}">{{ $error }}</notification>
        @endforeach
    @endforeach
@endif

@if ($message = Session::get('status'))
    <notification theme="info" title="Status">{{ $message }}</notification>
@endif

@if ($message = Session::get('success'))
    <notification theme="success" title="Success">{{ $message }}</notification>
@endif

@if ($message = Session::get('error'))
    <notification theme="error" title="Error">{{ $message }}</notification>
@endif

@if ($message = Session::get('warning'))
    <notification theme="warning" title="Warning">{{ $message }}</notification>
@endif

@if ($message = Session::get('info'))
    <notification theme="info" title="Notice">{{ $message }}</notification>
@endif

<notification ref="dynamicNotification" :start-open="false"></notification>