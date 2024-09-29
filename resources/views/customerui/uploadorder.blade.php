<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Order Page</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')

</head>
<body class="bg-mainbackground bg-cover overflow-y-hidden">

<!-- Ask for gender -->
  <div class="QuestionContainer flex justify-center items-center h-screen">
    <div class="BoxContainer bg-maroonbgcolor w-[400px] h-[300px] p-4 rounded-lg shadow-md bg-opacity-80 backdrop-blur-md text-center">

      <!-- Back button to addorderpage.html -->
      <a href="{{ route('addorder')}}" class="group">
        <img class="BackButton h-8 w-8 float-right rounded-lg p-1 cursor-pointer" src="../img/gobackbutton.svg">
      </a>
      
      <!-- Question -->
      <div class="MoveContentsDown pt-8">
        <span class="QuestionTxt text-white text-[48px]">Which gender?</span>
        <div class="flex justify-center mt-10">
          <a href="{{ route('uploadordermale')}}">
            <button class="bg-maroonbgcolor hover:bg-white hover:text-maroonbgcolor text-white font-bold py-2 px-4 rounded-lg text-[24px]" data-gender="Male">Male</button>
          </a>
          <a href="{{ route('uploadorderfemale')}}">
            <button class="bg-maroonbgcolor hover:bg-white hover:text-maroonbgcolor text-white font-bold py-2 px-4 rounded-lg ml-20 text-[24px]" data-gender="Female">Female</button>
          </a>
        </div>
      </div>
    </div>
  </div>

</body>
</html>