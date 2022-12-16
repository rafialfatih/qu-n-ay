<h4>{{ $question->title }}</h4>
<p>{{ $question->question }}</p>
Tags:
@foreach ($question->tags as $tag)
    <span>{{ $tag->tags }}</span>
@endforeach

<h4>Answer</h4>
<form action="{{ route('answer.update', [$question->id, $answer->id]) }}" method="post">
  @csrf
  @method('PUT')
  <textarea name="answer" cols="30" rows="10">{{ $answer->answer }}</textarea>
  <button name="submit">Submit</button>
</form>
<form action="{{ route('answer.delete', [$question->id, $answer->id]) }}" method="post">
@csrf
@method('DELETE')
<button>Delete</button>
</form>
