<div class="px-6 py-6 bg-blue-700 rounded-lg md:py-12 md:px-12 lg:py-16 lg:px-16 xl:flex xl:items-center">
    <div class="xl:w-0 xl:flex-1">
        <h2 class="text-2xl leading-8 font-extrabold tracking-tight text-white sm:text-3xl sm:leading-9">
            Want to know when we're ready?
        </h2>
        <p class="mt-3 max-w-3xl text-lg leading-6 text-blue-200" id="newsletter-headline">
            Sign up for our newsletter to stay up to date.
        </p>
    </div>
    <div class="mt-8 sm:w-full sm:max-w-md xl:mt-0 xl:ml-8">
        <form action="{{ url('/newsletter') }}" method="POST" class="sm:flex" aria-labelledby="newsletter-headline">
            @csrf
            <input aria-label="Email address" type="email" name="email" required="required" class="appearance-none w-full px-5 py-3 border border-transparent text-base leading-6 rounded-md text-gray-900 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 transition duration-150 ease-in-out" placeholder="Enter your email">
            <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                <button type="submit" class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-500 hover:bg-blue-400 focus:outline-none focus:bg-blue-400 transition duration-150 ease-in-out">
                    Keep me posted
                </button>
            </div>
        </form>
    </div>
</div>