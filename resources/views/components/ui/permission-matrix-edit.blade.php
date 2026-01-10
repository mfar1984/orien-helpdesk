@props([
    'permissions' => [],
])

@php
    $permissionLabels = config('permissions.labels', []);
    $permissionMatrix = config('permissions.matrix', []);
    $moduleLabels = config('permissions.modules', []);
    $moduleGroups = config('permissions.groups', []);
@endphp

<div>
    <!-- Header -->
    <div class="my-6">
        <div class="border-t border-gray-200"></div>
        <h3 class="text-sm font-semibold text-gray-900 mt-6 mb-2" style="font-family: Poppins, sans-serif;">
            Permission Matrix
        </h3>
        <p class="text-xs text-gray-500 mb-4" style="font-family: Poppins, sans-serif;">
            Set access permissions for each module in the system
        </p>
    </div>

    <!-- Permission Matrix Table -->
    <div class="overflow-x-auto border border-gray-200 rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200" style="font-size: 10px; min-width: 320px;">
                        Module
                    </th>
                    @foreach($permissionLabels as $action => $label)
                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200" style="font-size: 10px; min-width: 70px;">
                        {{ $label }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($moduleGroups as $groupName => $groupModules)
                    <!-- Group Header -->
                    <tr class="bg-gray-100">
                        <td colspan="{{ count($permissionLabels) + 1 }}" class="px-4 py-2">
                            <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider" style="font-family: Poppins, sans-serif; font-size: 10px;">
                                {{ $groupName }}
                            </span>
                        </td>
                    </tr>
                    
                    @foreach($groupModules as $module)
                        @if(isset($permissionMatrix[$module]))
                        @php $modulePermissions = $permissionMatrix[$module]; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-900 border-r border-gray-200" style="font-family: Poppins, sans-serif;">
                                <span class="pl-4">{{ $moduleLabels[$module] ?? ucfirst(str_replace('_', ' ', $module)) }}</span>
                            </td>
                            @foreach($permissionLabels as $action => $label)
                            <td class="px-3 py-3 text-center border-r border-gray-200">
                                @if(isset($modulePermissions[$action]) && $modulePermissions[$action])
                                @php
                                    $permissionKey = "{$module}.{$action}";
                                    $isChecked = is_array($permissions) && in_array($permissionKey, $permissions);
                                @endphp
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permissionKey }}"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                    {{ $isChecked ? 'checked' : '' }}
                                >
                                @else
                                <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Quick Actions -->
    <div class="flex justify-between items-center mt-4 p-3 bg-gray-50 rounded border border-gray-200">
        <div class="text-xs text-gray-600" style="font-family: Poppins, sans-serif;">
            Quick Actions:
        </div>
        <div class="flex gap-4">
            <button type="button" onclick="selectAllPermissions()" class="text-xs text-blue-600 hover:text-blue-800" style="font-family: Poppins, sans-serif;">
                Select All
            </button>
            <button type="button" onclick="clearAllPermissions()" class="text-xs text-red-600 hover:text-red-800" style="font-family: Poppins, sans-serif;">
                Clear All
            </button>
            <button type="button" onclick="selectViewOnly()" class="text-xs text-green-600 hover:text-green-800" style="font-family: Poppins, sans-serif;">
                View Only
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectAllPermissions() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
        checkbox.checked = true;
    });
}

function clearAllPermissions() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
        checkbox.checked = false;
    });
}

function selectViewOnly() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
        if (checkbox.value.includes('.view')) {
            checkbox.checked = true;
        } else {
            checkbox.checked = false;
        }
    });
}
</script>
@endpush
