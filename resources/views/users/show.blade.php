<h2>{{ $user->name }}</h2>
<h3>{{ $user->username }}</h3>

<p>Questions: {{ $user->questions_count }}</p>
<p>Answers: {{ $user->answers_count }}</p>

Top Questions:
@foreach ($top_questions as $top_question)
  <p>{{ $top_question->title }}</p>
@endforeach
