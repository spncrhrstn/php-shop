<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Validator;

class BooksApiController extends Controller
{
    //Gets all books
    public function index(){
        return response()->json(Book::get(), 200);
    }

    //Gets a specific book
    public function show($id){
        $book = Book::with('genres')->findOrFail($id);
        if(is_null($book)){
            return response()->json(null, 404);
        }
        $response['book'] = $book;
        $response['genres'] = $book->genres;
        return response()->json($response, 200);
    }

    //Stores a new book
    public function store(Request $request){
        $rules = [
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'description' => 'required|max:500',
            'price' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $book = Book::create($request->all());
        return response()->json($book, 201);
    }

    //Updates a book
    public function update(Request $request, Book $book){
        $book->update($request->all());
        return response()->json($book, 200);
    }

    //Deletes a book
    public function delete(Request $request, Book $book){
        $book->delete();
        return response()->json(null, 204);
    }

    public function storebook(){
        return response()->json(null, 405);
    }

    //handle errors
    public function errors(){
        return response()->json(['msg' => 'An error occurred'], 501);
    }

    public function genres(Request $request, Book $book){
        $genres = $book->genres;
        return response()->json($genres, 200);
    }
}
