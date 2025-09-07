<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enterprise;

class EnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enterprises = Enterprise::all();
        return view('enterprises.index', compact('enterprises'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('enterprises.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $enterprise = new Enterprise();
        $enterprise->name = $request->input('name');
        if ($request->filled('description')) {
            $enterprise->description = $request->input('description');
        }

        if ($request->filled('location')) {
            $enterprise->location = $request->input('location');
        }

        $enterprise->save();
        return redirect()->route('enterprises.index')->with('success', 'Підприємство успішно додано.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $enterprise = Enterprise::findOrFail($id);
        return view('enterprises.show', compact('enterprise'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $enterprise = Enterprise::findOrFail($id);
        return view('enterprises.edit', compact('enterprise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $enterprise = Enterprise::findOrFail($id);
        $enterprise->name = $request->input('name');
        $enterprise->description = $request->input('description');
        $enterprise->location = $request->input('location');
        $enterprise->save();

        return redirect()->route('enterprises.index')->with('success', 'Підприємство успішно оновлено.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $enterprise = Enterprise::findOrFail($id);
        $enterprise->delete();

        return redirect()->route('enterprises.index')->with('success', 'Підприємство успішно видалено.');
    }

    /*
      protected $table = 'enterprise';
    protected $fillable = [
        'name',
        'description',
        'location',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_enterprise')
                    ->withTimestamps();
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'enterprise_department')
                    ->withTimestamps();
    }

     */
}
