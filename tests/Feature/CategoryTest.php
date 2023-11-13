<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Scopes\IsActiveScope;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\ReviewSeeder;
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
                "name" => "Name $i",
                "is_active" => true
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
            $category->is_active = true;
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
                "name" => "Category $i",
                "is_active" => true
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

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find("FOOD");
        $result = $category->delete();

        self::assertTrue($result);

        $total = Category::query()->count();
        self::assertEquals(0, $total);
    }

    public function testDeleteMany()
    {
        $categories = [];
        for ($i = 0; $i < 10; $i++) {
            $categories[] = [
                "id" => "$i",
                "name" => "Category $i",
                "is_active" => true
            ];
        }

        $result = Category::query()->insert($categories);
        self::assertTrue($result);

        $total = Category::query()->count();
        self::assertEquals(10, $total);

        Category::query()->whereNull("description")->delete();

        $total = Category::query()->count();
        self::assertEquals(0, $total);
    }

    public function testCreate()
    {
        $request = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];

        $category = new Category($request);
        $category->save();

        self::assertNotNull($category);
    }

    public function testCreateMethod()
    {
        $request = [
            "id" => "FOOD",
            "name" => "Food",
            "description" => "Food Category"
        ];

        $category = Category::query()->create($request);

        self::assertNotNull($category->id);
    }

    public function testUpdateMass()
    {
        $this->seed(CategorySeeder::class);

        $request = [
            "name" => "Food Updated",
            "description" => "Food Category Updated"
        ];

        $category = Category::query()->find("FOOD");
        $category->fill($request);
        $category->save();

        self::assertNotNull($category->id);
    }

    public function testRemoveGlobalScope()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = false;
        $category->save();

        $category = Category::query()->find("FOOD");
        self::assertNull($category);

        $category = Category::withoutGlobalScopes([IsActiveScope::class])->find("FOOD");
        self::assertNotNull($category);
    }

    public function testOneToMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("FOOD");
        self::assertNotNull($category);

        $products = $category->products;

        self::assertNotNull($products);
        self::assertCount(2, $products);
    }

    public function testHasManyThrough()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, CustomerSeeder::class, ReviewSeeder::class]);

        $category = Category::query()->find("FOOD");
        self::assertNotNull($category);

        $reviews = $category->reviews;
        self::assertNotNull($reviews);
        self::assertCount(2, $reviews);
    }

    public function testQueryingRelations()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::query()->find("FOOD");
        $products = $category->products()->where("price", "=", 100)->get();

        self::assertCount(1, $products);
    }

    public function testQueryingRelationsAggregate()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::query()->find("FOOD");
        $totalProducts = $category->products()->count();

        self::assertEquals(2, $totalProducts);
    }
}
