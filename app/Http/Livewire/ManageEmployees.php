<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Import Validator
use Illuminate\Validation\ValidationException;

class ManageEmployees extends Component
{
    public $first_name, $last_name, $email, $password, $password_confirmation, $sex, $bday, $contact, $address;
    public $isAddEmployeeFormOpen = false;
    public $isEditEmployeeFormOpen = false;
    public $employeeIdBeingEdited;

    protected $listeners = ['addEmployee', 'editEmployee', 'deleteEmployee', 'showEditEmployeeForm']; // Listening for the events

    // Render the view with employees
    public function render()
    {
        return view('livewire.manage-employees', [
            'employees' => User::where('role', 'employee')->get(),
        ]);
    }

    // Show form to add a new employee
    public function showAddEmployeeForm()
    {
        $this->reset(['first_name', 'last_name', 'email', 'password', 'password_confirmation', 'sex', 'bday', 'contact', 'address']);
        $this->isAddEmployeeFormOpen = true;
    }

    // Close the Add Employee form
    public function closeAddEmployeeForm()
    {
        $this->isAddEmployeeFormOpen = false;
    }

    public function showEditEmployeeForm($employeeId)
    {
        $employee = User::where('user_id', $employeeId)->firstOrFail();
        $this->employeeIdBeingEdited = $employeeId;
        $this->first_name = $employee->first_name;
        $this->last_name = $employee->last_name;
        $this->email = $employee->email;
        $this->sex = $employee->sex;
        $this->bday = $employee->bday;
        $this->contact = $employee->contact;
        $this->address = $employee->address;
        $this->isEditEmployeeFormOpen = true;

        $this->dispatchBrowserEvent('show-edit-employee-form', ['employee' => [
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'email' => $employee->email,
            'sex' => $employee->sex,
            'bday' => $employee->bday,
            'contact' => $employee->contact,
            'address' => $employee->address,
        ]]);
    }


    // Close the Edit Employee form
    public function closeEditEmployeeForm()
    {
        $this->isEditEmployeeFormOpen = false;
    }

    // Add a new employee
    public function addEmployee($formData)
    {
        try {
            // Check if email, contact, or address already exists
            $emailExists = User::where('email', $formData['email'])->exists();
            $contactExists = User::where('contact', $formData['contact'])->exists();
            $addressExists = User::where('address', $formData['address'])->exists();

            if ($emailExists) {
                $this->emit('employeeAddedError', 'Email is already in use!');
                return;
            }

            if ($contactExists) {
                $this->emit('employeeAddedError', 'Contact is already in use!');
                return;
            }

            if ($addressExists) {
                $this->emit('employeeAddedError', 'Address is already in use!');
                return;
            }

            $validatedData = Validator::make($formData, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'sex' => 'required|string|max:10',
                'bday' => 'required|date',
                'contact' => 'required|string|max:15',
                'address' => 'required|string|max:255',
            ])->validate();

            User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'sex' => $validatedData['sex'],
                'bday' => $validatedData['bday'],
                'contact' => $validatedData['contact'],
                'address' => $validatedData['address'],
                'role' => 'employee',
            ]);

            $this->closeAddEmployeeForm();
            session()->flash('message', 'Employee added successfully.');
            $this->emit('employeeAdded');
        } catch (\Exception $e) {
            $this->emit('employeeAddedError', $e->getMessage());
        }
    }

    // Edit an existing employee
    public function editEmployee($formData)
    {
        $validatedData = Validator::make($formData, [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $this->employeeIdBeingEdited . ',user_id',
            'sex' => 'nullable|string|max:10',
            'bday' => 'nullable|date',
            'contact' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ])->validate();

        $employee = User::findOrFail($this->employeeIdBeingEdited);
        $employee->update($validatedData);

        $this->closeEditEmployeeForm();
        session()->flash('message', 'Employee updated successfully.');
    }

    // Delete an employee
    public function deleteEmployee($employeeId)
    {
        $employee = User::findOrFail($employeeId);
        $employee->delete();

        session()->flash('message', 'Employee deleted successfully.');
    }
}
