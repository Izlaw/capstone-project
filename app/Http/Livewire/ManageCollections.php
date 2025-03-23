<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ManageCollections extends Component
{
    use WithFileUploads;

    public $collections;
    public $collectID;
    public $collectName;
    public $collectPrice;
    public $collectFilePath;
    public $image;

    protected $listeners = [
        'createCollection' => 'store',
        'updateCollection' => 'update',
        'deleteCollection' => 'delete',
        'showCreateCollectionPopup' => 'showCreateCollectionPopup',
        'showEditCollectionPopup' => 'showEditCollectionPopup',
        'showDeleteCollectionPopup' => 'showDeleteCollectionPopup',
    ];

    protected $rules = [
        'collectName' => 'required|string|max:50',
        'collectPrice' => 'required|integer',
        'image' => 'nullable|image|max:1024', // 1MB Max
    ];

    public function mount()
    {
        Log::info('Mounting ManageCollections component');
        $this->collections = Collection::all();
    }

    public function store($data)
    {
        Log::info("Storing new collection:\n" . json_encode($data, JSON_PRETTY_PRINT));

        // Ensure the image is set correctly
        if (!isset($data['image'])) {
            Log::error('Image not found in data', ['data' => $data]);
            return;
        }

        $this->collectName = $data['collectName'];
        $this->collectPrice = $data['collectPrice'];
        $this->image = $data['image'];

        Log::info("Data after setting properties:\n" . json_encode([
            'collectName' => $this->collectName,
            'collectPrice' => $this->collectPrice,
            'image' => $this->image,
        ], JSON_PRETTY_PRINT));

        $validatedData = $this->validate([
            'collectName' => 'required|string|max:50',
            'collectPrice' => 'required|integer',
            'image' => 'required|image|max:1024', // 1MB Max
        ]);

        Log::info("Validated data:\n" . json_encode($validatedData, JSON_PRETTY_PRINT));

        // Check if the collections folder exists
        if (!Storage::exists('public/collections')) {
            Log::info('Collections folder does not exist, creating it...');
            Storage::makeDirectory('public/collections');
        } else {
            Log::info('Collections folder exists');
        }

        // Handle the image upload
        if ($this->image) {
            $imageName = $this->image->getClientOriginalName();
            $imagePath = $this->image->storeAs('public/collections', $imageName);
            $validatedData['collectFilePath'] = 'collections/' . $imageName;
            Log::info('Image stored successfully', ['imagePath' => $imagePath]);

            // Check if the file exists
            if (Storage::exists($imagePath)) {
                Log::info('Image file exists in storage', ['imagePath' => $imagePath]);
            } else {
                Log::error('Image file does not exist in storage', ['imagePath' => $imagePath]);
            }
        } else {
            Log::error('Image not found in data after validation', ['data' => $data]);
            return;
        }

        Collection::create($validatedData);

        Log::info('Collection created successfully', ['collection' => $validatedData]);

        $this->collections = Collection::all();
    }

    public function update($data)
    {
        Log::info('Updating collection', ['data' => $data]);

        $validatedData = $this->validateData($data);

        $collection = Collection::findOrFail($data['collectID']);

        // Handle the image upload
        if (isset($data['image'])) {
            $imageName = $validatedData['collectName'] . '.png';
            $imagePath = $data['image']->storeAs('public/collections', $imageName);
            $validatedData['collectFilePath'] = 'collections/' . $imageName;
            Log::info('Image stored successfully', ['imagePath' => $imagePath]);
        } else {
            $validatedData['collectFilePath'] = $collection->collectFilePath;
        }

        $collection->update($validatedData);

        Log::info('Collection updated successfully', ['collection' => $validatedData]);

        $this->collections = Collection::all();
    }

    public function delete($id)
    {
        Log::info('Deleting collection', ['id' => $id]);

        $collection = Collection::findOrFail($id);
        $collection->delete();

        Log::info('Collection deleted successfully', ['id' => $id]);

        $this->collections = Collection::all();
    }

    private function validateData($data)
    {
        Log::info('Validating data', ['data' => $data]);
        return validator($data, $this->rules)->validate();
    }

    public function showCreateCollectionPopup()
    {
        $this->dispatchBrowserEvent('showCreateCollectionPopup');
    }

    public function showEditCollectionPopup($collectID, $collectName, $collectPrice, $collectFilePath)
    {
        $this->collectID = $collectID;
        $this->collectName = $collectName;
        $this->collectPrice = $collectPrice;
        $this->collectFilePath = $collectFilePath;
        $this->dispatchBrowserEvent('showEditCollectionPopup', [
            'collectID' => $collectID,
            'collectName' => $collectName,
            'collectPrice' => $collectPrice,
            'collectFilePath' => $collectFilePath,
        ]);
    }

    public function showDeleteCollectionPopup($collectID)
    {
        $this->dispatchBrowserEvent('showDeleteCollectionPopup', ['collectID' => $collectID]);
    }

    public function render()
    {
        Log::info('Rendering ManageCollections component');
        return view('livewire.manage-collections');
    }
}
