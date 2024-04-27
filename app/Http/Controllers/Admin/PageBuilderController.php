<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageBuilderController extends Controller
{
    public function index()
    {
        $page_builders = PageBuilder::all();
        return view('admin.page-builder.index', compact('page_builders'));
    }

    public function create()
    {
        return view('admin.page-builder.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:page_builders|max:255',
            'position' => 'required',
            'description' => 'required',
        ]);

        try {
            $page_builder = new PageBuilder();
            $page_builder->title = $request->title;
            $page_builder->slug = Str::slug($request->title, "-");
            $page_builder->position = $request->position;
            $page_builder->description = $request->description;
            $page_builder->save();
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Created Successfully'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'danger',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(PageBuilder $page_builder)
    {
        return view('admin.page-builder.edit', compact('page_builder'));
    }

    public function update(PageBuilder $page_builder, Request $request)
    {
        $request->validate([
            'position' => 'required',
            'description' => 'required',
        ]);
        try {
            $page_builder->position = $request->position;
            $page_builder->description = $request->description;
            $page_builder->save();
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Updated Successfully'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'danger',
                'message' => $e->getMessage()
            ]);
        }
    }


    public function destroy(PageBuilder $page_builder)
    {
        try {
            $page_builder->delete();
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'danger',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function showDynamicPage($id)
    {
        // Assuming you have an "HtmlContent" model with a "content" attribute
        $htmlContent = PageBuilder::findOrFail($id);

        // Pass the HTML content to the Blade template
        return view('dynamic', ['htmlContent' => $htmlContent->content]);
    }
}
