<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\DoctorDetail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Session;

/**
 * Class UserController
 * @package App\Http\Controllers
 * @category Controller
 */
class UserController extends Controller
{
    /**
     * load constructor method
     *
     * @access public
     * @return void
     */
    function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-update|user-delete', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource
     *
     * @access public
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->export)
            return $this->doExport($request);

        $users = $this->filter($request)->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Performs exporting
     *
     * @param Request $request
     * @return void
     */
    private function doExport(Request $request)
    {
        return Excel::download(new UserExport($request), 'Users.xlsx');
    }

    /**
     * Filter function
     *
     * @param Request $request
     * @return Illuminate\Database\Eloquent\Builder
     */
    private function filter(Request $request)
    {
        $query = User::orderBy('id', 'DESC');

        if ($request->id)
            $query->where('id', $request->id);

        if ($request->name)
            $query->where('name', 'like', $request->name . '%');

        if ($request->email)
            $query->where('email', 'like', $request->email . '%');

        return $query;
    }

    /**
     * Show the form for creating a new resource
     *
     * @access public
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $staffRoles = Role::where('role_for', '1')->pluck('name', 'name')->all();
        $userRoles = Role::where('role_for', '0')->pluck('name', 'name')->all();
        $companies = auth()->user()->companies()->get();
        foreach ($companies as $company) {
            $company->setSettings();
        }
        return view('users.create', compact('staffRoles', 'userRoles', 'companies'));
    }

    /**
     * Store a newly created resource in storage
     *
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:password_confirmation',
            'status' => 'required',
            'role_for' => 'required',
            'photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg']
        ]);
// dd($request);
        $logoUrl = "";
        if ($request->hasFile('photo')) {
            $logo = $request->photo;
            $logoNewName = time() . $logo->getClientOriginalName();
            $logo->move('lara/user', $logoNewName);
            $logoUrl = 'lara/user/' . $logoNewName;
        }

        $input = array();

        if ($request->role_for == "0") //staff
        {
            $roles = $request->user_roles;
            $companies = $request->staff_company;
            $input['company_id'] = $companies;
        }

        if ($request->role_for == "1") //user
        {
            $roles = $request->staff_roles;

            $companies = $request->user_company; //array
        }

        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['password'] = bcrypt($request->password);
        $input['phone'] = $request->phone;
        $input['address'] = $request->address;
        $input['status'] = $request->status;
        $input['company_id'] = 1;
        $input['photo'] = $logoUrl;
        $user = User::create($input);
        $user->assignRole($roles);
        if ($request->role_for == "1") //staff
        {
            // Attach company
            $user->companies()->attach($companies);
        }
        if ($request->role_for == "0") //user
        {
            if (isset($companies) && !empty($companies)) {
                foreach ($companies as $company) {
                    $user->companies()->attach($company);
                }
            }
        }


        $doctorData = [
            'hospital_department_id' => 1, // Replace with actual department ID
            'user_id' => $user->id,               // Replace with actual user ID
            'specialist' => '...', // Replace with actual specialist
            'designation' => '...', // Replace with actual designation
            'biography' => '...', // Replace with actual biography
            'commission' => $request->commission          // Replace with actual commission percentage or amount
        ];

        $doctorDetail = DoctorDetail::create($doctorData);

        return redirect()->route('users.index')->with('success', trans('users.user created successfully'));
    }

    /**
     * Store a newly created resource in storage
     *
     * @access public
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource
     *
     * @access public
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roleName = $user->getRoleNames();
        $roleFor = Role::findByName($roleName['0']);

        $cId = array();
        $selectedCompanies = $user->companies()->select('id')->get();
        foreach ($selectedCompanies as $companies) {
            $cId[] = $companies->id;
        }
        $cIdStd = implode(",", $cId);

        $staffRoles = Role::where('role_for', '1')->pluck('name', 'name')->all();
        $userRoles = Role::where('role_for', '0')->pluck('name', 'name')->all();
        $companies = auth()->user()->companies()->get();

        foreach ($companies as $company) {
            $company->setSettings();
        }
        $doctorDetail = DoctorDetail::where('user_id', $user->id)->first();

// dd($roleFor->name);
        return view('users.edit', compact('user', 'roleFor', 'staffRoles', 'userRoles', 'companies', 'cIdStd', 'doctorDetail'));
    }


    /**
     * Remove the specified resource from storage
     *
     * @param $id
     * @access public
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();
            DB::table("model_has_roles")->where('model_id', $user->id)->delete();
            DB::table("user_companies")->where('user_id', $user->id)->delete();
            DB::commit();
            return redirect()->route('users.index')->with('success', trans('users.user deleted successfully'));
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->route('users.index')->with('error', $e);
        }
    }

    /**
     * Methot to custom update
     *
     * @access public
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        // dd($request);
        // Validate the request data
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|same:confirm_password', // Make password nullable and ensure it matches confirm_password
            'status' => 'required',
            'role_for' => 'required',
            'photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg']
        ]);
        
        // dd($request);
        // Handle file upload for photo
        $logoUrl = $user->photo; // Use existing photo if no new photo is uploaded
        if ($request->hasFile('photo')) {
            $logo = $request->photo;
            $logoNewName = time() . $logo->getClientOriginalName();
            $logo->move('lara/user', $logoNewName);
            $logoUrl = 'lara/user/' . $logoNewName;
        }

        // Prepare input data
        $input = $request->only(['name', 'email', 'phone', 'address', 'status']);
        $input['photo'] = $logoUrl; // Update photo if a new one was uploaded

        // Encrypt and update the password if a new one is provided
        if (!empty($request->password)) {
            $input['password'] = bcrypt($request->password);
        } else {
            // Keep the current password if not updated
            $input['password'] = $user->password;
        }

        // Update the user record
        $user->update($input);

        // Handle roles and company associations based on role_for field
        if ($request->role_for == "1") { // Staff
            $roles = $request->staff_roles;
            $companies = $request->user_company; // Array of company IDs
        } else { // User
            $roles = $request->user_roles;
            $companies = $request->staff_company;
        }

        // Clear existing roles and reassign
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        $user->assignRole($roles);

        // Handle company associations
        DB::table('user_companies')->where('user_id', $user->id)->delete();
        if (!empty($companies)) {
            foreach ($companies as $company) {
                $user->companies()->attach($company);
            }
        }

       
        // Redirect with success message
        return redirect()->route('users.index')->with('success', trans('users.user updated successfully'));
    }

}
