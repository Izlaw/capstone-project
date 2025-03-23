<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Collections</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @include('layouts.header')
</head>

<body class="bg-primary text-white font-sans">
    <!-- Back Button -->
    <x-backbutton route="home" />

    <div class="mx-auto bg-secondary w-full bg-opacity-80 backdrop-blur-lg rounded-2xl shadow-xl p-10">
        <!-- Main Title -->
        <h1 class="text-4xl text-highlight font-extrabold mb-6">Manage Collections</h1>

        <!-- Display models in a table -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-md">
            <table class="table-auto w-full">
                <thead>
                    <tr class="bg-primary text-highlight">
                        <th class="py-3 px-6 text-lg font-semibold">Model ID</th>
                        <th class="py-3 px-6 text-lg font-semibold">Model Name</th>
                        <th class="py-3 px-6 text-lg font-semibold">Model File</th>
                        <th class="py-3 px-6 text-lg font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($models as $model)
                    <tr class="border-b hover:bg-accent transition-all duration-200 ease-in-out text-black text-center">
                        <td class="py-3 px-6">{{ $model->modelID }}</td>
                        <td class="py-3 px-6">{{ ucfirst($model->modelName) }}</td>
                        <td class="py-3 px-6">{{ $model->modelFilePath }}</td>
                        <td class="py-3 px-6">
                            <a href="{{ asset($model->modelFilePath) }}" class="text-highlight hover:text-white hover:underline transition-all duration-200 ease-in-out" target="_blank">
                                View Model
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>