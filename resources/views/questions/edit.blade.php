<h5>Edit question</h5>
<form method="POST" action="{{ route('question.update', [$question->id]) }}/">
  @csrf
  @method('PUT')
    <h5>title</h5>
    <input type="text" name="title" value="{{ $question->title }}">
    <h5>question</h5>
    <input type="text" name="question" value="{{ $question->question }}">
    <h5>tags</h5>
    <input type="text" name="tags" value="{{ $tags }}">
    <button type="submit">Post</button>
</form>
