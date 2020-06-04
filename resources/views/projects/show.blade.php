@extends ('layouts.app')

@section ('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-gray-500 text-sm">
                <a
                    href="{{ route('project.dashboard') }}"
                    class="text-gray-500 text-sm no-underline"
                >
                    My Projects
                </a> / {{ $project->title }}
            </p>

            <a href="{{ route('project.create') }}" class="button">Add Project</a>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-lg text-gray-500 font-normal mb-3">Tasks</h2>

                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            <form method="post" action="{{ $task->path() }}">
                                @method('PATCH')
                                @csrf
                                <div class="flex">
                                    <input name="body"
                                        type="text"
                                        value="{{ $task->body }}"
                                        class="w-full {{ $task->completed ? 'text-gray-500' : '' }}"
                                    />
                                    <input name="completed"
                                        type="checkbox"
                                        onChange="this.form.submit()"
                                        {{ $task->completed ? 'checked' : '' }}
                                    />
                                </div>
                            </form>
                        </div>
                    @endforeach

                    <div class="card mb-3">
                        <form
                            method="post"
                            action="{{ route('task.create', $project) }}"
                        >
                            @csrf

                            <input
                                class="w-full"
                                type="text"
                                name="body"
                                placeholder="Add a new tasks..."
                            />
                        </form>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg text-gray-500 font-normal mb-3">General Notes:</h2>

                    <form method="post" action="{{ $project->path() }}">
                        @method('PATCH')
                        @csrf

                        <textarea
                            name="notes"
                            class="card w-full mb-4"
                            style="min-height: 200px"
                            placeholder="Want to jot down some notes?"

                        >{{ $project->notes }}</textarea>

                        <button type="submit" class="button">Save</button>
                    </form>
                </div>
            </div>

            <div class="lg:w-1/4 px-3">
                @include ('projects.card')
            </div>
        </div>
    </main>
@endsection
