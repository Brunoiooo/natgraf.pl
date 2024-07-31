<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class PetController extends Controller
{
    public function get(){
        $data = [
            "test" => 'xd'
        ];

        return view('dashboard', $data);
    }

    public function create(Request $request){
        $request->validate([
            'name' => 'required|min:1|max:255|string',
            'photoUrls' => 'array',
            'photoUrls.*' => 'string|min:1|max:255|url:http,https',
            'category' => 'string|min:1|max:255|nullable',
            'tags' => 'array',
            'tags.*' => 'min:1|max:255',
            'status' => 'min:1|max:255',
        ]);

        $data = [];
        $data['name'] = $request->get("name"); // Wymagane.
        $data['photoUrls'] = $request->get("photoUrls", []); // Samo pole jest wymagane, ale nikt nie powiedział że nie może przyjść pusta tablica.
        if($request->get('category')) $data['category'] = ['name' => $request->get('category')]; // Niewymagane.
        if(count($request->get('tags', [])) >= 1) $data['tags'] = array_map(function($item) {
            return ['name' => $item];
        }, $request->get('tags', [])); // Niewymagane
        if($request->get('status')) $data['status'] = $request->get('status'); // Niewymagane

        try{
            $res = Http::post('https://petstore.swagger.io/v2/pet', $data);

            if(!$res->ok()) throw new \RuntimeException($res->body());
        }catch(Throwable $e){
            report($e);
            return response()->view('errro');
        }
        
        return redirect()->route('dashboard')->with('success', 'Pet created!');
    }
}
