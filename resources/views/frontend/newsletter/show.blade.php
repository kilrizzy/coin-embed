@extends('layouts.front')

@section('content')

    <div class="relative">
        <div class="mx-auto max-w-7xl w-full pt-8 pb-8 text-center lg:text-left">

            <div class="px-6 py-6 bg-blue-700 rounded-lg md:py-12 md:px-12 lg:py-16 lg:px-16 xl:flex xl:items-center">
                <div class="xl:w-0 xl:flex-1">
                    <h2 class="text-2xl leading-8 font-extrabold tracking-tight text-white sm:text-3xl sm:leading-9">
                        Want to know when we're ready?
                    </h2>
                    <p class="mt-3 max-w-3xl text-lg leading-6 text-blue-200" id="newsletter-headline">
                        Sign up for our newsletter to stay up to date.
                    </p>
                </div>
                <div class="mt-8 sm:w-full sm:max-w-md xl:mt-0 xl:ml-8 bg-green-400 rounded p-4 text-lg text-white text-center">
                    <strong>Thanks for signing up,</strong> <br/>we'll send ya an email when we launch!
                </div>
            </div>

        </div>
    </div>




@endsection
