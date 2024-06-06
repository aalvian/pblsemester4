<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class SwitchRoleController extends Controller
{
    //
    public function __invoke(Role $role)
    {
        abort_unless(auth()->user()->hasRole($role), 404);
 
        auth()->user()->update(['current_role_id' => $role->id]);
 
        return to_route('dashboard'); // Replace this with your own home route
    }
}
