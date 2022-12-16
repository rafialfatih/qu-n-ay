{{ $question->title }}
<br>
{{ $question->user->username }}
<p>{{ $question->question }}</p>
<a href="{{ route('question.edit', [$question->id, $question->slug]) }}">Edit</a>
<p>Vote: {{ $question->upvotes_count }}</p>
<span><b>Tags: </b></span>
@foreach ($question->tags as $tag)
  <a href="{{ route('question.search') }}?tag={{ $tag->tags }}">{{ $tag->tags }}</a>
@endforeach

<form action="{{ route('question_vote') }}" method="post">
  @csrf
  <input type="hidden" name="question_id" value="{{ $question->id }}">
  <button name="vote" value="up">Upvote</button>
  <button name="vote" value="down">Downvote</button>
</form>

<br>
<h3>Answer</h3>

@if (count($question->answers) == 0)
   <p>
    No answer yet!
   </p>
@else
  @foreach ($answers as $answer)
    <h5>{{ $answer->user->username }}</h5>
    <h6>{{ $answer->created_at }}</h6>
    <p>{{ $answer->answer }}</p>
    <a href="{{ route('answer.edit', [$question->id, $answer->id]) }}">
      Edit
    </a>
  @endforeach
@endif

<br>
<h3>Answer this question</h3>
<form action="{{ route('answer.store') }}" method="post">
   @csrf
   <input type="hidden" name="question_id" value="{{ $question->id }}">
   <textarea name="answer"></textarea>
   <button>Submit</button>
</form>
