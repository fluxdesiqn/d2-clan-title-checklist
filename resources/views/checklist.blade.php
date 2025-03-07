<x-main-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('D2 Clan Checklist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('checklist.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label for="activityType" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Activity Type:</label>
                                <select id="activityType" name="activityType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" onchange="updateForm()">
                                    <option value="raid">Raid</option>
                                    <option value="dungeon">Dungeon</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label for="activity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Activity:</label>
                                <select id="activity" name="activity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    @foreach ($raids as $raidName)
                                        <option value="{{ $raidName }}">{{ $raidName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-1">
                                <label for="encounter" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Encounter:</label>
                                <select id="encounter" name="encounter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                    @for ($i = 1; $i <= 6; $i++)
                                        <option value="{{ $i }}">Encounter {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        @for ($i = 1; $i <= 6; $i++)
                            <div class="flex space-x-4 guardian-input" id="guardian-input-{{ $i }}">
                                <div class="w-1/4">
                                    <label for="platform{{ $i }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Platform {{ $i }}:</label>
                                    <select id="platform{{ $i }}" name="platform{{ $i }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                        <option value="1">Xbox</option>
                                        <option value="2">PSN</option>
                                        <option value="3">Steam</option>
                                    </select>
                                </div>
                                <div class="w-1/4">
                                    <label for="guardian{{ $i }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guardian {{ $i }}:</label>
                                    <input type="text" id="guardian{{ $i }}" name="guardian{{ $i }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                </div>
                                <div class="w-1/2">
                                    <label for="code{{ $i }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Code {{ $i }}:</label>
                                    <input type="text" id="code{{ $i }}" name="code{{ $i }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                </div>
                            </div>
                        @endfor
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const raids = @json($raids);
        const dungeons = @json($dungeons);
    </script>
</x-main-layout>