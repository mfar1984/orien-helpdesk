@extends('layouts.app')

@section('title', 'Edit User')

@section('breadcrumb')
    <x-breadcrumb :items="[
        ['label' => 'Platform Settings'],
        ['label' => 'Users', 'url' => route('settings.users')],
        ['label' => 'Edit', 'active' => true]
    ]" />
@endsection

@section('content')
<form action="{{ route('settings.users.update', $user) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="bg-white border border-gray-200">
        <!-- Page Header -->
        <div class="px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Edit User</h2>
                <p class="text-xs text-gray-500 mt-0.5">Update user information</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('settings.users') }}" 
                   class="inline-flex items-center gap-2 px-3 text-gray-700 text-xs font-medium rounded border border-gray-300 hover:bg-gray-50 transition"
                   style="min-height: 32px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">arrow_back</span>
                    BACK
                </a>
                <button type="submit"
                   class="inline-flex items-center gap-2 px-3 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition"
                   style="min-height: 32px;">
                    <span class="material-symbols-outlined" style="font-size: 14px;">save</span>
                    UPDATE
                </button>
            </div>
        </div>
    </div>

    <!-- Account Information Section -->
    <div class="bg-white border border-gray-200 mt-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-600" style="font-size: 18px;">account_circle</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Account Information</h3>
                    <p class="text-xs text-gray-500">Login credentials and role assignment</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Email Address <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="user@example.com"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                    @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Username <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="username" required value="{{ old('username', $user->username) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="username"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Password <span class="text-gray-400">(leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="••••••••"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Confirm Password
                    </label>
                    <input type="password" name="password_confirmation"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="••••••••"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Role <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="role_id" required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Status <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="status" required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Owner Information Section -->
    <div class="bg-white border border-gray-200 mt-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-600" style="font-size: 18px;">person</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Owner Information</h3>
                    <p class="text-xs text-gray-500">Personal details of the user</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        First Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="first_name" required value="{{ old('first_name', $user->first_name) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="John"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Last Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="last_name" required value="{{ old('last_name', $user->last_name) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="Doe"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Phone Number
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="+60 12-345 6789"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Mobile Number
                    </label>
                    <input type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="+60 12-345 6789"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div class="md:col-span-2">
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Address
                    </label>
                    <textarea name="address" rows="2"
                              style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; resize: vertical; outline: none; box-sizing: border-box;"
                              placeholder="Street address"
                              onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">{{ old('address', $user->address) }}</textarea>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        City
                    </label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="Kuala Lumpur"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        State
                    </label>
                    <input type="text" name="state" value="{{ old('state', $user->state) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="Selangor"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Postcode
                    </label>
                    <input type="text" name="postcode" value="{{ old('postcode', $user->postcode) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="50000"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Country
                    </label>
                    <select name="country"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="">Select Country</option>
                        <option value="MY" {{ old('country', $user->country) == 'MY' ? 'selected' : '' }}>Malaysia</option>
                        <option value="SG" {{ old('country', $user->country) == 'SG' ? 'selected' : '' }}>Singapore</option>
                        <option value="ID" {{ old('country', $user->country) == 'ID' ? 'selected' : '' }}>Indonesia</option>
                        <option value="TH" {{ old('country', $user->country) == 'TH' ? 'selected' : '' }}>Thailand</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Information Section -->
    <div class="bg-white border border-gray-200 mt-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-600" style="font-size: 18px;">business</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Company Information</h3>
                    <p class="text-xs text-gray-500">Organization details (optional)</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Company Name
                    </label>
                    <input type="text" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="Company Sdn Bhd"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Registration Number
                    </label>
                    <input type="text" name="company_registration" value="{{ old('company_registration', $user->company_registration) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="123456-X"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Company Phone
                    </label>
                    <input type="tel" name="company_phone" value="{{ old('company_phone', $user->company_phone) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="+60 3-1234 5678"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Company Email
                    </label>
                    <input type="email" name="company_email" value="{{ old('company_email', $user->company_email) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="info@company.com"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div class="md:col-span-2">
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Company Address
                    </label>
                    <textarea name="company_address" rows="2"
                              style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; resize: vertical; outline: none; box-sizing: border-box;"
                              placeholder="Company street address"
                              onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">{{ old('company_address', $user->company_address) }}</textarea>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Website
                    </label>
                    <input type="url" name="company_website" value="{{ old('company_website', $user->company_website) }}"
                           style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; outline: none; box-sizing: border-box;"
                           placeholder="https://www.company.com"
                           onfocus="this.style.borderColor='#3b82f6'" onblur="this.style.borderColor='#d1d5db'">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 500; color: #374151; margin-bottom: 6px; font-family: Poppins, sans-serif;">
                        Industry
                    </label>
                    <select name="industry"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-family: Poppins, sans-serif; font-size: 12px; color: #1f2937; background-color: #ffffff; outline: none; cursor: pointer; box-sizing: border-box;">
                        <option value="">Select Industry</option>
                        <option value="technology" {{ old('industry', $user->industry) == 'technology' ? 'selected' : '' }}>Technology</option>
                        <option value="finance" {{ old('industry', $user->industry) == 'finance' ? 'selected' : '' }}>Finance</option>
                        <option value="healthcare" {{ old('industry', $user->industry) == 'healthcare' ? 'selected' : '' }}>Healthcare</option>
                        <option value="education" {{ old('industry', $user->industry) == 'education' ? 'selected' : '' }}>Education</option>
                        <option value="retail" {{ old('industry', $user->industry) == 'retail' ? 'selected' : '' }}>Retail</option>
                        <option value="manufacturing" {{ old('industry', $user->industry) == 'manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                        <option value="other" {{ old('industry', $user->industry) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
