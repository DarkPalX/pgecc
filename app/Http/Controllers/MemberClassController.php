<?php

namespace App\Http\Controllers;

use App\Models\MemberClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberClassController extends Controller
{
    public function index()
    {
        $memberClasses = MemberClass::orderBy('created_at')->get();

        return view('pages.member-classes.index', compact('memberClasses'));
    }

    public function store(Request $request)
    {
        MemberClass::create($this->validated($request));

        return back()->with('success', 'Member class created successfully.');
    }

    public function update(Request $request, MemberClass $memberClass)
    {
        $memberClass->update($this->validated($request, $memberClass));

        return back()->with('success', 'Member class updated successfully.');
    }

    public function destroy(MemberClass $memberClass)
    {
        $memberClass->delete();

        return back()->with('success', 'Member class deleted successfully.');
    }

    private function validated(Request $request, ?MemberClass $memberClass = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('member_classes', 'name')->ignore($memberClass)],
            'carenderia_limit' => ['required', 'numeric', 'min:0'],
            'consumer_limit' => ['required', 'numeric', 'min:0'],
            'maximum_loan' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['name'] = strtoupper(trim($validated['name']));

        return $validated;
    }
}
