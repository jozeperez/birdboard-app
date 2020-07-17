@extends ('layouts.app')

@section ('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-gray-500 text-sm w-3/4">
                <a
                    href="/projects"
                    class="text-gray-500 text-sm no-underline"
                >
                    My Projects
                </a> / {{ $project->title }}
            </p>

            <div class="flex items-center">
                @foreach ($project->members as $member)
                    <img
                        src="{{ gravatar_url($member->email) }}"
                        alt="{{ $member->name }}'s avatar"
                        class="rounded-full w-8 mr-2"
                    />
                @endforeach

                <img
                    src="{{ gravatar_url($project->owner->email) }}"
                    alt="{{ $project->owner->name }}'s avatar"
                    class="rounded-full w-8 mr-2"
                />
            </div>

            <a href="{{ $project->path() . '/edit' }}" class="button ml-4">Edit Project</a>
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

                    @include('errors')
                </div>
            </div>

            <div class="lg:w-1/4 px-3">
                @include ('projects.card')
                @include ('projects.activity.card')

                <div class="card flex flex-col mt-3">
                    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-300 pl-4">
                        Invite a User
                    </h3>

                    <footer>
                        <!-- class="text-right bottom-0"-->
                        <form method="POST" action="{{ $project->path() . '/invitations' }}">
                            @csrf

                            <div class="mb-3">
                                <input
                                    type="email"
                                    name="email"
                                    class="border border-gray-500 rounded w-full py-2 px-3 text-sm"
                                    placeholder="Email address"
                                />
                            </div>

                            <button
                                type="submit"
                                class="button"
                            >
                                Invite
                            </button>

                            @include('errors', ['bag' => 'invitations'])
                        </form>
                    </footer>
                </div>

            </div>
        </div>
    </main>
@endsection
