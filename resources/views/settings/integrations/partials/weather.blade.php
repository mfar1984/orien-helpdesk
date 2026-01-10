<div class="space-y-6">
    @php
        $weatherSettings = $settings['weather_settings'] ?? [];
        $isConfigured = !empty($weatherSettings['api_key'] ?? '');
        $canEdit = $canEdit ?? false;
        $disabledAttr = $canEdit ? '' : 'disabled';
        $disabledClass = $canEdit ? '' : 'bg-gray-100 cursor-not-allowed';
    @endphp

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" style="font-family: Poppins, sans-serif; font-size: 12px;">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" style="font-family: Poppins, sans-serif; font-size: 12px;">
        <div class="flex items-start gap-2">
            <span class="material-symbols-outlined" style="font-size: 18px;">error</span>
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Status Card -->
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #fef9c3;">
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #ca8a04;">wb_sunny</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Weather Service</h3>
                    <p class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">OpenWeatherMap API</p>
                </div>
            </div>
            @if($isConfigured)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700" style="font-family: Poppins, sans-serif;">
                    ✓ Configured
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600" style="font-family: Poppins, sans-serif;">
                    Not Configured
                </span>
            @endif
        </div>
    </div>

    <!-- Info Box -->
    <div class="p-4 rounded-lg" style="background-color: #fefce8; border: 1px solid #fef08a;">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined" style="font-size: 18px; color: #ca8a04;">info</span>
            <div style="font-family: Poppins, sans-serif;">
                <p class="font-medium text-xs" style="color: #854d0e;">OpenWeatherMap API</p>
                <p class="mt-1 text-xs" style="color: #a16207;">Get your free API key from <a href="https://openweathermap.org/api" target="_blank" class="underline hover:no-underline">OpenWeatherMap</a></p>
            </div>
        </div>
    </div>

    <!-- Configuration Form -->
    <form method="POST" action="{{ route('settings.integrations.weather.save') }}">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                API Key <span class="text-red-500">*</span>
            </label>
            <input type="password" name="api_key" value="{{ $isConfigured ? '••••••••••••' : '' }}"
                   class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                   style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                   placeholder="Enter your API Key" {{ $disabledAttr }}>
            @if($isConfigured)
            <p class="text-xs text-gray-500 mt-1" style="font-family: Poppins, sans-serif;">Leave blank to keep existing key</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Default City
                </label>
                <input type="text" name="default_city" value="{{ $weatherSettings['default_city'] ?? 'Kuala Lumpur' }}" 
                       class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                       style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" 
                       placeholder="Kuala Lumpur" {{ $disabledAttr }}>
            </div>
            <div>
                <label class="block text-gray-700 mb-1.5" style="font-size: 11px; font-family: Poppins, sans-serif; font-weight: 500;">
                    Units <span class="text-red-500">*</span>
                </label>
                <select name="units" 
                        class="w-full px-3 border border-gray-300 rounded-md text-gray-700 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $disabledClass }}"
                        style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;" {{ $disabledAttr }}>
                    <option value="metric" {{ ($weatherSettings['units'] ?? 'metric') === 'metric' ? 'selected' : '' }}>Metric (°C)</option>
                    <option value="imperial" {{ ($weatherSettings['units'] ?? '') === 'imperial' ? 'selected' : '' }}>Imperial (°F)</option>
                </select>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center border-t border-gray-200 pt-5 mt-6" style="gap: 10px;">
            @if($canEdit)
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-4 text-white rounded-md hover:opacity-90 transition-all shadow-sm"
                    style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif; background-color: #ca8a04;">
                <span class="material-symbols-outlined" style="font-size: 16px;">save</span>
                Save Settings
            </button>
            @else
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 rounded-md" style="font-size: 12px; font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span>
                View Only
            </span>
            @endif
            <button type="button" onclick="testWeatherConnection()"
                    class="inline-flex items-center gap-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-all"
                    style="min-height: 36px; font-size: 12px; font-family: Poppins, sans-serif;">
                <span class="material-symbols-outlined" style="font-size: 16px;">wifi_tethering</span>
                Test Connection
            </button>
        </div>
    </form>
