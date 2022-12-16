<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_can_be_rendered()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/questions', [
            'user_id' => $question->user_id,
            'title' => $question->title,
            'question' => $question->question,
            'tags' => 'tag1,tag2',
        ]);

        $response->assertOk()
            ->assertSee('Questions')
            ->assertSee($question->title);

        $this->assertEquals(1, Question::count());
    }

    /** @test */
    public function ask_a_question_page_can_be_accessed_by_authorized_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/questions/create');

        $response->assertOk()
            ->assertSee('Ask your question');
    }

    /** @test */
    public function guest_redirected_to_login_from_ask_a_question_page()
    {
        $this->get('/questions/create')
            ->assertRedirect('/login');
    }

    /** @test */
    public function user_can_post_question()
    {
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
        $this->assertEquals(1, Question::count());
    }

    /** @test */
    public function user_can_edit_question()
    {
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

        $this->assertEquals('New Title', Question::first()->title);
        $this->assertEquals('New Question', Question::first()->question);
        $this->assertEquals('tag1,tag3', Question::first()->tags);
        $response->assertRedirect('question')
            ->assertSessionHas('message');
    }

    /** @test */
    public function user_can_delete_question()
    {
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

        $response = $this->delete('/questions/'.$questions->id);
        $this->assertEquals(1, Question::count());
    }
}
