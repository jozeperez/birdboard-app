<div class="card flex flex-col" style="height: 300px;">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-300 pl-4">
         <a href="{{ $project->path() }}" class="text-black no-underline">
            {{ $project->title }}
        </a>
    </h3>

    <div class="text-gray-500 flex-1">{{ Str::limit($project->description, 150) }}</div>

    <footer>
        <form method="POST" action="{{ $project->path() }}" class="text-right bottom-0">
            @method('DELETE')
            @csrf
            <button type="submit" class="text-xs">Delete</button>
        </form>
    </footer>
</div>
