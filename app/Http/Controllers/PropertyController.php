<?php

namespace LaraDev\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaraDev\Property;

class PropertyController extends Controller
{
    //
    public function index()
    {

        //$properties = DB::select("SELECT * FROM properties");
        $properties = Property::all();

        return view('property.index')->with('properties', $properties);
    }

    public function create()
    {
        return view('property.create');
    }

    public function store(Request $request)
    {

        $propertySlug = $this->setName($request->title);

        /*        $property = array(
                    $request->title,
                    $propertySlug,
                    $request->description,
                    $request->rental_price,
                    $request->sale_price
                );

                DB::insert("INSERT INTO properties (title, name, description, rental_price, sale_price) VALUES
                                    (?,?,?,?,?)", $property);*/

        $property = array(
            'title' => $request->title,
            'name' => $propertySlug,
            'description' => $request->description,
            'rental_price' => $request->rental_price,
            'sale_price' => $request->sale_price
        );

        Property::create($property);

        return redirect()->action('PropertyController@index');
    }

    public function show($name)
    {
        //$property = DB::select("SELECT * FROM properties WHERE name = ?", array($name));
        $property = Property::where('name', $name)->get();

        //ou
        //$property = DB::select("SELECT * FROM properties WHERE id = ?", [$id]);

        if (!empty($property)) {
            return view('property.show')->with('property', $property);
        } else {
            return redirect()->action('PropertyController@index');
        }
    }

    public function edit($name)
    {
        //$property = DB::select("SELECT * FROM properties WHERE name = ?", [$name]);
        $property = Property::where('name', $name)->get();

        if (!empty($property)) {
            return view('property.edit')->with('property', $property);

        } else {
            return redirect()->action('PropertyController@index');
        }
    }

    public function update(Request $request, $id)
    {
        /*        var_dump($request->description);
                exit;*/
        $propertySlug = $this->setName($request->title);

        /*$property = [
            $request->title,
            $propertySlug,
            $request->description,
            $request->rental_price,
            $request->sale_price,
            $id
        ];

        DB::update("UPDATE properties SET title = ?, name = ?, description = ?, rental_price = ?, sale_price = ?
                            WHERE id = ?", $property);*/

        $property = Property::find($id);

        $property->title = $request->title;
        $property->name = $propertySlug;
        $property->description = $request->description;
        $property->rental_price = $request->rental_price;
        $property->sale_price = $request->sale_price;

        $property->save();

        return redirect()->action('PropertyController@index');
    }

    public function destroy($name)
    {
        //$property = $property = DB::select("SELECT * FROM properties WHERE name = ?", [$name]);
        $property = Property::where('name', $name)->get();

        if (!empty($property)) {
            DB::delete("DELETE FROM properties WHERE name = ?", [$name]);
        }

        return redirect()->action('PropertyController@index');
    }

    //URL amigavel
    private function setName($name)
    {
        $propertySlug = str_slug($name);

        //$properties = DB::select("SELECT * FROM properties");
        $properties = Property::all();

        $t = 0;
        foreach ($properties as $property) {
            if (str_slug($propertySlug) === $propertySlug) {
                $t++;
            }
        }

        if ($t > 0) {
            $propertySlug = $propertySlug . '-' . $t;
        }
        return $propertySlug;
    }
}
