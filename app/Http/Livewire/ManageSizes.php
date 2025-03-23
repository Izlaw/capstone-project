<?php

namespace App\Http\Livewire;

use App\Models\Size;
use Livewire\Component;

class ManageSizes extends Component
{
    public $sizes = [];

    public function mount()
    {
        // Fetch all sizes from the database and initialize the sizes array
        $this->sizes = Size::all()->keyBy('sizeID')->map(function ($size) {
            return [
                'sizeName' => $size->sizeName,
                'sizePrice' => $size->sizePrice,
            ];
        })->toArray();
    }

    public function updatePrice($sizeID)
    {
        // Check if price is set for the size in the $sizes array
        if (isset($this->sizes[$sizeID]['price'])) {
            // Find the size object in the database
            $size = Size::find($sizeID);

            // If the size exists, update the price and save it
            if ($size) {
                $size->sizePrice = $this->sizes[$sizeID]['price'];  // Update price
                $size->save();

                // Re-fetch sizes to reflect updated prices
                $this->sizes = Size::all()->keyBy('sizeID')->map(function ($size) {
                    return [
                        'sizeName' => $size->sizeName,
                        'sizePrice' => $size->sizePrice,
                    ];
                })->toArray();

                // Flash success message
                session()->flash('message', 'Prices updated successfully!');
            }
        }
    }

    public function render()
    {
        return view('livewire.manage-sizes');
    }
}
