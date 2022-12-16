{{-- <x-layout> --}}
  <h1 class="text-red-600 font-extrabold text-4xl">Questions</h1>
  <a href="/questions/create">Ask Question</a>
  @include('partials._search')
  @auth
    <a href="{{ route('user.show', [Auth::user()->username]) }}">User</a>

    <form action="{{ route('auth.logout') }}" method="POST">
      @csrf
      @method('DELETE')
      <button>Logout</button>
    </form>
  @else
    <a href="{{ route('auth.create') }}">Login</a>
  @endauth
  <ul>
    @foreach ($questions as $question)
      <hr>
      <li>
        <a href="{{ route('question.show', [$question->id, $question->slug]) }}">
          {{ $question->title }}
        </a>
        <p>
          votes: {{ $question->upvotes_count }}
        </p>
        <p>
          {{ $question->user->username }}
        </p>
        <p>
          Tags:
          @foreach ($question->tags as $tag)
             <a href="{{ route('question.search') }}?tag={{ $tag->tags }}">{{ $tag->tags }}</a>
          @endforeach
        </p>
        <p>
          <b>{{ $question->created_at }}</b>
        </p>
        <p>
          Answers: {{ $question->answers_count }}
        </p>
      </li>
    @endforeach
  </ul>
{{-- </x-layout> --}}
