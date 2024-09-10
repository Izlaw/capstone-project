<header class="bg-maroonbgcolor h-12 flex justify-between items-center">
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
            <input class="searchInput rounded-full bg-brownbgcolor w-full h-8 pl-4 text-lg type=text text-white focus:outline-none focus:ring-0" placeholder="Search...">
            <img class="searchIcon absolute top-1 right-2 w-6 h-6" src="{{ asset('img/search.svg') }}" alt="Search icon">
        </div>
    </div>
    <!--User Info-->
    <div class="userContainer flex items-center space-x-2">
        <a href="{{ route('customersupport')}}">
            <img class="customerSupport h-8 w-8" src="{{ asset('img/customersupport.svg') }}" alt="Customer Support icon">
        </a>
        <span class="userName text-white">Customer Name</span>
        <img class="userIcon h-8 w-8" src="{{ asset('img/useraccount.svg') }}" alt="User Account icon">
    </div>
</header>