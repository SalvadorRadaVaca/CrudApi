<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Carbon\Carbon;

class BookController extends Controller{

    public function index() {
        $bookData = Book::all();
        return response()->json($bookData);
    }

    public function save(Request $request) {
        $bookData = new Book;

        if($request->hasFile('image')) {
            $originalFileName = $request->file('image')->getClientOriginalName();
            $newName = Carbon::now()->timestamp."_".$originalFileName;
            $destinationFolder = './upload/';
            $request->file('image')->move($destinationFolder, $newName);
            $bookData->title = $request->title;
            $bookData->image = ltrim($destinationFolder,'.').$newName;
            $bookData->save();
        }

        return response()->json($newName);
    }

    public function show($id) {
        $bookData = new Book;
        $dataFound = $bookData->find($id);
        return response()->json($dataFound);
    }

    public function delete($id) {
        $bookData = Book::find($id);

        if($bookData){
            $filePath = base_path('public').$bookData->image;

            if(file_exists($filePath)) {
                unlink($filePath);
            }
            $bookData->delete();
        }

        return response()->json("Register deleted");
    }

    public function update(Request $request, $id) {
        $bookData = Book::find($id);

        if($request->hasFile('image')) {

            if($bookData){
                $filePath = base_path('public').$bookData->image;
    
                if(file_exists($filePath)) {
                    unlink($filePath);
                }
                $bookData->delete();
            }
            
            $originalFileName = $request->file('image')->getClientOriginalName();
            $newName = Carbon::now()->timestamp."_".$originalFileName;
            $destinationFolder = './upload/';
            $request->file('image')->move($destinationFolder, $newName);
            $bookData->image = ltrim($destinationFolder,'.').$newName;
            $bookData->save();
        }

        if($request->input('title')) {
            $bookData->title = $request->input('title');
        }
        $bookData->save();

        return response()->json("Data updated");
    } 
}