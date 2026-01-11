<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Concerns\HandlesAuthorization;

class UserController extends Controller
{
    use HandlesAuthorization;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('doctor')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        // Get doctors without users
        $doctorsWithoutUsers = Doctor::whereDoesntHave('user')->get();
        
        return view('users.create', compact('doctorsWithoutUsers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'doctor_id' => $validated['doctor_id'] ?? null,
            'is_admin' => empty($validated['doctor_id']), // Admin if no doctor_id
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load('doctor');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        // Get doctors without users, plus the current user's doctor if exists
        $doctorsWithoutUsers = Doctor::where(function($query) use ($user) {
            $query->whereDoesntHave('user')
                  ->orWhere('id', $user->doctor_id);
        })->get();

        return view('users.edit', compact('user', 'doctorsWithoutUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Update doctor_id
        $user->doctor_id = $validated['doctor_id'] ?? null;
        
        // Update is_admin - Admin if no doctor_id, otherwise false
        $user->is_admin = empty($validated['doctor_id']);

        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
