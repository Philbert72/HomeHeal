<?php

namespace App\Http\Controllers;

use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // <--- MANDATORY IMPORT
use Illuminate\Routing\Controller; // Ensure we extend the base Controller

class ProtocolController extends Controller // Make sure you extend Controller, not just BaseController
{
    // Use the trait to gain access to the $this->authorize() method
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage. (Handles POST /protocols)
     */
    public function store(Request $request)
    {
        // 1. AUTHORIZATION CHECK: This calls ProtocolPolicy@create
        $this->authorize('create', Protocol::class);

        // 2. Validation (Minimal for testing)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // 3. Creation
        $protocol = Protocol::create([
            'therapist_id' => Auth::id(), // Assign current user (Therapist) as owner
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
        ]);
        
        // 4. Success Response: Redirect to the new protocol's page (HTTP 302)
        return redirect()->route('protocols.show', $protocol);
    }

    /**
     * Display the specified resource. (Handles GET /protocols/{protocol})
     */
    public function show(Protocol $protocol)
    {
        // AUTHORIZATION CHECK: This calls ProtocolPolicy@view
        $this->authorize('view', $protocol);

        // Success Response (HTTP 200) for passing the test
        return response('Protocol View', 200); 
    }

    /**
     * Update the specified resource in storage. (Handles PUT /protocols/{protocol})
     */
    public function update(Request $request, Protocol $protocol)
    {
        // AUTHORIZATION CHECK: This calls ProtocolPolicy@update
        $this->authorize('update', $protocol);

        // Validation (Minimal for testing)
        $request->validate(['title' => 'required']); 
        
        // Update the model
        $protocol->update($request->only('title', 'description'));

        // Success Response: Redirect (HTTP 302)
        return redirect()->route('protocols.show', $protocol);
    }

    // Index, create, edit, and destroy methods are omitted for brevity but should be defined
    // ...
}