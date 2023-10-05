<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function testCreateComment()
    {
        $comment = new Comment();
        $comment->email = "miftahfadilah71@gmail.com";
        $comment->title = "Sample Title";
        $comment->comment = "Sample Comment";
        $comment->created_at = new \DateTime();
        $comment->updated_at = new \DateTime();
        $comment->save();

        self::assertNotNull($comment->id);
    }

    public function testDefaultAttributeValues()
    {
        $comment = new Comment();
        $comment->email = "miftahfadilah71@gmail.com";
        $comment->created_at = new \DateTime();
        $comment->updated_at = new \DateTime();
        $comment->save();

        self::assertNotNull($comment->id);
    }
}
