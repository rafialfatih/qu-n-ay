<?php

use App\Models\Question;
use App\Models\User;

it('ask a question page can be accessed by authorized user', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/questions/create');

    $response->assertOk()
        ->assertSee('Ask your question');
});

it('redirect guest from question form')
    ->get('/questions/create')
    ->assertRedirect('/login');


it('can post a question', function() {
    $user = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $response = $this->post('/questions', [
        'user_id' => $question->user_id,
        'title' => $question->title,
        'question' => $question->question,
        'tags' => 'tag1,tag2',
    ]);

    $response->assertRedirect('/questions')
        ->assertSessionHas('message');

    expect(Question::count())->toEqual(2);
});

it('can edit question', function() {
    $user = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $this->post('/questions', [
        'user_id' => $question->user_id,
        'title' => 'Title',
        'question' => 'Question',
        'tags' => 'tag1,tag2',
    ]);

    $questions = Question::first();

    $response = $this->from('question')->put('/questions/'.$questions->id, [
        'title' => 'New Title',
        'question' => 'New Question',
        'tags' => 'tag1,tag3',
    ]);

    expect(Question::first())
      ->title->toBe('New Title')
      ->question->toBe('New Question')
      ->tags->not->toBe('tag1,tag3');

    $response->assertRedirect('/questions/'.$questions->id.'/new-title')
        ->assertSessionHas('message');
});

it('can delete question', function (){
    $user = User::factory()->create();
    $question = Question::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $this->post('/questions', [
        'user_id' => $question->user_id,
        'title' => 'Title',
        'question' => 'Question',
        'tags' => 'tag1,tag2',
    ]);

    $questions = Question::first();

    $this->delete('/questions/'.$questions->id);
    expect(Question::count())->toEqual(1);
});
