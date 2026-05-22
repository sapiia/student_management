<x-layouts.app title="Upload Student Scores">
    <section class="admin-card">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-black text-[#171326]">Upload Student Scores</h1>
                <p class="mt-1 text-sm text-zinc-500">Import student data from CSV file</p>
            </div>
            <a href="{{ route('admin.scores') }}" class="btn-secondary">Back to Scores</a>
        </div>

        <div class="mt-6">
            <form action="{{ route('admin.upload.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-zinc-700">CSV File</label>
                    <input type="file" name="csv_file" accept=".csv" required class="mt-1 block w-full rounded-md border border-zinc-300 px-3 py-2 text-sm focus:border-[#4f79c9] focus:outline-none focus:ring-1 focus:ring-[#4f79c9]">
                    @error('csv_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 rounded-md bg-blue-50 p-4 text-sm text-blue-800">
                    <p class="font-semibold">CSV Format:</p>
                    <p class="mt-1">The CSV file should have the following format:</p>
                    <pre class="mt-2 rounded bg-white p-2 text-xs">name,quiz_score,midterm_score
John Doe,85,90
Jane Smith,78,88
...</pre>
                    <p class="mt-2 text-xs">- First row should be the header</p>
                    <p class="text-xs">- Columns: name, quiz_score, midterm_score</p>
                    <p class="text-xs">- Existing students will be updated by name</p>
                </div>

                <button type="submit" class="btn-primary w-full">Upload CSV</button>
            </form>
        </div>
    </section>
</x-layouts.app>
