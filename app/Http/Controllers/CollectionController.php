<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::all();
        return response()->json($collections);
    }

    public function getData(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $name = $request->input('name');

        $query = Collection::query();

        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }


        $data = $query->paginate($perPage, ['*'], 'page', $page);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'thumbnail' => 'required|image', // Assuming the 'image' field is of type file input in the form
        ]);
        
        // Upload image to Cloudinary using CloudinaryService
        if ($request->hasFile('thumbnail')) {
            $uploadedFile = $request->file('thumbnail'); // Lấy file từ request
    
            // Upload image to Cloudinary using CloudinaryService
            $imageUrl = CloudinaryService::uploadImage($uploadedFile);
    
            // Create a new collection and save it to the database
            $collection = new Collection();
            $collection->name = $request->name;
            $collection->description = $request->description;
            $collection->thumbnail = $imageUrl; // Save the Cloudinary URL
            $collection->save();
    
            return response()->json($collection, 201);
        } else {
            // Xử lý khi không có file được tải lên
            return response()->json(['error' => 'No image uploaded'], 400);
        }
        
        
        // $data = $request->validate([
        //     'name' => 'required',
        //     'description' => 'nullable',
        //     'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Đảm bảo tệp ảnh hợp lệ
        // ]);
    
        // Lưu ảnh vào thư mục và lưu tên tệp vào cột thumbnail trong bảng collections
        // if ($request->hasFile('thumbnail')) {
        //     $data['thumbnail'] = $request->file('thumbnail');
        // }
    
        // $collection = Collection::create($data);
    }

    public function show($id)
    {
        $collection = Collection::findOrFail($id);
        return response()->json($collection);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'thumbnail' => 'required|image', // Assuming the 'image' field is of type file input in the form
        ]);
        $collection = Collection::findOrFail($id);
        if ($collection) {
            if ($request->hasFile('thumbnail')) {
                $uploadedFile = $request->file('thumbnail'); // Lấy file từ request
        
                // Upload image to Cloudinary using CloudinaryService
                $imageUrl = CloudinaryService::uploadImage($uploadedFile);
                $collection->name = $request->name;
                $collection->description = $request->description;
                $collection->thumbnail = $imageUrl; // Save the Cloudinary URL
                
                $collection->save();
        
                return response()->json($collection, 201);
            } else {
                // Xử lý khi không có file được tải lên
                return response()->json(['error' => 'No image uploaded'], 400);
            }
        } else {
            return response()->json($collection, 201);
        }
    }

    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);
        $collection->delete();
        return response()->json(null, 204);
    }
}
