<h2>Search</h2>
@include('partials._search')
@foreach ($search as $result)
  <p>
    <a href="{{ route('question.show', [$result->id, $result->slug]) }}">
      {{ $result->title }}
    </a>
  </p>
  Tags:
  @foreach ($result->tags as $tag)
    <a href="{{ route('question.search') }}?tag={{ $tag->tags }}">{{ $tag->tags }}</a>
  @endforeach
</br>
  <p>
    asked by:
    <a href="{{ route('user.show', [$result->user->username]) }}">
      {{ $result->user->username }}
    </a>
  </p>
</br>
@endforeach
