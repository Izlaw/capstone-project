<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Upload Order Page</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.customerheader')
</head>

<body class="bg-mainbackground bg-cover">
  
<!-- Container -->
  <div class="SelectionContainer mx-auto scale-95 bg-brownbgcolor bg-opacity-80 backdrop-blur-md">
    <div class="TopBar bg-maroonbgcolor text-white text-center flex justify-center items-center mt-2 h-12">
      <h2 class="text-black leading-8 text-3xl font-semibold">Customize</h2>
      
      <!-- Back Button -->
      <a href="{{ route('uploadorder')}}" class="GoBackButton absolute top-0.5 right-2 group">
        <img class="BackButton h-8 w-8 rounded-lg p-1" src="../img/gobackbutton.svg">
      </a>
    </div>
    <div class="flex h-83vh">
      <div class="OptionsBox bg-maroonbgcolor text-white w-300px text-28px tracking-wide max-w-280px">
        <div class="SelectionChoices w-280px flex flex-col p-2 gap-y-5">
          <a href="#SchoolUniform" class="SchoolUniform hover:bg-white hover:text-maroonbgcolor cursor-pointer">School Uniform</a>
          <a href="#SportsWear" class="SportsWear hover:bg-white hover:text-maroonbgcolor cursor-pointer">Sports Wear</a>
          <a href="#TShirts" class="TShirts hover:bg-white hover:text-maroonbgcolor cursor-pointer">T-Shirts</a>
          <a href="#Curtains" class="Curtains hover:bg-white hover:text-maroonbgcolor cursor-pointer">Curtains</a>
          <a href="#Accessories" class="Accessories hover:bg-white hover:text-maroonbgcolor cursor-pointer">Accessories</a>
        </div>
      </div>

      <div class="SchoolUniformOptionsOuter flex-1 overflow-x-auto max-w-full">
        <!-- Preset options -->
        <div class="SchoolUniformOptionPngsContainer flex flex-nowrap flex-shrink-0 max-w-1200px">
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for School Uniform image 1
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for School Uniform image 2
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for School Uniform image 3
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for School Uniform image 4
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for School Uniform image 5
          </div>
        </div>
      </div>

      <div class="SportsWearOptionsOuter flex-1 overflow-x-auto max-w-full">
        <!-- Preset options -->
        <div class="SportsWearOptionPngsContainer flex flex-nowrap flex-shrink-0 max-w-1200px">
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Sports Wear image 1
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Sports Wear image 2
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Sports Wear image 3
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Sports Wear image 4
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Sports Wear image 5
          </div>
        </div>
      </div>

      <div class="TShirtsOptionsOuter flex-1 overflow-x-auto max-w-full">
        <!-- Preset options -->
        <div class="TShirtsOptionPngsContainer flex flex-nowrap flex-shrink-0 max-w-1200px">
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for TShirts image 1
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for TShirts image 2
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for TShirts image 3
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for TShirts image 4
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for TShirts image 5
          </div>
        </div>
      </div>

      <div class="CurtainsOptionsOuter flex-1 overflow-x-auto max-w-full">
        <!-- Preset options -->
        <div class="CurtainsOptionPngsContainer flex flex-nowrap flex-shrink-0 max-w-1200px">
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Curtains image 1
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Curtains image 2
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Curtains image 3
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Curtains image 4
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Curtains image 5
          </div>
        </div>
      </div>
      
      <div class="AccessoriesOptionsOuter flex-1 overflow-x-auto max-w-full">
        <!-- Preset options -->
        <div class="AccessoriesOptionPngsContainer flex flex-nowrap flex-shrink-0 max-w-1200px">
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Accessories image 1
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Accessories image 2
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Accessories image 3
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Accessories image 4
          </div>
          <div class="OptionPngs bg-white w-280px h-83vh mr-4">
            This is a placeholder for Accessories image 5
          </div>
        </div>
      </div>

      </div>
    </div>
  </div>

</body>
</html>