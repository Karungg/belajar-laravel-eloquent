<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Wallet;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\WalletSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DB::delete('DELETE FROM customers');
        DB::delete('DELETE FROM wallets');
    }

    public function testOneToOne()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class]);

        $customer = Customer::query()->find("MIFTAH");
        self::assertNotNull($customer);

        $wallet = $customer->wallet;
        self::assertNotNull($wallet);

        self::assertEquals(1000000, $wallet->amount);
    }

    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = "MIFTAH";
        $customer->name = "Miftah Fadilah";
        $customer->email = "miftahfadilah71@gmail.com";
        $customer->save();
        self::assertNotNull($customer);

        $wallet = new Wallet();
        $wallet->amount = 10000000;
        $customer->wallet()->save($wallet);

        self::assertNotNull($wallet);
    }

    public function testOneToManyQuery()
    {
        $category = new Category();
        $category->id = "FOOD";
        $category->name = "Food";
        $category->description = "Food Category";
        $category->is_active = true;
        $category->save();
        self::assertNotNull($category);

        $product = new Product();
        $product->id = "1";
        $product->name = "Product 1";
        $product->description = "Description 1";
        $category->products()->save($product);
        self::assertNotNull($product);
    }

    public function testRelationshipQuery()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::query()->find("FOOD");
        $products = $category->products;
        self::assertCount(1, $products);

        $outOfStockProducts = $category->products()->where('stock', '<=', '0')->get();
        self::assertCount(1, $outOfStockProducts);
    }
}
