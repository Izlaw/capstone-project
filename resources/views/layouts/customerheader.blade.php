@vite('resources/js/app.js')

<header class="bg-maroonbgcolor h-12 flex justify-between items-center max-w-full">
<meta name="csrf-token" content="{{ csrf_token() }}">

    <!--7 GUYS HOUSE OF FASHION TEXT-->
    <a href="{{ route('home') }}">
        <div class="flex flex-col justify-center ml-0">
        <div class="weblogo1 text-lg font-bold text-yellow-500 text-stroke-pink text-center">7 GUYS</div>
        <div class="weblogo2 text-sm font-bold text-pinktext">HOUSE OF FASHION</div>
    </div>
    </a>
    <!--Search Bar-->
    <div class="search-container w-full max-w-md p-4 mx-auto">
        <div class="relative">
            <input class="searchInput rounded-full bg-brownbgcolor w-full h-8 pl-4 text-lg text-white focus:outline-none focus:ring-0" type="text" placeholder="Search...">
            <img class="searchIcon absolute top-1 right-2 w-6 h-6" src="{{ asset('img/search.svg') }}" alt="Search icon">
        </div>
    </div>

    <!--User Info-->
    <!--User Info-->
<div class="userContainer flex items-center space-x-2 mr-2">
    <a href="{{ route('customersupport') }}">
        <img class="customerSupport h-8 w-8" src="{{ asset('img/customersupport.svg') }}" alt="Customer Support icon">
    </a>
    @auth
        <span class="userName text-white">{{ Auth::user()->name }}</span>
    @else
        <span class="userName text-white">Guest</span>
    @endauth
    <img class="userIcon h-8 w-8 pointer cursor-pointer" src="{{ asset('img/useraccount.svg') }}" alt="User Account icon">

    <!-- Drop down -->
    <div class="userIconDropdown hidden absolute top-2 right-0 mt-10 w-48 bg-white rounded-md shadow-lg py-2 z-1">
        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>

        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
        </form>
    </div>
</div>

</header>