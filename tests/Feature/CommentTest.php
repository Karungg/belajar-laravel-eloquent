<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Product;
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
        $comment->commentable_id = "1";
        $comment->commentable_type = Product::class;
        $comment->created_at = new \DateTime();
        $comment->updated_at = new \DateTime();
        $comment->save();

        self::assertNotNull($comment->id);
    }

    public function testDefaultAttributeValues()
    {
        $comment = new Comment();
        $comment->email = "miftahfadilah71@gmail.com";
        $comment->commentable_id = "1";
        $comment->commentable_type = Product::class;
        $comment->created_at = new \DateTime();
        $comment->updated_at = new \DateTime();
        $comment->save();

        self::assertNotNull($comment->id);
    }
}
