<?php
namespace App\Http\Controllers;
use App\Models\Discipline;
use Illuminate\Http\Request;


class DisciplineController extends Controller {
    public function __construct() { $this->middleware(['auth', 'admin']); }
    public function index() { return view('disciplines.index', ['disciplines' => Discipline::all()]); }
    public function create() { return view('disciplines.create'); }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        Discipline::create($request->only('name'));
        return redirect()->route('disciplines.index');
    }
    // Add edit, update, destroy as needed
}