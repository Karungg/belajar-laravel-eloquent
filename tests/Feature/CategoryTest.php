<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testInsert()
    {
        $category = new Category();
        $category->id = "GADGET";
        $category->name = "Gadget";
        $result = $category->save();

        self::assertTrue($result);
    }

    public function testManyInsertCategories()
    {
        $categories = [];

        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "ID $i",
                "name" => "Name $i"
            ];
        }

        $result = Category::query()->insert($categories);
        self::assertTrue($result);

        $total = Category::query()->count();
        self::assertEquals(10, $total);
    }

    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        self::assertNotNull($category);
        self::assertEquals("FOOD", $category->id);
        self::assertEquals("Food", $category->name);
        self::assertEquals("Food Category", $category->description);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("FOOD");
        $category->name = "Food Update";
        $result = $category->update();

        self::assertTrue($result);
    }

    public function testSelect()
    {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->id = "$i";
            $category->name = "Category $i";
            $category->save();
        }

        $categories = Category::query()->whereNull("description")->get();
        self::assertCount(5, $categories);
        $categories->each(function ($category) {
            $category->description = "Updated";
            $category->update();
        });
    }

    public function testUpdateMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "$i",
                "name" => "Category $i"
            ];
        }

        $result = Category::query()->insert($categories);
        self::assertTrue($result);

        Category::query()->whereNull("description")->update([
            "description" => "Updated"
        ]);
        $total = Category::query()->where("description", "Updated")->count();
        self::assertEquals(10, $total);
    }
}
