<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::query()->first();
        $comment = new Comment();
        $comment->email = "miftahfadilah71@gmail.com";
        $comment->title = "Title";
        $comment->comment = "Comment Product";
        $comment->commentable_id = $product->id;
        $comment->commentable_type = 'product';
        $comment->save();

        $voucher = Voucher::query()->first();
        $comment2 = new Comment();
        $comment2->email = "miftahfadilah71@gmail.com";
        $comment2->title = "Title";
        $comment2->comment = "Comment Voucher";
        $comment2->commentable_id = $voucher->id;
        $comment2->commentable_type = 'voucher';
        $comment2->save();
    }
}
