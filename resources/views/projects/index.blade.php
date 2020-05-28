@extends ('layouts.app')

@section ('content')
    <div class="flex items-center mb-3">
        <a href="{{ route('create-project') }}">New Project</a>
    </div>

    <div class="flex">
        @forelse ($projects as $project)
            <div class="bg-white mr-4 rounded p-5 shadow w-1/3" style="height: 300px;">
                <h3 class="font-normal text-xl py-4">{{ $project->title }}</h3>

                <div class="text-gray-500">{{ Str::limit($project->description, 150) }}</div>
            </div>
        @empty
            <div>No projects yet.</div>
        @endforelse
    </div>
@endsection