</div>

<!-- Weather Test Modal -->
<div id="weatherTestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center" style="display: none; z-index: 9999;">
    <div class="bg-white rounded-lg shadow-xl" style="width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background-color: #fef9c3;">
                    <span class="material-symbols-outlined" style="font-size: 20px; color: #ca8a04;">wb_sunny</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-900" style="font-family: Poppins, sans-serif;">Weather API Test</h3>
            </div>
            <button type="button" onclick="closeWeatherTestModal()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined" style="font-size: 20px;">close</span>
            </button>
        </div>
        
        <div class="p-6">
            <div id="weatherTestResult"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function closeWeatherTestModal() {
    document.getElementById('weatherTestModal').style.display = 'none';
}

async function testWeatherConnection() {
    const modal = document.getElementById('weatherTestModal');
    const resultDiv = document.getElementById('weatherTestResult');
    
    modal.style.display = 'flex';
    
    // Show loading
    resultDiv.innerHTML = `
        <div class="text-center py-8">
            <span class="material-symbols-outlined animate-spin text-yellow-600" style="font-size: 48px;">wb_sunny</span>
            <p class="mt-4 text-gray-600" style="font-family: Poppins, sans-serif; font-size: 12px;">Fetching weather data...</p>
        </div>
    `;
    
    try {
        const response = await fetch('{{ route("settings.integrations.weather.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            const weather = data.data;
            resultDiv.innerHTML = `
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 text-center">
                    <div class="text-6xl mb-2">${getWeatherIcon(weather.condition)}</div>
                    <h2 class="text-3xl font-bold text-gray-800" style="font-family: Poppins, sans-serif;">${weather.temp}°${weather.unit}</h2>
                    <p class="text-gray-600 text-sm mt-1" style="font-family: Poppins, sans-serif;">${weather.condition}</p>
                    <p class="text-gray-700 font-semibold mt-3 text-lg" style="font-family: Poppins, sans-serif;">${weather.city}, ${weather.country}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-blue-500" style="font-size: 20px;">thermostat</span>
                            <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Feels Like</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-800" style="font-family: Poppins, sans-serif;">${weather.feels_like}°${weather.unit}</p>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-blue-500" style="font-size: 20px;">water_drop</span>
                            <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Humidity</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-800" style="font-family: Poppins, sans-serif;">${weather.humidity}%</p>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-blue-500" style="font-size: 20px;">air</span>
                            <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Wind Speed</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-800" style="font-family: Poppins, sans-serif;">${weather.wind_speed} ${weather.unit === 'C' ? 'm/s' : 'mph'}</p>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-blue-500" style="font-size: 20px;">compress</span>
                            <span class="text-xs text-gray-500" style="font-family: Poppins, sans-serif;">Pressure</span>
                        </div>
                        <p class="text-lg font-semibold text-gray-800" style="font-family: Poppins, sans-serif;">${weather.pressure} hPa</p>
                    </div>
                </div>
                
                <div class="mt-4 p-4 rounded-lg bg-green-50 border border-green-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-600" style="font-size: 20px;">check_circle</span>
                        <p class="text-green-700 text-sm" style="font-family: Poppins, sans-serif;">${data.message}</p>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-start gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">error</span>
                        <div>${data.message}</div>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        resultDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <div class="flex items-center gap-2" style="font-family: Poppins, sans-serif; font-size: 12px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">error</span>
                    <span>Connection failed: ${error.message}</span>
                </div>
            </div>
        `;
    }
}

function getWeatherIcon(condition) {
    condition = condition.toLowerCase();
    if (condition.includes('clear')) return '☀️';
    if (condition.includes('cloud')) return '☁️';
    if (condition.includes('rain')) return '🌧️';
    if (condition.includes('thunder')) return '⛈️';
    if (condition.includes('snow')) return '❄️';
    if (condition.includes('mist') || condition.includes('fog')) return '🌫️';
    return '🌤️';
}

// Close modal on backdrop click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('weatherTestModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeWeatherTestModal();
            }
        });
    }
});
</script>
@endpush
