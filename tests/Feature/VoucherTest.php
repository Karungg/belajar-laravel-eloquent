<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VoucherTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE FROM vouchers");
    }

    public function testCreateVoucher()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->voucher_code = "123123123123";
        $voucher->save();

        self::assertNotNull($voucher->id);
    }

    public function testCreateVoucherUUID()
    {
        $voucher = new Voucher();
        $voucher->name = "Sample Voucher";
        $voucher->save();

        self::assertNotNull($voucher->id);
        self::assertNotNull($voucher->voucher_code);
    }

    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::query()->where('name', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::query()->where('name', 'Sample Voucher')->first();
        self::assertNull($voucher);
    }

    public function testQueryWithTrashed()
    {
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::query()->where('name', 'Sample Voucher')->first();
        $voucher->delete();

        $voucher = Voucher::query()->where('name', 'Sample Voucher')->first();
        self::assertNull($voucher);

        $voucher = Voucher::withTrashed()->where('name', 'Sample Voucher')->first();
        self::assertNotNull($voucher);
    }
}
