<div class="Empcontainer mx-auto p-5">
    <h1 class="text-4xl text-highlight font-extrabold mb-6">Manage Employees</h1>

    <div class="staffcontainer mx-auto bg-secondary w-full shadow-xl p-10">
        <table class="table-auto w-full text-center bg-white rounded-lg shadow-lg">
            <thead class="sticky top-0 bg-primary text-highlight z-10 text-white">
                <tr class="bg-primary text-highlight">
                    <th class="px-4 py-2 text-sm text-white">Name</th>
                    <th class="px-4 py-2 text-sm text-white">Gender</th>
                    <th class="px-4 py-2 text-sm text-white">Email</th>
                    <th class="px-4 py-2 text-sm text-white">Address</th>
                    <th class="px-4 py-2 text-sm text-white">Contact</th>
                    <th class="px-4 py-2 text-sm text-white">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                <tr class="border-b border-gray-300 text-black hover:bg-accent transition-all duration-200 hover:text-white">
                    <td class="px-4 py-2">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td class="px-4 py-2">{{ ucfirst($employee->sex) }}</td>
                    <td class="px-4 py-2">{{ $employee->email }}</td>
                    <td class="px-4 py-2">{{ $employee->address }}</td>
                    <td class="px-4 py-2">{{ $employee->contact }}</td>
                    <td class="px-4 py-2">
                        <button wire:click="$emit('showEditEmployeeForm', {{ $employee->user_id }})" class="bg-primary text-white py-1 px-3 rounded hover:bg-highlight hover:scale-110 transition">Edit</button>
                        <button wire:click="deleteEmployee({{ $employee->user_id }})" class="bg-danger text-white py-1 px-3 rounded hover:bg-highlight hover:scale-110 transition">Delete</button>
                    </td>
                </tr>
                @endforeach

                <!-- Add Employee Button Row -->
                <tr>
                    <td colspan="6" class="px-4 py-4">
                        <div class="flex justify-center items-center h-full">
                            <button id="showAddEmployeeButton" class="bg-primary text-white py-2 px-6 rounded hover:bg-highlight hover:scale-110 transition">
                                + Add New Employee
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>