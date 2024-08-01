<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;
use App\Enums\StatusEnum;
use Illuminate\Validation\Rule;

class PetController extends Controller
{
    public function get(){
        $statuses = implode('&', array_map(function ($item){
            return "status={$item->name}";
        }, StatusEnum::cases()));

        $res = Http::get("https://petstore.swagger.io/v2/pet/findByStatus?$statuses");

        $data = [
            "pets" => $res->object()
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
            'status' => ['required',Rule::enum(StatusEnum::class)],
        ]);

        $data = [];
        $data['name'] = $request->get("name"); // Wymagane.
        $data['photoUrls'] = $request->get("photoUrls", []); // Samo pole jest wymagane, ale nikt nie powiedział że nie może przyjść pusta tablica.
        if($request->get('category')) $data['category'] = ['name' => $request->get('category')]; // Niewymagane.
        if(count($request->get('tags', [])) >= 1) $data['tags'] = array_map(function($item) {
            return ['name' => $item];
        }, $request->get('tags', [])); // Niewymagane
        $data['status'] = $request->get('status', StatusEnum::available); // Wymagane

        try{
            $res = Http::post('https://petstore.swagger.io/v2/pet', $data);

            if(!$res->ok()) throw new \RuntimeException($res->body());
        }catch(Throwable $e){
            report($e);
            return response()->view('errro');
        }
        
        return redirect()->route('dashboard')->with('success', 'Pet created!');
    }

    public function edit(Request $request){
        $request->validate([
            'id' => 'required|numeric',
            'name' => 'required|min:1|max:255|string',
            'photoUrls' => 'array',
            'photoUrls.*' => 'string|min:1|max:255|url:http,https',
            'category' => 'string|min:1|max:255|nullable',
            'tags' => 'array',
            'tags.*' => 'min:1|max:255',
            'status' => ['required',Rule::enum(StatusEnum::class)],
        ]);

        $data = [];
        $data['id'] = $request->get("id"); // Wymagane.
        $data['name'] = $request->get("name"); // Wymagane.
        $data['photoUrls'] = $request->get("photoUrls", []); // Samo pole jest wymagane, ale nikt nie powiedział że nie może przyjść pusta tablica.
        if($request->get('category')) $data['category'] = ['name' => $request->get('category')]; // Niewymagane.
        if(count($request->get('tags', [])) >= 1) $data['tags'] = array_map(function($item) {
            return ['name' => $item];
        }, $request->get('tags', [])); // Niewymagane
        $data['status'] = $request->get('status', StatusEnum::available); // Wymagane

        try{
            $res = Http::put('https://petstore.swagger.io/v2/pet', $data);

            if(!$res->ok()) throw new \RuntimeException($res->body());
        }catch(Throwable $e){
            report($e);
            return response()->view('errro');
        }

        return redirect()->route('dashboard')->with('success', 'Pet edited!');
    }

    public function delete(Request $request){
        $request->validate([
            'id' => 'required|numeric'
        ]);
        
        try{
            $res = Http::delete("https://petstore.swagger.io/v2/pet/{$request->get('id')}");

            if(!$res->ok()) throw new \RuntimeException($res->body());
        }catch(Throwable $e){
            report($e);
            return response()->view('errro');
        }

        return redirect()->route('dashboard')->with('success', 'Pet edited!');
    }
}
