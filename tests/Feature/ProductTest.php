<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\TagSeeder;
use Database\Seeders\VoucherSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::find("1");
        self::assertNotNull($product);

        $category = $product->category;
        self::assertNotNull($category);
        self::assertEquals("FOOD", $category->id);
    }

    public function testHasOneOfMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::query()->find('FOOD');
        self::assertNotNull($category);

        $cheapestProduct = $category->cheapestProduct;
        self::assertNotNull($cheapestProduct);
        self::assertEquals("1", $cheapestProduct->id);

        $mostExpensiveProduct = $category->mostExpensiveProduct;
        self::assertNotNull($mostExpensiveProduct);
        self::assertEquals("2", $mostExpensiveProduct->id);
    }

    public function testOneToOnePolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $product = Product::query()->find("1");
        self::assertNotNull($product);

        $image = $product->image;
        self::assertEquals("https://www.programmerzamannow.com/images/2.jpg", $image->url);
    }

    public function testOneToManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::query()->first();
        $comments = $product->comments;
        self::assertCount(1, $comments);

        foreach ($comments as $comment) {
            self::assertEquals('product', $comment->commentable_type);
            self::assertEquals($product->id, $comment->commentable_id);
        }
    }

    public function testHasOneOfManyPolymorhpic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::query()->first();
        $latestComment = $product->latestComment;

        self::assertNotNull($latestComment);

        $oldestComment = $product->oldestComment;
        self::assertNotNull($oldestComment);
    }

    public function testManyToManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, TagSeeder::class]);

        $product = Product::query()->first();
        $tags = $product->tags;
        self::assertNotNull($tags);
        self::assertCount(1, $tags);

        foreach ($tags as $tag) {
            self::assertNotNull($tag);
            self::assertNotNull($tag->id);
            self::assertNotNull($tag->name);
        }
    }

    public function testEloquentCollection()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::query()->get();
        self::assertCount(2, $products);

        $products = $products->toQuery()->where('price', "=", 100)->get();
        self::assertCount(1, $products);
    }
}
