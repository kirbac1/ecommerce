<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Cache::remember('categories', 120, function() {
            return Category::get()->toTree()->keyBy('id');
        });

        return $categories;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // To make it a child of another node, add parent_id to the request.
        Category::create($request->all());
        Cache::forget('categories');
        return Category::all()->toTree()->keyBy('id');
    }

    public function addChild(Request $request)
    {
        $parent = Category::find($request->get('parent'));
        $category = Category::create(['name' => $request->name]);
        Cache::forget('categories');

        if ($parent) {
            // Make it a child
            $category->prependToNode($parent)->save();
            return Category::all()->toTree()->keyBy('id');
        } else {
            // Root
            Category::create(['name' => $request->name]);
            return Category::all()->toTree()->keyBy('id');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category->descendants()->get()->toTree($category->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // $category->update($request->all());
        $category->update(['name' => $request->name]);
        Cache::forget('categories');
        return Category::findOrFail($category->id);
    }

    public function moveCategory(Request $request, Category $category)
    {
        $parent = $request->get('parent', null);
        $left = $request->get('left', null);
        $right = $request->get('right', null);
        $depth = $request->get('depth', null);
        if ($right) {
            $rightcat = Category::findOrFail($right);
            $category->insertBeforeNode($rightcat);
//            \Log::info("I am " . $category->id . ", I've got $left on the left and $right on the right. My parent is $parent and my depth is $depth. Moving to the left.");
        } elseif ($left) {
            // We have a sibling on the left
            $leftcat = Category::findOrFail($left);
//            \Log::info("I am " . $category->id . ", I've got $left on the left and $right on the right. My parent is $parent and my depth is $depth. Moving to the right.");
            $category->insertAfterNode($leftcat);
        } elseif ($parent) {
            // We don't have siblings, do we have a parent?
            $parentcat = Category::findOrFail($parent);
//            \Log::info("I am " . $category->id . ", I've got $left on the left and $right on the right. My parent is $parent and my depth is $depth. Making a child of $parent.");
            $category->makeLastChildOf($parentcat);
        }
        Cache::forget('categories');
        return Category::all()->toTree()->keyBy('id');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->deletable) $category->delete();
        Cache::forget('categories');
        return Category::all()->toTree()->keyBy('id');
    }
}
