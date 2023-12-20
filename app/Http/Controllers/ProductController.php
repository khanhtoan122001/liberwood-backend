<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\CloudinaryService;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function getData(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $page = $request->input('page', 1);
        $name = $request->input('name');

        $query = Product::query();

        if ($name !== null) {
            $query->where('name', 'like', '%' . $name . '%');
        }


        $data = $query->paginate($perPage, ['*'], 'page', $page);
        return response()->json($data);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required|string',
        //     'thumbnail' => 'required|image', // Đảm bảo thumbnail là ảnh
        //     'description' => 'string',
        //     'collectionId' => 'string',
        //     'color' => 'array',
        //     'image.*' => 'image' // Đảm bảo image là một list ảnh
        // ]);

        $thumbnailFile = $request->file('thumbnail');
        $thumbnailUrl = CloudinaryService::uploadImage($thumbnailFile);

        $imageUrls = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageUrl = CloudinaryService::uploadImage($image);
                $imageUrls[] = $imageUrl;
            }
        }

        $string = implode(", ", $imageUrls);


        $product = new Product();
        $product->name = $request->name;
        $product->thumbnail = $thumbnailUrl;
        $product->description = $request->description;
        $product->collectionId = $request->collectionId;
        $product->color = $request->color;
        $product->image = $string;
        $product->save();

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $thumbnailFile = $request->file('thumbnail');
        $thumbnailUrl = CloudinaryService::uploadImage($thumbnailFile);

        $imageUrls = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageUrl = CloudinaryService::uploadImage($image);
                $imageUrls[] = $imageUrl;
            }
        }

        $string = implode(", ", $imageUrls);

        $product->name = $request->name;
        $product->thumbnail = $thumbnailUrl;
        $product->description = $request->description;
        $product->collectionId = $request->collectionId;
        $product->color = $request->color;
        $product->image = $string;
        $product->save();
        return response()->json($product, 200);
    }

    public function getByCollectionId($id)
    {
        $products = Product::where('collectionId', $id)->get();
        return response()->json($products);
    }

    public function searchByName(Request $request)
    {
        $name = $request->input('name'); // Lấy tên từ request

        // Nếu tên không tồn tại, trả về một response thông báo lỗi
        if (!isset($name) || empty($name)) {
            return response()->json(['message' => 'Name is required'], 400);
        }

        // Tìm kiếm sản phẩm theo tên
        $products = Product::where('name', 'like', '%' . $name . '%')->get();

        return response()->json($products);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
