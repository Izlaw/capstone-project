<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\Size;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\CustomModel;
use App\Models\Collection;
use Illuminate\Support\Facades\Auth;
use App\Events\OrderStatusUpdatedEvent;
use App\Models\Notification; // If you're referencing your custom Notification model
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB; // Make sure this line is outside of the namespace


class ViewController extends Controller
{

    public function redirectUserToHome()
    {
        $user = Auth::user();

        if ($user) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'employee') {
                return redirect()->route('employee.dashboard');
            } elseif ($user->role === 'customer') {
                return view('customerui.home');
            } else {
                return redirect()->route('access-denied');
            }
        }

        return view('customerui.home');
    }

    public function showaddOrder()
    {
        return view('customerui.addorder');
    }

    public function addCustomOrder()
    {
        return view('customerui.addcustomorder');
    }

    public function uploadCustomOrder()
    {
        return view('customerui.uploadorder');
    }

    public function showFaq()
    {
        $conversation = Conversation::where('user_id', auth()->id())->first();

        return view('customerui.faq', compact('conversation'));
    }

    public function showEmpDboard()
    {
        return view('employeeui.empdboard');
    }

    public function showAdminManageOrder()
    {
        $orders = Order::with('user', 'collection', 'customOrder', 'uploadOrder', 'customOrder.sizes')->get();

        return view('adminui.adminmanageorder', compact('orders'));
    }

    public function showAdminDboard()
    {
        return view('adminui.admindboard');
    }

    public function showAdminManagePrices()
    {
        $sizes = Size::select('sizeName', 'sizePrice', 'sizeID')->get();

        return view('adminui.adminprices', compact('sizes'));
    }

    public function showAdminPrices()
    {
        $sizes = Size::all();

        return view('adminui.adminprices', compact('sizes'));
    }

    public function showEmpManageCollections()
    {
        $models = CustomModel::all();

        return view('employeeui.empmanagecollections', compact('models'));
    }

    public function showViewCollections()
    {
        $collections = Collection::all();
        return view('customerui.viewcollections', compact('collections'));
    }

    public function showManageEmployees()
    {
        $employees = User::where('role', 'employee')->get();

        return view('adminui.adminmanageemployees', ['employees' => $employees]);
    }

    public function showAdminCollections()
    {
        $collections = Collection::all();
        return view('adminui.admincollections', compact('collections'));
    }
}
