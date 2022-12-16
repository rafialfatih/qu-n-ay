<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  Ask your question
  <form action="{{ route('question.store') }}" method="post">
    @csrf
    <h5>title</h5>
    <input type="text" name="title">
    <h5>question</h5>
    <input type="text" name="question">
    <h5>tags</h5>
    <input type="text" name="tags">
    <button>Post</button>
  </form>
</body>
</html>
