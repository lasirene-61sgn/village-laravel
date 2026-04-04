<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Helper to get the ID of the currently authenticated admin.
     */
    private function getCurrentAdminId()
    {
        return Auth::guard('admin')->id();
    }
    
    /**
     * Get field permissions for the current admin user
     */
    private function getFieldPermissions()
    {
        return Auth::guard('admin')->user()->customer_field_permissions ?? [];
    }
    
    /**
     * Display a listing of the resource (Index).
     */
    public function index()
    {
        $adminId = $this->getCurrentAdminId();
        
        // CRITICAL FIX: Only fetch customers created by the current admin (paginated)
        // Sort by ID ascending to show oldest customers first (order of creation)
        $customers = Customer::with('village')
                            ->where('admin_id', $adminId)
                            ->orderBy('id', 'asc')
                            ->paginate(10); // Show 10 customers per page
        
        // Get all customers for printing (without pagination)
        // Limit to 5000 records to balance usability with performance
        // Sort by ID ascending to show oldest customers first (order of creation)
        $allCustomers = Customer::with('village')
                              ->where('admin_id', $adminId)
                              ->orderBy('id', 'asc')
                              ->limit(5000)
                              ->get();

        // Get field permissions
        $fieldPermissions = $this->getFieldPermissions();
        
        return view('admin.customer.index', compact('customers', 'fieldPermissions', 'allCustomers'));
    }

    /**
     * Show the form for creating a new resource (Create).
     */
    public function create()
    {
        $adminId = $this->getCurrentAdminId();
        
        // Fetch only villages created by the current admin (Data Isolation)
        $villages = Village::where('admin_id', $adminId)->pluck('name', 'id');
        
        // Get field permissions
        $fieldPermissions = $this->getFieldPermissions();
        
        return view('admin.customer.create', compact('villages', 'fieldPermissions'));
    }

    /**
     * Store a newly created resource in storage (Store).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'father_name' => 'nullable|string|max:100',
            'gotra' => 'nullable|string|max:100',
            'label_name' => 'nullable|string|max:100',
            'village_id' => 'nullable|exists:villages,id',
            'district' => 'nullable|string|max:100',
            'ms_firm_name' => 'nullable|string|max:100',
            'dno' => 'nullable|string|max:50',
            'street_road' => 'nullable|string|max:150',
            'address2' => 'nullable|string|max:150',
            'city' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'mobile' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'business_type' => 'nullable|string|max:100',
            'business_name' => 'nullable|string|max:100',
            'product_service' => 'nullable|string|max:100',
            'office_address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'education' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:10',
            'hobbies' => 'nullable|string|max:255',
            'native_place' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        // Safety check: Ensure the selected village belongs to the current admin
        if ($request->filled('village_id')) {
             $village = Village::find($request->village_id);
             if (!$village || $village->admin_id !== $this->getCurrentAdminId()) {
                 return redirect()->back()->withInput()->withErrors(['village_id' => 'Invalid village selection.']);
             }
         }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store new image
            $imagePath = $request->file('image')->store('customer_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Create the customer, injecting the current admin_id
        Customer::create(array_merge($validatedData, [
            'admin_id' => $this->getCurrentAdminId(),
        ]));

        return redirect()->route('admin.customer.index')->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified resource (Show).
     */
    public function show(Customer $customer)
    {
        $adminId = $this->getCurrentAdminId();
        
        // CRITICAL FIX: Enforce ownership check before showing
        if ($customer->admin_id !== $adminId) {
            abort(403, 'Unauthorized access: You can only view customers you created.');
        }

        // Load the village relationship
        $customer->load('village');
        
        // Get field permissions
        $fieldPermissions = $this->getFieldPermissions();
        
        return view('admin.customer.show', compact('customer', 'fieldPermissions'));
    }

    /**
     * Show the form for editing the specified resource (Edit).
     */
    public function edit(Customer $customer) 
    {
        $adminId = $this->getCurrentAdminId();
        
        // CRITICAL FIX: Enforce ownership check before editing
        if ($customer->admin_id !== $adminId) {
            abort(403, 'Unauthorized access: You can only edit customers you created.');
        }

        // 1. Get the IDs of villages created by the current admin.
        $allowedVillageIds = Village::where('admin_id', $adminId)->pluck('id')->toArray();
        
        // 2. Include the customer's currently saved village_id 
        if ($customer->village_id) {
            $allowedVillageIds[] = $customer->village_id;
            $allowedVillageIds = array_unique($allowedVillageIds);
        }
        
        // 3. Fetch the required villages using the collected IDs.
        $villages = Village::whereIn('id', $allowedVillageIds)->pluck('name', 'id');
        
        // Get field permissions
        $fieldPermissions = $this->getFieldPermissions();
        
        return view('admin.customer.edit', compact('customer', 'villages', 'fieldPermissions'));
    }

    /**
     * Update the specified resource in storage (Update).
     */
    public function update(Request $request, Customer $customer)
    {
        $adminId = $this->getCurrentAdminId();
        
        // CRITICAL FIX: Enforce ownership check before updating
        if ($customer->admin_id !== $adminId) {
            abort(403, 'Unauthorized action: You can only update customers you created.');
        }
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'father_name' => 'nullable|string|max:100',
            'gotra' => 'nullable|string|max:100',
            'label_name' => 'nullable|string|max:100',
            'village_id' => 'nullable|exists:villages,id',
            'district' => 'nullable|string|max:100',
            'ms_firm_name' => 'nullable|string|max:100',
            'dno' => 'nullable|string|max:50',
            'street_road' => 'nullable|string|max:150',
            'address2' => 'nullable|string|max:150',
            'city' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'mobile' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'age' => 'nullable|integer|min:0|max:150',
            'gender' => 'nullable|in:male,female,other',
            'business_type' => 'nullable|string|max:100',
            'business_name' => 'nullable|string|max:100',
            'product_service' => 'nullable|string|max:100',
            'office_address' => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'anniversary_date' => 'nullable|date',
            'education' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:10',
            'hobbies' => 'nullable|string|max:255',
            'native_place' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);
        
        // Safety check: Ensure the selected village is either owned by the admin or the existing saved one
        if ($request->filled('village_id')) {
             $village = Village::find($request->village_id);
             if (!$village || ($village->admin_id !== $adminId && $village->id !== $customer->village_id)) {
                 return redirect()->back()->withInput()->withErrors(['village_id' => 'Invalid village selection or permission denied.']);
             }
         }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($customer->image && Storage::exists('public/' . $customer->image)) {
                Storage::delete('public/' . $customer->image);
            }
            
            // Store new image
            $imagePath = $request->file('image')->store('customer_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        $customer->update($validatedData);

        return redirect()->route('admin.customer.index')->with('success', 'Customer updated successfully!');
    }
    
    /**
     * Remove the specified resource from storage (Destroy).
     */
    public function destroy(Customer $customer)
    {
        $adminId = $this->getCurrentAdminId();
        
        // CRITICAL FIX: Enforce ownership check before deletion
        if ($customer->admin_id !== $adminId) {
            abort(403, 'Unauthorized action: You can only delete customers you created.');
        }
        
        $customer->delete();
        return redirect()->route('admin.customer.index')->with('success', 'Customer deleted successfully!');
    }
    
    /**
     * Show the bulk upload form
     */
    public function showBulkUploadForm()
    {
        // Get field permissions
        $fieldPermissions = $this->getFieldPermissions();
        
        // Get existing villages for the current admin
        $adminId = $this->getCurrentAdminId();
        $villages = Village::where('admin_id', $adminId)->pluck('name', 'id');
        
        return view('admin.customer.bulk-upload', compact('fieldPermissions', 'villages'));
    }
    
    /**
     * Handle bulk upload of customers from CSV file
     */
    public function bulkUpload(Request $request)
    {
        $adminId = $this->getCurrentAdminId();
        
        $request->validate([
            'excel_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);
        
        try {
            // Get the uploaded file
            $file = $request->file('excel_file');
            
            // Open the CSV file
            $handle = fopen($file->getPathname(), 'r');
            
            // Read the header row
            $header = fgetcsv($handle);
            
            // Process each row
            $successCount = 0;
            $errors = [];
            
            $rowIndex = 1; // Start at 1 because we already read the header
            
            while (($row = fgetcsv($handle)) !== FALSE) {
                $rowIndex++;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Map row data to customer fields (based on the template)
                $customerData = [
                    'name' => $row[0] ?? '',
                    'father_name' => $row[1] ?? null,
                    'gotra' => $row[2] ?? null,
                    'label_name' => $row[3] ?? null,
                    'district' => $row[4] ?? null,
                    'ms_firm_name' => $row[5] ?? null,
                    'dno' => $row[6] ?? null,
                    'street_road' => $row[7] ?? null,
                    'address2' => $row[8] ?? null,
                    'city' => $row[9] ?? null,
                    'pincode' => $row[10] ?? null,
                    'mobile' => $row[11] ?? null,
                    'whatsapp' => $row[12] ?? null,
                    'email' => $row[13] ?? null,
                    'age' => isset($row[14]) && trim($row[14]) !== '' ? (int)$row[14] : null,
                    'gender' => $row[15] ?? null,
                    'business_type' => $row[16] ?? null,
                    'business_name' => $row[17] ?? null,
                    'product_service' => $row[18] ?? null,
                    'office_address' => $row[19] ?? null,
                    'date_of_birth' => !empty($row[20]) ? date('Y-m-d', strtotime($row[20])) : null,
                    'anniversary_date' => !empty($row[21]) ? date('Y-m-d', strtotime($row[21])) : null,
                    'education' => $row[21] ?? null,
                    'occupation' => $row[22] ?? null,
                    'blood_group' => $row[23] ?? null,
                    'hobbies' => $row[24] ?? null,
                    'native_place' => $row[25] ?? null,
                    'status' => $row[26] ?? 'active',
                    'area' => $row[27] ?? null, // Area field
                ];
                
                // Handle village_id if provided in the CSV
                if (isset($row[22]) && !empty($row[22])) {
                    // Check if the village exists and belongs to the current admin
                    $village = Village::where('name', trim($row[22]))->where('admin_id', $adminId)->first();
                    
                    // If village doesn't exist, create it
                    if (!$village) {
                        $village = Village::create([
                            'name' => trim($row[22]),
                            'admin_id' => $adminId
                        ]);
                    }
                    
                    // Assign the village ID to the customer
                    $customerData['village_id'] = $village->id;
                }
                
                // Validate the data
                $validator = Validator::make($customerData, [
                    'name' => 'required|string|max:100',
                    'father_name' => 'nullable|string|max:100',
                    'gotra' => 'nullable|string|max:100',
                    'label_name' => 'nullable|string|max:100',
                    'district' => 'nullable|string|max:100',
                    'ms_firm_name' => 'nullable|string|max:100',
                    'dno' => 'nullable|string|max:50',
                    'street_road' => 'nullable|string|max:150',
                    'address2' => 'nullable|string|max:150',
                    'city' => 'nullable|string|max:100',
                    'pincode' => 'nullable|string|max:10',
                    'mobile' => 'nullable|string|max:20',
                    'whatsapp' => 'nullable|string|max:20',
                    'email' => 'nullable|email|max:100',
                    'age' => 'nullable|integer|min:0|max:150',
                    'gender' => 'nullable|in:male,female,other',
                    'business_type' => 'nullable|string|max:100',
                    'business_name' => 'nullable|string|max:100',
                    'product_service' => 'nullable|string|max:100',
                    'office_address' => 'nullable|string|max:500',
                    'date_of_birth' => 'nullable|date',
                    'anniversary_date' => 'nullable|date',
                    'education' => 'nullable|string|max:100',
                    'occupation' => 'nullable|string|max:100',
                    'blood_group' => 'nullable|string|max:10',
                    'hobbies' => 'nullable|string|max:255',
                    'native_place' => 'nullable|string|max:100',
                    'status' => 'required|in:active,inactive',
                    'area' => 'nullable|string|max:100',
// 'village_id' validation is handled separately since it's derived from village name
                ]);
                
                if ($validator->fails()) {
                    $errors[] = "Row " . $rowIndex . ": " . implode(', ', $validator->errors()->all());
                    continue;
                }
                
                // Add admin_id
                $customerData['admin_id'] = $adminId;
                
                // Create customer
                Customer::create($customerData);
                $successCount++;
            }
            
            fclose($handle);
            
            if (!empty($errors)) {
                return redirect()->back()->with('errors', $errors)->with('success_count', $successCount);
            }
            
            return redirect()->route('admin.customer.index')->with('success', "$successCount customers imported successfully!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing customers: ' . $e->getMessage());
        }
    }
}