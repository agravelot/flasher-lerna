<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\GoldenBookRequest;
use App\Models\GoldenBookPost;
use App\Repositories\Contracts\GoldenBookRepository;

class GoldenBookController extends Controller
{

    /**
     * @var GoldenBookRepository
     */
    private $repository;

    public function __construct(GoldenBookRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goldenBooksPosts = GoldenBookPost::all();

        return view('goldenbook.index', ['goldenBooksPosts' => $goldenBooksPosts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('goldenbook.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GoldenBookRequest $request
     * @return
     */
    public function store(GoldenBookRequest $request)
    {
        $validated = $request->validated();
        $this->repository->create($validated);
        return redirect()->route('goldenbook.index')->withSuccess('Your message has been added to the golden book');
    }
}
