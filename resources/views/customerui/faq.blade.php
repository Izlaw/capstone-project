<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-primary bg-cover font-sans">

    <!-- FAQ Section -->
    <div class="FAQContainer mx-auto w-3/4 bg-opacity-90 p-8 rounded-xl shadow-lg h-customviewport">
        <x-backbutton route="home" />

        <!-- Title -->
        <p class="FAQTxt text-4xl text-white font-semibold text-center mb-4">FAQs</p>
        <p class="FAQTxt text-lg text-gray-200 text-center mb-8">Frequently Asked Questions about 7 GUYS House of Shop.</p>

        <!-- FAQs List -->
        <div class="QuestionList space-y-4">
            <!-- FAQ 1 -->
            <div class="QuestionContainer border-b-2 border-gray-300 pb-4">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-semibold text-gray-200">What is 7 GUYS House of Shop?</span>
                    <span class="RevealAnswer text-2xl font-bold cursor-pointer text-gray-300 group-hover:text-primary">+</span>
                </div>
                <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out text-gray-200">
                    7 GUYS House of Shop is a one-stop shop for all your needs.
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="QuestionContainer border-b-2 border-gray-300 pb-4">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-semibold text-gray-200">How do I place an order?</span>
                    <span class="RevealAnswer text-2xl font-bold cursor-pointer text-gray-300 group-hover:text-primary">+</span>
                </div>
                <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out text-gray-200">
                    You can place an order through our website or by calling us directly.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="QuestionContainer border-b-2 border-gray-300 pb-4">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-semibold text-gray-200">What payment methods do you accept?</span>
                    <span class="RevealAnswer text-2xl font-bold cursor-pointer text-gray-300 group-hover:text-primary">+</span>
                </div>
                <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out text-gray-200">
                    We accept all major credit cards, PayPal, and cash on delivery.
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="QuestionContainer border-b-2 border-gray-300 pb-4">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-semibold text-gray-200">Can I cancel my order?</span>
                    <span class="RevealAnswer text-2xl font-bold cursor-pointer text-gray-300 group-hover:text-primary">+</span>
                </div>
                <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out text-gray-200">
                    Yes, you can cancel your order within 30 minutes of placing it.
                </div>
            </div>

            <!-- FAQ 5 -->
            <div class="QuestionContainer border-b-2 border-gray-300 pb-4">
                <div class="flex justify-between items-center">
                    <span class="text-xl font-semibold text-gray-200">Do you offer delivery?</span>
                    <span class="RevealAnswer text-2xl font-bold cursor-pointer text-gray-300 group-hover:text-primary">+</span>
                </div>
                <div class="HiddenAnswer max-h-0 opacity-0 overflow-hidden transition-all duration-150 ease-in-out text-gray-200">
                    Yes, we offer delivery within a 20-mile radius of our store.
                </div>
            </div>
        </div>

        <!-- Contact Us Wrapper -->
        <div class="mt-12">
            @php
            $randomEmployee = App\Models\User::where('role', 'employee')->inRandomOrder()->first();
            @endphp

            @if ($randomEmployee)
            <a href="{{ route('askSupport', ['convoID' => $conversation->convoID]) }}" class="relative group w-full">
                <div class="MoreInfoContainer bg-white p-6 rounded-lg shadow-lg text-center border border-gray-200 hover:shadow-xl transition-shadow duration-300 relative z-10">
                    <!-- Initially visible text (hidden on hover) -->
                    <p id="helpText" class="MoreInfo text-2xl font-semibold text-gray-700 transition-all duration-300 transform scale-100 opacity-100 group-hover:scale-90 group-hover:opacity-0">
                        Still need help?
                    </p>
                </div>

                <!-- Text that becomes visible on hover (hidden by default) -->
                <p id="contactText" class="MoreInfo text-2xl font-semibold text-primary opacity-0 transition-all duration-300 transform scale-90 absolute top-0 left-0 right-0 bottom-0 flex justify-center items-center group-hover:opacity-100 group-hover:scale-100 z-20">
                    Contact us!
                </p>
            </a>
            @else
            <p class="text-center text-gray-400 mt-4">No employees available at the moment.</p>
            @endif
        </div>


    </div>

</body>

</html>